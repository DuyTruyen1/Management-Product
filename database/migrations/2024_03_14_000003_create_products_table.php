<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug')->unique();
      $table->text('description')->nullable();
      $table->string('image')->nullable();
      $table->decimal('price', 15, 2);
      $table->decimal('discount_price', 15, 2)->nullable();
      $table->integer('stock')->default(0);
      $table->foreignId('category_id')->constrained()->onDelete('cascade');
      $table->json('specifications')->nullable();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('products');
  }
};
