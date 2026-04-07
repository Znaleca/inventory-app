<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            // 'out' = we lend to another department (default, backward compatible)
            // 'in'  = we borrow FROM another department
            $table->string('type', 10)->default('out')->after('status');
            // The department we are borrowing FROM (only set when type=in)
            $table->string('source_department', 255)->nullable()->after('department');
        });
    }

    public function down(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            $table->dropColumn(['type', 'source_department']);
        });
    }
};
