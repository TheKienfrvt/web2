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
        Schema::create('laptop_detail', function (Blueprint $table) {
            $table->integer('laptop_id', true);
            $table->integer('product_id')->index('product_id');
            $table->string('thuong_hieu');
            $table->string('cpu');
            $table->string('gpu');
            $table->string('ram');
            $table->string('dung_luong');
            $table->string('kich_thuoc_man_hinh');
            $table->string('do_phan_giai');
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laptop_detail');
    }
};
