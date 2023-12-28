<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportProduct implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {

        return [
            'Tên thuốc',
            'Mã thuốc',
            'Nhóm thuốc',
            'Nhà sản xuất',
            'Đơn vị tính',
            'Số lượng',
            'Đơn giá',
            'Thông tin',
            'Cách dùng',
            'Trạng thái',
            'Hợp chất',
        ];
    }
    public function map($product): array
    {

        return [
            $product->name,
            $product->code,
            $product->category->name,
            $product->producer->name,
            $product->unit,
            $product->quantity,
            $product->price,
            $product->information,
            $product->usage,
            $product->status==1?'Hiển thị':'Ẩn tạm thời',
            $product->hoat_chat,
        ];
    }
}
