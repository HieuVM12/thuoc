<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgencyList extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'ten_nha_thuoc'=>$this->ten_nha_thuoc,
            'sdt'=>$this->phone,
            'dia_chi'=>$this->address,
        ];
    }
}
