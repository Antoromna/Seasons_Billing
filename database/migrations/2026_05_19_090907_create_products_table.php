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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('code')->unique();

            $table->enum('unit', ['box', 'kgs', 'tray']);

            $table->boolean('tray_required')->default(0);

            $table->string('hsn_no')->nullable();
            $table->decimal('gst', 5, 2)->nullable();

            $table->integer('stock')->default(0);
            $table->decimal('selling_price', 12, 2)->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
