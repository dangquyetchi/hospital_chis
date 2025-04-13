<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn thanh toán viện phí </title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .invoice-container { width: 100%; max-width: 600px; margin: auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 10px; text-align: left; }
        .footer { margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h2>Bệnh viện Hospital Chis</h2>
            <p>Địa chỉ: 41A Đ. Phú Diễn, Phú Diễn, Bắc Từ Liêm, Hà Nội</p>
            <p>SĐT: 0000000000</p>
            <hr>
            <h2>HÓA ĐƠN THANH TOÁN</h2>
            <p>Ngày: {{ date('d/m/Y') }}</p>
        </div>

        <p><strong>Họ và Tên:</strong> {{ $payment_pres->patient_name }}</p>
        <p><strong>Ngày Sinh:</strong> {{ date('d-m-Y', strtotime($payment_pres->birth_date)) }}</p>
        <p><strong>Tiền thuốc:</strong> {{ number_format($payment_pres->price_prescription, 0, ',', '.') }} VNĐ</p>
        <p>Tiền giảm BHYT: {{ number_format($payment_pres->price_prescription * ($payment_pres->coverage_rate/100), 0, ',', '.') }} VNĐ</p>
        <p><strong>Số tiền phải thanh toán: </strong>{{ number_format($payment_pres->price_prescription - ($payment_pres->price_prescription * ($payment_pres->coverage_rate/100)), 0, ',', '.') }} VNĐ</p>    
        <p><strong>Phương thức thanh toán:</strong> {{ $payment_pres->payment_method == 'Tiền mặt' ? 'Tiền mặt' : 'QRCODE' }}</p>

        <div class="footer">
            <p>Cảm ơn quý khách đã sử dụng dịch vụ!</p>
        </div>
    </div>
</body>
</html>
