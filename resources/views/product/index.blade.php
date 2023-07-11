@extends('layout.app')
@section('title', 'Data produk')

@push('style')
	<style>
		/*customButtonEditProdukStart*/
		.editProduk{
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
		}
		/*customButtonEditProdukEnd*/
	</style>
	<link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
	<link href="{{asset('assets/fontawesome/css/all.min.css')}}" rel="stylesheet" />
	<!--select2Start-->
	<link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
	<link href="{{asset('assets/plugins/select2/css/select2-bootstrap4.css')}}" rel="stylesheet">
	<!--select2End-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/zoom/css/jquery.pan.css')}}"><!--zoomImage-->
@endpush

@section('content')
	<div class="page-content">
		<div id="mainLayer">
			<div class="card">
				<div class="card-header">
					<div class="col-12">
						<button type="button" class="btn btn-primary px-3 btnAdd">
							<i class="fadeIn animated bx bx-plus"></i>Tambah Baru
						</button>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="dataTable" class="table table-striped table-bordered" style="width:100%">
							<thead class="text-center">
								<tr>
									<th>No</th>
									<th>Kategori</th>
									<th>Stok</th>
									<th>Nama produk</th>
									<th>Harga</th>
									<th>Tags</th>
									<th>Gambar</th>
									<th>Aksi</th>
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
	<script src="{{asset('assets/zoom/js/jquery.pan.js')}}"></script><!--zoomImage-->
	<script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
	<script src="{{asset('assets/fontawesome/js/all.min.js')}}"></script>
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
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
				ajax: '{{route("produk.index")}}',
				columns: [
					{data:'DT_RowIndex', name:'DT_RowIndex'},
					{data:'kategori', name:'kategori'},
					{data:'stok', name:'stok'},
					{data:'nama_produk', name:'nama_produk'},
					{data:'harga', name:'harga', render:function(data,type,row){
						return data ? formatRupiah(data,'Rp. ') : '-'
					}},
					{data:'tags', name:'tags'},
					{data:'gambar', name:'gambar'},
					{data:'aksi', name:'aksi'}
				],
			});
		}

		$('.btnAdd').click(()=>{
			$('#mainLayer').hide()
			$.post('{{route("produk.form_produk")}}').done((res)=>{
				if(res.success){
					$('#otherPage').html(res.data).fadeIn()
				}else{
					hideForm()
				}
			}).fail(()=>{
				hideForm()
			})
		})
		function editProduk(id){
			$('#mainLayer').hide()
			$.post('{{route("produk.form_produk")}}',{id:id}).done((res)=>{
				if(res.success){
					$('#otherPage').html(res.data).fadeIn()
				}else{
					hideForm()
				}
			}).fail(()=>{
				hideForm()
			})
		}
		function hapusProduk(id){
			Swal.fire({
				title: "Anda Yakin?",
				text: "Data Akan Dihapus Dari Sistem!",
				icon: "warning",
				showCancelButton: true,
				reverseButtons: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#rgb(155 155 155)',
				confirmButtonText: "Ya, Hapus!",
				cancelButtonText: "Batal."
			}).then((res)=>{
				if(res.isConfirmed){
					$.post("{{route('produk.destroy_produk')}}",{id:id}).done((res)=>{
						if(res.success){
							Swal.fire({
								icon: 'success',
								title: 'Berhasil',
								text: res.message,
								timer: 1000,
								showConfirmButton: false,
							});
							$('#dataTable').DataTable().ajax.reload()
						}else{
							Swal.fire({
								icon: 'error',
								title: 'Gagal',
								text: res.message,
								showConfirmButton: true,
							});
						}
					});
				}
			});
		}


		function hideForm(){
			$('#otherPage').empty()
			$('#mainLayer').show()
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
