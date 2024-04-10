<?php

namespace Database\Factories;

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
    public function definition(): array
    {
        $name = fake()->unique()->name();
        $slug = Str::slug($name);
        $subCategories = [5,6];
        $brands = [1,2,6,7,8,9];
        $subCatAndKey = array_rand($subCategories);
        $brandAndKey = array_rand($brands);
        return [
            'name'=> $name,
            'slug'=> $slug,
            'category_id'=> 28,
            'sub_category_id'=> $subCategories[$subCatAndKey],
            'brand_id' => $brands[$brandAndKey],
            'price'=> rand(10,1000),
            'sku' => rand(100,100000),
            'track_qty'=>'Yes',
            'qty'=>10,
            'is_featured'=> 'Yes',
            'status'=>1
        ];
    }
}
