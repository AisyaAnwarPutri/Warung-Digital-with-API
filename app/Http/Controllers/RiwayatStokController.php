<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatStok;
use DataTables;

class RiwayatStokController extends Controller{
	public function index(Request $request){
		if(request()->ajax()){
			$riwayat = RiwayatStok::with('product','user')->orderBy('id','ASC')->get();
			return DataTables::of($riwayat)
				->addIndexColumn()
				->addColumn('user',function($row){
					$text = $row->user ? $row->user->name : '-';
					return "<p class='text-center'>$text</p>";
				})
				->addColumn('produk',function($row){
					$text = $row->product ? $row->product->nama_produk : '-';
					return "<p class='text-center'>$text</p>";
				})
				->addColumn('tanggal',function($row){
					$date = $row->tanggal;
					$hari = ["Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu","Minggu"];
					$tanggal = $date ? date('d-m-Y',strtotime($date)) : '-';
					$numOfDay = $date ? date('N',strtotime($date)) : '-'; # Hari dalam angka : 1{Senin}, 2{Selasa}, dst...
					$text = $date ? $hari[$numOfDay-1].", $tanggal" : '-';
					return "<p class='text-center'>$text</p>";
				})->rawColumns(['user','produk','tanggal'])->toJson();
		}
		return view('riwayat-stok.index');
		return $request->all();
	}
}
