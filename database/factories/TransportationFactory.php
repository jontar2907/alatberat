<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transportation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transportation>
 */
class TransportationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transportation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['jemput sendiri', 'diantar oleh dinas']),
            'cost' => $this->faker->numberBetween(0, 200000),
        ];
    }
}
