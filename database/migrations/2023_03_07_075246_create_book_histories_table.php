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
        Schema::create('book_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->integer('qty');
            $table->text('description');
            $table->enum('operation',['Addition','Reduction']);
            $table->text('book_stock_added_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_histories');
    }
};
