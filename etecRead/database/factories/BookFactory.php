<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class BookFactory extends Factory
{
    protected $model = \App\Models\Book::class;

    public function definition()
    {
        return [
            'isbn' => $this->faker->unique()->isbn13,
            'title' => $this->faker->sentence(3),
            'category_id' => Category::factory(),
            'year' => $this->faker->year(),
            'total_quantity' => $this->faker->numberBetween(1, 10),
            'available_quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
