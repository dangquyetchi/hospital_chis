<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Thanh Toán</title>
</head>
<body>
    <h2>Mã QR Code cho Thanh Toán #{{ $paymentId }}</h2>
    <img src="{{ $qrCodeUrl }}" alt="QR Code">
    <br>
    <a href="/">Quay lại trang chủ</a>
</body>
</html>
