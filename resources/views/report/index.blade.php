@extends('layout.app')

@section('title', 'Laporan Penjualan')

@push('style')
<link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
<link href="{{asset('assets/fontawesome/css/all.min.css')}}" rel="stylesheet" />
<!--select2Start-->
<link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/plugins/select2/css/select2-bootstrap4.css')}}" rel="stylesheet">
<!--select2End-->
<style>
	.fwhite{
		color: white;
	}
	.btn-x{
		background: transparent !important;
		border: 0;
		opacity: .5;
	}
	.modal-header{
		background-color: #3caa6a!important;
	}
	button:focus{
		outline: unset !important;
	}
</style>
@endpush
@section('content')
<div class="page-content">
	<div id="mainLayer">
		<div class="card shadow">
			 <div class="card-header">
				  <h4 class="card-title">
					<div class="row">
						<div class="col-md-4">
							<span style="font-size:15px;">Tanggal awal</span>
							<input type="date" name="tanggalAwal" id="tanggalAwal" class="form-control">
						</div>
						<div class="col-md-4">
							<span style="font-size:15px;">Tanggal akhir</span>
							<input type="date" name="tanggalAkhir" id="tanggalAkhir" class="form-control">
						</div>
						<div class="col-md-4">
							<div class="row">
								<div class="col-md-6">
									<span style="font-size:15px;">&nbsp;</span>
									<button type="button" class="btn btn-success cari" style="display: block;"><i class="fas fa-search"></i></button>
								</div>
								<div class="col-md-6">
									<span style="font-size:15px;">&nbsp;</span>
									<button type="button" class="btn btn-success btn-block btn-tambah"><i class="fa-solid fa-plus"></i>Buat Laporan</button>
								</div>
							</div>
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
								{{-- <th>Nama Produk</th>
								<th>Harga</th>
								<th>Jumlah Dibeli</th>
								<th>Pendapatan</th> --}}
								<th>Invoice</th>
								<th>Tanggal</th>
								<th>Pendapatan</th>
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

<div class="modal" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title fwhite">Detail Laporan</h5>
				<button type="button" class="close-modal btn-x">
					<i class="far fa-times-circle fwhite" style="font-size: 25px;"></i>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Nama Produk</th>
							<th>Harga</th>
							<th>Qty</th>
							<th>Total Harga</th>
						</tr>
					</thead>
					<tbody class="detailData">
					</tbody>
				</table>
			</div>
			{{-- <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div> --}}
		</div>
	</div>
</div>
@endsection


@push('js')
<script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/fontawesome/js/all.min.js')}}"></script>
<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script><!--sweetAlert-->
<script>
	$(document).ready(()=>{
		dataTable()
		$('.close-modal').click(()=>{
			$('.modal').fadeOut('slow')
		})
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
				url: '{{route("laporan.index")}}',
				data: {
					tanggalAwal: tanggalAwal,
					tanggalAkhir: tanggalAkhir
				}
			},
			columns: [
				{data:'DT_RowIndex', name:'DT_RowIndex'},
				// {data:'nama_produk', name:'nama_produk'},
				// {data:'harga', name:'harga'},
				// {data:'total_qty', name:'total_qty'},
				// // {data:'grand_total', name:'grand_total', render:function(data,type,row){
				// // 	return '<p class="text-center">'+formatRupiah(data,'Rp. ')+'</p>'
				// // }},
				// {data:'pendapatan', name:'pendapatan', render:(data,type,row)=>{
				// 	return '<p class="text-center">'+formatRupiah(data,'Rp. ')+'</p>'
				// }}
				{data:'invoice', name:'invoice'},
				{data:'tanggal', name:'tanggal'},
				{data:'pendapatan', name:'pendapatan'},
				{data:'aksi', name:'aksi'},
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

	$('.btn-tambah').click(async ()=>{
		$('#mainLayer').hide()
		try{
			await $.post('{{route("laporan.form")}}').done(async (res)=>{
				await $('#otherPage').html(res.data).fadeIn()
			})
			.fail(async (e)=>{
				await Swal.fire({
					icon: 'error',
					title: 'ERROR!',
					text: 'Terjadi kesalahan sistem.',
					showConfirmButton: true,
				})
				hideForm()
			})
		}catch(error){
			await Swal.fire({
				icon: 'error',
				title: 'ERROR!',
				text: 'Terjadi kesalahan sistem!',
				showConfirmButton: true,
			})
			hideForm()
		}
	})
	async function detailOrder(id){
		try{
			await $.post('{{route("laporan.detail")}}',{id:id}).done(async (data, statusString, xhr)=>{
            const code = xhr.status
            if(code===204){
               await Swal.fire({
                  icon: 'warning',
                  title: 'Whoops..',
                  text: 'Data tidak ditemukan.',
                  showConfirmButton: true,
               })
               return
            }
            var html = ''
            await $('.detailData').empty()
            await $.each(data.data.order_detail,async (index,val)=>{
               html += `
                  <tr>
                     <td>${val.product.nama_produk.toUpperCase()}</td>
                     <td>${formatRupiah(val.product.harga,'Rp. ')}</td>
                     <td>${val.jumlah}</td>
                     <td>${formatRupiah(val.harga,'Rp. ')}</td>
                  </tr>
               `
            })
            await $('.detailData').html(html)
            await $('.modal').fadeIn('slow')
			})
		}catch(error){
			await Swal.fire({
				icon: 'error',
				title: 'ERROR!',
				text: 'Terjadi kesalahan sistem!',
				showConfirmButton: true,
			})
		}
	}
	async function editOrder(id){
		$('#mainLayer').hide()
		try{
			await $.post('{{route("laporan.form")}}',{id:id}).done(async (res)=>{
				await $('#otherPage').html(res.data).fadeIn()
			})
			.fail(async (e)=>{
				await Swal.fire({
					icon: 'error',
					title: 'ERROR!',
					text: 'Terjadi kesalahan sistem.',
					showConfirmButton: true,
				})
				hideForm()
			})
		}catch(error){
			await Swal.fire({
				icon: 'error',
				title: 'ERROR!',
				text: 'Terjadi kesalahan sistem!',
				showConfirmButton: true,
			})
			hideForm()
		}
	}
	function hapusOrder(id){
		alert('pengembangan')
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
