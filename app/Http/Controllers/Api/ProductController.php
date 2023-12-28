<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product as ResourcesProduct;
use App\Http\Resources\ProductCart as ResourcesProductCart;
use App\Http\Resources\HomeProduct as ResourcesHomeProduct;
use App\Http\Resources\AgencyList as ResourcesAgencyList;
use App\Http\Resources\ProductSearch;
use App\Models\Bill;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\DetailBill;
use App\Models\Product;
use App\Models\Tinh;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $itemsPerPage = $request->input('itemsPerPage', 50);
        $search = $request->input('search');
        $nhom_thuoc = $request->input('nhom_thuoc');
        $nha_san_xuat = $request->input('nha_san_xuat');
        $id_hoat_chat = $request->input('hoat_chat');
        $category = $request->input('category');
        $hashtag = $request->input('hashtag');
        $products = Product::query();
        if ($search) {
            $products->where('name', 'like', '%' . $search . '%');
        }
        if ($nhom_thuoc) {
            $products->where('category_id', $nhom_thuoc);
        }
        if ($nha_san_xuat) {
            $products->where('producer_id', $nha_san_xuat);
        }
        if ($id_hoat_chat) {
            $products->whereJsonContains('hoat_chat', ['id_hoat_chat' => $id_hoat_chat]);
        }
        if ($category) {
            if ($category == 'khuyen_mai') {
                $products->whereNotNull('khuyen_mai')->where('khuyen_mai', '!=', 0);
            } elseif ($category == 'moi') {
                $products->orderBy('created_at', 'desc')->take(2);
            } elseif ($category == 'ban_chay') {
                $products->where('ban_chay', 1);
            }
        }
        if ($hashtag) {
            $products->whereJsonContains('hashtag', ['id_tag' => $hashtag]);
        }
        $products = $products->paginate($itemsPerPage);
        $products->appends($request->all());
        $data = [
            'code' => 0,
            'message' => [],
            'response' => [
                'current_page' => $products->currentPage(),
                'data' => ResourcesProduct::collection($products),
                'first_page_url' => $products->url(1),
                'from' => $products->firstItem(),
                'last_page' => $products->lastPage(),
                'last_page_url' => $products->url($products->lastPage()),
                'next_page_url' => $products->nextPageUrl(),
                'path' => $products->url($page),
                'per_page' => $products->perPage(),
                'prev_page_url' => $products->previousPageUrl(),
                'to' => $products->lastItem(),
                'total' => $products->total(),
            ],
        ];
        return response()->json($data, 200);
    }

    public function home()
    {
        $sp_ban_chay = Product::where('ban_chay', 1)->orderBy('updated_at', 'desc')->take(4)->get();
        $sp_moi = Product::orderBy('updated_at', 'desc')->take(4)->get();
        $sp_khuyen_mai = Product::whereNotNull('khuyen_mai')->where('khuyen_mai', '!=', 0)->orderBy('updated_at', 'desc')->take(4)->get();
        $sp_all = Product::take(4)->get();
        $data = [
            'code' => 0,
            'message' => [],
            'response' => [
                'banners' => [],
                'events' => [],
                'products' => [
                    [
                        'key' => 'category',
                        'value' => 'ban_chay',
                        'name' => 'Sản phẩm bán chạy',
                        'data' => ResourcesHomeProduct::collection($sp_ban_chay),
                    ],
                    [
                        'key' => 'category',
                        'value' => 'moi',
                        'name' => 'Sản phẩm mới',
                        'data' => ResourcesHomeProduct::collection($sp_moi),
                    ],
                    [
                        'key' => 'category',
                        'value' => 'khuyen_mai',
                        'name' => 'Sản phẩm khuyến mãi',
                        'data' => ResourcesHomeProduct::collection($sp_khuyen_mai),
                    ],
                    [
                        'key' => 'category',
                        'value' => 'all',
                        'name' => 'Tất cả sản phẩm',
                        'data' => ResourcesHomeProduct::collection($sp_all),
                    ],
                ],
                'total_cart' => Cart::where('id_member', Auth::guard('customer-api')->user()->id)->sum('so_luong'),
                'total_notifications' => 0,
                'member_name' => Auth::guard('customer-api')->user()->name,
                'member_status' => Auth::guard('customer-api')->user()->trang_thai,
                'thu_hang_icon' => null,
            ]
        ];
        return response()->json($data, 200);
    }

    public function getCartInfo()
    {
        $prdCarts = Cart::where('id_member', Auth::guard('customer-api')->user()->id)->paginate(1000);
        $total_number = Cart::where('id_member', Auth::guard('customer-api')->user()->id)->sum('so_luong');
        $total_price = 0;

        foreach ($prdCarts as $prdInCart) {
            $total_price += $prdInCart->product->price * $prdInCart->so_luong;
        }

        return [
            'current_page' => $prdCarts->currentPage(),
            'data' => ResourcesProductCart::collection($prdCarts),
            'first_page_url' => $prdCarts->url(1),
            'from' => $prdCarts->firstItem(),
            'last_page' => $prdCarts->lastPage(),
            'last_page_url' => $prdCarts->url($prdCarts->lastPage()),
            'next_page_url' => $prdCarts->nextPageUrl(),
            'path' => $prdCarts->url($prdCarts->currentPage()),
            'per_page' => $prdCarts->perPage(),
            'prev_page_url' => $prdCarts->previousPageUrl(),
            'to' => $prdCarts->lastItem(),
            'total' => $prdCarts->total(),
            'total_number' => (int)$total_number,
            'total_price' => $total_price,
            'ti_le_giam' => 0.5,
        ];
    }

    public function addCart(Request $request)
    {
        $requiredFields = ['id', 'number'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (empty($request->input($field))) {
                $missingFields[] = "Vui lòng nhập " . $field;
            }
        }

        if (!empty($missingFields)) {
            return response()->json([
                'code' => 1,
                'message' => $missingFields,
                'response' => null
            ], 200);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'id' => ['required', 'numeric', 'min:0'],
                'number' => ['required', 'numeric', 'min:0'],
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator, response()->json([
                'code' => 1,
                'message' => array_values($validator->errors()->all()),
                'response' => null
            ], 200));
        }

        $checkprd = Product::find($request->id);

        if (!$checkprd) {
            return response()->json([
                'code' => 1,
                'message' => ['Không tồn tại sản phẩm'],
                'response' => null
            ], 200);
        }

        $cart = Cart::where('id_member', Auth::guard('customer-api')->user()->id)->where('id_product', $request->id)->first();

        if (!$cart) {
            Cart::create([
                'id_product' => $request->id,
                'id_member' => Auth::guard('customer-api')->user()->id,
                'so_luong' => $request->number,
            ]);
        } else {
            $cart->update([
                'so_luong' => $request->number,
            ]);
        }

        $data = [
            'code' => 0,
            'message' => [],
            'response' => [
                'products' => $this->getCartInfo(),
            ],
        ];

        return response()->json($data, 200);
    }

    public function indexCart()
    {
        $data = [
            'code' => 0,
            'message' => [],
            'response' => [
                'products' => $this->getCartInfo(),
            ],
        ];

        return response()->json($data, 200);
    }

    public function provinces()
    {
        $provinces = Tinh::all();
        return response()->json([
            'code' => 0,
            'message' => [],
            'response' => $provinces,
        ]);
    }

    public function agency_list(Request $request)
    {
        if (empty($request->province_id)) {
            return response()->json([
                'code' => 0,
                'message' => [
                    'Nhap province_id'
                ],
                'response' => null,
            ]);
        }
        $validator = Validator::make(
            $request->all(),
            [
                'province_id' => ['required', 'numeric', 'min:0', 'max:63'],
            ]
        );
        if ($validator->fails()) {
            throw new ValidationException($validator, response()->json([
                'code' => 1,
                'message' => array_values($validator->errors()->all()),
                'response' => null
            ], 200));
        }
        $agency_list = Tinh::findOrFail($request->province_id)->agency_list;
        $data = [
            'code' => 0,
            'message' => [],
            'response' => ResourcesAgencyList::collection($agency_list),
        ];
        return response()->json($data, 200);
    }
    public function payment(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'data_id' => ['required', 'array'],
                'data_id.*' => ['numeric', 'min:1'],
                'device' => ['nullable', 'min:0', 'max:1'],
                'ten' => ['required', 'string'],
                'sdt' => ['required', 'string', 'min:10', 'max:10', 'regex:/^[0-9]*$/'],
                'email' => ['nullable', 'email'],
                'dia_chi' => ['required', 'string', 'max:255'],
                'ma_so_thue' => ['nullable', 'string', 'regex:/^[0-9]*$/', 'min:10', 'max:10'],
                'ghi_chu' => ['nullable'],
                'ck_truoc' => ['required', 'numeric', 'min:0', 'max:1'],
                'voucher' => ['nullable', 'string', 'max:15'],
                'coin' => ['required', 'numeric', 'min:0', 'max:1'],
                'total_price' => ['nullable', 'string', 'regex:/^[0-9]*$/']
            ]
        );
        if ($validator->fails()) {
            throw new ValidationException($validator, response()->json([
                'code' => 1,
                'message' => array_values($validator->errors()->all()),
                'response' => null
            ], 200));
        }
        $totalPrice = 0;
        $check = 0;
        $error = [];
        foreach ($request->data_id as $dataId) {
            $checkPrdInCart = Cart::where('id_member', Auth::guard('customer-api')->user()->id)->find($dataId);
            if ($checkPrdInCart) {
                $check = 1;
                $totalPrice += $checkPrdInCart->product->price * $checkPrdInCart->so_luong;
            }
        }
        if ($check == 0) {
            $error[] = "San pham khong co trong gio hang";
        }

        if ($request->voucher) {
            $voucher = Voucher::where('ma_giam_gia', $request->voucher)->where('ngay_bat_dau', '<=', Carbon::now())->where('ngay_ket_thuc', '>=', Carbon::now())->first();
            if (!$voucher) {
                $error[] = "Ma giam gia khong hop le";
            } else {
                if ($totalPrice < $voucher->tong_hoa_don) {
                    $error[] = "voucher khong hop le do don hang chua dat gia tri toi thieu " . number_format($voucher->tong_hoa_don, 0, ',', '.') . " VND";
                } else {
                    $totalPrice -= $voucher->muc_tien;
                }
            }
        }
        $coin_su_dung = 0;
        if ($request->coin == 1) {
            $customer = Auth::guard('customer-api')->user();
            if ($customer->coin > 0) {
                if ($customer->coin < $totalPrice) {
                    $coin_su_dung = $customer->coin;
                    $totalPrice -= $customer->coin;
                } else {
                    $coin_su_dung = $totalPrice;
                    $totalPrice = 0;
                }
            } else {
                $error[] = "Khong du coin su dung";
            }
        }
        if (!empty($error)) {
            return response()->json([
                'code' => 1,
                'message' => $error,
                'response' => null,
            ], 200);
        }
        try {
            $bill = Bill::create([
                'dai_ly_id' => Auth::guard('customer-api')->user()->id,
                'device' => $request->device,
                'ghi_chu' => $request->ghi_chu,
                'ck_truoc' => $request->ck_truoc,
                'voucher' => $request->voucher == true ? $voucher->muc_tien : null,
                'coin' => $coin_su_dung,
                'total_price' => $totalPrice,
                'ten' => $request->ten,
                'sdt' => $request->sdt,
                'email' => $request->email,
                'dia_chi' => $request->dia_chi,
                'ma_so_thue' => $request->ma_so_thue
            ]);
            foreach ($request->data_id as $dataId) {
                $checkPrdInCart = Cart::where('id_member', Auth::guard('customer-api')->user()->id)->find($dataId);
                if ($checkPrdInCart) {
                    DetailBill::create([
                        'bill_id' => $bill->id,
                        'product_id' => $checkPrdInCart->id,
                        'so_luong' => $checkPrdInCart->so_luong,
                        'don_gia' => $checkPrdInCart->product->price
                    ]);
                }
                $checkPrdInCart->delete();
            }

            $dai_ly = Customer::findOrFail(Auth::guard('customer-api')->user()->id);
            if ($dai_ly->coin <= $coin_su_dung) {
                $coin_con_lai = 0;
            } else {
                $coin_con_lai = $dai_ly->coin - $coin_su_dung;
            }
            $dai_ly->update([
                'coin' => $coin_con_lai,
            ]);
            return response()->json([
                'code' => 0,
                'message' => [],
                'response' => [
                    'description' => "Taoj don hang thanh cong"
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 1,
                'message' => $e->getMessage(),
                'response' => null,
            ], 200);
        }
    }
    public function search(Request $request)
    {
        $error = [];
        if (!$request->search) {
            $error[] = "Nhap search";
        }
        if (strlen($request->search) < 2) {
            $error[] = "so ky tu phai lon hon 1";
        }
        if (!empty($error)) {
            return response()->json([
                'code' => 1,
                'message' => $error,
                'response' => null,
            ], 200);
        }
        $products = Product::where('name', 'like', '%' . $request->search . '%')->get();
        try {
            $data = [
                'code' => 0,
                'message' => [],
                'response' => ProductSearch::collection($products),
            ];
            return response()->json($data,200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 1,
                'message' => $e->getMessage(),
                'response' => null,
            ], 200);
        }
    }
}
