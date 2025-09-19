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
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'loan_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'return_date' => null,
            'status' => 'ativo',
            'reservation_id' => null,
        ];
    }
}
