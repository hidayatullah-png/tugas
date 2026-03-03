<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 10mm 15mm 10mm;
        }

        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            width: 20%;
            /* 5 kolom */
            height: 33mm;
            /* tinggi label asli */
            padding: 2mm;
            vertical-align: middle;
            text-align: center;
        }

        .tag-box {
            border: 1px dashed #ccc;
            border-radius: 6px;
            height: 28mm;
            padding: 2mm;
        }
    </style>
</head>

<body>

    @foreach($pages as $page)

        <table>
            @for($row = 0; $row < 8; $row++)
                <tr>
                    @for($col = 0; $col < 5; $col++)
                        @php
                            $index = $row * 5 + $col;
                            $item = $page[$index] ?? null;
                        @endphp
                        <td>
                            @if($item)
                                <div class="tag-box">
                                    <div class="nama">
                                        {{ \Illuminate\Support\Str::limit($item->nama, 20) }}
                                    </div>
                                    <div class="harga">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </div>
                                    <div class="id">
                                        {{ $item->id_barang }}
                                    </div>
                                </div>
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>

        <div class="page-break"></div>

    @endforeach

</body>

</html>