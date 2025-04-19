{{--
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang</title>
</head>

<body>
    <h1>Data Barang Pengguna</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Kode Barang</th>
            <th>Kategori Barang</th>
            <th>Nama Barang</th>
            <th>Harga Jual</th>
            <th>Harga Beli</th>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td>{{ $item->barang_id }}</td>
            <td>{{ $item->kategori_id }}</td>
            <td>{{ $item->barang_kode }}</td>
            <td>{{ $item->barang_nama }}</td>
            <td>{{ $item->harga_beli }}</td>
            <td>{{ $item->harga_jual }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html> --}}


{{-- @extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Filter Berdasarkan Kategori -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="kategori_id" name="kategori_id">
                            <option value="">- Semua -</option>
                            @foreach($kategori as $kat)
                            <option value="{{ $kat->kategori_id }}">{{ $kat->kategori_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Filter berdasarkan kategori</small>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data backdrop="static"
    data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }
    var dataBarang;
    $(document).ready(function () {
        var dataBarang = $('#table_barang').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('barang/list') }}",
                dataType: "json",
                type: "POST",
                // Kirim kategori_id sebagai parameter untuk filter
                data: function (d) {
                    d.kategori_id = $('#kategori_id').val();
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "barang_kode",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "barang_nama",
                    orderable: true,
                    searchable: true
                },
                {
                    // Tampilkan nama kategori dari relasi
                    data: "kategori.kategori_nama",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "aksi",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Saat filter berubah, reload DataTables
        $('#kategori_id').on('change', function () {
            dataBarang.ajax.reload();
        });
    });
</script>
@endpush --}}

@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar barang</h3>
            <div class="card-tools">
                {{-- <a href="{{ url('/barang/create') }}" class="btn btn-primary">Tambah
                    Data</a> --}}
                <a href="{{ url('/barang/export_pdf') }}" class="btn btn-sm btn-warning"><i class="fa fa-file-pdf"></i>
                    Export Barang</a>
                <a href="{{ url('/barang/export_excel') }}" class="btn btn-sm btn-primary"><i class="fa fa-file-excel"></i>
                    Export
                    Barang</a>
                <button onclick="modalAction('{{ url('/barang/import') }}')" class="btn btn-sm btn-info">Import
                    Barang</button>
                <button onclick="modalAction('{{ url('/barang/create_ajax') }}')" class="btn btn-sm btn-success">Tambah
                    Barang</button>
            </div>
        </div>
        <div class="card-body">
            <!-- untuk Filter data -->
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_date" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_kategori" class="form-control form-control-sm filter_kategori"
                                    id="filter_kategori">
                                    <option value="">- Semua -</option>
                                    @foreach($kategori as $l)
                                        <option value="{{ $l->kategori_id }}">{{ $l->kategori_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Kategori Barang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-sm table-striped table-hover" id="table-barang">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Barang</th>
                        <th>Kode Barang</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false"
        data-width="75%"></div>

@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        var tableBarang;
        $(document).ready(function () {
            tableBarang = $('#table-barang').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('barang/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.filter_kategori = $('.filter_kategori').val();
                    }
                },
                columns: [{
                    // data: "No_Urut",
                    data: "DT_RowIndex",
                    className: "text-center",
                    width: "5%",
                    orderable: false,
                    searchable: false
                }, {
                    data: "barang_kode",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "barang_nama",
                    className: "",
                    orderable: true,
                    searchable: true,
                }, {
                    data: "harga_beli",
                    className: "",
                    orderable: true,
                    searchable: false,
                    render: function (data, type, row) {
                        return new Intl.NumberFormat('id-ID').format(data);
                    }
                }, {
                    data: "harga_jual",
                    className: "",
                    orderable: true,
                    searchable: false,
                    render: function (data, type, row) {
                        return new Intl.NumberFormat('id-ID').format(data);
                    }
                }, {
                    data: "kategori.kategori_nama",
                    className: "",
                    orderable: true,
                    searchable: false
                }, {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }]
            });

            $('#table-barang_filter input').unbind().bind().on('keyup', function (e) {
                if (e.keyCode == 13) { // enter key 
                    tableBarang.search(this.value).draw();
                }
            });

            $('#filter_kategori').on('change', function () {
                tableBarang.draw();
            });
        }); 
    </script>
@endpush