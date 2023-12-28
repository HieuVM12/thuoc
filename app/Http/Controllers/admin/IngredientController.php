<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1); // Lấy tham số trang từ URL, mặc định là trang 1
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $ingredients = Ingredient::paginate($itemsPerPage);
        $ingredients->appends($request->all());
        if (!$page || ($page > $ingredients->lastPage() || $page < 1)) {
            return redirect('/admin/ingredient?page=1&itemsPerPage=' . $itemsPerPage);
        }
        return view('admin.ingredient.index',compact('ingredients','itemsPerPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ingredient.create');
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
        Ingredient::create([
            'name'=>$request->input('name'),
        ]);
        return redirect('admin/ingredient/')->with('message','Thêm hoạt chất thành công');
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
        $ingredient = Ingredient::findOrFail($id);
        return view('admin.ingredient.edit',compact('ingredient'));
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
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->update([
            'name' => $request->input('name'),
        ]);
        return redirect('admin/ingredient/')->with('message','Cập nhật thành công!!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();
        return redirect('admin/ingredient')->with('message','Xóa thành công !!!');
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
        $ingredients = Ingredient::where('name', 'like', '%' . $searchInput . '%')
                ->paginate($itemsPerPage); // Phân trang với 2 mục trên mỗi trang

        $ingredients->appends($request->all());
        if (!$page || ($page > $ingredients->lastPage() || $page < 1)) {
            return redirect('/admin/ingredient/search?search=' . $searchInput . '&itemsPerPage=' . $itemsPerPage . '&page=1'); // Chuyển hướng đến trang 1 nếu trang hiện tại không hợp lệ
        }
        return view('admin.ingredient.index', compact('ingredients', 'itemsPerPage','searchInput'));
    }
}
