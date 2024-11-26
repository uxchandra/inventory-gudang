<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1, p {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: center;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h1>Laporan Permintaan Barang</h1>
    @if ($tanggalMulai && $tanggalSelesai)
        <p>Rentang Tanggal : {{ \Carbon\Carbon::parse($tanggalMulai)->format('d F Y') }} - {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d F Y') }}</p>
    @else
        <p>Rentang Tanggal : Semua</p>
    @endif
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Permintaan</th>
                <th>Nama Barang</th>
                <th>Jumlah Permintaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</td> 
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->jumlah_permintaan }}</td> 
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name }}<br>
        Tanggal: {{ date('d-m-Y') }}
    </div>
</body>
</html>
