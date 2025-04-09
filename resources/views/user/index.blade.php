{{--
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
</head>

<body>
    <h1>Data User Pengguna</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Nama</th>
            <th>Kode Level</th>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td>{{ $item->user_id }}</td>
            <td>{{ $item->username }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->level_id }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html> --}}

@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('user/create') }}">Tambah</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_user">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Level Pengguna</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function () {
            var dataUser = $('#table_user').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing 
                serverSide: true,
                ajax: {
                    "url": "{{ url('user/list') }}",
                    "dataType": "json",
                    "type": "POST"
                },
                columns: [
                    {
                        // nomor urut dari laravel datatable addIndexColumn() 
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }, {
                        data: "username",
                        className: "",
                        // orderable: true, jika ingin kolom ini bisa diurutkan  
                        orderable: true,
                        // searchable: true, jika ingin kolom ini bisa dicari 
                        searchable: true
                    }, {
                        data: "nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    }, {
                        // mengambil data level hasil dari ORM berelasi 
                        data: "level.level_nama",
                        className: "",
                        orderable: false,
                        searchable: false
                    }, {
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        }); 
    </script>
@endpush