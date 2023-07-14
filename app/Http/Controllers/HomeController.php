<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Product;
use App\Models\Testimoni;
use App\Models\Slider;
use App\Models\Order;
use App\Models\OrderDetail;


class HomeController extends Controller
{
	public function index()
	{
		$data['slider'] = Slider::orderBy('id', 'DESC')->limit(3)->get();
		$data['product'] = Product::orderBy('id', 'ASC')->limit(8)->get();
		$data['order'] = '';
		if ($user = Auth::guard('webmember')->user()) {
			$data['order'] = Order::withCount('order_detail')->where([
				['id_member', $user->id],
				['status', '=', ""],
			])->first();
		}
		return view('home.index', $data);
	}

	public function products(Request $request, $id)
	{
		$data['products'] = Product::where('id_kategori', $id)->get();
		$data['order'] = $data['carts'] = '';
		if ($user = Auth::guard('webmember')->user()) {
			$data['order'] = Order::withCount('order_detail')->where([
				['id_member', $user->id],
				['status', '=', ''],
			])->first();
		}
		// $data['latest_products'] = Product::where('id', $id)->get();
		return view('home.products',$data);
	}

	public function product(Request $request, $id)
	{
		$data['carts'] = '';
		$data['order'] = '';
		if ($user = Auth::guard('webmember')->user()) {
			$data['order'] = Order::withCount('order_detail')->where([
				['id_member', $user->id],
				['status', '=', ""],
			])->first();
		}
		// return $data['order'];
		$data['product'] = Product::where('id', $id)->first();
		return view('home.product', $data);
	}

	public function removeItem(Request $request){
		if($detail = OrderDetail::find($request->id)){
			$id = $detail->id_order;
			$hargaPerItem = $detail->harga;
			// return ['success'=>true,'code'=>200,'message'=>'Data berhasil dihapus','id_order'=>$id];
			if($detail->delete()){
				if($order = Order::where('id',$id)->first()){
					$grand = $order->grand_total - $hargaPerItem; # Ubah grand_total jika ada item yang di hapus di order_detail
					$order->grand_total = $grand;
					$order->save();
				}
				return ['success'=>true,'code'=>200,'message'=>'Data berhasil dihapus','id_order'=>$id];
			}
			return ['success'=>false,'code'=>500,'message'=>'Data gagal dihapus'];
		}
		return ['success'=>false,'code'=>500,'message'=>'Data tidak ditemukan'];
	}

	public function cart(Request $request)
	{
		$order=$carts=$snapToken='';
		if($user = Auth::guard('webmember')->user()){
			$order = Order::withCount('order_detail')->where([
				['id_member', $user->id],
				['status', '=', ""],
			])->first();
			if ($id = $request->id) {
				$carts = OrderDetail::with('product')->where('id_order', $id)->get();
			}

			$merchantId = config('midtrans.merchant_id');
			$clientKey = config('midtrans.client_key');
			$serverKey = config('midtrans.server_key');

			if($order){
				\Midtrans\Config::$serverKey = $serverKey;
				// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
				\Midtrans\Config::$isProduction = false;
				// Set sanitization on (default)
				\Midtrans\Config::$isSanitized = true;
				// Set 3DS transaction for credit card to true
				\Midtrans\Config::$is3ds = true;
				
				$params = array(
					'transaction_details' => array(
						'order_id' => $order->id,
						'gross_amount' => $order->grand_total,
					),
					'customer_details' => array(
						'first_name' => $user->nama_member,
						// 'last_name' => '',
						'email' => $user->email,
						'phone' => $user->no_hp,
					),
				);
				$snapToken = \Midtrans\Snap::getSnapToken($params);
			}
		}

		$data = [
			'order' => $order,
			'carts' => $carts,
			'snapToken' => $snapToken,
		];
		return view('home.cart', $data);
	}

	public function checkout(Request $request)
	{
		$data = Order::with('order_detail')->where([
			['id',$request->id],
			['status',''],
		])->first();
		if($data){
			return ['success'=>false,'code'=>406,'message'=>'Data tidak ditemukan'];
		}
		return ['success'=>false,'code'=>406,'message'=>'Data tidak ditemukan'];
		// return $request->all();
		// return view('home.checkout');
	}

	public function countKeranjang(Request $request){
		// return $request->all();
		if($data = Order::withCount('order_detail')->where([['id',$request->id],['status',""]])->first()){
			return ['success'=>true,'code'=>200,'message'=>'Data found','data'=>$data];
		}
		return ['success'=>false,'code'=>500,'message'=>'Data not found'];
	}

	public function store_orders(Request $request)
	{
		if($produk = Product::find($request->id_barang)){
			// return ['success'=>true,'code'=>200,'message'=>'Produk berhasil ditambahkan'];
			if($user = Auth::guard('webmember')->user()){
				$count = Order::count()+1; # Nomor invoice
				$findOrder = Order::where([
					['status',""],
					['id_member',$user->id],
				])->first();
				$order = $findOrder ? $findOrder : new Order;
				$grand = $order->grand_total+$request->total; # Grand total awal + total baru
				if(!$findOrder){
					$order->id_member = $user->id;
					$order->invoice = $count;
					$order->status = ""; # Status ketika ditambahkan ke keranjang{Baru}
				}
				$order->grand_total = $grand;
				$order->save();
				$findDetail = OrderDetail::where([
					['id_order',$order->id],
					['id_produk',$request->id_barang],
				])->first();
				$detail = $findDetail ? $findDetail: new OrderDetail;
				$jumlah = $detail->jumlah+$request->jumlah; # Jumlah awal + jumlah baru
				$harga = $detail->harga+$request->total; # Total awal + total baru
				if(!$findDetail){
					$detail->id_order = $order->id;
					$detail->id_produk = $request->id_barang;
				}
				$detail->jumlah = $jumlah;
				$detail->harga = $harga;
				$detail->save();
				
				return $order ? ['success'=>true,'code'=>200,'message'=>'Produk berhasil ditambahkan','data'=>$order] : ['success'=>false,'code'=>500,'message'=>'Produk gagal ditambahkan'];
			}
			return ['success'=>false,'code'=>500,'message'=>'Terjadi kesalahan sistem'];
		}
		return ['success'=>false,'code'=>500,'message'=>'Produk tidak ditemukan'];
	}

	public function orders()
	{
		// $data['orders'] = 
		return view('home.orders');
	}

	public function about()
	{
		$testimoni = Testimoni::orderBy('id', 'DESC')->limit(3)->get();
		return view('home.about', ['testimoni' => $testimoni]);
	}

	public function contact()
	{
		return view('home.contact');
	}

	public function faq()
	{
		return view('home.faq');
	}

	public function callback(Request $request){
		// return $request->all();
		// $merchantId = config('midtrans.merchant_id');
		// $clientKey = config('midtrans.client_key');
		$serverKey = config('midtrans.server_key');
		// $hashed = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
		// if($hashed==$request->signature_key){
		// 	if($request->transaction_status=='capture'){
		// 		return 'berhasil';
		// 	}
		// }
		// return 'gagal';
	}
}
