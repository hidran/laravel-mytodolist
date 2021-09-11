<?php

namespace Database\Factories;

use App\Models\Todo;
use App\Models\TodoList;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Todo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $list = TodoList::inRandomOrder()->first();
        return [
            'name' => $this->faker->text(24),
            'list_id' => $list->id,
            'completed' => $this->faker->randomElement([0, 1]),
            'duedate' =>  $this->faker->dateTimeBetween('-1 week', '+1 week')
        ];
    }
}
