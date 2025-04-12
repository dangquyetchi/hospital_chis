<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ bệnh án </title>
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
            <h2>Bệnh án</h2>
            <p><i>Ngày in: {{ date('d/m/Y') }}</i></p>
        </div>
        
        <p><strong>Họ và Tên:</strong> {{ $patient->name }}</p>
        <p>
            <strong>Tuổi:</strong> {{ $age }} tuổi |
            <strong>Giới tính:</strong> {{ $patient->gender }} |
            <strong>Ngày sinh:</strong> {{ date('d-m-Y', strtotime($patient->birth_date)) }}
        </p>
        <p><strong>Địa chỉ:</strong> {{ $patient->address }}</p>
        <p><strong>Ngày vào viện:</strong> {{ date('d-m-Y', strtotime($patient->date_in)) }}</p>
        <p><strong>Ngày ra viện:</strong> {{ $patient->date_out ? date('d-m-Y', strtotime($patient->date_out)) : '' }}</p>
        <strong>Phòng bệnh:</strong> {{ $patient->room_name}} |
        <strong>Giường bệnh:</strong> {{ $patient->bed_name }} |
        <p><strong>Tình trạng ban đầu:</strong> {{ $patient->patient_condition }}</p>
        <p><strong>Tình trạng ra viện:</strong> {{ $patient->out_hospital }}</p>
        <div class="footer">
            
        </div>
    </div>
</body>
</html>
