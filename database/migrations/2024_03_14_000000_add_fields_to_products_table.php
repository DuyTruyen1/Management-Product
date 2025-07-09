<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('products', function (Blueprint $table) {
      $table->string('image')->nullable()->after('description');
      $table->string('category')->nullable()->after('image');
      $table->integer('stock_quantity')->default(0)->after('category');
      $table->string('sku')->nullable()->after('stock_quantity');
      $table->boolean('is_available')->default(true)->after('sku');
      $table->decimal('discount_price', 10, 2)->nullable()->after('price');
      $table->text('short_description')->nullable()->after('name');
      $table->json('specifications')->nullable()->after('description');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('products', function (Blueprint $table) {
      $table->dropColumn([
        'image',
        'category',
        'stock_quantity',
        'sku',
        'is_available',
        'discount_price',
        'short_description',
        'specifications'
      ]);
    });
  }
};
