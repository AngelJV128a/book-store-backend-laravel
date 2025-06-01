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
        Schema::create('sale_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sale');
            $table->uuid('id_book');
            $table->tinyInteger('quantity');
            $table->decimal('unit_price',10,2);
            $table->timestamps();

            $table->foreign('id_sale')->references('id')->on('sales');
            $table->foreign('id_book')->references('id')->on('books');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_detail');
    }
};
