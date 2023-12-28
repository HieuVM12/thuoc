<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $table = 'vouchers';
    protected $casts = [
        'doi_tuong_gui' => 'json',
    ];
    protected $fillable = [
        'tieu_de',
        'ma_giam_gia',
        'muc_tien',
        'tong_hoa_don',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'noi_dung',
        'doi_tuong_gui',
        'loai_voucher'
    ];
}
