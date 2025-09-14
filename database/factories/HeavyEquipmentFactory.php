<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\HeavyEquipment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HeavyEquipment>
 */
class HeavyEquipmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HeavyEquipment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' Equipment',
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(50000, 500000),
            'jenis_sewa' => $this->faker->randomElement(['Perhari', 'Perjam', 'Pertrip']),
            'availability' => $this->faker->boolean(),
            'image' => null,
        ];
    }
}
