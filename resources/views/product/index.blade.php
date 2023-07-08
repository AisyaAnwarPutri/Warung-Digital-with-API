@extends('layout.app')
@section('title', 'Data produk')

@push('style')
@endpush

@section('content')
<div class="card shadow">
    <div class="card-header">
        <h4 class="card-title">
            Data produk
        </h4>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-end mb-4">
            <a href="#modal-form" class="btn btn-success modal-tambah">Tambah Data</a>
        </div>
        <div class="table-responsive">
            <table id="table-data" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Subkategori</th>
                        <th>Nama produk</th>
                        <th>Harga</th>
                        <th>Tags</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
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
                            <input type="hidden" id="case" value="">
                            <input type="hidden" id="edit-id" value="">
                            <div class="form-group">
                                <label for="">Kategori</label>
                                <select name="id_kategori" id="id_kategori" class="form-control">
                                    @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->nama_kategori}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">SubKategori</label>
                                <select name="id_subkategori" id="id_subkategori" class="form-control">
                                    @foreach ($subcategories as $category)
                                    <option value="{{$category->id}}">{{$category->nama_subkategori}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Nama Produk</label>
                                <input type="text" class="form-control" name="nama_produk" placeholder="Nama produk">
                            </div>
                            <div class="form-group">
                                <label for="">Harga</label>
                                <input type="number" class="form-control" name="harga" placeholder="Harga">
                            </div>
                            <div class="form-group">
                                <label for="">Tags</label>
                                <input type="text" class="form-control" name="tags" placeholder="Tags">
                            </div>
                            <div class="form-group">
                                <label for="">Deskripsi</label>
                                <textarea name="deskripsi" placeholder="Deskripsi" class="form-control" id="" cols="30"
                                    rows="10" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Gambar</label>
                                <input type="file" class="form-control" name="gambar">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block">Submit</button>
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
<script src="https://code.jquery.com/jquery-3.1.0.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

<script>
    $(function() {
        // $.ajax({
        //     url: '/api/products',
        //     success:(res)=>{
        //         let row = ''
        //         res.data.map(function(val, index) {
        //             var category = '-'
        //             var subCategory = '-'
        //             if(val.category !== null){
        //                 category = val.category.nama_kategori
        //             }
        //             if(val.subcategory !== null){
        //                 subCategory = val.subcategory.nama_subkategori
        //             }
        //             row += `
        //                 <tr>
        //                     <td>${index+1}</td>
        //                     <td>${category}</td>
        //                     <td>${subCategory}</td>
        //                     <td>${val.nama_produk}</td>
        //                     <td>${val.harga}</td>
        //                     <td>${val.tags}</td>
        //                     <td><img src="/uploads/${val.gambar}" width="150"></td>
        //                     <td>
        //                         <a href="#modal-form" data-id="${val.id}" class="btn btn-warning modal-ubah">Edit</a>
        //                         <a href="#" data-id="${val.id}" class="btn btn-danger btn-hapus">hapus</a>
        //                     </td>
        //                 </tr>
        //                 `;
        //         });
        //         $('tbody').append(row)
        //     }
        // });

        $(document).on('click', '.btn-hapus', function() {
            const id = $(this).data('id')
            const token = localStorage.getItem('token')
            confirm_dialog = confirm('Apakah anda yakin?');
            if (confirm_dialog) {
                $.ajax({
                    url: '/api/products/' + id,
                    type: "DELETE",
                    headers: {
                        "Authorization": `Bearer ${token}`
                    },
                    success: function(data) {
                        if (data.message == 'success') {
                            alert('Data berhasil dihapus')
                            location.reload()
                        }
                    }
                });
            }
        });

        $('.modal-tambah').click(function() {
            $('#modal-form').modal('show')
            $('input[name="nama_kategori"]').val('')
            $('textarea[name="deskripsi"]').val('')
            $('#case').val('tambah')
        });
        $('.form-kategori').submit(function(e) {
            e.preventDefault()
            const token = localStorage.getItem('token')
            const frmdata = new FormData(this);
            var cases = $('#case').val()
            var url = 'api/products'
            if(cases=='edit'){
                const id = $('#edit-id').val()
                url = `api/products/${id}?_method=PUT`
            }
            $.ajax({
                url: url,
                type: 'POST',
                data: frmdata,
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    "Authorization": 'Bearer '+token
                },
                success: function(data) {
                    if (data.success) {
                        alert(data.message)
                        location.reload()
                    }
                },
            }).fail(()=>{
                $.post('api/refresh-token').done((res)=>{
                    console.log(res)
                })
                // alert('Token expired')
                // console.log(e)
            })
        });
        // $('.form-kategori').submit(function(e) {
        //     e.preventDefault()
        //     const token = localStorage.getItem('token')
        //     const frmdata = new FormData(this);
        //     $.ajax({
        //         url: `api/products/${id}?_method=PUT`,
        //         type: 'POST',
        //         data: frmdata,
        //         cache: false,
        //         contentType: false,
        //         processData: false,
        //         headers: {
        //             "Authorization": 'Bearer ' + token
        //         },
        //         success: function(data) {
        //             if (data.success) {
        //                 alert('Data berhasil diubah')
        //                 location.reload();
        //             }
        //         },
        //         fail : function(data){
        //             console.log(data)
        //         }
        //     })
        // });

        $(document).on('click', '.modal-ubah', function() {
            $('#modal-form').modal('show')
            const id = $(this).data('id');
            $('#case').val('edit')
            $('#edit-id').val(id)
            $.get('/api/products/' + id, function({
                data
            }) {
                $('input[name="nama_kategori"]').val(data.nama_kategori);
                $('textarea[name="deskripsi"]').val(data.deskripsi);
            });
        });

    });
    
    $(document).ready(function(){
        dataTable()
    });
    function dataTable(){
        $('#tabel-data').DataTable();
    }
</script>
@endpush
