@extends('layout.app')

@section('title', 'Data Kategori')

@section('content')
    <div class="card shadow">
        <div class="card-header">
            <h4 class="card-title">
                Data Kategori
            </h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-4">
                <a href="#modal-form" class="btn btn-success modal-tambah">Tambah Data</a>
            </div>
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
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
                                <label for="">Nama Kategori</label>
                                <input type="text" class="form-control" name="nama_kategori" placeholder="Nama Kategori" required>
                            </div>
                            <div class="form-group">
                                <label for="">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" placeholder="Deskripsi" id="" cols="30" rows="10" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Gambar</label>
                                <input type="file" class="form-control" name="gambar" required>
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
    <script>
        $(function(){

            $.ajax({
                url : '/api/categories',
                success : function ({data}) {

                    let row;

                    data.map(function (val, index) {
                        row += `
                        <tr>
                            <td>${index+1}</td>
                            <td>${val.nama_kategori}</td>
                            <td>${val.deskripsi}</td>
                            <td><img src="/uploads/${val.gambar}" width="150"></td>
                            <td>
                                <a data-toggle="modal" href="#modal-form" data-id="${val.id}" class="btn btn-warning modal-ubah">Ubah</a>
                                <a href="#" data-id="${val.id}" class="btn btn-danger btn-hapus">Hapus</a>
                            </td>
                        </tr>
                        `;

                    });

                    // append to table
                    $('tbody').prepend(row)

                    // hapus form
                    $('#nama_kategori').val('');
                    $('#deskripsi').val('');  
                    $('#gambar').val('');  
                    
                    // tutup modal
                    $('#modal-tambah').modal('hide');

                }
            });

            $(document).on('click', '.btn-hapus', function () {
                const id = $(this).data('id')
                const token = localStorage.getItem('token');

                confirm_dialog = confirm('Apakah Anda Yakin?');


                if (confirm_dialog) {
                    $.ajax({
                        url : '/api/categories/' + id,
                        type : "DELETE",
                        headers: {
                            "Authorization": 'Bearer' + token
                        },
                        success : function (data) {
                            if (data.message == 'success') {
                                alert('Data Berhasil Dihapus')
                                location.reload()
                            }
                        }
                    })
                }

            });


            $('.modal-tambah').click(function(){
                $('#modal-form').modal('show')

                $('.form-kategori').submit(function(e){
                    e.preventDefault()
                    const token = localStorage.getItem('token');

                    const frmdata = new FormData(this)


                    $.ajax({
                        url : 'api/categories',
                        type : 'POST',
                        data : frmdata,
                        cache : false,
                        contentType : false,
                        processData : false,
                        headers: {
                            "Authorization": 'Bearer' + token
                        },
                        success : function(data){
                            if (data.success) {
                                alert('Data Berhasil Ditambah')
                                location.reload();
                            }
                        }
                    })
                });
            });

            

            $(document).on('click', '.modal-ubah', function () {
                $('#modal-form').modal('show')
            })

        });
    </script>
@endpush