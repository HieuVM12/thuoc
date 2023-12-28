<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailBill extends Model
{
    use HasFactory;
    protected $table = 'detail_bills';
    protected $fillable = [
        'bill_id',
        'product_id',
        'so_luong',
        'don_gia',
    ];

    /**
     * Get all of the comments for the DetailBill
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    /**
     * Get the product that owns the DetailBill
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
