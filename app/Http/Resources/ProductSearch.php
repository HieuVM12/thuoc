<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSearch extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'ten_san_pham'=>$this->name,
            'ban_chay'=>$this->ban_chay,
            'khuyen_mai'=>$this->khuyen_mai,
        ];
    }
}
