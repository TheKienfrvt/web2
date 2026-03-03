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
        Schema::create('screen_detail', function (Blueprint $table) {
            $table->integer('screen_id', true);
            $table->integer('product_id')->index('product_id');
            $table->string('thuong_hieu');
            $table->string('kich_thuoc_man_hinh');
            $table->string('tang_so_quet');
            $table->string('ti_le');
            $table->string('tam_nen');
            $table->string('do_phan_giai');
            $table->string('khoi_luong');
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screen_detail');
    }
};
