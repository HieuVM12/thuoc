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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('id_nguoi_quan_ly')->nullable();
            $table->string('address')->nullable();
            $table->string('ma_so_thue')->nullable();
            $table->string('ten_nha_thuoc')->nullable();
            $table->tinyInteger('trang_thai')->default(0);
            $table->string('img')->nullable();
            $table->unsignedBigInteger('id_tinh')->default(1);
            $table->foreign('id_tinh')->references('id')->on('tinh')->onDelete('cascade');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
