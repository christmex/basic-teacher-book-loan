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
        Schema::create('book_lacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained();
            $table->foreignId('semester_id')->constrained();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->nullable()->constrained();
            $table->text('book_name')->nullable();
            $table->integer('qty')->default(1);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_lacks');
    }
};
