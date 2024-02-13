<?php

namespace Database\Factories;

use App\Helpers\DictionaryHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use PSpell\Dictionary;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'description' => $this->faker->unique()->randomElement(DictionaryHelper::getTasks()), //custom function, better than faker sentence
            'estimated_time' => $faker->numberBetween(10*60, 8*60*60), // Random estimated time between 10 minutes and 8 hour
            'user_id' => function () {
                return User::factory()->create()->id;
            },
        ];
    }
}
