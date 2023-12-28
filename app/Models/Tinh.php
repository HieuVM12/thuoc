<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tinh extends Model
{
    use HasFactory;
    protected $table = 'tinh';
    protected $fillable = [
        'ten',
    ];

    /**
     * Get all of the comments for the Tinh
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_tinh', 'id');
    }

    /**
     * Get all of the comments for the Tinh
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agency_list(): HasMany
    {
        return $this->hasMany(Customer::class, 'id_tinh', 'id');
    }
}
