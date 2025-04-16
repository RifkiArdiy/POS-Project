{{--
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan Detail</title>
</head>

<body>
    <h1>Data Penjualan Detail Pengguna</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Penjualan ID</th>
            <th>Barang ID</th>
            <th>Jumlah Barang</th>
            <th>Harga</th>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td>{{ $item->detail_id }}</td>
            <td>{{ $item->penjualan_id }}</td>
            <td>{{ $item->barang_id }}</td>
            <td>{{ $item->jumlah_barang }}</td>
            <td>{{ $item->harga_barang }}</td>
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
                {{-- <a class="btn btn-sm btn-primary mt-1" href="{{ url('user/create') }}">Tambah</a> --}}
                <a href="{{ url('/penjualan_detail/export_pdf') }}" class="btn btn-sm btn-warning mt-1"><i
                        class="fa fa-file-pdf"></i>
                    Export
                    Penjualan Detail</a>
                <a href="{{ url('/penjualan_detail/export_excel') }}" class="btn btn-sm btn-primary mt-1"><i
                        class="fa fa-file-excel"></i>
                    Export
                    Penjualan Detail</a>
                <button onclick="modalAction('{{ url('/penjualan_detail/import') }}')"
                    class="btn btn-sm btn-info mt-1">Import
                    Penjualan Detail</button>
                <button onclick="modalAction('{{url('penjualan_detail/create_ajax')}}')"
                    class="btn btn-sm btn-success mt-1">Tambah
                    Penjualan Detail</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            {{-- <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter</label>
                        <div class="col-3">
                            <select class="form-control" id="level_id" name="level_id" required>
                                <option value="">- Semua -</option>
                                @foreach($level as $item)
                                <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Level Pengguna</small>
                        </div>
                    </div>
                </div>
            </div> --}}
            <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan_detail">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Penjualan</th>
                        <th>Barang</th>
                        <th>Jumlah Barang</th>
                        <th>Harga Barang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data backdrop="static"
            data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    </div>
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
        var dataPenjualanDetail;
        $(document).ready(function () {
            dataPenjualanDetail = $('#table_penjualan_detail').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax: {
                    "url": "{{ url('penjualan_detail/list') }}",
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
                        data: "penjualan.penjualan_kode",
                        className: "",
                        // orderable: true, jika ingin kolom ini bisa diurutkan
                        orderable: true,
                        // searchable: true, jika ingin kolom ini bisa dicari
                        searchable: true
                    }, {
                        data: "barang.barang_nama",
                        className: "",
                        // orderable: true, jika ingin kolom ini bisa diurutkan
                        orderable: true,
                        // searchable: true, jika ingin kolom ini bisa dicari
                        searchable: true
                    }, {
                        data: "jumlah_barang",
                        className: "",
                        orderable: true,
                        searchable: true
                    }, {
                        data: "harga_barang",
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
            $('#level_id').on('change', function () {
                dataPenjualanDetail.ajax.reload();
            });
        });
    </script>
@endpush