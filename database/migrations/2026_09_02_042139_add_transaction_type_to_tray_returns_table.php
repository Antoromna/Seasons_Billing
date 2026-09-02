<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
   public function up(): void
    {
        Schema::table('tray_returns', function (Blueprint $table) {
            $table->string('transaction_type')
                ->default('returned')
                ->after('customer_id');
        });
    }

        public function down(): void
    {
        Schema::table('tray_returns', function (Blueprint $table) {
            $table->dropColumn('transaction_type');
        });
    }
};