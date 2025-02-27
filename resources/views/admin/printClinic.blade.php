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
    </style>
</head>
<body>
    <div class="main">
        <div class="print-container">
            <div class="info">
                <div class="logo_hospital">
                    <img src="" alt="">
                </div>
                <div class="name_hospital">Hospital - Chis</div>
            </div>
            <h2 class="text-center">Thông Tin Giấy Khám Bệnh</h2>
            <p><strong>Mã:</strong> {{ $clinic->id }}</p>
            <p><strong>Tên bệnh nhân:</strong> {{ $clinic->patient_name }}</p>
            <p><strong>Ngày khám:</strong> {{ $clinic->examination_date }}</p>
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

