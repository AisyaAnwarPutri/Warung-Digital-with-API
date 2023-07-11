<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller{
	public function __construct(){
		// $this->middleware('auth')->only(['list']);
		// $this->middleware('auth:api')->only(['store', 'update', 'destroy']);
	}

	public function list(){
		$sliders = Slider::all();
		return response()->json([
			'success' => true,
			'data' => $sliders
		]);
	}

	public function index(){
		$sliders = Slider::all();
		return view('slider.index',compact('sliders'));
	}

	public function store(Request $request){
		$gambar = $request->file('gambar');
		$id_slider = $request->id_slider;
		$empty_id = empty($id_slider);
		$rules = [
			'nama_slider' => 'required',
			'deskripsi' => 'required',
		];
		$message = [
			'nama_slider.required' => 'Nama slider harus diisi',
			'deskripsi.required' => 'Deskripsi harus diisi',
		];
		if($empty_id || $gambar){ # Required gambar hanya ketika tambah data
			$rules += [
				'gambar' => 'required|image|mimes:jpg,png,jpeg,webp'
			];
			$message += [
				'gambar.required' => 'Gambar harus diisi',
				'gambar.image' => 'Gambar harus berupa image',
				'gambar.mimes' => 'Format gambar harus berupa: jpg, png, jpeg, webp',
			];
		}
		$validator = Validator::make($request->all(), $rules, $message);
		if($validator->fails()){
			$msg = '';
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0];
				break;
			}
			return ['success' => false, 'code' => 406, 'message' => $msg];
		}
		$time       = date('His') . '-';
		$date       = date('d-m-Y') . '/';
		$namaGambar = $gambar ? $time . $gambar->getClientOriginalName() : '';
		$root       = 'slider/';
		$master     = $root . $date;
		$dir        = $master . $namaGambar;
		$path       = '/storage/' . $dir;

		$store = $empty_id ? new Slider : Slider::find($id_slider);
		$store->nama_slider = $request->nama_slider;
		$store->deskripsi = $request->deskripsi;
		if($gambar){
			$checkFile = public_path() . '/storage/' . $store->gambar;
			if (!empty($store->gambar) && file_exists($checkFile)) { # Remove old file if exists
				unlink($checkFile);
			}
			$saveAs = $gambar->storeAs("public/$master", $namaGambar);
			$store->gambar = $dir;
		}
		$store->save();
		if ($store) {
			return ['success' => true, 'code' => ($empty_id ? 201 : 200), 'message' => 'Produk berhasil di ' . ($empty_id ? 'simpan' : 'perbarui')];
		}
		return ['success' => false, 'code' => 500, 'message' => 'Produk gagal di ' . ($empty_id ? 'simpan' : 'perbarui')];
	}

	public function get(Request $request){ # Get for edit
		$slider = Slider::find($request->id);
		return $slider?['success'=>true,'data'=>$slider]:['success'=>false,'data'=>[]];
	}

	public function show(Slider $Slider){
		return response()->json([
			'success' => true,
			'data' => $Slider
		]);
	}

	public function update(Request $request, Slider $Slider){
		$validator = Validator::make($request->all(), [
			'nama_slider' => 'required',
			'deskripsi' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(
					$validator->errors(),
					422
			);
		}
		$input = $request->all();
		if ($request->has('gambar')) {
			File::delete('uploads/' . $Slider->gambar);
			$gambar = $request->file('gambar');
			$nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
			$gambar->move('uploads', $nama_gambar);
			$input['gambar'] = $nama_gambar;
		} else {
			unset($input['gambar']);
		}
		$Slider->update($input);
		return response()->json([
			'success' => true,
			'message' => 'success',
			'data' => $Slider
		]);
	}

	public function destroy(Request $request){
		if($find = Slider::find($request->id)){
			$checkFile = public_path().'/storage/'.$find->gambar;
			if(!empty($find->gambar) && file_exists($checkFile)){ # Remove old file if exists
				unlink($checkFile);
			}
			if($find->delete()){
				return ['success'=>true,'code'=>200,'message'=>'Slider berhasil dihapus'];
			}
			return ['success'=>false,'code'=>406,'message'=>'Slider gagal dihapus'];
		}
		return ['success'=>false,'code'=>204,'message'=>'Slider tidak ditemukan'];
	}
}
