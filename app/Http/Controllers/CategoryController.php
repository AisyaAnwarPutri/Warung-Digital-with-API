<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller{
	public function __construct(){
		$this->middleware('auth')->only(['list']);
		$this->middleware('auth:api')->only(['store', 'update', 'destroy']);
	}

	public function list(Request $request){
		$categories = Category::all();
		if(count($categories)>0){
			return ['success'=>true,'code'=>200,'message'=>'Data found','data'=>$categories];
		}
		return ['success'=>false,'code'=>204,'message'=>'Data not found','data'=>[]];
	}

	public function index(){
		return view('kategori.index');
	}

	public function store(Request $request){
		$validator = Validator::make($request->all(), [
			'nama_kategori' => 'required',
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
			$gambar = $request->file('gambar');
			$nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
			$gambar->move('uploads', $nama_gambar);
			$input['gambar'] = $nama_gambar;
		}
		$category = Category::create($input);
		return response()->json([
			'success' => true,
			'data' => $category
		]);
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

	public function destroy(Category $category){
		File::delete('uploads/' . $category->gambar);
		$category->delete();
		return response()->json([
			'success' => true,
			'message' => 'success'
		]);
	}
}
