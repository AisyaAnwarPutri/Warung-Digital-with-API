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
	#errStok{
		margin-top:4px;
		background: #ff5757;
		color:#fff;
		padding:4px;
		display:none;
		width: 250p
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
			<h5 class="mb-0">{{$page}} Laporan</h5>
		</div>
		<hr>
		{{-- <form class="g-3 formSave"> --}}
			<div class="row mb-4">
				<div class="col-md-6">
					<label class="form-label">Produk <span class="text-danger">*)</span></label>
					<input type="hidden" class="form-control" name="id_order" id="id_order" value="{{$order?$order->id:''}}">
					<select class="single-select" name="id_produk" id="id_produk">
						<!-- <option value="first" selected disabled>--Pilih Kategori--</option> -->
					</select>
				</div>
				<div class="col-md-6">
					<label class="form-label">Qty <span class="text-danger">*)</span></label>
					<input type="text" class="form-control" name="qty" id="qty" autocomplete="off" placeholder="Jumlah" value="{{$order?$order->stok:''}}">
					<div id="errStok"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="d-grid gap-2 d-md-block float-right">
						{{-- <button class="btn btn-secondary btnKembali" type="button">KEMBALI</button> --}}
						<button class="btn btn-info btn-append" type="button">TAMBAHKAN DATA</button>
						<div class="btn-update float-right"></div>
					</div>
				</div>
			</div>
		{{-- </form> --}}
	</div>
</div>
<div class="card border-top border-0 border-4 border-primary mt-5 mb-5">
	<div class="card-body p-3">
		<div class="card-title d-flex align-items-center">
			<h5 class="mb-0">Data Laporan</h5>
		</div>
		<form class="g-3 formSave">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table id="tbBarang" class="table table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>Nama Produk</th>
									<th>Harga</th>
									<th>Qty</th>
									<th>Total Harga</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody class="tempatData">
								<input type="hidden" name="orderId" id="orderId" value="{{$order?$order->id:''}}">
								@if($order)
									@foreach($order->order_detail as $key => $val)
										<tr class="rowBarang" id="{{$val->id}}">
											<td><span id="namaProduk">{{strtoupper($val->product->nama_produk)}}</span></td>
											<td><span id="textHarga">{{rupiah($val->product->harga)}}</span></td>
											<td><span id="textQty">{{$val->jumlah}}</span></td>
											<td><span id="textTotal">{{rupiah($val->harga)}}</span></td>
											<td>
												<center>
												<a href="javascript:void(0)" title="Edit" onclick="editData({{$val->id}})" style="text-decoration:none;">
													<i class="fa-regular fa-pen-to-square text-warning"></i>
												</a> &nbsp;&nbsp;
												<a href="javascript:void(0)" title="Hapus" onclick="deleteData({{$val->id}})" style="text-decoration:none;">
													<i class="fa fa-trash-alt text-danger"></i>
												</a>
												</center>
												<input type="hidden" name="idProduk[]" id="idProduk" value="{{$val->product->id}}">
												<input type="hidden" name="hargaProduk[]" id="hargaProduk" value="{{$val->product->harga}}">
												<input type="hidden" name="totalHarga[]" id="totalHarga" value="{{$val->harga}}">
												<input type="hidden" name="qtyAkhir[]" id="qtyAkhir" value="{{$val->jumlah}}">
												<input type="hidden" name="stokProduk[]" id="stokProduk" value="{{$val->product->stok}}">
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</form>
		<div class="row">
			<div class="col-md-6">
				<div class="d-grid gap-2 d-md-block">
					<button class="btn btn-secondary btnKembali" type="button">KEMBALI</button>
				</div>
			</div>
			<div class="col-md-6">
				<div class="d-grid gap-2 d-md-block float-right">
					<button class="btn btn-success btnSimpan" type="button">SIMPAN</button>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	$(document).ready(()=>{
		$('#qty').keypress(function(e){
			var stok = $('#qty').val()
			var res = stok.toString().replace(/[^,\d]/g, "")
			$('#qty').val(res)
			if(e.which!=8 && isNaN(String.fromCharCode(e.which))){
				e.preventDefault()
				$('#errStok').html('Hanya angka').stop().show().fadeOut('slow')
				return false;
			}
		})
	});
	$(document).on('select2:open',()=>{
		document.querySelector('.select2-search__field').focus();
	});

	var status = '{{$page}}'
	$(()=>{
		// if(status=='Tambah'){
			setProduk()
		// }else{
		// 	var data = JSON.parse('{!!$order!!}')
		// 	if(data.category===null){
		// 		setProduk()
		// 	}else{
		// 		setProduk(data.category.id)
		// 	}
		// }
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

	$('.btn-append').click(async ()=>{
		var id = generateId(5)
		var produk = $('#id_produk').val()
		var qty = $('#qty').val()
		var arrProduk = $('input[name^="idProduk"]')
		if(produk===null){
			await Swal.fire({
				icon: 'warning',
				title: 'Whoops',
				text: 'Produk harus diisi',
				showConfirmButton: true,
			})
			return
		}
		if(!qty || qty==0){
			await Swal.fire({
				icon: 'warning',
				title: 'Whoops',
				text: 'Masukkan QTY dengan benar!',
				showConfirmButton: true,
			})
			return
		}
		let duplikat = false
		for (var i = 0; i < arrProduk.length; i++) {
			if (produk == arrProduk[i].value) {
				duplikat = true;
			}
		}
		if(duplikat){
			await Swal.fire({
				icon: 'warning',
				title: 'Whoops',
				text: 'Produk sudah masuk ke list!',
				showConfirmButton: true,
			})
			await $('#id_produk').val('first').trigger('change')
			await $('#qty').val('')
			return
		}
		try{
			var data = {
				id: produk,
				qty: qty,
			}
			await $.post('{{route("produk.search")}}',data).done(async (data, statusString, xhr)=>{
				const produk = data.data
				if(xhr.status===204){
					await Swal.fire({
						icon: 'warning',
						title: 'Whoops',
						text: 'Produk tidak ditemukan',
						showConfirmButton: true,
					})
					return
				}

				// <i class="ik ik-edit f-16 mr-15 text-warning"></i>
				let harga = produk.harga
				let totalHarga = harga * qty
				var html = '<tr class="rowBarang" id="'+id+'">'
						html += '<td><span id="namaProduk">'+produk.nama_produk.toUpperCase()+'</span></td>'
						html += '<td><span id="textHarga">'+formatRupiah(harga,'Rp. ')+'</span></td>'
						html += '<td><span id="textQty">'+qty+'</span></td>'
						html += '<td><span id="textTotal">'+formatRupiah(totalHarga,'Rp. ')+'</span></td>'
						html += '<td>'
							html += '<center>'
							html += '<a href="javascript:void(0)" title="Edit" onclick="editData(`'+id+'`)" style="text-decoration:none;">'
								html += '<i class="fa-regular fa-pen-to-square text-warning"></i>'
							html += '</a> &nbsp;&nbsp;'
							html += '<a href="javascript:void(0)" title="Hapus" onclick="deleteData(`'+id+'`)" style="text-decoration:none;">'
								html += '<i class="fa fa-trash-alt text-danger"></i>'
							html += '</a>'
							html += '</center>'
							html += `
							<input type="hidden" name="idProduk[]" id="idProduk" value="${produk.id}">
							<input type="hidden" name="hargaProduk[]" id="hargaProduk" value="${harga}">
							<input type="hidden" name="totalHarga[]" id="totalHarga" value="${totalHarga}">
							<input type="hidden" name="qtyAkhir[]" id="qtyAkhir" value="${qty}">
							<input type="hidden" name="stokProduk[]" id="stokProduk" value="${produk.stok}">
							`
						html += '</td>'
					html += '</tr>'
				await Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: 'Data berhasil ditambahkan',
					showConfirmButton: false,
					timer: 900
				})
				await $('.tempatData').append(html)
				// Reset form
				await $('#id_produk').val('first').trigger('change')
				await $('#qty').val('')
			})
		}catch(e){
			const code = e.status
			var icon = 'error'
			var title = 'ERROR!'
			if(code===422){
				icon = 'info'
				title = 'Whoops'
			}
			await Swal.fire({
				icon: icon,
				title: title,
				text: e.responseJSON.message===undefined ? 'Terjadi kesalahan sistem' : e.responseJSON.message,
				showConfirmButton: true,
			})
			return
		}
	})
	async function editData(id){
		const idProduk = $(`#${id} #idProduk`).val()
		const qty = $(`#${id} #qtyAkhir`).val()
		const namaProduk = $(`#${id} #namaProduk`).html()
		const stokProduk = $(`#${id} #stokProduk`).val()

		$('#id_produk').val(idProduk)
		$('#select2-id_produk-container').attr('title', namaProduk)
		$('#select2-id_produk-container').html(`${namaProduk} | QTY ${stokProduk}`)
		$('#qty').val(qty)

		await $('.btn-append').hide()
		var buttonUpdate = '<input type="hidden" id="idRow" value="'+id+'">'
		buttonUpdate += '<button type="button" class="btn btn-secondary" onclick="batalEdit()">Batal</button>'
		buttonUpdate += '&nbsp;&nbsp;<button type="button" class="btn btn-warning" onclick="updateData(`'+id+'`)">Ubah Data</button>'
		await $('.btn-update').html(buttonUpdate)
		await $('#id_produk').attr('disabled',true)
	}
	async function batalEdit(){
		await $('#id_produk').val('first').trigger('change')
		await $('#qty').val('')
		await $('.btn-update').empty()
		await $('.btn-append').show()
		$('#id_produk').attr('disabled',false)
	}
	async function updateData(id){
		// const idProduk = $(`#${id} #idProduk`).val()
		const qty = parseInt($(`#qty`).val())
		const hargaProduk = $(`#${id} #hargaProduk`).val()
		const stokProduk = parseInt($(`#${id} #stokProduk`).val())
		if(!qty || qty==0){
			await Swal.fire({
				icon: 'warning',
				title: 'Whoops',
				text: 'Masukkan QTY dengan benar!',
				showConfirmButton: true,
			})
			return
		}
		if(qty>stokProduk){
			await Swal.fire({
				icon: 'warning',
				title: 'Whoops',
				text: `Stok hanya tersisa ${stokProduk}!`,
				showConfirmButton: true,
			})
			return
		}

		let totalHarga = hargaProduk*qty
		await $(`#${id} #totalHarga`).val(totalHarga)
		await $(`#${id} #qtyAkhir`).val(qty)
		await $(`#${id} #textQty`).text(qty)
		await $(`#${id} #textTotal`).text(formatRupiah(totalHarga,'Rp. '))

		await Swal.fire({
			icon: 'success',
			title: 'Berhasil',
			text: 'Data berhasil di ubah',
			showConfirmButton: false,
			timer: 900
		})
		await $('#id_produk').val('first').trigger('change')
		await $('#qty').val('')
		await $('.btn-update').empty()
		await $('.btn-append').show()
		$('#id_produk').attr('disabled',false)
	}
	function deleteData(id) {
		Swal.fire({
			title: 'Anda yakin?',
			text: 'Ingin menghapus data ini!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Saya yakin!',
			cancelButtonText: 'Batal!',
			// closeOnConfirm: true,
		}).then(async (result) => {
			if(result.value == true){
				// await array_stok_barang.splice($('#' + id).index(), 1);
				await $('#' + id).remove()
				await Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: 'Data berhasil dihapus',
					showConfirmButton: false,
					timer: 900
				})
			}
		})
	}

	async function setProduk(param=''){
		await $.post('{{route("produk.list")}}').done(async (data, statusString, xhr)=>{
			const code = xhr.status
			if(code===200){
				var html = '<option value="first" selected disabled>--Pilih Produk--</option>'
				$.each(data.data,(i,val)=>{
					html += `<option value="${val.id}">${val.nama_produk.toUpperCase()} | QTY ${val.stok}</option>`
				})
				$('#id_produk').html(html)
			}
		})
	}

	$('.single-select').select2({
		theme: 'bootstrap4',
		width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
		placeholder: $(this).data('placeholder'),
		allowClear: Boolean($(this).data('allow-clear')),
		// data: {id:'' , text:''}
	})

	$('.btnSimpan').click(async ()=>{
		const rowCount = $('.tempatData tr').length
		if(rowCount<1){
			await Swal.fire({
				icon: 'warning',
				title: 'Whoops',
				text: 'Tidak ada item untuk disimpan!',
				showConfirmButton: true,
			})
			return
		}	
		var data = new FormData($('.formSave')[0])
		$('.btnSimpan').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		try{
			await $.ajax({
				url: '{{route("laporan.store")}}',
				type: 'POST',
				data: data,
				contentType: false,
				processData: false,
			})
			.done(async (data, statusString, xhr)=>{
				console.log(data)
				await Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: data.message,
					showConfirmButton: false,
					timer: 900
				})
				await $('.btnSimpan').attr('disabled',false).html('SIMPAN')
				await $('#otherPage').fadeOut(async()=>{
					await $('#dataTable').DataTable().ajax.reload()
					hideForm()
				})
			})
		}catch(e){
			const code = e.status
			var icon = 'error'
			var title = 'ERROR!'
			if(code===422){
				icon = 'info'
				title = 'Whoops'
			}
			await Swal.fire({
				icon: icon,
				title: title,
				text: e.responseJSON.message===undefined ? 'Terjadi kesalahan sistem' : e.responseJSON.message,
				showConfirmButton: true,
			})
			await $('.btnSimpan').attr('disabled',false).html('SIMPAN')
		}
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

	function generateId(n) {
		var text = "";
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		
		for (var i = 0; i < n; i++) {
			text += possible.charAt(Math.floor(Math.random() * possible.length));
		}
		return text;
	}
</script>