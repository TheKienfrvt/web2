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
        Schema::create('order', function (Blueprint $table) {
            $table->integer('order_id', true);
            $table->integer('user_id')->index('user_id');
            $table->string('address');
            $table->timestamp('order_date')->useCurrent();
            $table->timestamp('delivery_date')->nullable();
            $table->integer('total_amount');
            $table->enum('status', ['đã nhận hàng', 'chờ xác nhận', 'đang giao', 'đã xác nhận', 'đã hủy'])->default('chờ xác nhận');
            $table->enum('payment_method', ['chuyển khoản', 'tiền mặt']);
            $table->enum('created_by', ['customer', 'admin'])->default('customer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
