<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('categories', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug');
      $table->longText('description')->nullable();
      $table->bigInteger('status')->default(0)->nullable(); // 0 is enable and 1 is disable
      $table->text('meta_title')->nullable();
      $table->text('meta_keyword')->nullable();
      $table->longText('meta_description')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('categories');
  }
};
