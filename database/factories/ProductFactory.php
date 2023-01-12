<?php

namespace Database\Factories;

use App\Models\Type;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
    public function definition()
    {
        $types = Type::pluck('id')->toArray();
        $units = Unit::pluck('id')->toArray();
        return [
            'product_code' => strtoupper(Str::random(10)),
            'type_id' => $types[rand(0, count($types) - 1)],
            'unit_id' => $units[rand(0, count($units) - 1)],
            'name' => $this->faker->word(),
            'price' => $this->faker->randomNumber(2) . '0000',
        ];
    }
}
