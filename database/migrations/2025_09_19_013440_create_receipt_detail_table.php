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
        Schema::create('receipt_detail', function (Blueprint $table) {
            $table->integer('receipt_detail_id', true);
            $table->integer('receipt_id')->index('receipt_id');
            $table->integer('product_id')->index('product_id');
            $table->integer('quantity');
            $table->integer('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_detail');
    }
};
