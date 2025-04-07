<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
</head>
<body>
    <h1>Data User Pengguna</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Nama</th>
            <th>Kode Level</th>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td>{{ $item->user_id }}</td>
            <td>{{ $item->username }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->level_id }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>