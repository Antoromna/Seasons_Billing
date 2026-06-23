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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
              $table->foreignId('sale_id')
                  ->constrained('sales')
                  ->cascadeOnDelete();

            $table->foreignId('product_id')
                  ->nullable()
                  ->constrained('products')
                  ->nullOnDelete();

            $table->string('code');

            $table->string('product');

            $table->string('unit');

            $table->integer('quantity');

            $table->decimal('price', 12, 2);

            $table->decimal('discount', 12, 2)->default(0);

            $table->decimal('total', 12, 2);

            $table->string('tray')->nullable();

            $table->integer('tray_qty')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
