<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository implements BaseRepository {
  public function store($attributes) {
    $product = new Product();
    $product->meta_title = data_get($attributes, 'meta_title');
    $product->meta_keyword = data_get($attributes, 'meta_keyword');
    $product->meta_description = data_get($attributes, 'meta_description');
    $product->name = data_get($attributes, 'name');
    $product->slug = data_get($attributes, 'slug');
    $product->brand = data_get($attributes, 'brand');
    $product->category_id = data_get($attributes, 'category_id');
    $product->description = data_get($attributes, 'description');
    $product->selling_price = data_get($attributes, 'selling_price');
    $product->original_price = data_get($attributes, 'original_price');
    $product->qty = data_get($attributes, 'qty');
    $product->featured = data_get($attributes, 'featured');
    $product->popular = data_get($attributes, 'popular');
    $product->status = data_get($attributes, 'status');
    $product->image = data_get($attributes, 'image');

    $product->save();

    return $product;
  }

  public function update($product, $attributes) {
    $updated =  $product->update([
      'meta_title' => data_get($attributes, 'meta_title', $product->meta_title),
      'meta_keyword' => data_get($attributes, 'meta_keyword', $product->meta_keyword),
      'meta_description' => data_get($attributes, 'meta_description', $product->meta_description),
      'name' => data_get($attributes, 'name', $product->name),
      'slug' => data_get($attributes, 'slug', $product->slug),
      'brand' => data_get($attributes, 'brand', $product->brand),
      'category_id' => data_get($attributes, 'category_id', $product->category_id),
      'description' => data_get($attributes, 'description', $product->description),
      'selling_price' => data_get($attributes, 'selling_price', $product->selling_price),
      'original_price' => data_get($attributes, 'original_price', $product->original_price),
      'qty' => data_get($attributes, 'qty', $product->qty),
      'featured' => data_get($attributes, 'featured', $product->featured),
      'popular' => data_get($attributes, 'popular', $product->popular),
      'status' => data_get($attributes, 'status', $product->status),
      'image' => data_get($attributes, 'image', $product->image),
    ]);

    return $product;
  }

  public function delete($product) {
    $deleted = $product->delete();
    return $deleted;
  }
}
