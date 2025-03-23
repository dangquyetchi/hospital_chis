<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu Dịch Vụ</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .container { width: 100%; margin: auto; }
        .header {  margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table, .table th, .table td { border: 1px solid black; }
        .table th, .table td { padding: 8px; text-align: left; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Bệnh viện Hospital Chis</h2>
        <p>Địa chỉ: 41A Đ. Phú Diễn, Phú Diễn, Bắc Từ Liêm, Hà Nội</p>
        <p>SĐT: 0000000000</p>
        <hr>
        <h2>Phiếu Dịch Vụ</h2>
        <p><strong>Mã Phiếu:</strong> {{ $service_record->id }}</p>
        <p><strong>Bệnh nhân:</strong> {{ $service_record->patient_name }}</p>
        <p><strong>Ngày sinh:</strong> {{ $service_record->patient_date }}</p>
        <p><strong>Bác sĩ:</strong> {{ $service_record->doctor_name }}</p>
        <p><strong>Ngày tạo phiếu:</strong> {{ date('d-m-Y', strtotime($service_record->created_at))}}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên dịch vụ</th>
                <th>Phòng</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($service_detail as $key => $service)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $service->service_name }}</td>
                    <td>{{ $service->room_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- <div class="text-center">
        <button class="btn-print" onclick="window.print()">In Phiếu</button>
    </div> --}}
</div>

</body>
</html>
