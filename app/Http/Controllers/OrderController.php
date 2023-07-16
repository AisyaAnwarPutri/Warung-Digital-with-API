<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use DataTables;

class OrderController extends Controller{
	public function __construct()
	{
	//   $this->middleware('auth')->only(['list', 'dikonfirmasi_list', 'dikemas_list', 'dikirim_list', 'diterima_list', 'selesai_list']);
	//   $this->middleware('auth:api')->only(['store', 'update', 'destroy', 'ubah_status', 'baru', 'dikonfirmasi', 'dikemas', 'dikirim', 'diterima', 'selesai']);
	}



	public function index()
	{
		$orders = Order::with('member')->get();

		return response()->json([
			'data' => $orders
		]);
	}

	public function list()
	{
		return view('pesanan.index');
	}
	public function baru(Request $request){
		if(request()->ajax()){
			$order = Order::with('member')->where('status','Baru')->orderBy('id','DESC')->get();
			return DataTables::of($order)
				->addIndexColumn()
				->addColumn('tanggal',function($row){
					$text = $row->created_at ? date('d-m-Y',strtotime($row->created_at)) : '-';
					return "<p class='text-center'>$text</p>";
				})
				->addColumn('member',function($row){
					$text = $row->member ? ucwords(strtolower($row->member->nama_member)) : '-';
					return "<p class='text-center'>$text</p>";
				})
				->addColumn('aksi',function($row){
					$txt = "
						<button class='btn btn-sm editProduk btn-info' onclick='konfirmasi($row->id)' type='button' title='konfirmasi pesanan'>Konfirmasi</button>
					";
					return $txt;
				})->rawColumns(['tanggal','member','aksi'])->toJson();
		}
		return view('pesanan.index');
	}
   public function ubahStatus(Request $request){
      return $request->all();
   }

	public function dikonfirmasi_list()
	{
		return view('pesanan.dikonfirmasi');
	}

	public function dikemas_list()
	{
		return view('pesanan.dikemas');
	}

	public function dikirim_list()
	{
		return view('pesanan.dikirim');
	}

	public function diterima_list()
	{
		return view('pesanan.diterima');
	}

	public function selesai_list()
	{
		return view('pesanan.selesai');
	}

	// public function store(Request $request)
	// {
	// 	$validator = Validator::make($request->all(), [
	// 		'id_member' => 'required',
	// 	]);

	// 	if ($validator->fails()) {
	// 		return response()->json(
	// 				$validator->errors(), 422
	// 		);
	// 	}

	// 	$input = $request->all();
	// 	$Order = Order::create($input);

	// 	for ($i=0; $i < count($input['id_produk']) ; $i++) { 
	// 		OrderDetail::create([
	// 				'id_order' => $Order['id'],
	// 				'id_produk' => $input['id_produk'][$i],
	// 				'jumlah' => $input['jumlah'][$i],
	// 				'total' => $input['total'][$i]
	// 		]);
	// 	}

	// 	return response()->json([
	// 		'data' => $Order
	// 	]);
	// }

	// public function show(Order $Order)
	// {
	// 	return response()->json([
	// 		'data' => $Order
	// 	]);
	// }

	// public function update(Request $request, Order $Order)
	// {
	// 	$validator = Validator::make($request->all(), [
	// 		'id_member' => 'required',
	// 	]);

	// 	if ($validator->fails()) {
	// 		return response()->json(
	// 				$validator->errors(), 422
	// 		);
	// 	}

	// 	$input = $request->all();
	// 	$Order->update($input);

	// 	OrderDetail::where('id_order', $Order['id'])->delete();

	// 	for ($i=0; $i < count($input['id_produk']) ; $i++) { 
	// 		OrderDetail::create([
	// 				'id_order' => $Order['id'],
	// 				'id_produk' => $input['id_produk'][$i],
	// 				'jumlah' => $input['jumlah'][$i],
	// 				'total' => $input['total'][$i]
	// 		]);
	// 	}

	// 	return response()->json([
	// 		'message' => 'success',
	// 		'data' => $Order
	// 	]);
	// }

	// public function ubah_status(Request $request, Order $order){
	// 	$order->update([
	// 		'status' => $request->status
	// 	]);

	// 	return response()->json([
	// 		'message' => 'success',
	// 		'data' => $order
	// 	]);
	// }

	// public function baru()
	// {
	// 	$orders = Order::with('member')->where('status', 'Baru')->get();

	// 	return response()->json([
	// 		'data' => $orders
	// 	]);
	// }

	// public function dikonfirmasi(){
	// 	$orders = Order::with('member')->where('status', 'Dikonfirmasi')->get();

	// 	return response()->json([
	// 		'data' => $orders
	// 	]);
	// }

	// public function dikemas(){
	// 	$orders = Order::with('member')->where('status', 'Dikemas')->get();

	// 	return response()->json([
	// 		'data' => $orders
	// 	]);
	// }

	// public function dikirim(){
	// 	$orders = Order::with('member')->where('status', 'Dikirim')->get();

	// 	return response()->json([
	// 		'data' => $orders
	// 	]);
	// }

	// public function diterima(){
	// 	$orders = Order::with('member')->where('status', 'Diterima')->get();

	// 	return response()->json([
	// 		'data' => $orders
	// 	]);
	// }

	// public function selesai(){
	// 	$orders = Order::with('member')->where('status', 'Selesai')->get();

	// 	return response()->json([
	// 		'data' => $orders
	// 	]);
	// }

	// public function destroy(Order $Order)
	// {
	// 	$Order->delete();

	// 	return response()->json([
	// 		'message' => 'success'
	// 	]);
	// }
}
