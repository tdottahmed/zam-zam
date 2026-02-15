<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'status' => 'pending',
            'payment_status' => 'pending',
            'total_amount' => $this->faker->randomFloat(2, 10, 500),
            'shipping_amount' => $this->faker->randomFloat(2, 5, 20),
            'tax_amount' => $this->faker->randomFloat(2, 1, 10),
            'shipping_address' => [
                'name' => $this->faker->name,
                'address' => $this->faker->address,
                'city' => $this->faker->city,
                'state' => $this->faker->state,
                'zip' => $this->faker->postcode,
                'country' => $this->faker->country,
            ],
            'billing_address' => [
                'name' => $this->faker->name,
                'address' => $this->faker->address,
                'city' => $this->faker->city,
                'state' => $this->faker->state,
                'zip' => $this->faker->postcode,
                'country' => $this->faker->country,
            ],
            'notes' => $this->faker->sentence,
        ];
    }
}
