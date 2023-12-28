<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1); // Lấy tham số trang từ URL, mặc định là trang 1
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $vouchers = Voucher::paginate($itemsPerPage);
        $vouchers->appends($request->all());
        if (!$page || ($page > $vouchers->lastPage() || $page < 1)) {
            return redirect('/admin/voucher?page=1&itemsPerPage=' . $itemsPerPage);
        }
        return view('admin.voucher.index',compact('vouchers','itemsPerPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.voucher.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tieu_de' => ['required', 'string', 'max:255'],
                'ma_giam_gia' => ['required', 'string', 'max:15','unique:vouchers'],
                'muc_tien' => ['required', 'numeric', 'min:1000'],
                'noi_dung' => ['required','string'],
                'tong_hoa_don' => ['required', 'numeric','min:'.($request->muc_tien + 1)],
                'ngay_bat_dau' => ['required', 'date_format:Y-m-d\TH:i',],
                'ngay_ket_thuc' => ['required', 'date_format:Y-m-d\TH:i', 'after:ngay_bat_dau'],
                'doi_tuong_gui' => ['required'],
            ],
            [
                'tong_hoa_don' => 'Tổng hóa đơn phải lớn hơn mức tiền'
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $doi_tuong_gui = [];
        if($request->doi_tuong_gui){
            foreach($request->doi_tuong_gui as $dt){
                $doi_tuong_gui[] = [
                    'doi_tuong_gui' => $dt,
                ];
            }
        }
        Voucher::create([
            'tieu_de' => $request->tieu_de,
            'ma_giam_gia' => $request->ma_giam_gia,
            'muc_tien' => $request->muc_tien,
            'tong_hoa_don' => $request->tong_hoa_don,
            'noi_dung'=>$request->noi_dung,
            'ngay_bat_dau' =>$request->ngay_bat_dau,
            'ngay_ket_thuc' =>$request->ngay_ket_thuc,
            'loai_voucher' =>$request->loai_voucher,
            'doi_tuong_gui' =>$doi_tuong_gui,
        ]);
        return redirect('admin/voucher')->with('message', 'Thêm mã giảm giá thành công!!!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
