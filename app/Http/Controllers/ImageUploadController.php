<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as ResizeImage;

class ImageUploadController extends Controller
{
    public function storeImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file =$request->file('upload');
            $ext = $file->getClientOriginalExtension();
            $filename=time().'.'.$ext;
            ResizeImage::make($request->file('upload'))->resize(700,350)->save('media/'.$filename);
            $url = asset('media/'.$filename);
            return response()->json(['fileName' => $filename,'uploaded'=> 1,'url'=> $url]);
        }
    }
}
