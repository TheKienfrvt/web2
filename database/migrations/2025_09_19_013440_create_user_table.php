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
        Schema::create('user', function (Blueprint $table) {
            $table->integer('user_id', true);
            $table->string('username');
            $table->string('password');
            $table->string('email')->unique('email');
            $table->enum('sex', ['nam', 'nữ'])->nullable();
            $table->string('phone_number', 10);
            $table->date('dob')->nullable();
            $table->enum('status', ['mở', 'khóa', 'đã xóa'])->default('mở');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
            $table->string('avatar_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
