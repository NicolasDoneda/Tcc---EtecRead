<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Author;
use App\Models\User;
use App\Models\Book;
use App\Models\Reservation;
use App\Models\Loan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1️ Cria categorias
        $categories = Category::factory(5)->create();

        // 2️ Cria autores
        $authors = Author::factory(10)->create();

        // 3️ Cria usuários (alunos)
        $users = User::factory(10)->create();

        // 4️ Cria livros e associa autores
        $books = Book::factory(15)->create()->each(function ($book) use ($authors, $categories) {
            // Garante que o livro tenha uma categoria existente
            $book->category_id = $categories->random()->id;
            $book->save();

            // Associa de 1 a 3 autores existentes
            $bookAuthors = $authors->random(rand(1, 3))->pluck('id')->toArray();
            $book->authors()->sync($bookAuthors);
        });

        // 5️ Cria reservas associando livros e usuários existentes
        $reservations = Reservation::factory(10)->create()->each(function ($reservation) use ($users, $books) {
            $reservation->user_id = $users->random()->id;
            $reservation->book_id = $books->random()->id;
            $reservation->save();
        });

        // 6️ Cria empréstimos, associando usuários, livros e reservas existentes
        Loan::factory(10)->create()->each(function ($loan) use ($users, $books, $reservations) {
            $reservation = $reservations->random();
            $loan->user_id = $reservation->user_id;
            $loan->book_id = $reservation->book_id;
            $loan->reservation_id = $reservation->id;
            $loan->save();
        });
    }
}
