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
            $table->string('bio_id')->nullable()->after('staff_id');
            $table->string('department')->nullable()->after('bio_id');
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->string('bio_id')->nullable()->after('destination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            $table->dropColumn(['bio_id', 'department']);
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn('bio_id');
        });
    }
};
