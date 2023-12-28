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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de');
            $table->string('ma_giam_gia');
            $table->integer('muc_tien');
            $table->integer('tong_hoa_don');
            $table->timestamp('ngay_bat_dau');
            $table->text('noi_dung');
            $table->json('doi_tuong_gui')->nullable();
            $table->tinyInteger('loai_voucher')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
