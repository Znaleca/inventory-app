<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stock_entry_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('quantity_used');
            $table->string('patient_id')->nullable();
            $table->string('procedure_type')->nullable();
            $table->string('used_by')->nullable();
            $table->datetime('used_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_logs');
    }
};
