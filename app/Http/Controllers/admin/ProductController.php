<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportProduct;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ImageProduct;
use App\Models\Ingredient;
use App\Models\Producer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image as ResizeImage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1); // Lấy tham số trang từ URL, mặc định là trang 1
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $all_products = Product::all();
        $products = Product::paginate($itemsPerPage);
        $products->appends($request->all());
        if (!$page || ($page > $products->lastPage() || $page < 1)) {
            return redirect('/admin/category?page=1&itemsPerPage=' . $itemsPerPage);
        }
        return view('admin.product.index', compact('products', 'itemsPerPage', 'all_products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $producers = Producer::all();
        $ingredients = Ingredient::all();
        return view('admin.product.create', compact('categories', 'producers', 'ingredients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'code' => ['required', 'string', 'max:255', 'unique:products'],
                'name' => ['required', 'string', 'max:255'],
                'unit' => ['required', 'string', 'max:255'],
                'quantity' => ['required', 'numeric'],
                'price' => ['required', 'numeric'],
                'information' => ['required', 'string'],
                'usage' => ['required', 'string'],
                'image.*' => ['image', 'mimes:jpeg,png,jpg'],
                'ingredient_amount.*' => ['required', 'string'],
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $hoat_chat = [];
        if ($request->ingredients) {
            foreach ($request->ingredients as $key => $ingredient) {
                $hoat_chat[] = [
                    'id_hoat_chat' => $ingredient,
                    'ham_luong' => $request->ingredient_amount[$key] ?? 0,
                ];
            }
        }
        $product = Product::create([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'unit' => $request->input('unit'),
            'quantity' => $request->input('quantity'),
            'price' => $request->input('price'),
            'information' => $request->input('information'),
            'usage' => $request->input('usage'),
            'category_id' => $request->category_id,
            'producer_id' => $request->producer_id,
            'status' => $request->status,
            'hoat_chat' => $hoat_chat,
        ]);
        if ($request->hasFile('image')) {
            $uploadPath = 'uploads/product/';
            $i = 1;
            foreach ($request->file('image') as $imageFile) {
                $ext = $imageFile->getClientOriginalExtension();
                $filename = time() . $i++ . '.' . $ext;
                ResizeImage::make($imageFile)->resize(350, 350)->save($uploadPath . $filename);
                $finalImage = $uploadPath . $filename;
                $product->image_products()->create([
                    'id_san_pham' => $product->id,
                    'img' => $finalImage,
                ]);
            }
        }
        // if ($request->ingredients) {
        //     foreach ($request->ingredients as $key => $ingredient) {
        //         $product->ingredient_products()->create([
        //             'product_id' => $product->id,
        //             'ingredient_id' => $ingredient,
        //             'content' => $request->ingredient_amount[$key] ?? 0,
        //         ]);
        //     }
        // }
        return redirect('admin/product')->with('message', 'Thêm sản phẩm thuốc thành công!!!');
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
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $producers = Producer::all();
        $ingredients = Ingredient::all();
        return view('admin.product.edit', compact('product', 'categories', 'producers', 'ingredients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'code' => ['required', 'string', 'max:255', 'unique:products,code,' . $id],
                'name' => ['required', 'string', 'max:255'],
                'unit' => ['required', 'string', 'max:255'],
                'quantity' => ['required', 'numeric'],
                'price' => ['required', 'numeric'],
                'information' => ['required', 'string'],
                'usage' => ['required', 'string'],
                'image.*' => ['image', 'mimes:jpeg,png,jpg'],
                'ingredient_amount.*' => ['required', 'string'],
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $hoat_chat = [];
        if ($request->ingredients) {
            foreach ($request->ingredients as $key => $ingredient) {
                $hoat_chat[] = [
                    'id_hoat_chat' => $ingredient,
                    'ham_luong' => $request->ingredient_amount[$key] ?? 0,
                ];
            }
        }
        $product = Product::findOrFail($id);
        $product->update([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'unit' => $request->input('unit'),
            'quantity' => $request->input('quantity'),
            'price' => $request->input('price'),
            'information' => $request->input('information'),
            'usage' => $request->input('usage'),
            'category_id' => $request->category_id,
            'producer_id' => $request->producer_id,
            'status' => $request->status,
            'hoat_chat' => $hoat_chat,
        ]);
        if ($request->hasFile('image')) {
            $uploadPath = 'uploads/product/';
            $i = 1;
            foreach ($request->file('image') as $imageFile) {
                $ext = $imageFile->getClientOriginalExtension();
                $filename = time() . $i++ . '.' . $ext;
                ResizeImage::make($imageFile)->resize(350, 350)->save($uploadPath . $filename);
                $finalImage = $uploadPath . $filename;
                $product->image_products()->create([
                    'id_san_pham' => $product->id,
                    'img' => $finalImage,
                ]);
            }
        }
        // if ($product->ingredient_products) {
        //     foreach ($product->ingredient_products as $ingredient_product) {
        //         $ingredient_product->delete();
        //     }
        // }
        // if ($request->ingredients) {
        //     foreach ($request->ingredients as $key => $ingredient) {
        //         $product->ingredient_products()->create([
        //             'product_id' => $product->id,
        //             'ingredient_id' => $ingredient,
        //             'content' => $request->ingredient_amount[$key] ?? 0,
        //         ]);
        //     }
        // }
        return redirect('admin/product')->with('message', 'Cập nhật sản phẩm thuốc thành công!!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        if ($product->image_products) {
            foreach ($product->image_products as $image) {
                if (File::exists($image->img)) {
                    File::delete($image->img);
                }
                $image->delete();
            }
        }
        if ($product->ingredient_products) {
            foreach ($product->ingredient_products as $ingredient_product) {
                $ingredient_product->delete();
            }
        }
        $product->delete();
        return redirect()->back()->with('message', 'Xóa thành công!!!');
    }

    public function deleteImage(int $imageId)
    {
        $image_product = ImageProduct::findOrFail($imageId);
        if (File::exists($image_product->img)) {
            File::delete($image_product->img);
        }
        $image_product->delete();
        return response()->json(['message' => 'Đã xóa ảnh!!!']);
    }
    public function search(Request $request)
    {
        $all_products = Product::all();
        $searchInput = $request->search;
        $page = $request->input('page', 1);
        $itemsPerPage = $request->input('itemsPerPage', 10);
        if ($searchInput == 0) {
            $products = Product::paginate($itemsPerPage);
            $products->appends($request->all());
            if (!$page || ($page > $products->lastPage() || $page < 1)) {
                return redirect('/admin/product/search?search=' . $searchInput . '&itemsPerPage=' . $itemsPerPage . '&page=1'); // Chuyển hướng đến trang 1 nếu trang hiện tại không hợp lệ
            }
        } else {
            $products = Product::where('id',$searchInput)->paginate(1);
            $products->appends($request->all());
        }
        return view('admin.product.index', compact('products', 'itemsPerPage', 'searchInput', 'all_products'));
    }

    public function exportProduct(Request $request)
    {
        $products = Product::all();
        return Excel::download(new ExportProduct($products),'products.xlsx');
    }
    public function exportProductSelected (Request $request)
    {
        $ids = $request->ids;
        if($ids){
            $products = Product::whereIn('id', $ids)->get();
            return Excel::download(new ExportProduct($products), 'products-selected.xlsx');
        }else{
            return redirect()->back()->with('message','Chọn ít nhất 1 sản phẩm');
        }
    }
}
