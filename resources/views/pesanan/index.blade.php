@extends('layout.app')
@section('title', 'Data Pesanan Baru')

@push('style')
<link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
<link href="{{asset('assets/fontawesome/css/all.min.css')}}" rel="stylesheet" />
@endpush
@section('content')
<div class="card shadow">
	<div class="card-header">
		<h4 class="card-title">
			Data Pesanan Baru
		</h4>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table id="dataTable" class="text-center table table-bordered table-hover table-striped" style="width:100%">
				<thead class="">
					<tr>
						<th>No</th>
						<th>Tanggal Pesanan</th>
						<th>Invoice</th>
						<th>Member</th>
						<th>Total</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody></tbody>
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
			ajax: {
				url: '{{route("pesanan.baru")}}',
				// type: 'GET',
			},
			columns: [
				{data:'DT_RowIndex', name:'DT_RowIndex'},
				{data:'tanggal', name:'tanggal'},
				{data:'invoice', name:'invoice'},
				{data:'member', name:'member'},
				{data:'grand_total', name:'grand_total', render:function(data,type,row){
					return '<p class="text-center">'+formatRupiah(data,'Rp. ')+'</p>'
				}},
				{data:'aksi', name:'aksi'}
			],
		});
	}
	
	function konfirmasi(id){
		Swal.fire({
			title: "Anda yakin?",
			text: "Ingin mengkonfirmasi pesanan ini!",
			icon: "info",
			showCancelButton: true,
			reverseButtons: true,
			confirmButtonColor: '#rgb(71 122 204)',
			cancelButtonColor: '#rgb(155 155 155)',
			confirmButtonText: "Ya.",
			cancelButtonText: "Batal."
		}).then((res)=>{
			if(res.isConfirmed){
				// $.post("{{route('produk.destroy_produk')}}",{id:id}).done((res)=>{
				// 	if(res.success){
				// 		Swal.fire({
				// 			icon: 'success',
				// 			title: 'Berhasil',
				// 			text: res.message,
				// 			timer: 1000,
				// 			showConfirmButton: false,
				// 		});
				// 		$('#dataTable').DataTable().ajax.reload()
				// 	}else{
				// 		Swal.fire({
				// 			icon: 'error',
				// 			title: 'Gagal',
				// 			text: res.message,
				// 			showConfirmButton: true,
				// 		});
				// 	}
				// });
			}
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

	// $(function() {
	// 	function rupiah(angka){
	// 		const format = angka.toString().split('').reverse().join('');
	// 		const convert = format.match(/\d{1,3}/g);
	// 		return 'Rp ' + convert.join('.').split('').reverse().join('')
	// 	}
	// 	function date(date) {
	// 		var date = new Date(date);
	// 		var day = date.getDate(); //Date of the month: 2 in our example
	// 		var month = date.getMonth(); //Month of the Year: 0-based index, so 1 in our example
	// 		var year = date.getFullYear()
	// 		return `${day}-${month}-${year}`;
	// 	}

	// 	const token = localStorage.getItem('token')
	// 	$.ajax({
	// 		url: '/api/pesanan/baru',
	// 		headers: {
	// 			"Authorization": 'Bearer ' + token
	// 		},
	// 		success: (data)=>{
	// 			let row;
	// 			data.map(function(val, index) {
	// 				row += `
	// 					<tr>
	// 						<td>${index+1}</td>
	// 						<td>${date(val.created_at)}</td>
	// 						<td>${val.invoice}</td>
	// 						<td>${val.member.nama_member}</td>
	// 						<td>${rupiah(val.grand_total)}</td>
	// 						<td>
	// 							<a href="#" data-id="${val.id}" class="btn btn-success btn-aksi">Konfirmasi</a>
	// 						</td>
	// 					</tr>
	// 					`;
	// 			});
	// 			$('tbody').append(row)
	// 		}
	// 	});

	// 	$(document).on('click','.btn-aksi',function(){
	// 		const id = $(this).data('id')
	// 		$.ajax({
	// 			url : '/api/pesanan/ubah_status/' + id,
	// 			type : 'POST',
	// 			data : {
	// 				status : 'Dikonfirmasi'
	// 			},
	// 			headers: {
	// 				"Authorization": 'Bearer ' + token
	// 			},
	// 			success : function(data){
	// 				location.reload()
	// 			}
	// 		})
	// 	})
	// });
</script>
@endpush
