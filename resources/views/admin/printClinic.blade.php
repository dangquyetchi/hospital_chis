<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giấy Khám Bệnh</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .print-container { width: 80%; margin: auto; padding: 20px; border: 1px solid #ddd; }
        .btn-print { display: block; margin: 20px auto; padding: 10px 20px; background: blue; color: white; cursor: pointer; }
        .text-center{
            text-align: center
        }
        .info {
        margin-bottom: 20px;
        }
        .info img {
            width: 100px;
            height: auto;
            border-radius: 50%;
        }
        .name_hospital {
            font-style: italic;
            font-size: 25px;
            font-weight: bold;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="print-container">
            <div class="info">
                <img src="{{ asset('images/nenlaptop_chis.jpg') }}" alt="Logo Bệnh Viện">
                <div class="name_hospital">Hospital - Chis</div>
            </div>
            <p>Địa chỉ: 41A Đ. Phú Diễn, Phú Diễn, Bắc Từ Liêm, Hà Nội</p>
            <p>SĐT: 0000000000</p>
            <hr>
            <h2 class="text-center">Thông Tin Giấy Khám Bệnh</h2>
            <p><strong>Mã:</strong> {{ $clinic->id }}</p>
            <p><strong>Tên bệnh nhân:</strong> {{ $clinic->patient_name }}</p>
            <p><strong>Ngày khám:</strong> {{date('d-m-Y', strtotime($clinic->examination_date))}}</p>
            <p><strong>Phòng khám:</strong> {{ $clinic->room_name }}</p>
            <p><strong>Triệu chứng ban đầu:</strong> {{ $clinic->diagnosis }}</p>
    
            <button class="btn-print" onclick="printPage()">In Giấy Khám Bệnh</button>
        </div>
    </div>
</body>
</html>
<script>
    function printPage() {
        let printButton = document.querySelector(".btn-print");
        printButton.style.display = "none";
        window.print();
        printButton.style.display = "block"; 
    }
</script>

