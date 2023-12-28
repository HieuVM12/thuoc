<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngredientProduct extends Model
{
    use HasFactory;
    protected $table = 'ingredient_products';
    protected $fillable = [
        'product_id',
        'ingredient_id',
        'content'
    ];

    /**
     * Get the user that owns the IngredientProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id', 'id');
    }
}
