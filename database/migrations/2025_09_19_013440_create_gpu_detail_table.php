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
        Schema::create('gpu_detail', function (Blueprint $table) {
            $table->integer('gpu_id', true);
            $table->integer('product_id')->index('product_id');
            $table->string('thuong_hieu');
            $table->string('gpu');
            $table->string('cuda');
            $table->string('toc_do_bo_nho');
            $table->string('bo_nho');
            $table->string('nguon');
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gpu_detail');
    }
};
