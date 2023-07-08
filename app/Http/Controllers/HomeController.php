<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Testimoni;
use App\Models\Slider;
use App\Models\Product;


class HomeController extends Controller
{
    public function index(){
        $data['slider'] = Slider::orderBy('id','DESC')->limit(3)->get();
        $data['product'] = Product::orderBy('id', 'DESC')->limit(8)->get();
        return view('home.index',$data);
    }

    public function products()
    {
        return view('home.products');
    }

    public function product()
    {
        return view('home.product');
    }

    public function cart()
    {
        return view('home.cart');
    }

    public function checkout()
    {
        return view('home.checkout');
    }

    public function orders()
    {
        return view('home.orders');
    }

    public function about()
    {
        $testimoni = Testimoni::orderBy('id','DESC')->limit(3)->get();
        return view('home.about',['testimoni' => $testimoni]);
    }

    public function contact()
    {
        return view('home.contact');
    }

    public function faq()
    {
        return view('home.faq');
    }
}