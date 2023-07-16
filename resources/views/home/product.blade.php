@extends('layout.home')

@section('title', 'Product')

@section('content')

@php
$url_image = ($product && $product->gambar) ? ('/storage/'.$product->gambar) : '/assets/default.png'
@endphp
<!-- Single Product -->
<section class="section-wrap pb-40 single-product">
	<div class="container-fluid semi-fluid">
		<div class="row">
			<div class="col-md-6 col-xs-12 product-slider mb-60">
				<div class="flickity flickity-slider-wrap mfp-hover" id="gallery-main">
					<div class="gallery-cell">
						<a href="{{$url_image}}" class="lightbox-img">
							<img src="{{$url_image}}" alt="" />
							<i class="ui-zoom zoom-icon"></i>
						</a>
					</div>
					<!-- <div class="gallery-cell">
						<a href="{{$url_image}}" class="lightbox-img">
							<img src="{{$url_image}}" alt="" />
							<i class="ui-zoom zoom-icon"></i>
						</a>
					</div>
					<div class="gallery-cell">
						<a href="{{$url_image}}" class="lightbox-img">
							<img src="{{$url_image}}" alt="" />
							<i class="ui-zoom zoom-icon"></i>
						</a>
					</div>
					<div class="gallery-cell">
						<a href="{{$url_image}}" class="lightbox-img">
							<img src="{{$url_image}}" alt="" />
							<i class="ui-zoom zoom-icon"></i>
						</a>
					</div>
					<div class="gallery-cell">
						<a href="{{$url_image}}" class="lightbox-img">
							<img src="{{$url_image}}" alt="" />
							<i class="ui-zoom zoom-icon"></i>
						</a>
					</div> -->
				</div> <!-- end gallery main -->

				<!-- <div class="gallery-thumbs">
					<div class="gallery-cell">
						<img src="{{$url_image}}" alt="" />
					</div>
					<div class="gallery-cell">
						<img src="{{$url_image}}" alt="" />
					</div>
					<div class="gallery-cell">
						<img src="{{$url_image}}" alt="" />
					</div>
					<div class="gallery-cell">
						<img src="{{$url_image}}" alt="" />
					</div>
					<div class="gallery-cell">
						<img src="{{$url_image}}" alt="" />
					</div>
				</div> -->
			</div> <!-- end col img slider -->
			<div class="col-md-6 col-xs-12 product-description-wrap">
				<ol class="breadcrumb">
					<li>
						<a href="/">Beranda</a>
					</li>
					<li>
						<a href="/products/{{$product->id_kategori}}">{{$product->category->nama_kategori}}</a>
					</li>
					<li class="active">
						{{$product->nama_produk}}
					</li>
				</ol>
				<h1 class="product-title">{{$product->nama_produk}}</h1>
				<span class="price">
					<ins>
						<span class="amount">Rp. {{number_format($product->harga)}}</span>
					</ins>
				</span>
				<p class="short-description">{{$product->deskripsi}}</p>

				<div class="color-swatches clearfix">
					<span>Stok:</span>
					<span for="" style="margin-right: 20px">{{$product->stok}}</span>
				</div>

				<!-- User Id Member -->
				<input type="hidden" id="id_member" value="@if($user = Auth::guard('webmember')->user()){{$user->id}}@endif">

				<div class="product-actions">
					<span>Qty:</span>
					<div class="quantity buttons_added">
						<input type="number" step="1" min="0" value="1" title="Qty" class="input-text jumlah qty text" />
						<div class="quantity-adjust">
							<a href="#" class="plus">
								<i class="fa fa-angle-up"></i>
							</a>
							<a href="#" class="minus">
								<i class="fa fa-angle-down"></i>
							</a>
						</div>
					</div>
						<button type="button" class="btn btn-dark btn-lg add-to-cart"><span>Tambah Keranjang</span></button>
						<!-- <a href="#" class="product-add-to-wishlist"><i class="fa fa-heart"></i></a> -->
					</div>

					<div class="product_meta">
						<!-- <span class="sku">SKU: <a href="#">{{$product->sku}}</a></span> -->
						<span class="brand_as">Kategori: <a href="#">{{$product->category->nama_kategori}}</a></span>
						<span class="posted_in">Tag: <a href="#">{{$product->tags}}</a></span>
					</div>

					<!-- Accordion -->
					<div class="panel-group accordion mb-50" id="accordion">
						<div class="panel">
							<div class="panel-heading">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
									class="minus">Deskripsi<span>&nbsp;</span>
								</a>
							</div>
							<div id="collapseOne" class="panel-collapse collapse in">
								<div class="panel-body">
									{{$product->deskripsi}}
								</div>
							</div>
						</div>
					</div>

					<div class="socials-share clearfix">
						<!-- <span>Share:</span>
						<div class="social-icons nobase">
							<a href="#"><i class="fa fa-twitter"></i></a>
							<a href="#"><i class="fa fa-facebook"></i></a>
							<a href="#"><i class="fa fa-google"></i></a>
							<a href="#"><i class="fa fa-instagram"></i></a>
						</div> -->
					</div>
				</div> <!-- end col product description -->
			</div> <!-- end row -->
		</div> <!-- end container -->
	</div>
</section> <!-- end single product -->

<!-- Related Products -->
<section class="section-wrap pt-0 shop-items-slider">
	<div class="container">
		<div class="row heading-row">
			<div class="col-md-12 text-center">
					<h2 class="heading bottom-line">
                  Produk Terbaru
					</h2>
			</div>
		</div>
		<div class="row">
			<div id="owl-related-items" class="owl-carousel owl-theme">
				<div class="product">
					<div class="product-item hover-trigger">
						<div class="product-img">
							<a href="/product/{{$product->id}}">
								<img src="/storage/{{$product->gambar}}" alt="">
								<img src="/storage/{{$product->gambar}}" alt="" class="back-img">
							</a>
							<!-- <div class="product-label">
								<span class="sale">sale</span>
							</div> -->
							<div class="hover-2">
								<div class="product-actions">
									<!-- <a href="#" class="product-add-to-wishlist">
										<i class="fa fa-heart"></i>
									</a> -->
								</div>
							</div>
							<a href="/product/{{$product->id}}" class="product-quickview">Detail</a>
						</div>
						<div class="product-details">
							<h3 class="product-title">
								<a href="/product/{{$product->id}}">{{$product->nama_produk}}</a>
							</h3>
							<span class="category">
								<a href="/products/{{$product->id_kategori}}">{{$product->category->nama_kategori}}</a>
							</span>
						</div>
						<span class="price">
							<ins>
								<span class="amount">Rp. {{number_format($product->harga)}}</span>
							</ins>
						</span>
					</div>
				</div>
			</div> <!-- end slider -->
		</div>
	</div>
</section> <!-- end related products -->
@endsection

@push('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script><!--sweetAlert-->
<script>
	var user = "{{Auth::guard('webmember')->user()}}"
	$(function(){
		$('.add-to-cart').click(function(e){
			id_member = $('#id_member').val()
			if(id_member){
				Swal.fire({
					title: "Confirm",
					text: "Ingin menambahkan produk ke keranjang?",
					icon: "info",
					showCancelButton: true,
					reverseButtons: true,
					confirmButtonColor: '#rgb(46 151 199)',
					// cancelButtonColor: '#rgb(155 155 155)',
					cancelButtonColor: '#rgb(155 155 155)',
					confirmButtonText: "Ya!",
					cancelButtonText: "Batal."
				}).then((res)=>{
					if(res.isConfirmed){
						id_barang = {{$product->id}}
						jumlah = $('.jumlah').val()
						total = {{$product->harga}}*jumlah
						is_checkout = 0
						$.ajax({
							url : '{{route("home.store_orders")}}',
							method : "POST",
							headers: {
								'X-CSRF-TOKEN': "{{csrf_token()}}",
							},
							data : {
								id_member,
								id_barang,
								jumlah,
								total,
								is_checkout,
							},
							success : function(res){
								if(res.success){
									$.ajax({
										url: '{{route("home.count_keranjang")}}',
										method: 'POST',
										headers: {
											'X-CSRF-TOKEN': "{{csrf_token()}}",
										},
										data: {id: res.data.id}
									}).done((res)=>{
										if(res.success){
											$('.jumlah').val('1')
											$('#keranjang').text(res.data.order_detail_count)
											$('#link-keranjang').attr("href", "/cart?id="+res.data.id)
										}
									});
									Swal.fire({
										icon: 'success',
										title: 'Berhasil',
										text: res.message,
										timer: 1000,
										showConfirmButton: false,
									});
								}else{
									Swal.fire({
										icon: 'error',
										title: 'Gagal',
										text: res.message,
										showConfirmButton: true,
									});
								}

								// window.location.href = '/cart'
							}
						});
					}
				});
			}else{
				Swal.fire({
					title: "Whoops",
					text: "Silahkan login terlebih dahulu.",
					icon: "warning",
					showCancelButton: true,
					reverseButtons: true,
					confirmButtonColor: '#rgb(46 151 199)',
					// cancelButtonColor: '#rgb(155 155 155)',
					cancelButtonColor: '#rgb(155 155 155)',
					confirmButtonText: "Login.",
					cancelButtonText: "Batal."
				}).then((res)=>{
					if(res.isConfirmed){
						window.location.href = "/login_member";
					}
				});
			}
		});
	});

</script>
@endpush
