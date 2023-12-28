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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('device')->nullable();
            $table->unsignedBigInteger('dai_ly_id');
            $table->text('ghi_chu')->nullable();
            $table->tinyInteger('ck_truoc')->default(0);
            $table->integer('voucher')->nullable();
            $table->integer('coin');
            $table->unsignedBigInteger('total_price')->nullable();
            $table->tinyInteger('trang_thai_thanh_toan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
