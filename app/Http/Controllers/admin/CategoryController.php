<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1); // Lấy tham số trang từ URL, mặc định là trang 1
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $categories = Category::paginate($itemsPerPage);
        $categories->appends($request->all());
        if (!$page || ($page > $categories->lastPage() || $page < 1)) {
            return redirect('/admin/category?page=1&itemsPerPage=' . $itemsPerPage);
        }
        return view('admin.category.index',compact('categories','itemsPerPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
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
        Category::create([
            'name'=>$request->input('name'),
        ]);
        return redirect('admin/category/')->with('message','Thêm nhóm thuốc thành công');
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
        $category = Category::findOrFail($id);
        return view('admin.category.edit',compact('category'));
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
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->input('name'),
        ]);
        return redirect('admin/category/')->with('message','Cập nhật thành công!!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect('admin/category')->with('message','Xóa thành công !!!');
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
        $categories = Category::where('name', 'like', '%' . $searchInput . '%')
                ->paginate($itemsPerPage); // Phân trang với 2 mục trên mỗi trang

        $categories->appends($request->all());
        if (!$page || ($page > $categories->lastPage() || $page < 1)) {
            return redirect('/admin/category/search?search=' . $searchInput . '&itemsPerPage=' . $itemsPerPage . '&page=1'); // Chuyển hướng đến trang 1 nếu trang hiện tại không hợp lệ
        }
        return view('admin.category.index', compact('categories', 'itemsPerPage','searchInput'));
    }
}
