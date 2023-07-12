@extends('layout.app')
@section('title', 'Riwayat Stok')

@push('style')
	<style>
		/*customButtonEditProdukStart*/
		/* .editProduk{
			background-color: #8186B4;
			border-color: #8186B4;
			color: white;
		}
		.editProduk:hover {
			color: white;
			background-color: #696D96;
			border-color: #696D96;
		}
		.editProduk:active{
			color: white;
			background-color: #696D96;
			border-color: #696D96;
		}
		.editProduk:focus {
			color: white;
			background-color: #696D96;
			border-color: #696D96;
			box-shadow: none;
		}
		.editProduk:active:focus{
			box-shadow: 0 0 0 0.25rem rgb(129 134 180 / 52%);
		} */
		/*customButtonEditProdukEnd*/
	</style>
	<link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
	<link href="{{asset('assets/fontawesome/css/all.min.css')}}" rel="stylesheet" />
	<!--select2Start-->
	<link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
	<link href="{{asset('assets/plugins/select2/css/select2-bootstrap4.css')}}" rel="stylesheet">
	<!--select2End-->
	<!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/zoom/css/jquery.pan.css')}}"> -->
   <!--zoomImage-->
@endpush

@section('content')
	<div class="page-content">
		<div id="mainLayer">
			<div class="card">
				<!-- <div class="card-header">
					<div class="col-12">
						<button type="button" class="btn btn-primary px-3 btnAdd">
							<i class="fadeIn animated bx bx-plus"></i>Tambah Baru
						</button>
					</div>
				</div> -->
				<div class="card-body">
					<div class="table-responsive">
						<table id="dataTable" class="text-center table table-striped table-bordered" style="width:100%">
							<thead>
								<tr>
									<th>No</th>
									<th>User</th>
									<th>Nama Produk</th>
									<th>Stok Awal</th>
									<th>Stok Update</th>
									<th>Sisa Stok</th>
									<th>Tanggal</th>
								</tr>	
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="otherPage"></div>
	</div>
@endsection

@push('js')
   <!--zoomImage-->
	<!-- <script src="{{asset('assets/zoom/js/jquery.pan.js')}}"></script> -->
	<script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
	<script src="{{asset('assets/fontawesome/js/all.min.js')}}"></script>
	<!-- <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script> -->
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script><!--sweetAlert-->

	<script>
		$(document).ready(()=>{
			dataTable()
		});
		function dataTable(){
			const loading = '<div class="spinner-grow text-primary" role="status"> <span class="visually-hidden"></span></div>'
			$('#dataTable').DataTable({
				scrollX: true,
				// lengthChange: true,
				serverSide: true,
				processing: true,
				language: {
					processing: loading+' '+loading+' '+loading
				},
				ajax: '{{route("riwayat_stok.index")}}',
				columns: [
					{data:'DT_RowIndex', name:'DT_RowIndex'},
					{data:'user', name:'user'},
					{data:'produk', name:'produk'},
					{data:'stok_awal', name:'stok_awal'},
					{data:'stok_update', name:'stok_update'},
					{data:'stok_terbaru', name:'stok_terbaru'},
					{data:'tanggal', name:'tanggal'},
					// {data:'aksi', name:'aksi'}
				],
			});
		}
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
