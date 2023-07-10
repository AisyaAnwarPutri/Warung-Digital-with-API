<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use DataTables;

class ProductController extends Controller{
	public function __construct(){
		// $this->middleware('auth')->only(['list']);
		// $this->middleware('auth:api')->only(['store', 'update', 'destroy']);
	}

	public function list(){
		$categories = Category::all();
		$subcategories = Subcategory::all();

		return view('product.index', compact('categories', 'subcategories'));
	}

	public function index(){
		if(request()->ajax()){
			$products = Product::with('category', 'subcategory')->orderBy('id','ASC')->get();
			return DataTables::of($products)
				->addIndexColumn()
				->addColumn('pendaftar',function($row){
					$txt = "<button class='btn btn-sm btn-info detailPendaftar' onclick='detailPendaftar($row->id)'><b>($row->pendaftar_count Orang)</b> Detail</button>";
					return $txt;
				})
				->addColumn('kategori',function($row){
					$text = $row->category ? $row->category->nama_kategori : '-';
					return "<p class='text-center'>$text</p>";
				})
				->addColumn('sub_kateogori',function($row){
					$text = $row->subcategory ? $row->subcategory->nama_subkategori : '-';
					return "<p class='text-center'>$text</p>";
				})
				->addColumn('gambar',function($row){
					$gambar = "<p class='text-center'>-</p>";
					if($row->gambar && file_exists(public_path().'/storage/'.$row->gambar)){
						$gambar = "<img class='rounded mx-auto d-block responsive img-thumbnail' src='storage/".$row->gambar."'>";
					}
					return $gambar;
				})
				->addColumn('aksi',function($row){
					$txt = "
						<button class='btn btn-sm editProduk' onclick='editProduk($row->id)' type='button' title='edit'><i class='fa-solid fa-pen-to-square'></i></button>
						<button class='btn btn-sm btn-danger' onclick='hapusProduk($row->id)' type='button' title='hapus'><i class='fa-solid fa-trash'></i></button>
					";
					return $txt;
				})->rawColumns(['pendaftar','kategori','sub_kateogori','gambar','aksi'])->toJson();
		}
		return view('product.index');
	}

	public function form(Request $request){
		if(isset($request->id)){
			$data['page'] = 'Edit';
			$data['product'] = Product::with('category','subcategory')->where('id',$request->id)->first();
		}else{
			$data['page'] = 'Tambah';
			$data['product'] = '';
		}
		$content = view('product.form',$data)->render();
		return ['success'=>true,'message'=>'Data berhasil diambil','data'=>$content];
	}

	public function store(Request $request){
		$gambar = $request->file('gambar');
		$id_produk = $request->id_produk;
		$empty_id = empty($id_produk);
		$rules = [
			'id_kategori' => 'required',
			'id_subkategori' => 'required',
			'nama_produk' => 'required',
			'harga' => 'required',
			'deskripsi' => 'required',
		];
		$message = [
			'id_kategori.required' => 'Kategori harus diisi',
			'id_subkategori.required' => 'Sub kategori harus diisi',
			'nama_produk.required' => 'Nama produk harus diisi',
			'harga.required' => 'Harga harus diisi',
			'deskripsi.required' => 'Deskripsi harus diisi',
		];
		if($empty_id || $gambar){ # Required gambar hanya ketika tambah data
			$rules += [
				// ...$rules,
				'gambar' => 'required|image|mimes:jpg,png,jpeg,webp'
			];
			$message += [
				// ...$message,
				'gambar.required' => 'Gambar harus diisi',
				'gambar.image' => 'Gambar harus berupa image',
				'gambar.mimes' => 'Format gambar harus berupa: jpg, png, jpeg, webp',
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

		$time       = date('His').'-';
		$date       = date('d-m-Y').'/';
		$namaGambar = $gambar ? $time.$gambar->getClientOriginalName() : '';
		$root       = 'produk/';
		$master     = $root.$date;
		$dir        = $master.$namaGambar;
		$path       = '/storage/'.$dir;

		$store = $empty_id ? new Product : Product::find($id_produk);
		$store->id_kategori = $request->id_kategori;
		$store->id_subkategori = $request->id_subkategori;
		$store->nama_produk = $request->nama_produk;
		$store->deskripsi = $request->deskripsi;
		$store->harga = preg_replace("/\D+/", "", $request->harga);
		$store->tags = $request->tags;
		if($gambar){
			$checkFile = public_path().'/storage/'.$store->gambar;
			if(!empty($store->gambar) && file_exists($checkFile)){ # Remove old file if exists
				unlink($checkFile);
			}
			$saveAs = $gambar->storeAs("public/$master", $namaGambar);
			$store->gambar = $dir;
		}
		$store->save();
		if($store){
			return ['success'=>true,'code'=>($empty_id?201:200),'message'=>'Produk berhasil di '.($empty_id?'simpan':'perbarui')];
		}
		return ['success'=>false,'code'=>500,'message'=>'Produk gagal di '.($empty_id?'simpan':'perbarui')];
	}

	public function show(Product $Product){
		return response()->json([
			'success' => true,
			'data' => $Product
		]);
	}

	public function update(Request $request, Product $Product){
		$validator = Validator::make($request->all(), [
			'id_kategori' => 'required',
			'id_subkategori' => 'required',
			'nama_produk' => 'required',
			'harga' => 'required',
			'tags' => 'required',
			'gambar' => 'required',
			'deskripsi' => 'required',
			'gambar' => 'required|image|mimes:jpg,png,jpeg,webp'
		]);
		if ($validator->fails()) {
			return response()->json(
					$validator->errors(),
					422
			);
		}
		$input = $request->all();
		if ($request->has('gambar')) {
			File::delete('uploads/' . $Product->gambar);
			$gambar = $request->file('gambar');
			$nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
			$gambar->move('uploads', $nama_gambar);
			$input['gambar'] = $nama_gambar;
		} else {
			unset($input['gambar']);
		}
		$Product->update($input);
		return response()->json([
			'success' => true,
			'message' => 'Data berhasil update',
			'data' => $Product
		]);
	}

	public function destroy(Request $request){
		if($find = Product::find($request->id)){
			$checkFile = public_path().'/storage/'.$find->gambar;
			if(!empty($find->gambar) && file_exists($checkFile)){ # Remove old file if exists
				unlink($checkFile);
			}
			if($find->delete()){
				return ['success'=>true,'code'=>200,'message'=>'Produk berhasil dihapus'];
			}
			return ['success'=>false,'code'=>406,'message'=>'Produk gagal dihapus'];
		}
		return ['success'=>false,'code'=>204,'message'=>'Produk tidak ditemukan'];
	}

	// public function destroy(Product $Product){
	// 	File::delete('uploads/' . $Product->gambar);
	// 	$Product->delete();
	// 	return response()->json([
	// 		'success' => true,
	// 		'message' => 'success'
	// 	]);
	// }
}
