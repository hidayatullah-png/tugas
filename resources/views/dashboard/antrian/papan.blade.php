<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Papan Antrian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1a1a1a;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .highlight-box {
            background-color: #3ea2c7;
            border-radius: 15px;
            padding: 40px;
        }

        .text-yellow {
            color: #ffdd57;
        }
    </style>
</head>

<body class="d-flex flex-column justify-content-center align-items-center vh-100 text-center">

    <div id="init-overlay"
        class="position-absolute w-100 h-100 bg-dark d-flex justify-content-center align-items-center"
        style="z-index: 9999;">
        <button class="btn btn-lg btn-warning fw-bold px-5 py-3" onclick="mulaiSistem()"
            style="background-color: #ffdd57; color: #333;">Mulai Papan Antrian</button>
    </div>

    <div class="container">
        <h2 class="mb-5 text-uppercase fw-bold text-yellow">Loket Pelayanan</h2>
        <div class="highlight-box shadow-lg">
            <h4 class="text-light mb-3">Nomor Antrian:</h4>
            <h1 class="display-1 fw-bold mb-0" id="display-nomor" style="font-size: 15rem;">-</h1>
            <h2 class="mt-4 text-yellow" id="display-nama">Menunggu panggilan...</h2>
        </div>
    </div>

    <audio src="{{ asset('audio/dingdong.mp3') }}" id="audio-bel"></audio>

    <script>
        let lastCalledId = null;

        function mulaiSistem() {
            // Hilangkan overlay tombol
            document.getElementById('init-overlay').classList.add('d-none');

            // Buka koneksi SSE setelah user berinteraksi
            const sse = new EventSource("{{ route('antrian.sse') }}");

            sse.addEventListener('queue-update', function (event) {
                const data = JSON.parse(event.data);

                if (data.dipanggil && data.dipanggil.id !== lastCalledId) {
                    lastCalledId = data.dipanggil.id;

                    // Update UI Layar
                    document.getElementById('display-nomor').innerText = data.dipanggil.nomor_antrian;
                    document.getElementById('display-nama').innerText = data.dipanggil.nama;

                    // Putar Suara
                    putarSuara(data.dipanggil.nomor_antrian, data.dipanggil.nama);
                }
            });
        }

        function putarSuara(nomor, nama) {
            if (!('speechSynthesis' in window)) return;

            window.speechSynthesis.cancel(); // Hentikan suara yang sedang jalan
            const audio = document.getElementById('audio-bel');

            // Teks untuk dibaca robot
            const pesan = new SpeechSynthesisUtterance(`Nomor antrian, ${nomor}. Atas nama, ${nama}. Silakan menuju loket.`);
            pesan.lang = 'id-ID';
            pesan.rate = 0.85;

            // Mainkan nada bel ting-tong dulu
            audio.currentTime = 0;
            audio.play().catch(e => console.log("Audio autoplay dicekal:", e));

            // Setelah bel selesai, robot bicara
            audio.onended = function () {
                window.speechSynthesis.speak(pesan);
            };
        }
    </script>
</body>

</html>