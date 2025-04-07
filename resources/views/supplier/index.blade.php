<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier</title>
</head>
<body>
    <h1>Data Supplier Pengguna</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Kode Supplier</th>
            <th>Nama Supplier</th>
            <th>Alamat</th>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td>{{ $item->supplier_id }}</td>
            <td>{{ $item->supplier_kode }}</td>
            <td>{{ $item->supplier_nama }}</td>
            <td>{{ $item->supplier_alamat }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>