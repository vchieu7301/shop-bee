<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     * 
     *
     * @return array<string, mixed>
     */

    protected $model = Product::class;

    public function definition(): array
    {
        $imagePaths = [
            'image_01.jpg',
            'image_02.jpg',
            'image_03.jpg',
            'image_04.jpg',
        ];

        $randomImagePath = Arr::random($imagePaths); // Chọn một đường dẫn ảnh ngẫu nhiên

        Storage::copy($randomImagePath, basename($randomImagePath));

        return [
            'product_name' => $this->faker->sentence(3), 
            'price' => $this->faker->randomFloat(2, 10, 1000), 
            'product_description' => $this->faker->paragraph(3), 
            'quantity' => $this->faker->numberBetween(1, 100), 
            'category_id' => function () {
                return \App\Models\Category::factory()->create()->id;
            },
            'images' => basename($randomImagePath)
        ];
    }
}
