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
        Schema::create('keyboard_detail', function (Blueprint $table) {
            $table->integer('keyboard_id', true);
            $table->integer('product_id')->index('product_id');
            $table->string('thuong_hieu');
            $table->string('ket_noi');
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keyboard_detail');
    }
};
