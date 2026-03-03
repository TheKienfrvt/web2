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
        Schema::create('headset_detail', function (Blueprint $table) {
            $table->integer('headset_id', true);
            $table->integer('product_id')->index('product_id');
            $table->string('thuong_hieu');
            $table->enum('micro', ['có', 'không']);
            $table->string('trong_luong');
            $table->string('pin');
            $table->string('ket_noi');
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('headset_detail');
    }
};
