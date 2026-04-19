<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Items: device-level serial number
        Schema::table('items', function (Blueprint $table) {
            $table->string('serial_number')->nullable()->after('sku');
        });

        // Stock entries: per-batch/delivery serial number
        Schema::table('stock_entries', function (Blueprint $table) {
            $table->string('serial_number')->nullable()->after('lot_number');
        });

        // Transfers: carry serial number when device moves
        Schema::table('transfers', function (Blueprint $table) {
            $table->string('serial_number')->nullable()->after('notes');
        });

        // Borrows: carry serial number when device is borrowed
        Schema::table('borrows', function (Blueprint $table) {
            $table->string('serial_number')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('serial_number');
        });
        Schema::table('stock_entries', function (Blueprint $table) {
            $table->dropColumn('serial_number');
        });
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn('serial_number');
        });
        Schema::table('borrows', function (Blueprint $table) {
            $table->dropColumn('serial_number');
        });
    }
};
