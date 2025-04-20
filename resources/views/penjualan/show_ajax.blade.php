@php
    $details = $penjualanDetails; // Koleksi detail penjualan
@endphp

@if($details->isEmpty())
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data detail penjualan tidak ditemukan.
                </div>
                <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
            </div>
        </div>
    </div>
@else
    @php
        // Data header dari item pertama karena semua detail memiliki penjualan_id yang sama
        $header = $details->first()->penjualan;
        // $header = $penjualan;
        $totalHarga = $details->sum('harga');
    @endphp
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Tampilkan header penjualan -->
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID Penjualan</th>
                        <td>{{ $header->penjualan_id }}</td>
                    </tr>
                    <tr>
                        <th>Kode Penjualan</th>
                        <td>{{ $header->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pegawai</th>
                        <td>{{ $header->user->nama }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pembeli</th>
                        <td>{{ $header->pembeli }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Penjualan</th>
                        <td>{{ \Carbon\Carbon::parse($header->penjualan_tanggal)->translatedFormat('d F Y \ H:i:s') }}</td>
                    </tr>
                </table>

                <!-- Tampilkan daftar detail penjualan -->
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID Detail</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach($details as $detail)
                                        @php
                                            $subtotal = $detail->barang->harga_jual * $detail->jumlah_barang;
                                            $total += $subtotal;
                                        @endphp
                                        <tr>
                                            <td>{{ $detail->detail_id }}</td>
                                            <td>{{ $detail->barang->barang_nama }}</td>
                                            <td>{{ 'Rp' . number_format($detail->harga_barang, 0, ',', '.') }}</td>
                                            <td>{{ $detail->jumlah_barang }}</td>
                                            <td>{{ 'Rp' . number_format($subtotal, 0, ',', '.') }}</td>
                                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total Harga</th>
                            <th>{{ 'Rp. ' . number_format($total, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
            </div>
        </div>
    </div>
@endif

{{-- @empty($penjualan)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data Penjualan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <!-- Tampilkan header penjualan -->
            <table class="table table-bordered table-striped table-hover table-sm">
                <tr>
                    <th>ID Penjualan</th>
                    <td>yaa</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endempty --}}