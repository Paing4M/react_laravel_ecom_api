<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository implements BaseRepository {
  public function store($attributes) {

    $category = new Category();
    $created = $category->create([
      'name' => data_get($attributes, 'name'),
      'slug' => data_get($attributes, 'slug'),
      'description' => data_get($attributes, 'description'),
      'status' => data_get($attributes, 'status', 0),
      'meta_title' => data_get($attributes, 'meta_title'),
      'meta_keyword' => data_get($attributes, 'meta_keyword'),
      'meta_description' => data_get($attributes, 'meta_description'),
    ]);

    return $created;
  }

  public function update($category, $attributes) {

    $category->update([
      'name' => data_get($attributes, 'name', $category->name),
      'slug' => data_get($attributes, 'slug', $category->slug),
      'description' => data_get($attributes, 'description', $category->description),
      'status' => data_get($attributes, 'status', $category->status),
      'meta_title' => data_get($attributes, 'meta_title', $category->meta_title),
      'meta_keyword' => data_get($attributes, 'meta_keyword', $category->meta_keyword),
      'meta_description' => data_get($attributes, 'meta_description', $category->meta_description),
    ]);

    return $category;
  }

  public function delete($category) {
    $deleted = $category->delete();
    return $deleted;
  }
}
