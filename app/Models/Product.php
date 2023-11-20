<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
  use HasFactory;
  protected $fillable = [
    'name',
    'slug',
    'category_id',
    'brand',
    'description',
    'selling_price',
    'original_price',
    'qty',
    'image',
    'featured',
    'popular',
    'status',
    'meta_title',
    'meta_keyword',
    'meta_description',
  ];

  protected $with = ['category'];

  public function category() {
    return $this->belongsTo(Category::class, 'category_id');
  }
}
