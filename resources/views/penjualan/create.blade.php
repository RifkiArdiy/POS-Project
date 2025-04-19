@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Transaksi Penjualan</h3>
            </div>
            <div class="card-body">
                {{-- Informasi Pembeli --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="pembeli">Nama Pembeli</label>
                        <input type="text" id="pembeli" class="form-control" placeholder="Nama Pembeli" required>
                    </div>
                </div>

                {{-- Tombol Pilih Barang --}}
                <div class="mb-3">
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalBarang">
                        <i class="fa fa-search"></i> Cari & Tambah Barang
                    </button>
                </div>

                {{-- Tabel Keranjang --}}
                <table class="table table-bordered" id="tabelKeranjang">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Total:</th>
                            <th id="totalHarga">Rp 0</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- Simpan Transaksi --}}
                <form id="formTransaksi" method="POST" action="{{ route('penjualan.store2') }}">
                    @csrf
                    <input type="hidden" name="data" id="dataTransaksi">
                    <input type="hidden" name="pembeli_input" id="pembeli_input">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Transaksi</button>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL PILIH BARANG --}}
    <div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="modalBarangLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Barang dari Stok</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" id="tabelStok">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Qty</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stokBarang as $item)
                                <tr>
                                    <td>{{ $item->barang_kode }}</td>
                                    <td>{{ $item->barang_nama }}</td>
                                    <td>{{ number_format($item->harga_jual) }}</td>
                                    <td>{{ $item->stok_jumlah }}</td>
                                    <td>
                                        <input type="number" class="form-control qtyInput" value="1" min="1"
                                            data-barang-id="{{ $item->barang_id }}">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm btnPilihBarang"
                                            data-id="{{ $item->barang_id }}" data-nama="{{ $item->barang_nama }}"
                                            data-harga="{{ $item->harga_jual }}">
                                            Tambah
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let keranjang = [];

        function renderKeranjang() {
            let tbody = $('#tabelKeranjang tbody');
            let total = 0;
            tbody.empty();
            keranjang.forEach((item, index) => {
                let subtotal = item.harga * item.qty;
                total += subtotal;
                tbody.append(`
                    <tr>
                        <td>${item.nama}</td>
                        <td>Rp ${item.harga.toLocaleString()}</td>
                        <td>${item.qty}</td>
                        <td>Rp ${subtotal.toLocaleString()}</td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusItem(${index})">Hapus</button></td>
                    </tr>
                `);
            });
            $('#totalHarga').text(`Rp ${total.toLocaleString()}`);
            $('#dataTransaksi').val(JSON.stringify(keranjang));
        }

        function hapusItem(index) {
            keranjang.splice(index, 1);
            renderKeranjang();
        }

        $(document).on('click', '.btnPilihBarang', function () {
            let barang_id = $(this).data('id');
            let nama = $(this).data('nama');
            let harga = $(this).data('harga');
            let qty = $(`.qtyInput[data-barang-id="${barang_id}"]`).val();

            if (!qty || qty <= 0) {
                alert('Qty tidak valid');
                return;
            }

            keranjang.push({
                barang_id,
                nama,
                harga,
                qty: parseInt(qty)
            });

            renderKeranjang();
            $('#modalBarang').modal('hide');
        });

        $('#formTransaksi').submit(function (e) {
            if (keranjang.length === 0) {
                e.preventDefault();
                alert("Keranjang kosong!");
                return;
            }

            let pembeli = $('#pembeli').val();
            if (!pembeli) {
                e.preventDefault();
                alert("Nama pembeli harus diisi");
                return;
            }

            $('#pembeli_input').val(pembeli);
        });
    </script>
@endpush