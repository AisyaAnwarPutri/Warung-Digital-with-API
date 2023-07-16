<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderDetail;

class DashboardController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		$data['jumlahOrder'] = OrderDetail::whereHas('order',fn($q)=>$q->where('lunas',true))->sum('jumlah');
		$data['penghasilan'] = OrderDetail::whereHas('order',fn($q)=>$q->where('lunas',true))->sum('harga');
		return view('dashboard',$data);
	}
}
