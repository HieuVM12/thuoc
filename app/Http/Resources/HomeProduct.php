<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Hashtag as ResourcesHashtag;

class HomeProduct extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($this->hashtag){
            $tags = ResourcesHashtag::collection($this->hashtag);
        }else{
            $tags = null;
        }
        return [
            'id' => $this->id,
            'khuyen_mai' => $this->khuyen_mai,
            'ten_san_pham' => $this->name,
            'quy_cach_dong_goi' => $this->quy_cach_dong_goi,
            'so_luong' => $this->quantity,
            'don_gia'=>$this->price,
            'bonus_coin' => null,
            'so_luong_toi_thieu'=>$this->sl_toi_thieu,
            'so_luong_toi_da'=>$this->sl_toi_da,
            'img_url'=>asset($this->image_products[0]->img),
            'discount_price'=>$this->price,
            'detail_url'=>$this->url,
            'tags'=> $tags,
        ];
    }
}
