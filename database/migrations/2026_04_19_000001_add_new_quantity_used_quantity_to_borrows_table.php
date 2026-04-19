<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            $table->integer('new_quantity')->default(0)->after('quantity_borrowed');
            $table->integer('used_quantity')->default(0)->after('new_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            $table->dropColumn(['new_quantity', 'used_quantity']);
        });
    }
};
