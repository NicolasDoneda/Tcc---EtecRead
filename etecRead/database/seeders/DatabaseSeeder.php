<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Category::factory(5)->create();
        \App\Models\Author::factory(10)->create();
        \App\Models\User::factory(10)->create();

        \App\Models\Book::factory(15)->create()->each(function ($book) {
            $authors = \App\Models\Author::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $book->authors()->sync($authors);
        });

        \App\Models\Reservation::factory(10)->create();
        \App\Models\Loan::factory(10)->create();
    }
}
