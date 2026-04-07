<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            // new_quantity = how much of the transfer is new stock
            // used_quantity = how much of the transfer is used stock
            // Both can be 0; their sum equals the total `quantity`
            $table->integer('new_quantity')->default(0)->after('quantity');
            $table->integer('used_quantity')->default(0)->after('new_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn(['new_quantity', 'used_quantity']);
        });
    }
};
