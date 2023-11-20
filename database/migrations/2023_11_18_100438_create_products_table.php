<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug');
      $table->unsignedBigInteger('category_id');
      $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
      $table->string('brand')->nullable();
      $table->longText('description')->nullable();
      $table->string('selling_price')->nullable();
      $table->string('original_price');
      $table->string('qty');
      $table->string('image')->nullable();
      $table->tinyInteger('featured')->default(0)->nullable();
      $table->tinyInteger('popular')->default(0)->nullable();
      $table->tinyInteger('status')->default(0)->nullable();
      $table->string('meta_title')->nullable();
      $table->string('meta_keyword')->nullable();
      $table->longText('meta_description')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('products');
  }
};
