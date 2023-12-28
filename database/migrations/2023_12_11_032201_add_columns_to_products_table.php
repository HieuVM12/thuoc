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
        Schema::table('products', function (Blueprint $table) {
            $table->json('hoat_chat')->nullable();
            $table->tinyInteger('combo')->default(0);
            $table->string('can_nang')->nullable();
            $table->string('quy_cach_dong_goi')->nullable();
            $table->string('tang_coin')->nullable();
            $table->json('san_pham_uu_dai')->nullable();
            $table->string('khuyen_mai')->nullable();
            $table->unsignedBigInteger('ten_san_pham_khuyen_mai')->nullable();
            $table->string('so_luong_khuyen_mai')->nullable();
            $table->date('ngay_bat_dau_km')->nullable();
            $table->date('ngay_ket_thuc_km')->nullable();
            $table->date('ngay_het_han')->nullable();
            $table->string('url')->nullable();
            $table->string('nuoc_sx')->nullable();
            $table->text('chi_dinh')->nullable();
            $table->text('chong_chi_dinh')->nullable();
            $table->text('tuong_tac')->nullable();
            $table->text('bao_quan')->nullable();
            $table->text('qua_lieu')->nullable();
            $table->json('hashtag')->nullable();
            $table->string('sl_toi_thieu')->nullable();
            $table->string('sl_toi_da')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
