<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('categories', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug')->unique();
      $table->text('description')->nullable();
      $table->unsignedBigInteger('parent_id')->nullable();
      $table->timestamps();

      $table->foreign('parent_id')
        ->references('id')
        ->on('categories')
        ->onDelete('set null');
    });

    // Modify products table to add category_id
    Schema::table('products', function (Blueprint $table) {
      // Drop the old category column if it exists
      if (Schema::hasColumn('products', 'category')) {
        $table->dropColumn('category');
      }

      $table->unsignedBigInteger('category_id')->nullable()->after('description');
      $table->foreign('category_id')
        ->references('id')
        ->on('categories')
        ->onDelete('set null');
    });
  }

  public function down(): void
  {
    Schema::table('products', function (Blueprint $table) {
      $table->dropForeign(['category_id']);
      $table->dropColumn('category_id');
      $table->string('category')->nullable(); // Add back the old column
    });

    Schema::dropIfExists('categories');
  }
};
