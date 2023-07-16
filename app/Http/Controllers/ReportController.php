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

	public function get_reports(Request $request)
	{
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

	public function index(Request $request)
	{
		if(request()->ajax()){
			$query = DB::table('order_details as od')
				->join('products as p', 'p.id', '=', 'od.id_produk')
				->join('orders','od.id_order','=','orders.id')
				->select(DB::raw('
						nama_produk,
						count(*) as jumlah_dibeli,
						p.harga,
						SUM(od.harga) as pendapatan,
						SUM(jumlah) as total_qty'))
				->where('orders.lunas',true);
			$query->when( # Jika parameter tanggal tidak kosong
				$request->tanggalAwal && $request->tanggalAkhir,fn($q)=>
					$q->whereBetween('orders.created_at',["$request->tanggalAwal 00:00:00","$request->tanggalAkhir 23:59:59"]
				)
			);
			$query->when( # Jika parameter tanggal kosong, default value tanggal hari ini
				!$request->tanggalAwal && !$request->tanggalAkhir,fn($q)=>
					$q->whereBetween('orders.created_at',[date('Y-m-d')." 00:00:00",date('Y-m-d')." 23:59:59"]
				)
			);
			$data = $query->groupBy('id_produk', 'nama_produk', 'harga')->get();
			return DataTables::of($data)
				->addIndexColumn()
				->rawColumns([])->toJson();
		}
		return view('report.index');
	}
}
