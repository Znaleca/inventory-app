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
        Schema::table('borrows', function (Blueprint $table) {
            $table->string('approved_by')->nullable()->after('department');
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->string('approved_by')->nullable()->after('bio_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            $table->dropColumn('approved_by');
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn('approved_by');
        });
    }
};
