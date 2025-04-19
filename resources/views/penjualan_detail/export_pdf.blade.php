<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export PDF - Detail Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 4px; }
        th { background-color: #eee; }
        h2 { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>Data Detail Penjualan</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Penjualan</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Nama Barang</th>
                <th>Harga Barang</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($details as $detail)
                @php
                    $penjualan = $detail->penjualan;
                    $subtotal = $detail->harga_barang * $detail->jumlah_barang;
                @endphp
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $penjualan->penjualan_kode ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d-m-Y') }}</td>
                    <td>{{ $penjualan->user->nama ?? '-' }}</td>
                    <td>{{ $detail->barang->barang_nama ?? '-' }}</td>
                    <td>{{ number_format($detail->harga_barang, 0, ',', '.') }}</td>
                    <td>{{ $detail->jumlah_barang }}</td>
                    <td>{{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
