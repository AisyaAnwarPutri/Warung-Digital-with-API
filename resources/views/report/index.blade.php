@extends('layout.app')

@section('title', 'Laporan Pesanan')

@push('style')
<link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
<link href="{{asset('assets/fontawesome/css/all.min.css')}}" rel="stylesheet" />
@endpush
@section('content')
<div class="card shadow">
	 <div class="card-header">
		  <h4 class="card-title">
			<div class="row">
				 <div class="col-md-5">
					 <div class="row">
						<div class="col-md-3 text-right mt-1" style="width: 100%;">
							<span style="font-size:15px;">Tanggal awal</span>
						</div>
						<div class="col-md-8">
							<input type="date" name="tanggalAwal" id="tanggalAwal" class="form-control">
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="row">
						<div class="col-md-3 text-right mt-1" style="width: 100%;">
							<span style="font-size:15px;">Tanggal akhir</span>
						</div>
						<div class="col-md-8">
							<input type="date" name="tanggalAkhir" id="tanggalAkhir" class="form-control">
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<button type="button" class="btn btn-success btn-block cari">Cari</button>
				</div>
			</div>
		</h4>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table id="dataTable" class="text-center table table-bordered table-hover table-striped" style="width:100%">
				<thead>
					<tr>
						<th>No</th>
						<th>Nama Produk</th>
						<th>Harga</th>
						<th>Jumlah Dibeli</th>
						{{-- <th>Total Qty</th> --}}
						<th>Pendapatan</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

@endsection


@push('js')
<script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/fontawesome/js/all.min.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script><!--sweetAlert-->
<script>
	$(document).ready(()=>{
		dataTable()
	});
	function dataTable(tanggalAwal='',tanggalAkhir=''){
		const loading = '<div class="spinner-grow text-primary" role="status"> <span class="visually-hidden"></span></div>'
		$('#dataTable').DataTable({
			scrollX: true,
			// lengthChange: true,
			destroy: true,
			serverSide: true,
			processing: true,
			language: {
				processing: loading+' '+loading+' '+loading
			},
			ajax: {
				url: '{{route("laporan")}}',
				data: {
					tanggalAwal: tanggalAwal,
					tanggalAkhir: tanggalAkhir
				}
			},
			columns: [
				{data:'DT_RowIndex', name:'DT_RowIndex'},
				{data:'nama_produk', name:'nama_produk'},
				{data:'harga', name:'harga'},
				{data:'total_qty', name:'total_qty'},
				// {data:'grand_total', name:'grand_total', render:function(data,type,row){
				// 	return '<p class="text-center">'+formatRupiah(data,'Rp. ')+'</p>'
				// }},
				{data:'pendapatan', name:'pendapatan', render:(data,type,row)=>{
					return '<p class="text-center">'+formatRupiah(data,'Rp. ')+'</p>'
				}}
			],
		});
	}

	$('.cari').click(()=>{
		var tanggalAwal = $('#tanggalAwal').val()
		var tanggalAkhir = $('#tanggalAkhir').val()
		if(!tanggalAwal){
			Swal.fire({
				icon: 'warning',
				title: 'Whoops',
				text: 'Tanggal awal harus diisi!',
				showConfirmButton: true,
			});
		}else if(!tanggalAkhir){
			Swal.fire({
				icon: 'warning',
				title: 'Whoops',
				text: 'tanggal akhir harus diisi',
				showConfirmButton: true,
			});
		}else if(tanggalAwal>tanggalAkhir){
			Swal.fire({
				icon: 'warning',
				title: 'Whoops',
				text: 'Tanggal awal harus lebih kecil dari tanggal akhir!',
				showConfirmButton: true,
			});
		}else{
			dataTable(tanggalAwal,tanggalAkhir)
		}
	});
	
	function formatRupiah(angka, prefix) {
		var number_string = angka.toString().replace(/[^,\d]/g, '')
		// var number_string = angka.replace(/[^,\d]/g, "").toString()
		split = number_string.split(',')
		sisa = split[0].length % 3
		rupiah = split[0].substr(0, sisa)
		ribuan = split[0].substr(sisa).match(/\d{3}/gi)

		// tambahkan titik jika yang di input sudah menjadi angka ribuan
		if (ribuan) {
			separator = sisa ? '.' : ''
			rupiah += separator + ribuan.join('.')
		}
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah
		return prefix == undefined ? rupiah : rupiah ? 'Rp. ' + rupiah : ''
	}
</script>
@endpush
