<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrow_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrow_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stock_entry_id')->constrained()->cascadeOnDelete();
            // 'new' = device was new when borrowed, 'used' = device was already used when borrowed
            $table->enum('original_condition', ['new', 'used'])->default('new');
            // null = still out, 'returned_new' = came back and going to new stock,
            // 'returned_used' = came back and going to used pool, 'consumed' = used/consumed
            $table->enum('disposition', ['returned_new', 'returned_used', 'consumed'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrow_entries');
    }
};
