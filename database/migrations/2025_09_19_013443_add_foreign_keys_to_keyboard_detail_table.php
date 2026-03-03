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
        Schema::table('keyboard_detail', function (Blueprint $table) {
            $table->foreign(['product_id'], 'keyboard_detail_ibfk_1')->references(['product_id'])->on('product')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keyboard_detail', function (Blueprint $table) {
            $table->dropForeign('keyboard_detail_ibfk_1');
        });
    }
};
