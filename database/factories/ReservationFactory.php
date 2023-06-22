<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $user = User::factory()->create();

        return [
            'room_id' => $this->faker->numberBetween(1,15),
            'user_id' => $this->faker->numberBetween(1,5),
            'name' => $user->name,
            'address' => $user->address,
            'contact_no' => $user->contact_no,
            'payment_process' => 'paypal',
            'status' => $this->faker->randomElement(["approved", "cancelled", "pending"])

        ];
    }
}
