<style type="text/css">
	.form-check-input:checked {
		background-color: red;
		border-color: pink;
	}
	.form-check-input:focus{
		box-shadow: none;
	}
	.form-check-input:active{
		filter: brightness(100%);
	}
</style>

@php
	function rupiah($angka){
		$hasil_rupiah = "Rp. " . number_format((int)$angka);
		$hasil_rupiah = str_replace(',', '.', $hasil_rupiah);
		return $hasil_rupiah;
	}
@endphp

<div class="card border-top border-0 border-4 border-primary">
	<div class="card-body p-3">
		<div class="card-title d-flex align-items-center">
			<h5 class="mb-0">{{$page}} Produk</h5>
		</div>
		<hr>
		<form class="g-3 formSave">
			<div class="row mb-2">
				<div class="col-md-12">
					<label class="form-label">Kategori <span class="text-danger">*)</span></label>
					<input type="hidden" class="form-control" name="id_produk" id="id_produk" value="{{$product?$product->id:''}}">
					<select class="single-select" name="id_kategori" id="id_kategori">
						<!-- <option value="first" selected disabled>--Pilih Kategori--</option> -->
					</select>
				</div>
				<!-- <div class="col-md-6">
					<label class="form-label">Sub Kategori <span class="text-danger">*)</span></label>
					<select class="single-select" name="id_subkategori" id="id_subkategori">
						<option value="first" selected disabled>--Pilih Sub Kategori--</option>
					</select>
				</div> -->
			</div>
			<div class="row mb-2">
				<div class="col-md-4">
					<label class="form-label">Nama Produk <span class="text-danger">*)</span></label>
					<input type="text" class="form-control datepicker" name="nama_produk" id="nama_produk" placeholder="Nama Produk" value="{{$product?$product->nama_produk:''}}">
				</div>
				<div class="col-md-4">
					<label class="form-label">Harga <span class="text-danger">*)</span></label>
					<input type="text" class="form-control datepicker" name="harga" id="harga" onkeyup="ubahFormat(this)" placeholder="Harga" value="{{$product?rupiah($product->harga):''}}">
				</div>
				<div class="col-md-4">
					<label class="form-label">Tags</label>
					<input type="text" autocomplete="off" class="form-control" name="tags" id="tags" placeholder="Tags" value="{{$product?$product->tags:''}}">
					<div id="errTags"></div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-12">
					<label for="deskripsi" class="form-label">Deskripsi Produk <span class="text-danger">*)</span></label>
					<textarea class="form-control" name="deskripsi" id="deskripsi" placeholder="Deskripsi..." rows="5">{{$product?$product->deskripsi:''}}</textarea>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-6">
					<label for="gambar" class="form-label">Upload Gambar @if(!$product)<span class="text-danger">*)</span></label>@endif
					<input type="file" id="gambar" class="form-control" name="gambar" onchange="loadFile(event)">
				</div>
				<div class="col-6">
					<div class="row mt-2">
						<div class="col-2"></div>
						<div class="col-8">
							@if($product && $product->gambar && file_exists(public_path().'/storage/'.$product->gambar))
							<a class="pan" id="btnOutPut" @if(!empty($product->gambar)) data-big="{{asset('storage/'.$product->gambar)}}" @endif>
								<img id="outPut" class="rounded mx-auto d-block responsive @if(!empty($product->gambar)) img-thumbnail" src="{{asset('storage/'.$product->gambar)}}" @else " @endif >
							</a>
							@else
							<a class="pan" id="btnOutPut">
								<img id="outPut" class="rounded mx-auto d-block responsive">
							</a>
							@endif
						</div>
						<div class="col-2"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="d-grid gap-2 d-md-block">
						<button class="btn btn-secondary btnKembali" type="button">KEMBALI</button>
						<button class="btn btn-primary btnSimpan" type="button">SIMPAN</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>


<script>
	$(document).ready(()=>{
		// $('#penanggungJawab').keypress(function(e){
		// 	var penanggungJawab = $('#penanggungJawab').val()
		// 	var res = penanggungJawab.toString().replace(/[^,\d]/g, "")
		// 	$('#penanggungJawab').val(res)
		// 	if(e.which!=8 && isNaN(String.fromCharCode(e.which))){
		// 		e.preventDefault()
		// 		$('#errPenanggungJawab').html('Hanya angka').stop().show().fadeOut('slow')
		// 		return false;
		// 	}
		// })
		// $('#jumlahOrang').keypress(function(e){
		// 	var jumlahOrang = $('#jumlahOrang').val()
		// 	var res = jumlahOrang.toString().replace(/[^,\d]/g, "")
		// 	$('#jumlahOrang').val(res)
		// 	if(e.which!=8 && isNaN(String.fromCharCode(e.which))){
		// 		e.preventDefault()
		// 		$('#errJumlahOrang').html('Hanya angka').stop().show().fadeOut('slow')
		// 		return false;
		// 	}
		// })
	});
	$(document).on('select2:open',()=>{
		document.querySelector('.select2-search__field').focus();
	});

	var status = '{{$page}}'
	$(()=>{
		$('.pan').pan()
		if(status=='Tambah'){
			setKategori()
			// setSubKategori()
		}else{
			var data = JSON.parse('{!!$product!!}')
			if(data.category===null){
				setKategori()
			}else{
				setKategori(data.category.id)
			}
			// if(data.subcategory===null){
			// 	setSubKategori()
			// }else{
			// 	setSubKategori(data.subcategory.id)
			// }
		}
	})
	var loadFile = function(event){
		// var btn = $('#btnOutPut') // jQuery Object
		// var btn = document.getElementById('btnOutPut') // html DOM Object
		var btn = $('#btnOutPut')[0] // html DOM Object
		var outPut = $('#outPut')[0]
		outPut.src = URL.createObjectURL(event.target.files[0])
		outPut.onload = function(){
			URL.revokeObjectURL(outPut.src)
		}
		btn = $('#btnOutPut').attr('data-big',URL.createObjectURL(event.target.files[0]))
		$('#outPut').addClass('img-thumbnail')
	}

	function setKategori(param=''){
		$.post('{{route("kategori.list")}}').done((res)=>{
			if(res.success){
				var html = '<option value="first" selected disabled>--Pilih Kategori--</option>'
				$.each(res.data,(i,val)=>{
					var selected = ''
					if(val.id==param){
						selected = 'selected'
					}
					html += `<option value="${val.id}" ${selected}>${val.nama_kategori}</option>`
				})
				$('#id_kategori').html(html)
				// $.each(res.data,(i,val)=>{
					// 	res.data[i].text = res.data[i].nama
					// })
					// $('#provinsi').select2({
					// 	theme: 'bootstrap4',
					// 	width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
					// 	placeholder: $(this).data('placeholder'),
					// 	allowClear: Boolean($(this).data('allow-clear')),
					// 	data: res.data
					// 	// [
					// 	// {id:0,text:"enhancement"},
					// 	// ],
					// })
			}
		})
	}
	// function setSubKategori(param=''){
	// 	$.post('{{route("sub_kategori.list")}}').done((res)=>{
	// 		if(res.success){
	// 			var html = '<option value="first" selected disabled>--Pilih Sub Kategori--</option>'
	// 			$.each(res.data,(i,val)=>{
	// 				var selected = ''
	// 				if(val.id==param){
	// 					selected = 'selected'
	// 				}
	// 				html += `<option value="${val.id}" ${selected}>${val.nama_subkategori}</option>`
	// 			})
	// 			$('#id_subkategori').html(html)
	// 			// $.each(res.data,(i,val)=>{
	// 				// 	res.data[i].text = res.data[i].nama
	// 				// })
	// 				// $('#provinsi').select2({
	// 				// 	theme: 'bootstrap4',
	// 				// 	width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
	// 				// 	placeholder: $(this).data('placeholder'),
	// 				// 	allowClear: Boolean($(this).data('allow-clear')),
	// 				// 	data: res.data
	// 				// 	// [
	// 				// 	// {id:0,text:"enhancement"},
	// 				// 	// ],
	// 				// })
	// 		}
	// 	})
	// }

	$('.single-select').select2({
		theme: 'bootstrap4',
		width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
		placeholder: $(this).data('placeholder'),
		allowClear: Boolean($(this).data('allow-clear')),
		// data: {id:'' , text:''}
	})

	$('.btnSimpan').click(()=>{
		var data = new FormData($('.formSave')[0])
		$('.btnSimpan').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
			url: '{{route("produk.save_produk")}}',
			type: 'POST',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false,
			success: function(res){
				if(res.code==201 || res.code==200){
					Swal.fire({
						icon: 'success',
						title: 'Berhasil',
						text: res.message,
						showConfirmButton: false,
						timer: 1100
					})
					setTimeout(()=>{
						$('#otherPage').fadeOut(()=>{
							$('#dataTable').DataTable().ajax.reload()
							hideForm()
						})
					}, 1000);
				}else{
					Swal.fire({
						icon: 'warning',
						title: 'Whoops',
						text: res.message,
						showConfirmButton: false,
						timer: 1300,
					})
				}
				$('.btnSimpan').attr('disabled',false).html('SIMPAN')
			}
		}).fail(()=>{
			Swal.fire({
				icon: 'error',
				title: 'Whoops..',
				text: 'Terjadi kesalahan silahkan ulangi kembali',
				showConfirmButton: false,
				timer: 1300,
			})
			$('.btnSimpan').attr('disabled',false).html('SIMPAN')
		})
	})

	$('.btnKembali').click(()=>{
		$('#otherPage').fadeOut(function(){
			hideForm()
		})
	})


	function ubahFormat(val){
		$('#harga').val(formatRupiah(val.value,'Rp. '))
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