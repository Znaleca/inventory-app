<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop specialization if it has not been dropped
        Schema::table('staff', function (Blueprint $table) {
            if (Schema::hasColumn('staff', 'specialization')) {
                $table->dropColumn('specialization');
            }
        });

        // Convert the 'type' column from ENUM to a standard STRING 
        // to support the 'programmer' and 'tech support' values
        Schema::table('staff', function (Blueprint $table) {
            $table->string('type')->default('programmer')->change();
        });
        
        // Update existing data to default fallback so it stays clean 
        // (technician -> tech support, etc. just in case)
        DB::table('staff')->where('type', 'technician')->update(['type' => 'tech support']);
        DB::table('staff')->whereIn('type', ['doctor', 'nurse', 'other'])->update(['type' => 'programmer']);
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->string('specialization')->nullable();
        });
    }
};
