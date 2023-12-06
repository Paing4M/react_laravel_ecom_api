<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory {
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array {
    $name = $this->faker->text(15);
    $slug = Str::slug($name);



    return [
      'name' => $name,
      'slug' => $slug,
      'category_id' => $this->faker->randomElement([1, 2, 3, 5, 8, 9, 10, 11, 12, 13, 14, 15]),
      'brand' => $this->faker->text(5),
      'description' => $this->faker->text(30),
      'original_price' => $this->faker->randomNumber(4),
      'qty' => 10,
      'featured' => $this->faker->randomElement([1, 0]),
      'popular' => $this->faker->randomElement([1, 0]),
      'status' => $this->faker->randomElement([1, 0]),
    ];
  }
}
