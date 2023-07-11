<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use DataTables;

class CategoryController extends Controller{
	public function __construct(){
		// $this->middleware('auth')->only(['list']);
		// $this->middleware('auth:api')->only(['store', 'update', 'destroy']);
	}

	public function list(Request $request){
		$categories = Category::all();
		if(count($categories)>0){
			return ['success'=>true,'code'=>200,'message'=>'Data found','data'=>$categories];
		}
		return ['success'=>false,'code'=>204,'message'=>'Data not found','data'=>[]];
	}

	public function index(){
		if(request()->ajax()){
			$products = Category::orderBy('id','ASC')->get();
			return DataTables::of($products)
				->addIndexColumn()
				->addColumn('nama',function($row){
					$text = $row->nama_kategori ? $row->nama_kategori : '-';
					return "<p class='text-center'>$text</p>";
				})
				// ->addColumn('deskripsi',function($row){
				// 	$text = $row->deskripsi ? $row->deskripsi : '-';
				// 	return "<p class='text-center'>$text</p>";
				// })
				// ->addColumn('gambar',function($row){
				// 	$gambar = "<p class='text-center'>-</p>";
				// 	if($row->gambar && file_exists(public_path().'/storage/'.$row->gambar)){
				// 		$gambar = "<img class='rounded mx-auto d-block responsive img-thumbnail' src='storage/".$row->gambar."'>";
				// 	}
				// 	return $gambar;
				// })
				->addColumn('aksi',function($row){
					$txt = "
                  <div class='row'>
                     <div class='col text-center'>
                     <button class='btn btn-sm editKategori' onclick='editKategori($row->id)' type='button' title='edit'><i class='fa-solid fa-pen-to-square'></i></button>
                     <button class='btn btn-sm btn-danger' onclick='hapusKategori($row->id)' type='button' title='hapus'><i class='fa-solid fa-trash'></i></button>
                     </div>
                  </div>
					";
					return $txt;
				})->rawColumns(['nama','deskripsi','gambar','aksi'])->toJson();
		}
		return view('kategori.index');
	}

	public function get(Request $request){
		$kategori = Category::find($request->id);
		return $kategori?['success'=>true,'data'=>$kategori]:['success'=>false,'data'=>[]];
	}

	public function store(Request $request){
		$gambar = $request->file('gambar');
		$id_kategori = $request->id_kategori;
		$empty_id = empty($id_kategori);
		$rules = [
			'nama_kategori' => 'required',
			// 'deskripsi' => 'required',
		];
		$message = [
			'nama_kategori.required' => 'Nama kategori harus diisi',
			// 'deskripsi.required' => 'Deskripsi harus diisi',
		];
		// if($empty_id || $gambar){ # Required gambar hanya ketika tambah data
		// 	$rules += [
		// 		'gambar' => 'required|image|mimes:jpg,png,jpeg,webp'
		// 	];
		// 	$message += [
		// 		'gambar.required' => 'Gambar harus diisi',
		// 		'gambar.image' => 'Gambar harus berupa image',
		// 		'gambar.mimes' => 'Format gambar harus berupa: jpg, png, jpeg, webp',
		// 	];
		// }
		$validator = Validator::make($request->all(), $rules, $message);
		if($validator->fails()){
			$msg = '';
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0];
				break;
			}
			return ['success' => false, 'code' => 406, 'message' => $msg];
		}
		// $time       = date('His') . '-';
		// $date       = date('d-m-Y') . '/';
		// $namaGambar = $gambar ? $time . $gambar->getClientOriginalName() : '';
		// $root       = 'kategori/';
		// $master     = $root . $date;
		// $dir        = $master . $namaGambar;
		// $path       = '/storage/' . $dir;

		$store = $empty_id ? new Category : Category::find($id_kategori);
		$store->nama_kategori = $request->nama_kategori;
		// $store->deskripsi = $request->deskripsi;
		// if($gambar){
		// 	$checkFile = public_path() . '/storage/' . $store->gambar;
		// 	if (!empty($store->gambar) && file_exists($checkFile)) { # Remove old file if exists
		// 		unlink($checkFile);
		// 	}
		// 	$saveAs = $gambar->storeAs("public/$master", $namaGambar);
		// 	$store->gambar = $dir;
		// }
		$store->save();
		if ($store) {
			return ['success' => true, 'code' => ($empty_id ? 201 : 200), 'message' => 'Kategori berhasil di ' . ($empty_id ? 'simpan' : 'perbarui')];
		}
		return ['success' => false, 'code' => 500, 'message' => 'Kategori gagal di ' . ($empty_id ? 'simpan' : 'perbarui')];
		// $validator = Validator::make($request->all(), [
		// 	'nama_kategori' => 'required',
		// 	'deskripsi' => 'required',
		// 	'gambar' => 'required|image|mimes:jpg,png,jpeg,webp'
		// ]);
		// if ($validator->fails()) {
		// 	return response()->json(
		// 			$validator->errors(),
		// 			422
		// 	);
		// }
		// $input = $request->all();
		// if ($request->has('gambar')) {
		// 	$gambar = $request->file('gambar');
		// 	$nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
		// 	$gambar->move('uploads', $nama_gambar);
		// 	$input['gambar'] = $nama_gambar;
		// }
		// $category = Category::create($input);
		// return response()->json([
		// 	'success' => true,
		// 	'data' => $category
		// ]);
	}

	public function show(Category $category){
		return response()->json([
			'data' => $category
		]);
	}

	public function update(Request $request, Category $category){
		$validator = Validator::make($request->all(), [
			'nama_kategori' => 'required',
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
			File::delete('uploads/' . $category->gambar);
			$gambar = $request->file('gambar');
			$nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
			$gambar->move('uploads', $nama_gambar);
			$input['gambar'] = $nama_gambar;
		} else {
			unset($input['gambar']);
		}
		$category->update($input);
		return response()->json([
			'success' => true,
			'message' => 'success',
			'data' => $category
		]);
	}

	public function destroy(Request $request){
		if($find = Category::find($request->id)){
			// $checkFile = public_path().'/storage/'.$find->gambar;
			// if(!empty($find->gambar) && file_exists($checkFile)){ # Remove old file if exists
			// 	unlink($checkFile);
			// }
			if($find->delete()){
				return ['success'=>true,'code'=>200,'message'=>'Kategori berhasil dihapus'];
			}
			return ['success'=>false,'code'=>406,'message'=>'Kategori gagal dihapus'];
		}
		return ['success'=>false,'code'=>204,'message'=>'Kategori tidak ditemukan'];
	}
}
