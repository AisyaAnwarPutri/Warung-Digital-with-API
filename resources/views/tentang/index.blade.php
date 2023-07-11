@extends('layout.app')
@section('title')
Tentang
@endsection

@section('content')
<div class="card shadow">
	<div class="card-header">
		<h4 class="card-title">
			Data tentang
		</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
				<form class="form-tentang formSave">
					<div class="form-group">
						<label for="">Judul Website</label>
						<input type="hidden" class="form-control" name="id" id="id" value="{{$about?$about->id:''}}">
						<input type="text" class="form-control" name="judul_website" placeholder="Judul Website" required value="{{$about?$about->judul_website:''}}">
					</div>
					@if($about)
						<img src="/storage/{{$about?$about->logo:''}}" alt="" width="200">
					@endif
					<div class="form-group">
						<label for="">Logo</label>
						<input type="file" class="form-control" name="logo">
					</div>
					<div class="form-group">
						<label for="">Deskripsi</label>
						<textarea name="deskripsi" placeholder="Deskripsi" class="form-control" id="" cols="30" rows="10" required>{{$about?$about->deskripsi:''}}</textarea>
					</div>
					<div class="form-group">
						<label for="">Alamat</label>
						<textarea name="alamat" placeholder="Alamat" class="form-control" id="" cols="30" rows="10" required>{{$about?$about->alamat:''}}</textarea>
					</div>
					<div class="form-group">
						<label for="">Email</label>
						<input type="text" class="form-control" name="email" placeholder="Email" required value="{{$about?$about->email:''}}">
					</div>
					<div class="form-group">
						<label for="">Telepon</label>
						<input type="text" class="form-control" name="telepon" placeholder="Telepon" required value="{{$about?$about->telepon:''}}">
					</div>
					{{-- <div class="form-group">
						<label for="">Atas Nama</label>
						<input type="text" class="form-control" name="atas_nama" placeholder="Atas Nama" required value="{{$about?$about->atas_nama:''}}">
					</div>
					<div class="form-group">
						<label for="">No Rekening</label>
						<input type="text" class="form-control" name="no_rekening" placeholder="No Rekening" required value="{{$about?$about->no_rekening:''}}">
					</div> --}}
					<div class="form-group">
						<button type="button" class="btn btn-success btn-block btn-submit">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
@push('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script><!--sweetAlert-->
<script>
	$('.btn-submit').click(()=>{
		var data = new FormData($('.formSave')[0])
		$.ajax({
			url: '{{route("tentang.store")}}',
			data: data,
			method: 'POST',
			cache: false,
			contentType: false,
			processData: false,
		}).done((res)=>{
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
	})
</script>
@endpush