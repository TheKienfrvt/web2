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
        Schema::table('receipt_detail', function (Blueprint $table) {
            $table->foreign(['receipt_id'], 'receipt_detail_ibfk_1')->references(['receipt_id'])->on('receipt')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['product_id'], 'receipt_detail_ibfk_2')->references(['product_id'])->on('product')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipt_detail', function (Blueprint $table) {
            $table->dropForeign('receipt_detail_ibfk_1');
            $table->dropForeign('receipt_detail_ibfk_2');
        });
    }
};
