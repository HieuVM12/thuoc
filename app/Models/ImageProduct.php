<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImageProduct extends Model
{
    use HasFactory;
    protected $table = 'image_products';
    protected $fillable = [
        'id_san_pham',
        'img',
    ];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_san_pham', 'id');
    }
}
