<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Reservation;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Notifications\ReservationConfirmedNotification;

class AdminController extends Controller
{
    // ============= DASHBOARD =============
    public function dashboard()
    {
        $stats = [
            'total_livros' => Book::count(),
            'livros_disponiveis' => Book::where('available_quantity', '>', 0)->count(),
            'total_usuarios' => User::count(),
            'total_alunos' => User::where('role', 'aluno')->count(),
            'emprestimos_ativos' => Loan::where('status', 'ativo')->count(),
            'total_emprestimos' => Loan::count(),
            'reservas_pendentes' => Reservation::where('status', 'pendente')->count(),
            'total_reservas' => Reservation::count(),
            'emprestimos_atrasados' => Loan::where('status', 'ativo')
                ->where('due_date', '<', now())
                ->count(),
        ];

        $emprestimosRecentes = Loan::with(['book', 'user'])
            ->orderBy('loan_date', 'desc')
            ->take(5)
            ->get();

        $reservasPendentes = Reservation::with(['book', 'user'])
            ->where('status', 'pendente')
            ->orderBy('reserved_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'emprestimosRecentes',
            'reservasPendentes'
        ));
    }

    // ============= LIVROS =============
    public function books(Request $request)
    {
        $query = Book::with(['authors', 'category']);

        // Busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhereHas('authors', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filtro por categoria
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $books = $query->orderBy('title', 'asc')->paginate(15);
        $categories = Category::orderBy('name', 'asc')->get();

        return view('admin.livros.index', compact('books', 'categories'));
    }

    public function createBook()
    {
        $categorias = Category::all();
        $autores = Author::all();
        return view('admin.livros.create', compact('categorias', 'autores'));
    }

    public function storeBook(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'isbn' => 'nullable|string|unique:books,isbn',
            'year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'total_quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:authors,id',
        ]);

        if ($validated['available_quantity'] > $validated['total_quantity']) {
            return back()->withErrors(['available_quantity' => 'Quantidade disponível não pode ser maior que total.'])->withInput();
        }

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('books', 'public');
            $validated['cover_image'] = $path;
        }

        $book = Book::create($validated);

        // Associa os autores
        if ($request->has('authors')) {
            $book->authors()->attach($request->authors);
        }

        return redirect()->route('admin.livros.index')->with('success', 'Livro criado com sucesso!');
    }

    public function editBook($id)
    {
        $livro = Book::with('authors')->findOrFail($id);
        $categorias = Category::all();
        $autores = Author::all();
        return view('admin.livros.edit', compact('livro', 'categorias', 'autores'));
    }

    public function updateBook(Request $request, $id)
    {
        $livro = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'isbn' => ['nullable', 'string', Rule::unique('books')->ignore($id)],
            'year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'total_quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:authors,id',
        ]);

        if ($validated['available_quantity'] > $validated['total_quantity']) {
            return back()->withErrors(['available_quantity' => 'Quantidade disponível não pode ser maior que total.'])->withInput();
        }

        if ($request->hasFile('cover_image')) {
            if ($livro->cover_image && Storage::disk('public')->exists($livro->cover_image)) {
                Storage::disk('public')->delete($livro->cover_image);
            }

            $path = $request->file('cover_image')->store('books', 'public');
            $validated['cover_image'] = $path;
        }

        $livro->update($validated);

        // Atualiza os autores
        if ($request->has('authors')) {
            $livro->authors()->sync($request->authors);
        } else {
            $livro->authors()->detach();
        }

        return redirect()->route('admin.livros.index')->with('success', 'Livro atualizado com sucesso!');
    }

    public function destroyBook($id)
    {
        $livro = Book::findOrFail($id);

        if ($livro->cover_image && Storage::disk('public')->exists($livro->cover_image)) {
            Storage::disk('public')->delete($livro->cover_image);
        }

        $livro->delete();

        return redirect()->route('admin.livros.index')->with('success', 'Livro deletado com sucesso!');
    }
    // ============= AUTORES =============
    public function authors(Request $request)
    {
        $query = Author::withCount('books');

        // Busca
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $authors = $query->orderBy('name', 'asc')->paginate(12);

        return view('admin.autores.index', compact('authors'));
    }

    public function createAuthor()
    {
        return view('admin.autores.create');
    }

    public function storeAuthor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('authors', 'public');
            $validated['photo'] = $path;
        }

        Author::create($validated);

        return redirect()->route('admin.autores.index')->with('success', 'Autor criado com sucesso!');
    }

    public function editAuthor($id)
    {
        $autor = Author::findOrFail($id);
        return view('admin.autores.edit', compact('autor'));
    }

    public function updateAuthor(Request $request, $id)
    {
        $autor = Author::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($autor->photo && Storage::disk('public')->exists($autor->photo)) {
                Storage::disk('public')->delete($autor->photo);
            }

            $path = $request->file('photo')->store('authors', 'public');
            $validated['photo'] = $path;
        }

        $autor->update($validated);

        return redirect()->route('admin.autores.index')->with('success', 'Autor atualizado com sucesso!');
    }

    public function destroyAuthor($id)
    {
        $autor = Author::findOrFail($id);

        if ($autor->photo && Storage::disk('public')->exists($autor->photo)) {
            Storage::disk('public')->delete($autor->photo);
        }

        $autor->delete();

        return redirect()->route('admin.autores.index')->with('success', 'Autor deletado com sucesso!');
    }
    // ============= CATEGORIAS =============
    public function categories(Request $request)
    {
        $query = Category::withCount('books');

        // Busca
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->orderBy('name', 'asc')->paginate(12);

        return view('admin.categorias.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categorias.create');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function editCategory($id)
    {
        $categoria = Category::findOrFail($id);
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function updateCategory(Request $request, $id)
    {
        $categoria = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($id)],
            'description' => 'nullable|string',
        ]);

        $categoria->update($validated);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroyCategory($id)
    {
        $categoria = Category::findOrFail($id);
        $categoria->delete();

        return redirect()->route('admin.categorias.index')->with('success', 'Categoria deletada com sucesso!');
    }

    // ============= USUÁRIOS =============
    public function users(Request $request)
    {
        $query = User::query();

        // Filtro por tipo
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('rm', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name', 'asc')->paginate(15);

        return view('admin.usuarios.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.usuarios.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'rm' => 'nullable|string|unique:users|max:50',
            'role' => 'required|in:aluno,admin',
            'ano_escolar' => 'required_if:role,aluno|nullable|in:1,2,3',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('users', 'public');
            $validated['photo'] = $path;
        }

        User::create($validated);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function editUser($id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function updateUser(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string|min:6',
            'rm' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($id)],
            'role' => 'required|in:aluno,admin',
            'ano_escolar' => 'nullable|in:1,2,3',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('photo')) {
            if ($usuario->photo && Storage::disk('public')->exists($usuario->photo)) {
                Storage::disk('public')->delete($usuario->photo);
            }

            $path = $request->file('photo')->store('users', 'public');
            $validated['photo'] = $path;
        }

        $usuario->update($validated);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroyUser($id)
    {
        $usuario = User::findOrFail($id);

        // Impede que o admin exclua a si mesmo
        if ($usuario->id === auth()->id()) {
            return redirect()->route('admin.usuarios.index')->with('error', 'Você não pode excluir sua própria conta!');
        }

        if ($usuario->photo && Storage::disk('public')->exists($usuario->photo)) {
            Storage::disk('public')->delete($usuario->photo);
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário deletado com sucesso!');
    }

    // ============= EMPRÉSTIMOS =============
    public function loans(Request $request)
    {
        $query = Loan::with(['book.authors', 'user']);

        // Busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('book', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Filtro por status
        if ($request->filled('status')) {
            if ($request->status === 'atrasado') {
                $query->where('status', 'ativo')
                    ->where('due_date', '<', now());
            } else {
                $query->where('status', $request->status);
            }
        }

        $loans = $query->orderBy('loan_date', 'desc')->paginate(15);

        return view('admin.emprestimos.index', compact('loans'));
    }

    public function createLoan()
    {
        $usuarios = User::where('role', 'aluno')->orderBy('name', 'asc')->get();
        $livros = Book::where('available_quantity', '>', 0)->orderBy('title', 'asc')->get();
        return view('admin.emprestimos.create', compact('usuarios', 'livros'));
    }

    public function storeLoan(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'due_date' => 'required|date|after:today',
        ]);

        $book = Book::find($validated['book_id']);
        if ($book->available_quantity <= 0) {
            return back()->withErrors(['book_id' => 'Livro sem estoque disponível.'])->withInput();
        }

        $validated['loan_date'] = now();
        $validated['status'] = 'ativo';

        Loan::create($validated);

        // Diminui estoque
        $book->decrement('available_quantity');

        return redirect()->route('admin.emprestimos.index')->with('success', 'Empréstimo criado com sucesso!');
    }

    public function returnLoan($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'ativo') {
            return redirect()->route('admin.emprestimos.index')->with('error', 'Este empréstimo já foi devolvido!');
        }

        $loan->status = 'finalizado';
        $loan->return_date = now();
        $loan->save();

        // Aumenta a quantidade disponível do livro
        $loan->book->increment('available_quantity');

        // Confirma próxima reserva pendente
        $proximaReserva = Reservation::where('book_id', $loan->book_id)
            ->where('status', 'pendente')
            ->oldest()
            ->first();

        if ($proximaReserva) {
            $proximaReserva->update(['status', 'confirmado']);

            try {
                $proximaReserva->user->notify(new ReservationConfirmedNotification($proximaReserva));
            } catch (\Exception $e) {
                \Log::error('Erro ao enviar email de reserva: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.emprestimos.index')->with('success', 'Devolução registrada com sucesso!');
    }

    public function destroyLoan($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status === 'ativo') {
            $loan->book->increment('available_quantity');
        }

        $loan->delete();

        return redirect()->route('admin.emprestimos.index')->with('success', 'Empréstimo deletado com sucesso!');
    }

    // ============= RESERVAS =============
    public function reservations(Request $request)
    {
        $query = Reservation::with(['book.authors', 'user']);

        // Busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('book', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->orderBy('reserved_at', 'desc')->paginate(15);

        // Stats
        $stats = [
            'pendentes' => Reservation::where('status', 'pendente')->count(),
            'confirmadas' => Reservation::where('status', 'confirmado')->count(),
            'canceladas' => Reservation::where('status', 'cancelado')->count(),
        ];

        return view('admin.reservas.index', compact('reservations', 'stats'));
    }

    public function confirmReservation($id)
    {
        $reservation = Reservation::with(['user', 'book'])->findOrFail($id);

        if ($reservation->status !== 'pendente') {
            return redirect()->route('admin.reservas.index')->with('error', 'Esta reserva não está pendente!');
        }

        if ($reservation->book->available_quantity <= 0) {
            return redirect()->route('admin.reservas.index')->with('error', 'Livro sem estoque disponível!');
        }

        $reservation->status = 'confirmado';
        $reservation->save();

        // Notifica o usuário
        try {
            $reservation->user->notify(new ReservationConfirmedNotification($reservation));
            return redirect()->route('admin.reservas.index')->with('success', 'Reserva confirmada e aluno notificado por email!');
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email de reserva: ' . $e->getMessage());
            return redirect()->route('admin.reservas.index')->with('success', 'Reserva confirmada! (Email não pôde ser enviado)');
        }
    }

    public function cancelReservation($id)
    {
        $reservation = Reservation::findOrFail($id);

        $reservation->status = 'cancelado';
        $reservation->save();

        return redirect()->route('admin.reservas.index')->with('success', 'Reserva cancelada com sucesso!');
    }


    // ============= COMANDOS =============
    public function promoteStudents()
    {
        \Artisan::call('students:promote');

        return redirect()->route('admin.dashboard')->with('success', 'Alunos promovidos com sucesso!');
    }

    public function deleteGraduated()
    {
        \Artisan::call('students:delete-graduated');

        return redirect()->route('admin.dashboard')->with('success', 'Alunos formandos deletados com sucesso!');
    }
}