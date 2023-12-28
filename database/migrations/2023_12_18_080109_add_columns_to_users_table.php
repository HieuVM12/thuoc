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
        Schema::table('users', function (Blueprint $table) {
            $table->string('ten_nha_thuoc')->nullable();
            $table->tinyInteger('trang_thai')->default(0);
            $table->string('img')->nullable();
            $table->unsignedBigInteger('id_tinh')->default(1);
            $table->foreign('id_tinh')->references('id')->on('tinh')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
