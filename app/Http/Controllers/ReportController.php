<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class ReportController extends Controller
{
	public function __construct()
	{
	//   $this->middleware('auth')->only(['index']);
	//   $this->middleware('auth:api')->only(['get_reports']);
	}

	public function get_reports(Request $request){
		$report = DB::table('order_details')
			->join('products', 'products.id', '=', 'order_details.id_produk')
			->select(DB::raw('
					nama
					nama_produk,
					count(*) as jumlah_dibeli,
					harga,
					SUM(total) as pendapatan,
					SUM(jumlah) as total_qty'))
			->whereRaw("date(order_details.created_at) >= '$request->dari'")
			->whereRaw("date(order_details.created_at) <=' $request->sampai'")
			->groupBy('id_produk', 'nama_produk', 'harga')
			->get();

		return response()->json([
			'data' => $report
		]);
	}

	public function index(Request $request){
		if(request()->ajax()){
			// $query = DB::table('order_details as od')
			// 	->join('products as p', 'p.id', '=', 'od.id_produk')
			// 	->join('orders','od.id_order','=','orders.id')
			// 	->select(DB::raw('
			// 			nama_produk,
			// 			count(*) as jumlah_dibeli,
			// 			p.harga,
			// 			SUM(od.harga) as pendapatan,
			// 			SUM(jumlah) as total_qty'))
			// 	->where('orders.lunas',true);
			// $query->when( # Jika parameter tanggal tidak kosong
			// 	$request->tanggalAwal && $request->tanggalAkhir,fn($q)=>
			// 		$q->whereBetween('orders.created_at',["$request->tanggalAwal 00:00:00","$request->tanggalAkhir 23:59:59"]
			// 	)
			// );
			// // $query->when( # Jika parameter tanggal kosong, default value tanggal hari ini
			// // 	!$request->tanggalAwal && !$request->tanggalAkhir,fn($q)=>
			// // 		$q->whereBetween('orders.created_at',[date('Y-m-d')." 00:00:00",date('Y-m-d')." 23:59:59"]
			// // 	)
			// // );
			// $data = $query->groupBy('id_produk', 'nama_produk', 'harga')->get();
			$query = Order::has('order_detail.product')->
         with('order_detail.product')->
			where('lunas',true)->
			orderBy('tanggal','ASC');
			$query->when( # Jika parameter tanggal tidak kosong
				$request->tanggalAwal && $request->tanggalAkhir,fn($q)=>
					$q->whereBetween('tanggal',["$request->tanggalAwal","$request->tanggalAkhir"]
				)
			);
			$data = $query->get();
			return DataTables::of($data)->
				addIndexColumn()->
				addColumn('pendapatan',function($row){
					$txt = "<p class='m-0'>".$this->rupiah($row->grand_total)."</p>";
					return $txt;
				})->
				addColumn('aksi',function($row){
					$txt = "
						<button class='btn btn-sm btn-info' onclick='detailOrder($row->id)' type='button' title='Detail'><i class='fa-solid fa-eye'></i></button>
						<button class='btn btn-sm btn-warning' onclick='editOrder($row->id)' type='button' title='Edit'><i class='fa-solid fa-pen-to-square'></i></button>
                  ";
						// <button class='btn btn-sm btn-danger' onclick='hapusOrder($row->id)' type='button' title='Hapus'><i class='fa-solid fa-trash'></i></button>
					return $txt;
				})->
				rawColumns(['pendapatan','aksi'])->
				toJson();
		}
		return view('report.index');
	}

	function rupiah($angka){
		$hasil_rupiah = "Rp. " . number_format((int)$angka);
		$hasil_rupiah = str_replace(',', '.', $hasil_rupiah);
		return $hasil_rupiah;
	}

	public function form(Request $request){
		$data['produk'] = Product::get();
		if(isset($request->id)){
			$data['page'] = 'Edit';
			$data['order'] = Order::with('order_detail.product')->where('id',$request->id)->first();
		}else{
			$data['page'] = 'Tambah';
			$data['order'] = '';
		}
		// return $data;
		$content = view('report.form',$data)->render();
		return response()->json(['success'=>true,'message'=>'Data berhasil diambil','data'=>$content],200);
		// return ['success'=>true,'message'=>'Data berhasil diambil','data'=>$content];
	}

	public function store(Request $request){
		date_default_timezone_set('Asia/Jakarta');
		// return response()->json(['success'=>false,'code'=>200,'message'=>'Stok produk gagal di update','data'=>[]],200);
		// return $request->all();
		// return new OrderDetail;
		DB::beginTransaction();
		try{
			$totalHarga = array_sum($request->totalHarga);
			if(!$request->orderId){ # Laporan baru
				$invoice = Order::max('invoice')+1;
				$order = new Order;
				$order->invoice = $invoice;
				$order->grand_total = $totalHarga;
				$order->status = 'Selesai';
				$order->lunas = 1;
				$order->tanggal = date('Y-m-d');
			}else{ # Update laporan lama
				$order = Order::where('id',$request->orderId)->first();
				$order->grand_total = $totalHarga;
				$orderDetail = OrderDetail::where('id_order',$request->orderId)->whereNotIn('id_produk',$request->idProduk)->get();
				if(count($orderDetail)>0){ # Jika $orderDetail > 0 maka hapus detail order
					foreach($orderDetail as $k => $v){
						if($updateStokProduk = Product::where('id',$v->id_produk)->first()){ # Update stok di table produk sebelum detail order dihapus
							$updateStokProduk->stok = $updateStokProduk->stok + $v->jumlah;
							$updateStokProduk->save();
							if(!$updateStokProduk){
								DB::rollback();
								return response()->json(['success'=>false,'code'=>500,'message'=>'Data order gagal perbarui(update)','data'=>[]],500);
							}
						}
						if($deleteDetail = OrderDetail::where('id',$v->id)->first()){
							$deleteDetail->delete();
							if(!$deleteDetail){
								DB::rollback();
								return response()->json(['success'=>false,'code'=>500,'message'=>'Data order gagal perbarui(hapus)','data'=>[]],500);
							}
						}
					}
				}
			}
			// return $order;
			$order->save();
			if(!$order){
				DB::rollback();
				return response()->json(['success'=>false,'code'=>500,'message'=>'Data order gagal disimpan','data'=>[]],500);
			}
			foreach($request->idProduk as $k => $v){
				if(!$product = Product::where('id',$v)->first()){
					DB::rollback();
					return response()->json(['success'=>false,'code'=>500,'message'=>'Produk tidak ditemukan','data'=>[]],500);
				}
				$stokAwal = $product->stok;
				// $update = true;
				// $cekDetail = OrderDetail::where([['id_order',$request->orderId],['id_produk',$v]])->first();
				// if(!$detail = OrderDetail::where([['id_order',$request->orderId],['id_produk',$v]])->first()){
				// 	$detail = new OrderDetail;
				// 	$update = false;
				// }
				// if($cekDetail){
				// 	$detail = $cekDetail;
				// }else{
				// 	$detail = new OrderDetail;
				// }
				// if(!$request->orderId && !$detail){
				// }
				// $detail = $request->orderId ? OrderDetail::where([['id_order',$request->orderId],['id_produk',$v]])->first() : new OrderDetail;
				// if(!$request->orderId){ # Laporan baru
				if($detail = OrderDetail::where([['id_order',$request->orderId],['id_produk',$v]])->first()){ # Laporan lama
					if($detail->jumlah != $request->qtyAkhir[$k]){
						if($detail->jumlah < $request->qtyAkhir[$k]){ # Jumlah lama lebih kecil dari jumlah baru
							$updateStok = $request->qtyAkhir[$k] - $detail->jumlah; # Ambil selisih dari jumlah baru
							$stokAwal = $stokAwal - $updateStok; # Update stok
						}
						if($detail->jumlah > $request->qtyAkhir[$k]){ # Jumlah lama lebih besar dari jumlah baru
							$updateStok = $detail->jumlah - $request->qtyAkhir[$k]; # Ambil selisih dari jumlah lama
							$stokAwal = $stokAwal + $updateStok; # Update stok
						}
						$detail->jumlah = $request->qtyAkhir[$k];
						$detail->harga = $request->totalHarga[$k];
					}
				}else{ # Laporan baru
					$detail = new OrderDetail;
					$detail->id_order = $order->id;
					$detail->id_produk = $v;
					$detail->jumlah = $request->qtyAkhir[$k];
					$detail->harga = $request->totalHarga[$k];
					$stokAwal = $stokAwal - $request->qtyAkhir[$k]; # Update stok
				}
				$detail->save();
				if(!$detail){
					DB::rollback();
					return response()->json(['success'=>false,'code'=>500,'message'=>'Data detail order gagal disimpan','data'=>[]],500);
				}
				$product->stok = $stokAwal;
				$product->save();
				if(!$product){
					DB::rollback();
					return response()->json(['success'=>false,'code'=>500,'message'=>'Stok produk gagal di update','data'=>[]],500);
				}
			}
			DB::commit();
			return response()->json(['success'=>true,'code'=>200,'message'=>'Data berhasil disimpan','data'=>[]],200);
		}catch(\Throwable $e){
			DB::rollback();
			$e->getFile(); # Get location file error
			$e->getMessage(); # Get error message
			$e->getLine(); # Get line error
			return response()->json(['success'=>false,'code'=>500,'message'=>$e->getLine().' || '.$e->getMessage(),'data'=>[]],500);
		}
	}

	public function detail(Request $request){
      if($order = Order::with('order_detail.product')->where('id',$request->id)->first()){
         return response()->json(['success'=>true,'code'=>200,'message'=>'Data ditemukan','data'=>$order],200);
      }
      return response()->json(['success'=>false,'code'=>204,'message'=>'Data tidak ditemukan','data'=>[]],204);
   }
}
