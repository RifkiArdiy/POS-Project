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
</html>