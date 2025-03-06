<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn Thuốc</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .container { width: 100%; margin: auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table, .table th, .table td { border: 1px solid black; }
        .table th, .table td { padding: 8px; text-align: left; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Bệnh viện XYZ</h2>
        <p>Địa chỉ: 123 Đường ABC, TP.HCM</p>
        <p>SĐT: 0987 654 321</p>
        <hr>
        <h3>ĐƠN THUỐC</h3>
    </div>

    <p><strong>Bệnh nhân:</strong> {{ $pres->patient_name }}</p>
    <p><strong>Bác sĩ:</strong> {{ $pres->doctor_name }}</p>

    <h4>Danh sách thuốc:</h4>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên thuốc</th>
                <th>Số lượng</th>
                <th>Đơn vị</th>
                <th>Cách dùng</th>
                <th>Giá</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $key => $detail)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $detail->medicine_name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->medicine_unit }}</td>
                    <td>{{ $detail->usage_instruction }}</td>
                    <td>{{ number_format($detail->price) }} VNĐ</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Tổng tiền:</strong> {{ number_format($details->sum('price')) }} VNĐ</p>
    <p style="text-align: right;">Ngày {{ now()->format('d') }} tháng {{ now()->format('m') }} năm {{ now()->format('Y') }}</p>
    <p style="text-align: right;"><strong>Bác sĩ kê đơn</strong></p>
    <p style="text-align: right;"><em>(Ký và ghi rõ họ tên)</em></p>
    <p style="text-align: right;"><strong>{{ $pres->doctor_name }}</strong></p>

</div>

</body>
</html>
