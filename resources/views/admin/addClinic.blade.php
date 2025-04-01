@extends('admin_layout')
@section('admin_content')

<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            Thêm giấy khám bệnh
        </header>
        @if(Session::has('message'))
            <span class="text-bold">{{ Session::get('message') }}</span>
            {{ Session::put('message', null) }}
        @endif
        <div class="panel-body">
            <div class="position-center">
                <form role="form" action="{{ url('/save-clinic') }}" method="POST">
                    @csrf
                        <div class="form-group">
                            <label>Số thẻ BHYT</label>
                            <input type="text" name="card_number" id="card_number" class="form-control"
                                    placeholder="Nhập số thẻ BHYT" maxlength="15"
                                   >
                                   {{-- oninput="validateCardNumber(this)" --}}
                                <small id="card_error" class="text-danger d-block mt-1"></small>
                        </div>
    
                        <div id="bhyt-details" style="display: none;">
                            <div class="form-group">
                                <label>Ngày cấp</label>
                                <input type="date" name="issue_date" id="issue_date" class="form-control"  
                                oninput="validateDateIssue()">
                            <small id="issue_date_error" class="text-danger d-block mt-1"></small>
                            </div>
    
                            <div class="form-group">
                                <label>Ngày hết hạn</label>
                                <input type="date" name="expiry_date" id="expiry_date" class="form-control" min="{{ date('Y-m-d') }}"
                                 oninput="validateDateExpiry()">
                                <small id="expiry_date_error" class="text-danger d-block mt-1"></small>
                            </div>
    
                            <div class="form-group">
                                <label>Loại bảo hiểm</label>
                                <select name="insurance_type" id="insurance_type" class="form-control"  >
                                    <option value="">Chọn loại bảo hiểm</option>
                                    <option value="Bảo hiểm tự nguyện">Bảo hiểm tự nguyện</option>
                                    <option value="Bảo hiểm bắt buộc">Bảo hiểm bắt buộc</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Họ và Tên</label>
                            <input type="text" name="patient_name" id="patient_name" class="form-control" placeholder="Nhập tên bệnh nhân" required>
                        </div>

                        <div class="form-group">
                            <label>Giới tính</label>
                            <select name="patient_gender" id="patient_gender" class="form-control" required>
                                <option value="">Chọn giới tính</option>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                                <option value="Khác">Khác</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Ngày sinh</label>
                            <input type="date" name="birth_date" id="birth_date" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Ngày khám</label>
                            <input type="date" name="examination_date" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Giá khám</label>
                            <input type="number" name="price_exam" class="form-control" placeholder="Nhập giá" required>
                        </div>

                        <div class="form-group">
                            <label>Triệu trứng</label>
                            <input type="text" name="diagnosis" class="form-control" placeholder="" required>
                        </div>

                        <div class="form-group">
                            <label>Phòng khám</label>
                            <select name="room_id" class="form-control">
                                <option value="">Chọn phòng khám</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Bác sĩ</label>
                            <select name="doctor_id" class="form-control">
                                <option value="">Chọn bác sĩ</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info">Thêm giấy khám bệnh</button>
                </form>
            </div>
        </div>
    </section>
</div>
{{-- ẩn hiện mục nhập thẻ bhyt nếu có --}}
<script>
    $(document).ready(function () {
        $("#card_number").focus(function () {
            $("#bhyt-details").slideDown();
        });

        $("#card_number").blur(function () {
            if ($(this).val().trim() === "") {
                $("#bhyt-details").slideUp();
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
    $("#card_number").on("input", function () {
        let value = $(this).val().trim();
        
        if (value.length > 0) {
            $("input[name='issue_date'], input[name='expiry_date'], select[name='insurance_type']").attr("required", true);
        } else {
            $("input[name='issue_date'], input[name='expiry_date'], select[name='insurance_type']").removeAttr("required");
        }
    }); 
});
</script>

<script>
    function validateCardNumber(input) {
        let value = input.value;
        let errorElement = document.getElementById("card_error");
        let submitButton = document.getElementById("submitBtn");

        // Kiểm tra chỉ chứa số
        if (!/^\d*$/.test(value)) {
            errorElement.innerText = "Chỉ được nhập số.";
            input.value = value.replace(/\D/g, ""); 
            submitButton.disabled = true;
            return false;
        }

        // Giới hạn số ký tự tối đa là 15
        if (value.length > 15) {
            input.value = value.slice(0, 15);
        }

        // Kiểm tra độ dài chính xác
        if (value.length > 0 && value.length < 15) {
            errorElement.innerText = "Số thẻ BHYT phải có đúng 15 số.";
            submitButton.disabled = true;
            return false;
        }

        // Kiểm tra số đầu tiên có hợp lệ không
        if (value.length > 0 && !/^[1-3]/.test(value)) {
            errorElement.innerText = "Số đầu tiên phải là 1, 2 hoặc 3.";
            submitButton.disabled = true;
            return false;
        }

        // Nếu không có lỗi, xóa thông báo và kích hoạt nút submit
        errorElement.innerText = "";
        submitButton.disabled = false;
        return true;
    }

    function getFormattedDate() {
    let today = new Date();
    let dd = today.getDate();
    let mm = today.getMonth() + 1; 
    let yyyy = today.getFullYear();
    if (dd < 10) dd = '0' + dd;
    if (mm < 10) mm = '0' + mm;
    return yyyy + '-' + mm + '-' + dd;
    }

    function validateDateIssue() {
        let issue_date = document.getElementById("issue_date").value;
        let expiry_date = document.getElementById("expiry_date").value;
        let today = getFormattedDate();
        let errorElement = document.getElementById("issue_date_error");
        let submitButton = document.getElementById("submitBtn");

        if (issue_date > today) {
            errorElement.innerText = "Ngày cấp không được lớn hơn ngày hiện tại.";
            submitButton.disabled = true;
            return false;
        }

        if (issue_date === expiry_date) {
            errorElement.innerText = "Ngày hết hạn không được trùng với ngày cấp.";
            submitButton.disabled = true;
            return false;
        }

        errorElement.innerText = "";
        submitButton.disabled = false;
        return true;
    }

    function validateForm() {
        let cardInput = document.getElementById("card_number");
        let errorElement = document.getElementById("card_error");
        if (!validateCardNumber(cardInput)) {
            errorElement.innerText = "Vui lòng nhập số thẻ BHYT hợp lệ.";
            return false; 
        }
        return true; 
    }
</script>

<script>
    document.getElementById('card_number').addEventListener('input', function () {
    let cardNumber = this.value.trim();

    if (cardNumber.length === 15) { 
        fetch(`/check-card?card_number=${cardNumber}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'exists') {
                    document.getElementById('patient_name').value = data.patient_name;
                    document.getElementById('birth_date').value = data.birth_date;
                    document.getElementById('patient_gender').value = data.gender;
                    document.getElementById('expiry_date').value = data.expiry_date;
                    document.getElementById('issue_date').value = data.issue_date;
                    document.getElementById('insurance_type').value = data.insurance_type;

                    document.getElementById('patient_name').readOnly = true;
                    document.getElementById('patient_gender').readOnly = true;
                    document.getElementById('birth_date').readOnly = true;
                    document.getElementById('expiry_date').readOnly = true;
                    document.getElementById('issue_date').readOnly = true;
                    document.getElementById('insurance_type').readOnly = true;

                } else {
                    document.getElementById('patient_name').value = '';
                    document.getElementById('birth_date').value = '';
                    document.getElementById('patient_gender').value = '';
                    document.getElementById('expiry_date').value = '';
                    document.getElementById('issue_date').value = '';
                    document.getElementById('insurance_type').value = '';
                    
                    document.getElementById('patient_name').readOnly = false;
                    document.getElementById('birth_date').readOnly = false;
                    document.getElementById('patient_gender').readOnly = false;
                    document.getElementById('expiry_date').readOnly = false;
                    document.getElementById('issue_date').readOnly = false;
                    document.getElementById('insurance_type').readOnly = false;

                }
            })
            .catch(error => console.error('Lỗi:', error));
    }
});


</script>
@endsection
