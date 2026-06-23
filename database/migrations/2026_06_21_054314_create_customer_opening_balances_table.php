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
        Schema::create('customer_opening_balances', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('customer_id');

        $table->decimal('amount', 12, 2)->default(0);

        $table->text('remarks')->nullable();

        $table->timestamps();

        $table->foreign('customer_id')
            ->references('id')
            ->on('customers')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_opening_balances');
    }
};
