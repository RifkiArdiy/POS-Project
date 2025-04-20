<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 10px;
            margin: 0;
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .border-top {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 2px 0;
        }
    </style>
</head>

<body>
    <div class="text-center">
        <strong>Sigma Mart</strong><br>
        Jl. Kenangan No.8, Malang<br>
        Telp: 0812-3456-7890
    </div>

    <div class="border-top"></div>

    <table>
        <tr>
            <td>Kode</td>
            <td>: {{ $penjualan->penjualan_kode }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($penjualan->penjualan_tanggal)->format('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td>: {{ $penjualan->user->nama }}</td>
        </tr>
        <tr>
            <td>Pembeli</td>
            <td>: {{ $penjualan->pembeli }}</td>
        </tr>
    </table>

    <div class="border-top"></div>
    @php
        $total = 0;
    @endphp
    @foreach($penjualan->penjualanDetails as $detail)
            @php
                $subtotal = $detail->barang->harga_jual * $detail->jumlah_barang;
                $total += $subtotal;
            @endphp
            <div>
                {{ $detail->barang->barang_nama }}<br>
                {{ number_format($detail->barang->harga_jual, 0, ',', '.') }} x {{ $detail->jumlah_barang }}
                <span class="text-right" style="float: right">
                    {{-- {{ number_format($detail->harga_barang * $detail->jumlah_barang, 0, ',', '.') }} --}}
                    {{ number_format($subtotal, 0, ',', '.') }}
                </span>
            </div>
    @endforeach

    <div class="border-top"></div>

    <table>
        <tr>
            <td><strong>Total</strong></td>
            <td class="text-right"><strong>{{ number_format($total, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <div class="border-top"></div>

    <div class="text-center">
        *** TERIMA KASIH ***<br>
        Barang yang sudah dibeli<br>
        tidak dapat dikembalikan.<br>
    </div>
</body>

</html>