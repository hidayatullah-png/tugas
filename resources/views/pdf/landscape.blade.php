<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat FIKKIA UNAIR</title>
    <style>
        /* MANTRA KHUSUS DOMPDF */
        @page {
            size: A4 landscape;
            margin: 40px; /* Jarak kertas putih di luar bingkai */
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            text-align: center;
            margin: 0;
            padding: 0;
            color: #333;
        }
        
        /* Trik agar bingkai selalu penuh satu halaman penuh */
        .bingkai {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            border: 8px solid #6C4A61; 
            padding: 40px 20px;
        }
        
        .judul {
            font-size: 45px;
            font-weight: bold;
            color: #6C4A61;
            letter-spacing: 10px;
            margin-top: 10px;
        }
        .nomor {
            font-size: 14px;
            letter-spacing: 4px;
            color: #666;
            margin-bottom: 30px;
        }
        .diberikan-kepada {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .nama {
            font-size: 38px;
            font-weight: bold;
            color: #3B2435;
            border-bottom: 2px solid #ccc;
            display: inline-block;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
        .peran-label {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .peran {
            font-size: 28px;
            font-weight: bold;
            color: #6C4A61;
            margin-bottom: 20px;
        }
        .deskripsi {
            font-size: 15px;
            line-height: 1.5;
            width: 85%;
            margin: 0 auto;
            text-align: center;
        }
        
        /* Layout tanda tangan */
        .tabel-ttd {
            width: 100%;
            margin-top: 40px;
            font-size: 13px;
            text-align: center;
        }
        .tabel-ttd td {
            width: 33.3%;
            vertical-align: bottom;
        }
        .jarak-ttd {
            height: 80px; /* Ruang untuk tanda tangan */
        }
    </style>
</head>
<body>
    <div class="bingkai">
        <div class="judul">SERTIFIKAT</div>
        <div class="nomor">3353/B/UN3.FIKKIA/S.KM/PM.03/2025</div>
        
        <div class="diberikan-kepada">Diberikan kepada :</div>
        <div class="nama">Alfia Rizqy Hanifah</div>
        
        <div class="peran-label">Atas Partisipasinya Sebagai:</div>
        <div class="peran">WAKIL KETUA PELAKSANA</div>
        
        <div class="deskripsi">
            Dalam acara: Seminar Nasional dengan tema <b>"Membalik Tren Global : Menjawab Epidemi Penyakit Tidak Menular Melalui Revolusi Gaya Hidup dan Kekuatan Kesehatan Masyarakat"</b> yang diselenggarakan oleh Program Studi Kesehatan Masyarakat FIKKIA Universitas Airlangga pada Sabtu, 18 Oktober 2025.
        </div>

        <table class="tabel-ttd">
            <tr>
                <td>
                    <b>Dekan FIKKIA UNAIR</b>
                    <div class="jarak-ttd"></div>
                    <b>Dr. Rahadian Indarto Susilo, Dr., Sp.BS. (K)</b><br>
                    NIP. 197712272009121002
                </td>
                <td>
                    <b>Koordinator Program Studi<br>Kesehatan Masyarakat FIKKIA UNAIR</b>
                    <div class="jarak-ttd"></div>
                    <b>Jayanti Dian Eka, S.KM., M.Kes</b><br>
                    NIK. 198409172015043201
                </td>
                <td>
                    <b>Ketua Pelaksana</b>
                    <div class="jarak-ttd"></div>
                    <b>Diansanto Prayoga, S.KM., M.Kes.</b><br>
                    NIK. 198605122015043101
                </td>
            </tr>
        </table>
    </div>
</body>
</html>