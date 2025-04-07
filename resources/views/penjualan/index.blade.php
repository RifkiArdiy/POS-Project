<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan</title>
</head>
<body>
    <h1>Data Penjualan Pengguna</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Pembeli</th>
            <th>Kode Penjualan</th>
            <th>Tanggal Penjualan</th>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td>{{ $item->penjualan_id }}</td>
            <td>{{ $item->user_id }}</td>
            <td>{{ $item->pembeli }}</td>
            <td>{{ $item->penjualan_kode }}</td>
            <td>{{ $item->tanggal_penjualan }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>