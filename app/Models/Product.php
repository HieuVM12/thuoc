<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $casts = [
        'hoat_chat' => 'json',
        'hashtag' =>'json',
    ];
    protected $fillable =[
        'code',
        'category_id',
        'producer_id',
        'name',
        'unit',
        'quantity',
        'price',
        'information',
        'usage',
        'status',
        'hoat_chat',
    ];

    /**
     * Get the user that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    /**
     * Get the user that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class, 'producer_id', 'id');
    }
    /**
     * Get all of the comments for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function image_products(): HasMany
    {
        return $this->hasMany(ImageProduct::class, 'id_san_pham', 'id');
    }
    /**
     * Get all of the comments for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingredient_products(): HasMany
    {
        return $this->hasMany(IngredientProduct::class, 'product_id', 'id');
    }
}
