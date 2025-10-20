<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Reservation;
use App\Models\Category;
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
            'total_alunos' => User::where('role', 'aluno')->count(),
            'total_livros' => Book::sum('total_quantity'),
            'livros_disponiveis' => Book::sum('available_quantity'),
            'total_emprestimos' => Loan::count(),
            'emprestimos_ativos' => Loan::where('status', 'ativo')->count(),
            'reservas_pendentes' => Reservation::where('status', 'pendente')->count(),
        ];

        $livrosMaisEmprestados = Book::withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->take(5)
            ->get();

        $alunosPorAno = User::where('role', 'aluno')
            ->selectRaw('ano_escolar, COUNT(*) as total')
            ->groupBy('ano_escolar')
            ->orderBy('ano_escolar')
            ->get();

        $emprestimosAtrasados = Loan::where('status', 'ativo')
            ->where('due_date', '<', now())
            ->with(['user', 'book'])
            ->get();

        $emprestimosRecentes = Loan::with(['user', 'book'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'livrosMaisEmprestados',
            'alunosPorAno',
            'emprestimosAtrasados',
            'emprestimosRecentes'
        ));
    }

    // ============= LIVROS =============
    public function books(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $livros = $query->paginate(15);

        return view('admin.livros.index', compact('livros'));
    }

    public function createBook()
    {
        $categorias = Category::all();
        return view('admin.livros.create', compact('categorias'));
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
            'cover_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        if ($validated['available_quantity'] > $validated['total_quantity']) {
            return back()->withErrors(['available_quantity' => 'Quantidade disponível não pode ser maior que total.'])->withInput();
        }

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('books', 'public');
            $validated['cover_image'] = $path;
        }

        Book::create($validated);

        return redirect()->route('admin.livros.index')->with('success', 'Livro criado com sucesso!');
    }

    public function editBook($id)
    {
        $livro = Book::findOrFail($id);
        $categorias = Category::all();
        return view('admin.livros.edit', compact('livro', 'categorias'));
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
            'cover_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
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

    // ============= CATEGORIAS =============
    public function categories()
    {
        $categorias = Category::withCount('books')->paginate(15);
        return view('admin.categorias.index', compact('categorias'));
    }

    public function createCategory()
    {
        return view('admin.categorias.create');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
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

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $usuarios = $query->paginate(15);

        return view('admin.usuarios.index', compact('usuarios'));
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

        if ($usuario->photo && Storage::disk('public')->exists($usuario->photo)) {
            Storage::disk('public')->delete($usuario->photo);
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário deletado com sucesso!');
    }

    // ============= EMPRÉSTIMOS =============
    public function loans(Request $request)
    {
        $query = Loan::with(['user', 'book']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $emprestimos = $query->latest()->paginate(15);

        return view('admin.emprestimos.index', compact('emprestimos'));
    }

    public function createLoan()
    {
        $usuarios = User::where('role', 'aluno')->get();
        $livros = Book::where('available_quantity', '>', 0)->get();
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
        if (!$book->hasAvailableStock()) {
            return back()->withErrors(['book_id' => 'Livro sem estoque disponível.'])->withInput();
        }

        $validated['loan_date'] = now();
        $validated['status'] = 'ativo';

        Loan::create($validated);
        $book->decreaseStock();

        return redirect()->route('admin.emprestimos.index')->with('success', 'Empréstimo criado com sucesso!');
    }

    public function editLoan($id)
    {
        $emprestimo = Loan::with(['user', 'book'])->findOrFail($id);
        return view('admin.emprestimos.edit', compact('emprestimo'));
    }

    public function updateLoan(Request $request, $id)
    {
        $emprestimo = Loan::findOrFail($id);

        $validated = $request->validate([
            'due_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'status' => 'required|in:ativo,finalizado',
        ]);

        if ($validated['status'] === 'finalizado' && $emprestimo->status === 'ativo') {
            $validated['return_date'] = $validated['return_date'] ?? now();
            $emprestimo->book->increaseStock();

            // ✅ Confirma próxima reserva pendente
            $proximaReserva = Reservation::where('book_id', $emprestimo->book_id)
                ->where('status', 'pendente')
                ->oldest()
                ->first();

            if ($proximaReserva) {
                $proximaReserva->update(['status' => 'confirmado']); // ✅ CORRIGIDO

                // ✅ ENVIA EMAIL!
                try {
                    $proximaReserva->user->notify(new ReservationConfirmedNotification($proximaReserva));
                } catch (\Exception $e) {
                    \Log::error('Erro ao enviar email de reserva: ' . $e->getMessage());
                }
            }
        }

        $emprestimo->update($validated);

        return redirect()->route('admin.emprestimos.index')->with('success', 'Empréstimo atualizado com sucesso!');
    }

    public function destroyLoan($id)
    {
        $emprestimo = Loan::findOrFail($id);

        if ($emprestimo->status === 'ativo') {
            $emprestimo->book->increaseStock();
        }

        $emprestimo->delete();

        return redirect()->route('admin.emprestimos.index')->with('success', 'Empréstimo deletado com sucesso!');
    }

    // ============= RESERVAS =============
    public function reservations(Request $request)
    {
        $query = Reservation::with(['user', 'book.category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservas = $query->latest()->paginate(15);

        return view('admin.reservas.index', compact('reservas'));
    }

    public function confirmReservation($id)
    {
        $reserva = Reservation::with(['user', 'book'])->findOrFail($id);

        if ($reserva->status !== 'pendente') {
            return back()->withErrors(['error' => 'Esta reserva não está pendente.']);
        }

        if ($reserva->book->available_quantity <= 0) {
            return back()->withErrors(['error' => 'Livro sem estoque disponível.']);
        }

        // ✅ Confirma a reserva (CORRIGIDO: confirmado em vez de confirmada)
        $reserva->update(['status' => 'confirmado']);

        // ✅ ENVIA EMAIL!
        try {
            $reserva->user->notify(new ReservationConfirmedNotification($reserva));
            return back()->with('success', 'Reserva confirmada e aluno notificado por email!');
        } catch (\Exception $e) {
            return back()->with('success', 'Reserva confirmada! (Email não pôde ser enviado: ' . $e->getMessage() . ')');
        }
    }

    public function cancelReservation($id)
    {
        $reserva = Reservation::findOrFail($id);

        // ✅ CORRIGIDO: cancelado em vez de cancelada
        $reserva->update(['status' => 'cancelado']);

        return back()->with('success', 'Reserva cancelada com sucesso!');
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