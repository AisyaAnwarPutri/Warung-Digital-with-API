<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**untuk menghubungkan model subkategori 
     * dengan model kategori dalam relasi many-to-one */
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_kategori', 'id');
    }

    /**untuk menghubungkan model kategori 
     * dengan model produk dalam relasi one-to-many. */
    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
