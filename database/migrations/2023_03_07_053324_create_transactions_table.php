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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained();
            $table->foreignId('semester_id')->constrained();
            $table->foreignId('member_id')->constrained();
            $table->foreignId('book_id')->constrained();
            $table->integer('qty');
            $table->date('loaned_at');
            $table->date('returned_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
