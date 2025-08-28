<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absensi Magang {{ today('Asia/Kuala_Lumpur')->year }}</title>
    <style type="text/css">
        @font-face {
            font-family: "Lato", sans-serif;
            font-style: normal;
            font-optical-sizing: auto;
            src: url({{ storage_path('fonts/Lato-Regular.ttf') }}) format('truetype');
        }

        body {
            font-family: "Lato", sans-serif;
        }

        p {
            text-align: center;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <h1>{{ $title_absen }}</h1>
    <h3>Bulan: {{ $month }}</h3>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $key => $item)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $item->user?->name }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->jam_masuk }}</td>
                    <td>{{ $item->jam_keluar }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Laporan dibuat pada: {{ $datetime }} WITA</p>

    <div class="page-break"></div>

    {{-- Belum selesai, layout masih belum ter rencana di kepala. --}}

    <h1>{{ $title_CoD }}</h1>
    <table>
        <thead>
            <tr>
                <th>Hari</th>
                <th>Nama</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($callOfDuties as $item)
                <tr>
                    <td>{{ $item->days }}</td>
                    <td>{{ $item->user?->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
