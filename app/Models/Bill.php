<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    use HasFactory;
    protected $table = 'bills';
    protected $fillable = [
        'device',
        'dai_ly_id',
        'ghi_chu',
        'ck_truoc',
        'voucher',
        'coin',
        'total_price',
        'trang_thai_thanh_toan',
        'ten',
        'sdt',
        'email',
        'dia_chi',
        'ma_so_thue',
    ];
    /**
     * Get all of the comments for the Bill
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detail_bills(): HasMany
    {
        return $this->hasMany(DetailBill::class, 'bill_id', 'id');
    }
}
