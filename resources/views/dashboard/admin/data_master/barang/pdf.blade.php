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
            padding: 2px;
            /* dikasih sedikit padding dalam */
            overflow: hidden;
            border: 1px dashed #999;
            /* GARIS GRID */
        }

        .nama {
            font-weight: bold;
            font-size: 7px;
            margin-bottom: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .harga {
            font-size: 10px;
            font-weight: bold;
            margin: 1px 0;
        }

        /* Styling Barcode agar Rapih */
        .barcode-container {
            margin: 1px 0;
        }

        .barcode-container img {
            width: 28mm;
            /* Lebar barcode disesuaikan */
            height: 5mm;
            /* Tinggi barcode diperpendek agar muat di 18mm */
            display: block;
            margin: 0 auto;
        }

        .id {
            font-size: 5px;
            color: #555;
            margin-top: 1px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @php
        // Inisialisasi Generator Barcode PNG
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    @endphp

    @foreach ($pages as $page)
        <div class="kertas-wrapper">
            <table>
                @foreach (array_chunk($page, 5) as $row)
                    <tr>
                        @foreach ($row as $item)
                            <td>
                                @if($item)
                                    <div class="nama">{{ $item->nama }}</div>
                                    <div class="harga">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>

                                    <div class="barcode-container">
                                        {{-- Render Barcode ke Base64 PNG --}}
                                        <img
                                            src="data:image/png;base64,{{ base64_encode($generator->getBarcode($item->id_barang, $generator::TYPE_CODE_128)) }}">
                                    </div>

                                    <div class="id">{{ $item->id_barang }}</div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
        </div>
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>