<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TentangController extends Controller{
	public function index(){
		$about = About::first();
		return view('tentang.index', compact('about'));
	}

	public function store(Request $request){
		$logo = $request->file('logo');
		$id = $request->id;
		$empty_id = empty($id);
		$rules = [
			'judul_website' => 'required',
			'deskripsi' => 'required',
			'alamat' => 'required',
			'email' => 'required',
			'telepon' => 'required',
		];
		$message = [
			'judul_website.required' => 'Judul website harus diisi',
			'deskripsi.required' => 'Deskripsi harus diisi',
			'alamat.required' => 'Alamat harus diisi',
			'email.required' => 'Email produk harus diisi',
			'telepon.required' => 'Telepon harus diisi',
		];
		if($empty_id || $logo){ # Required logo hanya ketika tambah data{produk baru}
			$rules += [
				'logo' => 'required|image|mimes:jpg,png,jpeg,webp'
			];
			$message += [
				'logo.required' => 'Logo harus diisi',
				'logo.image' => 'Logo harus berupa image',
				'logo.mimes' => 'Format logo harus berupa: jpg, png, jpeg, webp',
			];
		}
		$validator = Validator::make($request->all(),$rules,$message);
		if($validator->fails()){
			$msg = '';
			foreach($validator->errors()->toArray() as $key => $val){
				$msg = $val[0];
				break;
			}
			return ['success'=>false,'code'=>406,'message'=>$msg];
		}
		$time     = date('His').'-';
		$date     = date('d-m-Y').'/';
		$namaLogo = $logo ? $time.$logo->getClientOriginalName() : '';
		$root     = 'tentang/';
		$master   = $root.$date;
		$dir      = $master.$namaLogo;
		$path     = '/storage/'.$dir;

		$store = $empty_id ? new About : About::find($id);
		$store->judul_website = $request->judul_website;
		$store->deskripsi = $request->deskripsi;
		$store->alamat = $request->alamat;
		$store->email = $request->email;
		$store->telepon = $request->telepon;
		if($logo){
			$checkFile = public_path().'/storage/'.$store->logo;
			if(!empty($store->logo) && file_exists($checkFile)){ # Remove old file if exists
				unlink($checkFile);
			}
			$saveAs = $logo->storeAs("public/$master", $namaLogo);
			$store->logo = $dir;
		}
		$store->save();
		if($store){
			return ['success'=>true,'code'=>($empty_id?201:200),'message'=>'Data berhasil di '.($empty_id?'simpan':'perbarui')];
		}
		return ['success'=>false,'code'=>500,'message'=>'Data gagal di '.($empty_id?'simpan':'perbarui')];


		// $input = $request->all();
		// if($request->has('logo')){
		// 	File::delete('uploads/' . $about->logo);
		// 	$logo = $request->file('logo');
		// 	$nama_logo = time() . rand(1, 9) . '.' . $logo->getClientOriginalExtension();
		// 	$logo->move('uploads', $nama_logo);
		// 	$input['logo'] = $nama_logo;
		// }else{
		// 	unset($input['logo']);
		// }
		// $about->update($input);
		// return redirect('/tentang');
	}
}
