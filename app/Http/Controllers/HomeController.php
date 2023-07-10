<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Product;
use App\Models\Testimoni;
use App\Models\Slider;
use App\Models\Order;
use App\Models\OrderDetail;


class HomeController extends Controller{
	public function index(){
		$data['slider'] = Slider::orderBy('id','DESC')->limit(3)->get();
		$data['product'] = Product::orderBy('id', 'DESC')->limit(8)->get();
		$data['order'] = '';
		if($user = Auth::guard('webmember')->user()){
			$data['order'] = Order::withCount('order_detail')->where([
				['id_member',$user->id],
				['status','=',''],
			])->first();
		}
		// return $data['order'];
		return view('home.index',$data);
	}

	public function products(){
		$data['product'] = Product::where('id', $id)->first();
		$data['latest_products'] = Product::where('id', $id)->get();
		return view('home.products');
	}

	public function product(Request $request, $id){
		$data['product'] = Product::where('id', $id)->first();
		return view('home.product',$data);
	}

	public function cart(Request $request){
      $data['order']=$data['carts']= '';
		if($user = Auth::guard('webmember')->user()){
			$data['order'] = Order::withCount('order_detail')->where([
				['id_member',$user->id],
				['status','=',''],
			])->first();
		}
		if($id = $request->id){
			$data['carts'] = OrderDetail::with('product')->where('id_order',$id)->get();
		}
		// return $data['carts'];
		return view('home.cart',$data);
	}

	public function checkout(){
		return view('home.checkout');
	}

	public function store_orders(Request $request){
		return $request->all();
		
	}

	public function orders(){
		return view('home.orders');
	}

	public function about(){
		$testimoni = Testimoni::orderBy('id','DESC')->limit(3)->get();
		return view('home.about',['testimoni' => $testimoni]);
	}

	public function contact(){
		return view('home.contact');
	}

	public function faq(){
		return view('home.faq');
	}
}