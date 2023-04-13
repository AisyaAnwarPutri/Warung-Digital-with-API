<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    /*untuk menghubungkan model kategori utama 
    dengan model subkategori dalam relasi one-to-many*/
    public function Subcategory()
    {
        return $this->hasMany(Subcategory::class);
    }

    /**untuk menghubungkan model kategori
     *  dengan model produk dalam relasi one-to-many */
    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
