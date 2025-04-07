<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Level</title>
</head>
<body>
    <h1>Data Level Pengguna</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Kode Level</th>
            <th>Nama Level</th>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td>{{ $item->level_id }}</td>
            <td>{{ $item->level_kode }}</td>
            <td>{{ $item->level_nama }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>