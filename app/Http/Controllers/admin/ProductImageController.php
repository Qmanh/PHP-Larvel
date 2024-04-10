<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $tempImageLocation = $image->getPathName();

        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image='NULL';
        $productImage->save();

        $imageName = $request->product_id.'-'.$productImage->id.'-'.time().'.'.$ext;
        $dPath = public_path().'/upload/category/'.$imageName;
        File::copy($tempImageLocation,$dPath);
        $productImage->image = $imageName;
        $productImage->save();


        return response()->json([
            'status'=> true,
            'image_id'=> $productImage->id,
            'ImagePath'=> asset('/upload/category/'.$productImage->image),
            'message'=> 'Image saved successfully',
        ]);
    }

    public function destroy(Request $request)
    {
        $productImage = ProductImage::find($request->id);

        if(empty($productImage)){
            return response()->json([
                'status'=> false,
                'message'=> 'Image not found',
            ]);
        }
        File::delete(public_path('upload/category/'.$productImage->image));

        $productImage->delete();
        return response()->json([
            'status'=> true,
            'message'=> 'Image deleted successfully',
        ]);
    }
}
