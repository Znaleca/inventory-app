<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('is_expirable')->default(false)->after('stock_used');
        });

        // Devices are never expirable; consumables default to expirable
        DB::table('items')->where('item_type', 'consumable')->update(['is_expirable' => true]);
        DB::table('items')->where('item_type', 'device')->update(['is_expirable' => false]);
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('is_expirable');
        });
    }
};
