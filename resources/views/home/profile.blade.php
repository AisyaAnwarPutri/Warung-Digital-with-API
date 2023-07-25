@extends('layout.home')

@section('title', 'Tentang')
@push('style')
<style>
	.styled-table {
		border-collapse: collapse;
		margin: 25px 0;
		font-size: 0.9em;
		font-family: sans-serif;
		/* min-width: 400px; */
		box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
	}
	.styled-table thead tr {
		background-color: #009879;
		color: #ffffff;
		text-align: left;
	}
	.styled-table th,
	.styled-table td {
		padding: 12px 15px;
	}
</style>
@endpush
@section('content')
@php
	function rupiah($angka){
		$hasil_rupiah = "Rp. " . number_format((int)$angka);
		$hasil_rupiah = str_replace(',', '.', $hasil_rupiah);
		return $hasil_rupiah;
	}
@endphp
	<section class="section-wrap">
		<div class="container">
			<div class="row">
				<div class="col-md-7">
					<form class="formSave">
						<div class="row">
							{{-- <div class="col-md-6">
								<input name="text" type="text" placeholder="Input with placeholder">
								<input name="password" id="password" type="password" placeholder="Password input">
								<textarea placeholder="Textarea" rows="3"></textarea>
								<label for="input-label">Input With Label</label>
								<input name="name" id="input-label" type="text">
							</div>
							<div class="col-md-6">
								<select>
									<option selected value="default">Select an option</option>
									<option value="green">Green</option>
									<option value="black">Black</option>
									<option value="white">White</option>
								</select>
								<div class="row mt-30">
									<div class="col-md-6 mb-30">
										<h6>Radio Buttons</h6>
										<ul class="radio-buttons">
											<li>
												<input type="radio" class="input-radio" name="radio" id="radio1" value="radio1" checked="checked">
												<label for="radio1">Radio 1</label>
											</li>
											<li>
												<input type="radio" class="input-radio" name="radio" id="radio2" value="radio2">
												<label for="radio2">Radio 2</label>
											</li>
											<li>
												<input type="radio" class="input-radio" name="radio" id="radio3" value="radio3">
												<label for="radio3">Radio 2</label>
											</li>
										</ul>
									</div>
									<div class="col-md-6 mb-30">
										<h6>Checkboxes</h6>
										<ul class="checkboxes">
											<li>
												<input type="checkbox" class="input-checkbox" name="checkbox" id="checkbox1" value="1" checked="checked">
												<label for="checkbox1">Checkbox 1</label>
											</li>
											<li>
												<input type="checkbox" class="input-checkbox" name="checkbox" id="checkbox2" value="2">
												<label for="checkbox2">Checkbox 2</label>
											</li>
											<li>
												<input type="checkbox" class="input-checkbox" name="checkbox" id="checkbox3" value="3">
												<label for="checkbox3">Checkbox 3</label>
											</li>
										</ul>
									</div>
								</div>
							</div> --}}
							<div class="col-md-4">
								<label for="nama">Nama</label>
								<input name="id" type="hidden" value="{{$user?$user->id:''}}">
								<input name="nama" id="nama" type="text" placeholder="Nama lengkap" autocomplete="off" value="{{$user?$user->nama_member:''}}">
							</div>
							<div class="col-md-4">
								<label for="nomor">No. HP</label>
								<input name="nomor" id="nomor" type="text" placeholder="Nomor HP" autocomplete="off" value="{{$user?$user->no_hp:''}}">
							</div>	
							<div class="col-md-4">
								<label for="email">Email</label>
								<input name="email" id="email" type="email" placeholder="	" autocomplete="off" value="{{$user?$user->email:''}}">
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<label for="provinsi">Provinsi</label>
								<input name="provinsi" id="provinsi" type="text" placeholder="Provinsi" autocomplete="off" value="{{$user?$user->provinsi:''}}">
							</div>
							<div class="col-md-4">
								<label for="kabupaten">Kabupaten</label>
								<input name="kabupaten" id="kabupaten" type="text" placeholder="Kabupaten" autocomplete="off" value="{{$user?$user->kabupaten:''}}">
							</div>
							<div class="col-md-4">
								<label for="kecamatan">Kecamatan</label>
								<input name="kecamatan" id="kecamatan" type="text" placeholder="Kecamatan" autocomplete="off" value="{{$user?$user->kecamatan:''}}">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="kecamatan">Alamat</label>
								<textarea name="alamat" placeholder="Detail alamat" rows="4">{{$user?$user->detail_alamat:''}}</textarea>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<button type="button" class="btn btn-dark save"><span>Simpan perubahan</span></button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-5">
					<div class="tabs">
						<ul class="nav nav-tabs"> 
							<li class="active">
								<a href="#tab-one" data-toggle="tab">Status Pesanan</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="tab-one">
								<table class="styled-table" style="width: 100%;">
									<thead>
										<tr>
											<th>Nama Produk</th>
											<th>Total</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										@if(count($pesanan)>0)
										@foreach($pesanan as $key => $val)
										@php
											$nama = '';
											foreach($val->order_detail as $k2 => $v2){
												$nama .= $v2->product->nama_produk.', ';
											}
											$nama = rtrim($nama, ", ");
										@endphp
											<tr>
												<td>{{$nama}}</td>
												<td>{{rupiah($val->grand_total)}}</td>
												<td>{{$val->status}}</td>
											</tr>
										@endforeach
										@else
											<tr class="text-center">
												<td colspan="3"><i>Anda belum memiliki pesanan</i></td>
											</tr>
										@endif
									</tbody>
								</table>
								{{-- <div class="row">
									<div class="col-md-12">
										<p>
											{{$user}}
										</p>
									</div>
								</div> --}}
							</div>
						</div>
					 </div>
				</div>
			</div>
		</div>
	</section> <!-- end shortcodes -->
@endsection
@push('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script><!--sweetAlert-->
<script>
	$('.save').click(()=>{
		Swal.fire({
			title: "Anda yakin",
			text: "Ingin menyimpan perubahan.",
			icon: "question",
			showCancelButton: true,
			reverseButtons: true,
			confirmButtonColor: '#rgb(46 151 199)',
			// cancelButtonColor: '#rgb(155 155 155)',
			cancelButtonColor: '#rgb(155 155 155)',
			confirmButtonText: "Simpan.",
			cancelButtonText: "Batal."
		}).then((res)=>{
			if(res.isConfirmed){
				var data = new FormData($('.formSave')[0])
				$.ajax({
					url: '{{route("save_profile")}}',
					data: data,
					method: 'POST',
					cache: false,
					contentType: false,
					processData: false,
				}).done((res)=>{
					// console.log(res)
					if(res.success){
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: res.message,
							timer: 1000,
							showConfirmButton: false,
						});
						setTimeout(() => {
							window.location.reload()
						},1100);
					}else{
						Swal.fire({
							icon: 'warning',
							title: 'Whoops..',
							text: res.message,
							showConfirmButton: true,
						});
					}
				}).fail(()=>{
					Swal.fire({
						icon: 'error',
						title: 'Gagal',
						text: 'Terjadi kesalahan sistem',
						showConfirmButton: true,
					});
				})
			}
		});
	})
</script>
@endpush
