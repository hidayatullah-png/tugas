<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            font-family: Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }

        .kertas-wrapper {
            padding-left: 8.5mm;
            padding-top: 3.5mm;
        }

        table {
            border-collapse: separate;
            border-spacing: 3mm 3mm;
            table-layout: fixed;
        }

        td {
            width: 37mm;
            height: 18mm;
            text-align: center;
            vertical-align: middle;
            padding: 0;
        }

        .page-break {
            page-break-after: always;
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