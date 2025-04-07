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
</html>