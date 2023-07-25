@extends('layout.app')

@section('title', 'Data Slider')

@push('style')
<link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
<link href="{{asset('assets/fontawesome/css/all.min.css')}}" rel="stylesheet" />
@endpush

@section('content')
<div class="card shadow">
	<div class="card-header">
		<h4 class="card-title">
			Data Slider
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
							<th>No</th>
							<th>Nama Slider</th>
							<th>Deskripsi</th>
							<th>Gambar</th>
							<th>Aksi</th>
						</tr>
					</thead>
					{{-- <tbody>
						@if(count($sliders)>0)
						@foreach($sliders as $key => $val)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$val->nama_slider}}</td>
								<td>{{$val->deskripsi}}</td>
								<td>
									@if(file_exists(public_path().'/storage/'.$val->gambar))
									<img src="/storage/{{$val->gambar}}" width="150">
									@else
									-
									@endif
								</td>

								<td>
									<a href="javascript:void(0)" data-id="{{$val->id}}" class="btn btn-warning modal-ubah" onclick="ubah({{$val->id}})">Edit</a>
									<a href="javascript:void(0)" data-id="{{$val->id}}" class="btn btn-danger btn-hapus" onclick="hapus({{$val->id}})">hapus</a>
								</td>
							</tr>
						@endforeach
						@endif
					</tbody> --}}
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-form" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
					<h5 class="modal-title">Form slider</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
			</div>
			<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<form class="form-slider">
									<div class="form-group">
										<label for="">Nama slider</label>
										<input type="hidden" name="id_slider" class="form-control" id="id_slider">
										<input type="text" class="form-control" name="nama_slider" placeholder="Nama slider" required>
									</div>
									<div class="form-group">
										<label for="">Deskripsi</label>
										<textarea name="deskripsi" placeholder="Deskripsi" class="form-control" id="" cols="30" rows="10" required></textarea>
									</div>
									<div class="form-group">
										<label for="">Gambar</label>
										<input type="file" class="form-control" name="gambar">
									</div>
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
				ajax: '{{route("slider.index")}}',
				columns: [
					{data:'DT_RowIndex', name:'DT_RowIndex'},
					{data:'nama_slider', name:'nama_slider'},
					{data:'deskripsi', name:'deskripsi'},
					{data:'gambar', name:'gambar'},
					{data:'aksi', name:'aksi'}
				],
			});
		}

		$('.btn-submit').click(()=>{
			const frmdata = new FormData($('.form-slider')[0]);
			$.ajax({
				url: '{{route("slider.store")}}',
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
						$('#id_slider').val('')
						$('input[name="nama_slider"]').val('')
						$('textarea[name="deskripsi"]').val('')
						setTimeout(() => {
							$('#dataTable').DataTable().ajax.reload()
						}, 1000);
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
					title: 'Gagal',
					text: 'Terjadi kesalahan sistem!',
					showConfirmButton: true,
				});
			})
		});

		$('.modal-tambah').click(()=>{
			$('#modal-form').modal('show')
			$('#id_slider').val('')
			$('input[name="nama_slider"]').val('')
			$('textarea[name="deskripsi"]').val('')
		});

		function ubahSlider(id){
			$('#modal-form').modal('show')
			$.post('{{route("slider.get")}}',{id:id},(res)=>{
				if(res.success){
					$('#id_slider').val(res.data.id)
					$('input[name="nama_slider"]').val(res.data.nama_slider);
					$('textarea[name="deskripsi"]').val(res.data.deskripsi);
				}
			});
		}

		function hapusSlider(id){
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
					$.post("{{route('slider.destroy')}}",{id:id}).done((res)=>{
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
	</script>
@endpush
