<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori</title>
</head>
<body>
    <h1>Data Kategori Pengguna</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Kode Kategori</th>
            <th>Nama Kategori</th>
            <th>Deskripsi</th>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td>{{ $item->kategori_id }}</td>
            <td>{{ $item->kategori_kode }}</td>
            <td>{{ $item->kategori_nama }}</td>
            <td>{{ $item->deskripsi }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>