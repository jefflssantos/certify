<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Credential>
 */
class CredentialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'module_id' => null,
            'uuid' => null,
            'issued_to' => $this->faker->name,
            'email' => $this->faker->email,
            'image' => $this->faker->imageUrl,
            'pdf' => $this->faker->url,
            'expires_at' => $this->faker->numberBetween(0, 2)
                ? $this->faker->dateTimeInInterval('+ 60 days', '+ 2 years')->format('Y-m-d H:i:s')
                : null
        ];
    }
}
