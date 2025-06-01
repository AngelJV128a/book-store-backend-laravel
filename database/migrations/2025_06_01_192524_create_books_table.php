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
        Schema::create('books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->unsignedBigInteger('author_id');
            $table->string('isbn');
            $table->unsignedBigInteger('editorial_id');
            $table->unsignedBigInteger('category_id');
            $table->string('price');    
            $table->string('stock');
            $table->date('release_date');
            $table->string('language');
            $table->string('image');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('authors');
            $table->foreign('editorial_id')->references('id')->on('editorials');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
