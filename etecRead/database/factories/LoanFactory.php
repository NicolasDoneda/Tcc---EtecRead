<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Book;
use App\Models\Reservation;

class LoanFactory extends Factory
{
    protected $model = \App\Models\Loan::class;

    public function definition()
    {
        $reservation = Reservation::inRandomOrder()->first(); // pega uma reserva existente ou null

        return [
            'user_id' => $reservation ? $reservation->user_id : User::inRandomOrder()->first()->id,
            'book_id' => $reservation ? $reservation->book_id : Book::inRandomOrder()->first()->id,
            'loan_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'return_date' => null,
            'status' => 'ativo',
            'reservation_id' => $reservation ? $reservation->id : null,
        ];
    }
}
