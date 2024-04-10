<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        $products = Product::where('is_featured','Yes')
            ->orderBy('id','DESC')
            ->take(8)
            ->where('status',1)
            ->get();
        $data['featuredProducts'] = $products;

        $latestProducts = Product::orderBy('id','DESC')
            ->where('status',1)
            ->take(8)
            ->get();
        $data['latestProducts'] =$latestProducts;
        return view('front.home',$data);
    }
    public function page($slug)
    {
        $page = Page::where('slug',$slug)->first();
        if($page == null){
            abort(405);
        }
        return view('front.layout.page',[
            'page' => $page
        ]);

    }
}
