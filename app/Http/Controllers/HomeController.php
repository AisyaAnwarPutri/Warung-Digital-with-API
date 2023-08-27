<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Helpers\Helpers;

use App\Models\About;
use App\Models\Product;
use App\Models\Testimoni;
use App\Models\Slider;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Category;
use App\Models\Member;


class HomeController extends Controller
{
	public function index()
	{
		$data['slider'] = Slider::orderBy('id', 'DESC')->limit(3)->get();
		$data['product'] = Product::with('order_detail')
			->withCount('order_detail')
			->orderBy('order_detail_count','DESC')
			->limit(8)->get();
		$data['order'] = '';
		if ($user = Auth::guard('webmember')->user()) {
			$data['order'] = Order::has('order_detail.product')->withCount('order_detail')->where([
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
			$data['order'] = Order::has('order_detail.product')->withCount('order_detail')->where([
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
			$order = Order::has('order_detail.product')->withCount('order_detail')->where([
				['id_member', $user->id],
				['status', '=', ""],
			])->first();
			if ($order && ($id = $request->id)) {
				// $carts = OrderDetail::with('product')->where('id_order', $id)->get();
				$carts = OrderDetail::has('product')->with('product')->where('id_order', $id)->get();
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
		if($data = Order::has('order_detail.product')->withCount('order_detail')->where([['id',$request->id],['status',""]])->first()){
			return ['success'=>true,'code'=>200,'message'=>'Data found','data'=>$data];
		}
		return ['success'=>false,'code'=>500,'message'=>'Data not found'];
	}

	public function store_orders(Request $request)
	{
		if($produk = Product::find($request->id_barang)){
			if($request->jumlah > $produk->stok){
				return ['success'=>false,'code'=>406,'message'=>"Stok produk tersisa $produk->stok",'data'=>$produk];
			}
			// return ['success'=>true,'code'=>200,'message'=>'Produk berhasil ditambahkan','data'=>$produk];
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

	public function validasiStok(Request $request){
		if(count($orderDetail = OrderDetail::where('id_order',$request->id)->get()) > 0){
			foreach($orderDetail as $key => $val){
				if($produk = Product::where('id',$val->id_produk)->first()){
					if($val->jumlah>$produk->stok){
						return ['success'=>false,'code'=>400,'message'=>"Stok $produk->nama_produk tersisa $produk->stok",'data'=>$val];
					}
				}
			}
			return ['success'=>true,'code'=>200,'message'=>'Data ditemukan','data'=>''];
		}
		return ['success'=>false,'code'=>404,'message'=>'Data tidak ditemukan','data'=>''];
	}

	public function orders()
	{
		return view('home.orders');
	}

	public function about()
	{
		// $data['products'] = Product::where('id_kategori', $id)->get();
		$data['order'] = $data['carts'] = '';
		if ($user = Auth::guard('webmember')->user()) {
			$data['order'] = Order::has('order_detail.product')->withCount('order_detail')->where([
				['id_member', $user->id],
				['status', '=', ''],
			])->first();
		}
		$data['about'] = About::first();
		return view('home.about', $data);
	}

	public function profile(){
		$user = Auth::guard('webmember')->user();
		$data['user'] = $user ? $user : '';
		$data['pesanan'] = [];
		if($user){
			$data['pesanan'] = Order::has('order_detail.product')->with('order_detail.product','member')->where([
				['lunas',1],
				['id_member',$user->id],
			])->orderBy('id','DESC')->get();
		}
		return view('home.profile',$data);
	}
	public function save_profile(Request $request){
		$validator = Validator::make(
			$request->all(),
			[
				'nama' => 'required',
				'nomor' => 'required',
				'email' => 'required',
				'provinsi' => 'required',
				'kabupaten' => 'required',
				'kecamatan' => 'required',
				'alamat' => 'required',
			],
			[
				'nama.required' => 'Nama harus diisi',
				'nomor.required' => 'Nomor HP harus diisi',
				'email.required' => 'Email harus diisi',
				'provinsi.required' => 'Provinsi harus diisi',
				'kabupaten.required' => 'Kabupaten harus diisi',
				'kecamatan.required' => 'Kecamatan harus diisi',
				'alamat.required' => 'Alamat harus diisi',
			]
		);
		if($validator->fails()){
			$msg = '';
			foreach($validator->errors()->toArray() as $key => $val){
				$msg = $val[0];
				break;
			}
			return ['success'=>false,'code'=>406,'message'=>$msg];
		}
		if(!$member = Member::where('id',$request->id)->first()){
			return ['success'=>false,'code'=>400,'message'=>'Data tidak ditemukan'];
		}
		$member->nama_member = $request->nama;
		$member->provinsi = $request->provinsi;
		$member->kabupaten = $request->kabupaten;
		$member->kecamatan = $request->kecamatan;
		$member->detail_alamat = $request->alamat;
		$member->no_hp = $request->nomor;
		$member->email = $request->email;
		$member->save();
		if(!$member){
			return ['success'=>false,'code'=>500,'message'=>'Data gagal diperbarui'];
		}
		return ['success'=>true,'code'=>200,'message'=>'Data berhasil diperbarui'];
	}

	public function contact()
	{
		$data['order'] = $data['carts'] = '';
		if ($user = Auth::guard('webmember')->user()) {
			$data['order'] = Order::has('order_detail.product')->withCount('order_detail')->where([
				['id_member', $user->id],
				['status', '=', ''],
			])->first();
		}
		$data['about'] = About::first();
		return view('home.contact',$data);
	}

	public function faq()
	{
		return view('home.faq');
	}

	public function category(Request $request,$id)
	{
		$data['category'] = Category::where('id', $id)->first();
		$data['produk'] = Product::with('category')->where('id_kategori',$id)->get();
		return view ('home.category', $data);
	}

	public function callback(Request $request){
		// $merchantId = config('midtrans.merchant_id');
		// $clientKey = config('midtrans.client_key');
		date_default_timezone_set('Asia/Jakarta');
		$serverKey = config('midtrans.server_key');
		$string = $request->order_id.$request->status_code.$request->gross_amount;
		$hashed = hash('SHA512', $string.$serverKey); # Buat enskripsi string{signature_key} 
		if($hashed==$request->signature_key){
			$status = $request->transaction_status;
			if(in_array($status,['capture','settlement'])){
				$orderId = $request->order_id;
				if($order = Order::where('id',$orderId)->first()){
					$order->status = 'Baru';
					$order->lunas = true;
					$order->tanggal = date('Y-m-d');
					$order->save();
					if(count($orderDetail = OrderDetail::where('id_order',$orderId)->get()) > 0){ # Ambil semua data detail berdasarkan id order
						foreach($orderDetail as $key => $val){
							if($produk = Product::where('id',$val->id_produk)->first()){
								$stokAkhir = $produk->stok - $val->jumlah; # Kurangi stok
								$produk->stok = $stokAkhir;
								$produk->save();
							}
						}
					}
				}
				Helpers::logging([
					'url'     => $request->url(),
					'title'   => 'MIDTRANS CALLBACK SUCCESS',
					'message' => 'Pembayaran berhasil',
					'data'    => $request->all(),
				]);
				return true;
			}
		}
		Helpers::logging([
			'url'     => $request->url(),
			'title'   => 'MIDTRANS CALLBACK FAILED',
			'message' => 'Pembayaran gagal',
			'data'    => $request->all(),
		]);
		return false;
	}
}
