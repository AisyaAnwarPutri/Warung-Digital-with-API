<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * untuk menghubungkan model produk 
     * dengan model kategori dalam relasi many-to-one
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_kategori', 'id');
    }

    /** untuk menghubungkan model produk 
     * dengan model subkategori dalam relasi many-to-one*/
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'id_subkategori', 'id');
    }

    /**untuk menghubungkan model produk 
     * dengan model cart dalam relasi one-to-many */
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
}
