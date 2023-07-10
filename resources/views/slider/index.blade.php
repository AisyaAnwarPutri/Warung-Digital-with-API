@extends('layout.app')

@section('title', 'Data Slider')

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
			<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Slider</th>
							<th>Deskripsi</th>
							<th>Gambar</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
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
					</tbody>
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
<script>
	// function loadTable(){
	// 	$.ajax({
	// 		url: '{{route("slider.list")}}',
	// 		method: 'POST',
	// 		success: (res)=>{
	// 			let row;
	// 			res.data.map(function(val, index) {
	// 				row += `
	// 					<tr>
	// 						<td>${index+1}</td>
	// 						<td>${val.nama_slider}</td>
	// 						<td>${val.deskripsi}</td>
	// 						<td><img src="/storage/${val.gambar}" width="150"></td>
	// 						<td>
	// 							<a href="#modal-form" data-id="${val.id}" class="btn btn-warning modal-ubah">Edit</a>
	// 							<a href="#" data-id="${val.id}" class="btn btn-danger btn-hapus">hapus</a>
	// 						</td>
	// 					</tr>
	// 				`;
	// 			});
	// 			$('tbody').empty()
	// 			$('tbody').append(row)
	// 		}
	// 	});
	// }

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
					window.location.reload()
				}
				alert(res.message)
			}
		}).fail(()=>{
			alert('Terjadi kesalahan sistem')
		})
	});

	// $(document).on('click', '.btn-hapus', function() {
	// 	const id = $(this).data('id')
	// 	const token = localStorage.getItem('token')

	// 	confirm_dialog = confirm('Apakah anda yakin?');

	// 	if (confirm_dialog) {
	// 			$.ajax({
	// 				url: '/api/sliders/' + id,
	// 				type: "DELETE",
	// 				headers: {
	// 					"Authorization":"Bearer "+token
	// 				},
	// 				success: function(data) {
	// 					if (data.message == 'success') {
	// 							alert('Data berhasil dihapus')
	// 							location.reload()
	// 					}
	// 				}
	// 			});
	// 	}


	// });

	$('.modal-tambah').click(()=>{
		$('#modal-form').modal('show')
		$('input[name="nama_slider"]').val('')
		$('textarea[name="deskripsi"]').val('')
		// $('.form-slider').submit(function(e) {
		// 	//  e.preventDefault()
		// 	//  const token = localStorage.getItem('token')
		// 	//  const frmdata = new FormData(this);
		// 	$.ajax({
		// 		url: '{{route("slider.store")}}',
		// 		type: 'POST',
		// 		data: frmdata,
		// 		cache: false,
		// 		contentType: false,
		// 		processData: false,
		// 		// headers: {
		// 		// 	"Authorization":"Bearer "+token
		// 		// },
		// 		success: function(res) {
		// 			console.log(res)
		// 			// if (data.success) {
		// 			// 		// alert('Data berhasil ditambah')
		// 			// 		// location.reload();
		// 			// }
		// 		}
		// 	})
		// });
	});

	function ubah(id){
		$('#modal-form').modal('show')
		$.post('{{route("slider.get")}}',{id:id},(res)=>{
			if(res.success){
				$('#id_slider').val(res.data.id)
				$('input[name="nama_slider"]').val(res.data.nama_slider);
				$('textarea[name="deskripsi"]').val(res.data.deskripsi);
			}
		});
	}

	function hapus(id){
		confirm_dialog = confirm("Anda yakin?\ndata yang sudah dihapus tidak bisa dikembalikan");
		if (confirm_dialog) {
			$.ajax({
				url: '{{route("slider.destroy")}}',
				data: {id:id},
				method: 'POST',
				success: function(res) {
					alert(res.message)
					if(res.success){
						window.location.reload()
					}
				}
			});
		}
	}

	// $('.modal-ubah').click(()=>{
	// 	const id = $(this).data('id');
	// 	$.get('sliders/' + id,(res)=>{
	// 		$('input[name="nama_slider"]').val(data.nama_slider);
	// 		$('textarea[name="deskripsi"]').val(data.deskripsi);
	// 	});
	// })

	// $(document).on('click', '.modal-ubah', function() {
	// 	$('#modal-form').modal('show')
	// 	const id = $(this).data('id');

	// 	$.get('sliders/' + id,(res)=>{
	// 		$('input[name="nama_slider"]').val(data.nama_slider);
	// 		$('textarea[name="deskripsi"]').val(data.deskripsi);
	// 	});

	// 	// $('.form-slider').submit(function(e) {
	// 	// 	e.preventDefault()
	// 	// 	const token = localStorage.getItem('token')
	// 	// 	const frmdata = new FormData(this);

	// 	// 	$.ajax({
	// 	// 		url: `api/sliders/${id}?_method=PUT`,
	// 	// 		type: 'POST',
	// 	// 		data: frmdata,
	// 	// 		cache: false,
	// 	// 		contentType: false,
	// 	// 		processData: false,
	// 	// 		headers: {
	// 	// 			"Authorization":"Bearer "+token
	// 	// 		},
	// 	// 		success: function(data) {
	// 	// 			if (data.success) {
	// 	// 					alert('Data berhasil diubah')
	// 	// 					location.reload();
	// 	// 			}
	// 	// 		}
	// 	// 	})
	// 	// });

	// });
</script>
@endpush
