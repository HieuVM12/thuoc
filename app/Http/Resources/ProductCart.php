<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Hashtag as ResourcesHashtag;

class ProductCart extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($this->product->hashtag){
            $tags = ResourcesHashtag::collection($this->product->hashtag);
        }else{
            $tags = null;
        }
        return [
            'gio_hang_id' => $this->id,
            'id_member' => $this->id_member,
            'id' => $this->id_product,
            'so_luong' => $this->so_luong,
            'ten_san_pham' => $this->product->name,
            'quy_cach_dong_goi' => $this->product->quy_cach_dong_goi,
            'khuyen_mai'=>$this->product->khuyen_mai,
            'don_gia' =>$this->product->price,
            'gia_uu_dai'=>null,
            'id_sp_km'=>$this->product->ten_san_pham_khuyen_mai,
            'so_luong_km'=>$this->product->so_luong_khuyen_mai,
            'so_luong_toi_thieu'=>$this->product->sl_toi_thieu,
            'so_luong_toi_da'=>$this->product->sl_toi_da,
            'ngay_het_han'=>$this->product->ngay_het_han,
            'bonus_coins'=>0,
            'img_url'=>asset($this->product->image_products[0]->img),
            'img_sp_km'=>null,
            'discount_price'=>$this->product->price,
            'detail_url'=>null,
            'tags'=>$tags,
        ];
    }
}
