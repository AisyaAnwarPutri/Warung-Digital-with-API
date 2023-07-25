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

	public function dataTable($statusAwal,$nextStatus){
		$order = Order::has('order_detail.product')
			->with('order_detail.product','member')->where('status',$statusAwal)->orderBy('id','DESC')->get();
		return DataTables::of($order)
			->addIndexColumn()
			->addColumn('tanggal',function($row){
				$text = $row->tanggal ? date('d-m-Y',strtotime($row->tanggal)) : '-';
				return "<p class='text-center'>$text</p>";
			})
			->addColumn('nama_produk',function($row){
				if(count($row->order_detail)>0){
					$nama = '';
					foreach($row->order_detail as $key => $val){
						$nama .= $val->product->nama_produk.', ';
					}
					$nama = rtrim($nama, ", ");
					return $nama;
				}
				$text = $row->order_detail ? ucwords(strtolower($row->member->nama_member)) : '-';
				return "<p class='text-center'>$text</p>";
			})
			->addColumn('member',function($row){
				$text = $row->member ? ucwords(strtolower($row->member->nama_member)) : '-';
				return "<p class='text-center'>$text</p>";
			})
			->addColumn('no_hp',function($row){
				$text = $row->member ? $row->member->no_hp : '-';
				return "<p class='text-center'>$text</p>";
			})
			->addColumn('alamat',function($row){
				$text = $row->member ? $row->member->detail_alamat : '-';
				return "<p class='text-center'>$text</p>";
			})
			->addColumn('kabupaten',function($row){
				$text = $row->member ? $row->member->kabupaten : '-';
				return "<p class='text-center'>$text</p>";
			})
			->addColumn('provinsi',function($row){
				$text = $row->member ? $row->member->provinsi : '-';
				return "<p class='text-center'>$text</p>";
			})
			->addColumn('aksi',function($row)use($nextStatus){
				$params = json_encode([$row->id,$nextStatus],true);
				$text = 'Konfirmasi';
				$title = 'Konfirmasi pesanan';
				if($nextStatus=='Dikemas'){
					$text = 'Kemas pesanan';
					$title = 'Kemas pesanan';
				}
				if($nextStatus=='Dikirim'){
					$text = 'Kirim pesanan';
					$title = 'Kirim pesanan';
				}
				if($nextStatus=='Diterima'){
					$text = 'Sudah diterima';
					$title = 'Pesanan sudah diterima';
				}
				if($nextStatus=='Selesai'){
					$text = 'Selesai';
					$title = 'Selesaikan pesanan';
				}
				$txt = "
					<button class='btn btn-sm editProduk btn-info' onclick='ubahStatus($params)' type='button' title='$title'>$text</button>
				";
				return $txt;
			})->rawColumns(['tanggal','nama_produk','member','no_hp','alamat','kabupaten','provinsi','aksi'])->toJson();
	}

	public function baru(Request $request){
		if(request()->ajax()){
			return $this->dataTable('Baru','Dikonfirmasi');
		}
		return view('pesanan.index');
	}

	public function konfirmasi(Request $request){
		if(request()->ajax()){
			return $this->dataTable('Dikonfirmasi','Dikemas');
		}
		return view('pesanan.dikonfirmasi');
	}

	public function kemas(Request $request){
		if(request()->ajax()){
			return $this->dataTable('Dikemas','Dikirim');
		}
		return view('pesanan.dikemas');
	}

	public function kirim(Request $request){
		if(request()->ajax()){
			return $this->dataTable('Dikirim','Diterima');
		}
		return view('pesanan.dikirim');
	}

	public function terima(Request $request){
		if(request()->ajax()){
			return $this->dataTable('Diterima','Selesai');
		}
		return view('pesanan.diterima');
	}

	public function selesai(Request $request){
		if(request()->ajax()){
			return $this->dataTable('Selesai','Dikemas');
		}
		return view('pesanan.selesai');
	}

	public function ubah_status(Request $request){
		if($order = Order::where('id',$request->id)->first()){
			$order->status = $request->status;
			$order->save();
			return ['success'=>true,'message'=>'Perubahan status berhasil'];
		}
		return ['success'=>false,'message'=>'Data tidak ditemukan'];
	}
}
