<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image as ResizeImage;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $requiredFields = ['ten', 'ten_nha_thuoc', 'sdt', 'password', 'dia_chi', 'tinh'];
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
                'ten' => ['required', 'string', 'max:255'],
                'ten_nha_thuoc' => ['required', 'string', 'max:255'],
                'email' => ['nullable', 'string', 'email', 'max:255', 'unique:customers'],
                'sdt' => ['required', 'string', 'max:10', 'min:10', 'unique:customers,phone', 'regex:/^[0-9]*$/'],
                'password' => ['required', 'string', 'min:8'],
                'dia_chi' => ['required', 'string', 'max:255'],
                'tinh' => ['required','numeric','min:1','max:63'],
                'img' => ['required', 'image', 'mimes:jpeg,png,jpg'],
            ],
            [
                'sdt.regex' => 'Số điện thoại phải là số',
            ]
        );
        if ($validator->fails()) {
            throw new ValidationException($validator, response()->json([
                'code' => 1,
                'message' => array_values($validator->errors()->all()),
                'response' => null
            ], 200));
        }
        $customer = new Customer();
        $customer->name = $request->input('ten');
        $customer->ten_nha_thuoc = $request->input('ten_nha_thuoc');
        $customer->phone = $request->input('sdt');
        $customer->email = $request->input('email');
        $customer->password = $request->input('password');
        $customer->address = $request->input('dia_chi');
        $customer->id_tinh = $request->tinh;
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            ResizeImage::make($request->file('img'))->resize(800, 1000)->save('uploads/customer/' . $filename);
            $customer->img = $filename;
        }
        $customer->save();
        return response()->json([
            'code' => 0,
            'message' => [],
            'response' => [
                'description' => 'Tạo tài khoản thành công',
            ],
        ], 200);
    }

    public function login(Request $request)
    {
        $requiredFields = ['username', 'password'];
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
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:10', 'min:10', 'regex:/^[0-9]*$/'],
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator, response()->json([
                'code' => 1,
                'message' => array_values($validator->errors()->all()),
                'response' => null
            ], 200));
        }
        $data = [
            'phone' => $request->username,
            'password' => $request->password
        ];
        if (Auth::guard('customer')->attempt($data)) {
            $customer = Auth::guard('customer')->user();
            $token = $customer->createToken('MyFirstWeb')->accessToken;
            return response()->json([
                'code' => 0,
                'message' => [],
                'response' => [
                    'id' => $customer->id,
                    'ten' => $customer->name,
                    'ten_nha_thuoc' => $customer->ten_nha_thuoc,
                    'sdt' => $customer->phone,
                    'email' => $customer->email,
                    'dia_chi' => $customer->address,
                    'ma_so_thue' => $customer->ma_so_thue,
                    'trang_thai' => $customer->trang_thai,
                    'token' => $token,
                    'description' => 'Dang nhap thanh cong',
                ]
            ], 200);
        } else {
            return response()->json([
                'code' => 1,
                'message' => ' SAI TAI KHOAN HOAC MAT KHAU',
                'response' => null,
            ], 200);
        }
    }
    public function logout(Request $request)
    {
        $customer = Auth::guard('customer-api')->user();
        if (!$customer) {
            return response()->json([
                'code' => 401,
                'message' => 'Người dùng không hợp lệ',
                'response' => null
            ], 200);
        }

        $token = $customer->token();
        if ($token) {
            $token->revoke(); // Thu hồi mã thông báo
            return response()->json([
                'code' => '0',
                'message' => [],
                'response' => [
                    'description' => 'Đăng xuất thành công',
                ]
            ], 200);
        } else {
            return response()->json([
                'code' => 401,
                'message' => 'Token không tồn tại',
                'response' => null
            ], 200);
        }
    }
}
