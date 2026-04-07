<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Berhasil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="text-center mt-5">
    <div class="container">
        <h1 class="text-success">Terima Kasih!</h1>
        <p>Pembayaran Anda telah kami terima. Silakan tunggu pesanan Anda diproses oleh vendor.</p>
        <a href="{{ route('guest.index') }}" class="btn btn-primary">Kembali ke Menu Utama</a>
    </div>
</body>
</html>