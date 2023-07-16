@extends('layout.app')
@section('title', 'Data Kategori')

@push('style')
<link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
<link href="{{asset('assets/fontawesome/css/all.min.css')}}" rel="stylesheet" />

@endpush

@section('content')
<div class="card shadow">
	 <div class="card-header">
		  <h4 class="card-title">
				Data Kategori
		  </h4>
	 </div>
	 <div class="card-body">
		  <div class="d-flex justify-content-end mb-4">
				<a href="javascript:void(0)" class="btn btn-success modal-tambah">Tambah Data</a>
		  </div>
		  <div class="table-responsive">
				<table id="dataTable" class="table table-bordered table-striped text-center" style="width:100%">
					 <thead>
						  <tr>
								<th style="width:5%;">No</th>
								<th>Nama Kategori</th>
								<!-- <th>Deskripsi</th>
								<th>Gambar</th> -->
								<th>Aksi</th>
						  </tr>
					 </thead>
				</table>
		  </div>
	 </div>
</div>

<div class="modal fade" id="modal-form" tabindex="-1" role="dialog">
	 <div class="modal-dialog modal-lg" role="document">
		  <div class="modal-content">
				<div class="modal-header">
					 <h5 class="modal-title">Form Kategori</h5>
					 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
					 </button>
				</div>
				<div class="modal-body">
					 <div class="row">
						  <div class="col-md-12">
								<form class="form-kategori">
									 <div class="form-group">
										<label for="nama_kategori">Nama Kategori</label>
										<input type="hidden" class="form-control" name="id_kategori" id="id_kategori">
										<input type="text" class="form-control" name="nama_kategori" id="nama_kategori" placeholder="Nama Kategori">
									</div>
									<!-- <div class="form-group">
										<label for="deskripsi">Deskripsi</label>
										<textarea name="deskripsi" placeholder="Deskripsi" class="form-control" id="deskripsi" cols="30" rows="10"></textarea>
									</div>
									<div class="form-group">
										<label for="gambar">Gambar</label>
										<input type="file" class="form-control" name="gambar" id="gambar">
									</div> -->
									<div class="form-group">
										<button type="button" class="btn btn-success btn-block btn-submit">Submit</button>
									</div>
								</form>
						  </div>
					 </div>
				</div>
				<div class="modal-footer">
					 <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				</div>
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
		});
		function dataTable(){
			const loading = '<div class="spinner-grow text-primary" role="status"> <span class="visually-hidden"></span></div>'
			$('#dataTable').DataTable({
				scrollX: true,
				serverSide: true,
				processing: true,
				language: {
					processing: loading+' '+loading+' '+loading
				},
				ajax: '{{route("kategori.index")}}',
				columns: [
					{data:'DT_RowIndex', name:'DT_RowIndex'},
					{data:'nama', name:'nama'},
					// {data:'deskripsi', name:'deskripsi'},
					// {data:'gambar', name:'gambar'},
					// {data:'harga', name:'harga', render:function(data,type,row){
					// 	return data ? formatRupiah(data,'Rp. ') : '-'
					// }},
					{data:'aksi', name:'aksi'}
				],
			});
		}
		
		$('.modal-tambah').click(function() {
			$('#modal-form').modal('show')
			emptyForm()
		});
		function emptyForm(){
			$('#id_kategori').val('')
			$('input[name="nama_kategori"]').val('')
			// $('textarea[name="deskripsi"]').val('')
			// $('#gambar').val('')
		}

		$('.btn-submit').click(()=>{
			const frmdata = new FormData($('.form-kategori')[0]);
			$.ajax({
				url: '{{route("kategori.store")}}',
				type: 'POST',
				data: frmdata,
				cache: false,
				contentType: false,
				processData: false,
				success: (res)=>{
					if(res.success){
						$('#modal-form').modal('hide')
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: res.message,
							timer: 1000,
							showConfirmButton: false,
						});
						emptyForm()
						$('#dataTable').DataTable().ajax.reload()
					}else{
						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							text: res.message,
							showConfirmButton: true,
						});
					}
				}
			}).fail(()=>{
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Terjadi kesalahan sistem',
					showConfirmButton: true,
				});
			})
		});

		function editKategori(id){
			$('#modal-form').modal('show')
			$.post('{{route("kategori.get")}}',{id:id},(res)=>{
				if(res.success){
					$('#id_kategori').val(res.data.id)
					$('input[name="nama_kategori"]').val(res.data.nama_kategori);
					// $('textarea[name="deskripsi"]').val(res.data.deskripsi);
				}
			});
		}
		function hapusKategori(id){
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
					$.post("{{route('kategori.destroy')}}",{id:id}).done((res)=>{
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
			// confirm_dialog = confirm("Anda yakin?\ndata yang sudah dihapus tidak bisa dikembalikan");
			// if (confirm_dialog) {
			// 	$.ajax({
			// 		url: '{{route("slider.destroy")}}',
			// 		data: {id:id},
			// 		method: 'POST',
			// 		success: function(res) {
			// 			alert(res.message)
			// 			if(res.success){
			// 				window.location.reload()
			// 			}
			// 		}
			// 	});
			// }
		}
	</script>
@endpush
