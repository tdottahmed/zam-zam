<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'name' => ucfirst($name),
            'product_code' => strtoupper($this->faker->bothify('PRO-????-####')),
            'weight' => $this->faker->randomElement(['240GM', '500GM', '1KG', '5KG']),
            'pcs_in_ctn' => $this->faker->randomElement(['12', '24', '48']),
            'box_price' => $this->faker->randomFloat(2, 50, 200),
            'unit_price' => $this->faker->randomFloat(2, 5, 20),

            'buying_price' => $this->faker->randomFloat(2, 3, 15),
            'notes' => $this->faker->sentence(),
        ];
    }
}
