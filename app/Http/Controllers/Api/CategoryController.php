<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category as ResourcesCategory;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Producer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function category()
    {
        $hoat_chat = Ingredient::orderBy('updated_at','desc')->take(4)->get();
        $nhom_thuoc = Category::orderBy('updated_at','desc')->take(4)->get();
        $nha_san_xuat = Producer::orderBy('updated_at','desc')->take(4)->get();
        $data = [
            'code'=> 0,
            'message'=>[],
            'response'=>[
                [
                    'name'=>'Hoat chat',
                    'key' =>'hoat_chat',
                    'icon'=>null,
                    'category'=>ResourcesCategory::collection($hoat_chat),
                ],[
                    'name'=>'Nhom thuoc',
                    'key' =>'nhom_thuoc',
                    'icon'=>null,
                    'category'=>ResourcesCategory::collection($nhom_thuoc),
                ],[
                    'name'=>'Nha san xuat',
                    'key' =>'nha_san_xuat',
                    'icon'=>null,
                    'category'=>ResourcesCategory::collection($nha_san_xuat),
                ],
            ]
        ];
        return response()->json($data,200);
    }

    public function category_type(Request $request)
    {
        $error = [];
        if(!$request->type){
            $error[] = "Vui long nhap type";
        }
        if($request->type != 'hoat_chat' && $request->type != 'nha_san_xuat' && $request->type != 'nhom_thuoc' ){
            $error[] = "Sai type";
        }
        if(!empty($error)){
            return response()->json([
                'code'=>1,
                'message'=>$error,
                'response'=>null,
            ],200);
        }
        if($request->type == 'hoat_chat'){
            if($request->search){
                $data = Ingredient::where('name','like','%'.$request->search.'%')->paginate(50);
            }else{
                $data = Ingredient::paginate(50);
            }
        }
        if($request->type == 'nha_san_xuat'){
            if($request->search){
                $data = Producer::where('name','like','%'.$request->search.'%')->paginate(50);
            }else{
                $data = Producer::paginate(50);
            }
        }
        if($request->type == 'nhom_thuoc'){
            if($request->search){
                $data = Category::where('name','like','%'.$request->search.'%')->paginate(50);
            }else{
                $data = Category::paginate(50);
            }
        }
        $data->appends($request->all());
        return response()->json([
            'code'=>0,
            'message'=>[],
            'response'=>[
                'current_page' => $data->currentPage(),
                'data' => ResourcesCategory::collection($data),
                'first_page_url' => $data->url(1),
                'from' => $data->firstItem(),
                'last_page' => $data->lastPage(),
                'last_page_url' => $data->url($data->lastPage()),
                'next_page_url' => $data->nextPageUrl(),
                'path' => $data->url($data->currentPage()),
                'per_page' => $data->perPage(),
                'prev_page_url' => $data->previousPageUrl(),
                'to' => $data->lastItem(),
                'total' => $data->total(),
            ]
        ]);
    }
}
