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

        /* offset posisi kertas */
        .kertas-wrapper {
            padding-top: 2mm;
            padding-left: 4mm;
        }

        /* tabel grid */
        table {
            border-collapse: separate;
            border-spacing: 3mm 2mm;
            table-layout: fixed;
        }

        /* kotak label */
        td {
            width: 37mm;
            height: 18mm;
            text-align: center;
            vertical-align: middle;
            padding: 0;
            overflow: hidden;

            border: 1px dashed #999;
            /* GARIS GRID */
        }

        /* isi label */

        .nama {
            font-weight: bold;
            font-size: 7px;
            margin-bottom: 1px;
            white-space: nowrap;
        }

        .harga {
            font-size: 10px;
            font-weight: bold;
        }

        .id {
            font-size: 5px;
            color: #555;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    @foreach($pages as $page)

        <div class="kertas-wrapper">

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

                                    <div class="nama">
                                        {{ \Illuminate\Support\Str::limit($item->nama, 20) }}
                                    </div>

                                    <div class="harga">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </div>

                                    <div class="id">
                                        {{ $item->id_barang }}
                                    </div>

                                @endif

                            </td>

                        @endfor

                    </tr>
                @endfor

            </table>

        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif

    @endforeach

</body>

</html>