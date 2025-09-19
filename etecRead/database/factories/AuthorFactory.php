<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    protected $model = \App\Models\Author::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'bio' => $this->faker->paragraph(),
            'birth_date' => $this->faker->date(),
            'death_date' => $this->faker->optional()->date(),
        ];
    }
}
