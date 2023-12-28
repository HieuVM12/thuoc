<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Resources\Voucher as ResourcesVoucher;
use App\Models\Cart;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function list_voucher(Request $request)
    {
        if(!$request->data_id){
            return response()->json([
                "code" => 1,
                "message" => [
                    "nhap data_id"
                ],
                'respone' => null,
            ],200);
        }else{
            if (!is_array($request->data_id)) {
                return response()->json([
                    "code" => 1,
                    "message" => [
                        "data_id la kieu mang"
                    ],
                    'respone' => null,
                ],200);
            }else{
                foreach($request->data_id as $dataId){
                    $checkPrdInCart = Cart::where('id_member',Auth::guard('customer-api')->user()->id)->find($dataId);
                    if($checkPrdInCart){
                        $vouchers = Voucher::where('ngay_bat_dau', '<=', Carbon::now())
                                    ->where('ngay_ket_thuc', '>=', Carbon::now())
                                    ->get();
                        $data = [
                            'code' => 0,
                            'message' => [],
                            'response' => ResourcesVoucher::collection($vouchers),
                        ];
                        return response()->json($data,200);
                    }
                }
                return response()->json([
                    "code" => 1,
                    "message" => [
                        "Khong co san pham trong gio hàng"
                    ],
                    'respone' => null,
                ],200);
            }
        }
    }

    public function discount(Request $request)
    {
        $requiredFields = ['coin', 'data_id'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (empty($request->input($field))) {
                $missingFields[] = "Vui lòng nhập " . $field;
            }
        }
        if($request->input('coin')!=0 && $request->input('coin')!=1){
            $missingFields[] = "Coin khong hop le";
        }
        if(!is_array($request->data_id)){
            $missingFields[] = "data_id la kieu mang";
        }
        if (!empty($missingFields)) {
            return response()->json([
                'code' => 1,
                'message' => $missingFields,
                'response' => null
            ], 200);
        }
        $totalPrice = 0;
        $check = 0;
        foreach($request->data_id as $dataId){
            $checkPrdInCart = Cart::where('id_member',Auth::guard('customer-api')->user()->id)->find($dataId);
            if($checkPrdInCart){
                $check = 1;
                $totalPrice += $checkPrdInCart->product->price * $checkPrdInCart->so_luong;
            }
        }
        if($check == 0){
            return response()->json([
                'code' => 1,
                'message' => [
                    'sp khong co trong gio hang'
                ],
                'response' => null
            ], 200);
        }
        $voucher_available = 0 ;
        $coin_available = 0;
        $money = 0 ;
        if($request->coin == 1){
            $customer = Auth::guard('customer-api')->user();
            if($customer->coin > 0){
                $coin_available = 1;
                $coin_description = "Su dung ".number_format($customer->coin, 0, ',', '.')." coin duoc giam ".number_format($customer->coin, 0, ',', '.')." VND";
                $money += $customer->coin;
            }
            else{
                $coin_description = "Khong du coin su dung";
            }
        }
        if($request->voucher){
            $voucher = Voucher::where('ma_giam_gia',$request->voucher)->where('ngay_bat_dau', '<=', Carbon::now())->where('ngay_ket_thuc', '>=', Carbon::now())->first();
            if(!$voucher){
                $voucher_description = "Ma giam gia khong hop le";
            }else{
                if($totalPrice < $voucher->tong_hoa_don){
                    $voucher_description = "voucher khong hop le do don hang chua dat gia tri toi thieu ".number_format($voucher->tong_hoa_don, 0, ',', '.')." VND";
                }else{
                    $voucher_available = 1;
                    $money += $voucher->muc_tien;
                    $voucher_description = "Su dung voucher ".$voucher->ma_giam_gia." duoc giam ".number_format($voucher->muc_tien, 0, ',', '.')." VND";
                }
            }

        }else{
            $voucher_description = "";
        }
        return response()->json([
            'code'=>0,
            'message'=>[],
            'response'=>[
                'voucher_available'=>$voucher_available,
                'coin_available'=>$coin_available,
                'money' =>$money,
                'voucher_description'=>$voucher_description,
                'coin_description' =>$coin_description,
            ]
        ],200);
    }
}
