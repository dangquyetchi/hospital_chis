@extends('admin_layout')
@section('admin_content')

<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            Thêm bệnh nhân
        </header>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
         @endif    
               
         <div class="panel-body">
            <div class="position-center">
                <form id="patientForm" onsubmit="return validateForm()" role="form" action="{{ url('/save-patient') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Chọn Bệnh Nhân</label>
                        <select name="medical_id" id="medical_id" class="form-control" required onchange="fillPatientInfo()">
                            <option value="">-- Chọn bệnh nhân --</option>
                            @foreach($medicalRecords as $record)
                                <option 
                                    value="{{ $record->id }}"
                                    data-gender="{{ $record->gender }}"
                                    data-birth="{{ $record->birth_date }}"
                                >
                                    {{ $record->patient_name }} (ID: {{ $record->id }})
                                </option>
                            @endforeach
                        </select>
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
                        <input type="date" name="patient_birth" id="patient_birth" class="form-control" required oninput="validateDate_birth()">
                        <small id="patient_birth_error" class="text-danger d-block mt-1"></small>
                    </div>
                    
                    <div class="form-group">
                        <label>Địa chỉ</label>
                        <input type="text" name="patient_address" class="form-control" placeholder="Nhập địa chỉ" required>
                    </div>
                    <div class="form-group">
                        <label>Tình trạng</label>
                        <input type="text" name="patient_condition" class="form-control" placeholder="Nhập tình trạng bệnh nhân" required>
                    </div>
                    <div class="form-group">
                        <label>Ngày vào viện</label>
                        <input type="date" name="patient_datein" id="patient_datein" class="form-control" required oninput="validateDate_in()">
                        <small id="patient_datein_error" class="text-danger d-block mt-1"></small>
                    </div>

                    <label for="room_id">Chọn phòng</label>
                        <select name="room_id" id="room-select" class="form-control">
                            <option value="">-- Chọn phòng --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>

                        <label for="bed_id">Chọn giường</label>
                        <select name="bed_id" id="bed-select" class="form-control">
                            <option value="">-- Chọn giường --</option>
                        </select>

                    <button type="submit" id="submitBtn" class="btn btn-info" >Thêm bệnh nhân</button>
                </form>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

    function validateDate_birth() {
        let patient_birth = document.getElementById("patient_birth").value;
        let today = getFormattedDate();
        let errorElement = document.getElementById("patient_birth_error");
        let submitButton = document.getElementById("submitBtn");

        if (patient_birth > today) {
            errorElement.innerText = "Ngày sinh không được lớn hơn ngày hiện tại.";
            submitButton.disabled = true;
            return false;
        }

        errorElement.innerText = "";
        submitButton.disabled = false;
        return true;
    }

    function validateDate_in() {
        let patient_datein = document.getElementById("patient_datein").value;
        let today = getFormattedDate();
        let errorElement = document.getElementById("patient_datein_error");
        let submitButton = document.getElementById("submitBtn");

        if (patient_datein > today) {
            errorElement.innerText = "Ngày vào viện không được lớn hơn ngày hiện tại.";
            submitButton.disabled = true;
            return false;
        }

        errorElement.innerText = "";
        submitButton.disabled = false;
        return true;
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
    function fillPatientInfo() {
        var select = document.getElementById("medical_id");
        var selectedOption = select.options[select.selectedIndex];
    
        if (selectedOption.value === "") {
            document.getElementById("patient_gender").value = "";
            document.getElementById("patient_birth").value = "";
            return;
        }
    
        var gender = selectedOption.getAttribute("data-gender");
        var birthDate = selectedOption.getAttribute("data-birth");
    
        document.getElementById("patient_gender").value = gender || "";
        document.getElementById("patient_birth").value = birthDate || "";
    }
</script>
<script>
    document.getElementById('room-select').addEventListener('change', function () {
        const roomId = this.value;
        const bedSelect = document.getElementById('bed-select');
        bedSelect.innerHTML = '<option value="">Đang tải...</option>';

        if (roomId) {
            fetch(`/get-beds/${roomId}`)
                .then(response => response.json())
                .then(data => {
                    let options = '<option value="">-- Chọn giường --</option>';
                    data.forEach(bed => {
                        options += `<option value="${bed.id}">${bed.name_bed}</option>`;
                    });
                    bedSelect.innerHTML = options;
                });
        } else {
            bedSelect.innerHTML = '<option value="">-- Chọn giường --</option>';
        }
    });
</script>
   
@endsection
