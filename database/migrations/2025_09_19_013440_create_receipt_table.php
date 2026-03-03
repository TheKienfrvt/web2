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
        Schema::create('receipt', function (Blueprint $table) {
            $table->integer('receipt_id', true);
            $table->integer('supplier_id')->nullable()->index('supplier_id');
            $table->timestamp('order_date')->useCurrent();
            $table->enum('status', ['đang chờ', 'đã nhận', 'đã hủy'])->nullable()->default('đang chờ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt');
    }
};
