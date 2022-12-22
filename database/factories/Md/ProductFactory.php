<?php

namespace Database\Factories\Md;

use App\Models\Md\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */


    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
