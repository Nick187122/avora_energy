<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('regular_price', 14, 2);
            $table->decimal('sale_price', 14, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->json('specifications')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();

            $table->index(['category_id', 'brand_id', 'is_active', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};