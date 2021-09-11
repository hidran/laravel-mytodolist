<?php

namespace Database\Factories;

use App\Models\{TodoList, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TodoList::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        return [
            'name' => $this->faker->text(32),
            'user_id' => $user->id

        ];
    }
}
