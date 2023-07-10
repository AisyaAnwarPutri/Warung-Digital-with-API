<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\TestimoniController;
use Illuminate\Support\Facades\Route;


// auth login admin
Route::controller(AuthController::class)->group(function(){
	 Route::get('login', 'index')->name('login');
	 Route::post('login', 'do_login');
	 Route::get('logout', 'logout');
});
// Route::get('login', [AuthController::class, 'index'])->name('login');
// Route::post('login', [AuthController::class, 'login']);
// Route::get('logout', [AuthController::class, 'logout']);

// login user
Route::get('login_member', [AuthController::class, 'login_member']);
Route::post('login_member', [AuthController::class, 'login_member_action']);
Route::get('logout_member', [AuthController::class, 'logout_member']);

// register user
Route::get('register_member', [AuthController::class, 'register_member']);
Route::post('register_member', [AuthController::class, 'register_member_action']);

# Kategori start
Route::controller(CategoryController::class)
	->prefix('kategori')
	->as('kategori.')
	->group(function(){
		Route::get('/', 'index');
		Route::post('get', 'list')->name('get');
});
// Route::get('/kategori', [CategoryController::class, 'list']);
# Kategori end

# Sub kategori start
Route::controller(SubcategoryController::class)
	->prefix('subkategori')
	->as('sub_kategori.')
	->group(function(){
		Route::get('/', 'index');
		Route::post('get', 'list')->name('get');
});
// Route::get('/subkategori', [SubcategoryController::class, 'list']);
# Sub kategori end

# Produk start
Route::controller(ProductController::class)
	->prefix('produk')
	->as('produk.')
	->group(function(){
		Route::get('/', 'index')->name('index');
		Route::post('form', 'form')->name('form_produk');
		Route::post('store', 'store')->name('save_produk');
		Route::post('destroy', 'destroy')->name('destroy_produk');
});
# Produk end

# Slider start
Route::controller(SliderController::class)
	->prefix('slider')
	->as('slider.')
	->group(function(){
		Route::get('/', 'index')->name('index');
		Route::post('list', 'list')->name('list');
		Route::post('store', 'store')->name('store');
		Route::post('get', 'get')->name('get');
		Route::post('destroy', 'destroy')->name('destroy');
});
# Slider end
// Route::get('/slider', [SliderController::class, 'list']);
Route::get('/testimoni', [TestimoniController::class, 'list']);
Route::get('/review', [ReviewController::class, 'list']);
Route::get('/payment', [PaymentController::class, 'list']);

// Pesanan
Route::get('/pesanan/baru', [OrderController::class, 'list']);
Route::get('/pesanan/dikonfirmasi', [OrderController::class, 'dikonfirmasi_list']);
Route::get('/pesanan/dikemas', [OrderController::class, 'dikemas_list']);
Route::get('/pesanan/dikirim', [OrderController::class, 'dikirim_list']);
Route::get('/pesanan/diterima', [OrderController::class, 'diterima_list']);
Route::get('/pesanan/selesai', [OrderController::class, 'selesai_list']);
Route::get('/pesanan/selesai', [OrderController::class, 'selesai_list']);

Route::get('/laporan', [ReportController::class, 'index']);
Route::get('/tentang', [TentangController::class, 'index']);
Route::post('/tentang/{about}', [TentangController::class, 'update']);

Route::get('/dashboard', [DashboardController::class, 'index']);

// Route untuk Beranda
Route::get('/', [HomeController::class, 'index']);
Route::get('/products/{category}', [HomeController::class, 'product']);
Route::get('/product/{id}', [HomeController::class, 'product']);
Route::get('/orders', [HomeController::class, 'orders']);
Route::get('/cart', [HomeController::class, 'cart']);
Route::get('/checkout', [HomeController::class, 'checkout']);
Route::get('/about', [HomeController::class, 'about']);
Route::get('/contact', [HomeController::class, 'contact']);
Route::get('/faq', [HomeController::class, 'faq']);

Route::controller(HomeController::class)
	// ->prefix('subkategori')
	// ->as('sub_kategori.')
	->group(function(){
		Route::post('store-orders', 'store_orders')->name('store_orders');
});