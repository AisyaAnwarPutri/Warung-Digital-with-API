<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model{
	use HasFactory;
	protected $guarded = [];

	public function category(){
		return $this->belongsTo(Category::class, 'id_kategori', 'id');
	}
	public function subcategory(){
		return $this->belongsTo(Subcategory::class, 'id_subkategori', 'id');
	}
	public function cart(){
		return $this->hasMany(Cart::class);
	}
	public function order_detail(){
		return $this->hasMany(OrderDetail::class,'id_produk','id');
	}
	public function riwayat_stok(){
		return $this->hasMany(RiwayatStok::class,'produk_id','id');
	}
}
