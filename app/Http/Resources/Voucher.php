<?php

namespace App\Http\Resources;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Voucher extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->tieu_de,
            'value' => $this->ma_giam_gia,
            'content' => "Ngày kết thúc: ".Carbon::parse($this->ngay_ket_thuc)->format('Y-m-d H:i:s'),
        ];
    }
}
