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
        Schema::table('cart_item', function (Blueprint $table) {
            $table->foreign(['cart_id'], 'cart_item_ibfk_1')->references(['cart_id'])->on('cart')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['product_id'], 'cart_item_ibfk_2')->references(['product_id'])->on('product')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_item', function (Blueprint $table) {
            $table->dropForeign('cart_item_ibfk_1');
            $table->dropForeign('cart_item_ibfk_2');
        });
    }
};
