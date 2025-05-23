<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        img {
            width: 200px;
            margin-bottom: 12px;
        }

        #waktu {
            font-size: 12px;
        }

        h5 {
            text-align: center;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('assets/img/kop.png') }}" alt="Kop Surat">
    <h5>Hasil Simulasi Rotasi Kepala</h5>
    <table>
        <thead>
            <tr>
                <th>NIP</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Satker Saat Ini</th>
                <th>Satker Tujuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $pegawai)
                <tr>
                    <td>{{ $pegawai['nip'] }}</td>
                    <td>{{ $pegawai['nama'] }}</td>
                    <td>{{ $pegawai['jabatan'] }}</td>
                    <td>{{ $pegawai['satker_asal'] }}</td>
                    <td>{{ $pegawai['satker_tujuan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>    
    <p id="waktu">Dicetak pada {{ now()->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') }} WIB</p>
</body>
</html>