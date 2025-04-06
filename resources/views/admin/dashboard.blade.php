@extends('admin_layout')

@section('admin_content')

<!-- Dropdown chọn theo thời gian -->
<div class="form-group">
    <label for="timeSelection">Chọn thống kê theo:</label>
    <select id="timeSelection" class="form-control" onchange="updateCharts()">
        <option value="daily">Ngày</option>
        <option value="monthly">Tháng</option>
        <option value="yearly">Năm</option>
    </select>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <canvas id="revenueChart" height="100"></canvas>
    </div>
    <div class="col-md-12 mt-5">
        <canvas id="patientChart" height="100"></canvas>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let revenueChart, patientChart;

    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const patientCtx = document.getElementById('patientChart').getContext('2d');

    // Cập nhật biểu đồ dựa trên thời gian
    function updateCharts() {
        const timeSelection = document.getElementById('timeSelection').value;

        // Xóa các biểu đồ cũ nếu có
        if (revenueChart) {
            revenueChart.destroy();
        }
        if (patientChart) {
            patientChart.destroy();
        }

        // Dữ liệu cho các loại thời gian
        let labels, revenueData, patientData;

        if (timeSelection === 'daily') {
            labels = {!! json_encode($dailyRevenue->pluck('date')) !!};
            revenueData = {!! json_encode($dailyRevenue->pluck('total')) !!};
            patientData = {!! json_encode($patientCount->pluck('count')) !!};
        } else if (timeSelection === 'monthly') {
            labels = {!! json_encode($monthlyRevenue->pluck('month')) !!};
            revenueData = {!! json_encode($monthlyRevenue->pluck('total')) !!};
            patientData = {!! json_encode($monthlyPatientCount->pluck('count')) !!};
        } else if (timeSelection === 'yearly') {
            labels = {!! json_encode($yearlyRevenue->pluck('year')) !!};
            revenueData = {!! json_encode($yearlyRevenue->pluck('total')) !!};
            patientData = {!! json_encode($yearlyPatientCount->pluck('count')) !!};
        }

        // Tạo lại biểu đồ doanh thu
        revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: revenueData,
                    borderColor: 'green',
                    backgroundColor: 'rgba(0, 128, 0, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Biểu đồ doanh thu'
                    }
                }
            }
        });

        // Tạo lại biểu đồ số lượng bệnh nhân
        patientChart = new Chart(patientCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Số lượng bệnh nhân',
                    data: patientData,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'blue',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Biểu đồ số lượng bệnh nhân'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Khi trang tải, mặc định chọn "Ngày"
    window.onload = function() {
        updateCharts();
    }
</script>

@endsection
