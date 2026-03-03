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
        Schema::table('order_detail', function (Blueprint $table) {
            $table->foreign(['order_id'], 'order_detail_ibfk_1')->references(['order_id'])->on('order')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['product_id'], 'order_detail_ibfk_2')->references(['product_id'])->on('product')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_detail', function (Blueprint $table) {
            $table->dropForeign('order_detail_ibfk_1');
            $table->dropForeign('order_detail_ibfk_2');
        });
    }
};
