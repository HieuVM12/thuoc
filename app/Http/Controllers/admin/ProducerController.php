<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProducerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1); // Lấy tham số trang từ URL, mặc định là trang 1
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $producers = Producer::paginate($itemsPerPage);
        $producers->appends($request->all());
        if (!$page || ($page > $producers->lastPage() || $page < 1)) {
            return redirect('/admin/category?page=1&itemsPerPage=' . $itemsPerPage);
        }
        return view('admin.producer.index',compact('producers','itemsPerPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.producer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        Producer::create([
            'name'=>$request->input('name'),
        ]);
        return redirect('admin/producer/')->with('message','Thêm nhà sản xuất thành công');
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
        $producer = Producer::findOrFail($id);
        return view('admin.producer.edit',compact('producer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $producer = Producer::findOrFail($id);
        $producer->update([
            'name' => $request->input('name'),
        ]);
        return redirect('admin/producer/')->with('message','Cập nhật thành công!!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producer = Producer::findOrFail($id);
        $producer->delete();
        return redirect('admin/producer')->with('message','Xóa thành công !!!');
    }

    public function search(Request $request)
    {
        $searchInput = $request->input('search');
        $page = $request->input('page', 1); // Lấy tham số trang từ URL, mặc định là trang 1
        // Sử dụng Validator để kiểm tra trường tìm kiếm
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $validator = Validator::make($request->all(), [
            'search' => 'required',
        ], [
            'search.required' => 'Nhập từ khóa tìm kiếm',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // Nếu không có lỗi, tiếp tục thực hiện tìm kiếm và phân trang
        $producers = Producer::where('name', 'like', '%' . $searchInput . '%')
                ->paginate($itemsPerPage); // Phân trang với 2 mục trên mỗi trang

        $producers->appends($request->all());
        if (!$page || ($page > $producers->lastPage() || $page < 1)) {
            return redirect('/admin/producer/search?search=' . $searchInput . '&itemsPerPage=' . $itemsPerPage . '&page=1'); // Chuyển hướng đến trang 1 nếu trang hiện tại không hợp lệ
        }
        return view('admin.producer.index', compact('producers', 'itemsPerPage','searchInput'));
    }
}
