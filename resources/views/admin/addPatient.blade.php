@extends('admin_layout')
@section('admin_content')

<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            Thêm bệnh nhân
        </header>
        @if(Session::has('message'))
            <span class="text-bold">{{ Session::get('message') }}</span>
            {{ Session::put('message', null) }}
        @endif
        <div class="panel-body">
            <div class="position-center">
                <form id="patientForm" onsubmit="return validateForm() role="form" action="{{ url('/save-patient') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Họ và Tên</label>
                        <input type="text" name="patient_name" class="form-control" placeholder="Nhập Tên bệnh nhân" required>
                    </div>
                    <div class="form-group">
                        <label>Giới tính</label>
                        <select name="patient_gender" class="form-control" required>
                            <option value="">Chọn giới tính</option>
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ngày sinh</label>
                        <input type="date" name="patient_birth" class="form-control" required>
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
                        <input type="date" name="patient_datein" class="form-control" required>
                    </div>

                  {{-- thông tin thẻ BHYT --}}
                <div class="form-group">
                  <label>Số thẻ BHYT</label>
                  <input type="text" name="card_number" id="card_number" class="form-control"
                         placeholder="Nhập số thẻ BHYT" maxlength="15"
                         oninput="validateCardNumber(this)">
                  <small id="card_error" class="text-danger d-block mt-1"></small>
                </div>
                
                <script>
                    function validateCardNumber(input) {
                        let value = input.value;
                        let errorElement = document.getElementById("card_error");
                
                        // Kiểm tra chỉ chứa số
                        if (!/^\d*$/.test(value)) {
                            errorElement.innerText = "Chỉ được nhập số.";
                            input.value = value.replace(/\D/g, ""); 
                            return false;
                        }
                
                        // Giới hạn số ký tự tối đa là 15
                        if (value.length > 15) {
                            input.value = value.slice(0, 15);
                        }
                
                        // Kiểm tra độ dài chính xác
                        if (value.length > 0 && value.length < 15) {
                            errorElement.innerText = "Số thẻ BHYT phải có đúng 15 số.";
                            return false;
                        }
                
                        // Kiểm tra số đầu tiên có hợp lệ không
                        if (value.length > 0 && !/^[1-3]/.test(value)) {
                            errorElement.innerText = "Số đầu tiên phải là 1, 2 hoặc 3.";
                            return false;
                        }
                
                        // Nếu không có lỗi, xóa thông báo
                        errorElement.innerText = "";
                        return true;
                    }
                
                    function validateForm() {
                        let cardInput = document.getElementById("card_number");
                        let errorElement = document.getElementById("card_error");
                
                        // Kiểm tra số thẻ BHYT trước khi gửi
                        if (!validateCardNumber(cardInput)) {
                            errorElement.innerText = "Vui lòng nhập số thẻ BHYT hợp lệ.";
                            return false; // Chặn form submit
                        }
                
                        return true; // Cho phép submit nếu hợp lệ
                    }
                </script>                               

                    <div id="bhyt-details" style="display: none;">
                        <div class="form-group">
                            <label>Ngày cấp</label>
                            <input type="date" name="issue_date" class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Ngày hết hạn</label>
                            <input type="date" name="expiry_date" class="form-control" min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="form-group">
                            <label>Loại bảo hiểm</label>
                            <select name="insurance_type" class="form-control" >
                                <option value="">Chọn loại bảo hiểm</option>
                                <option value="Bảo hiểm tự nguyện">Bảo hiểm tự nguyện</option>
                                <option value="Bảo hiểm bắt buộc">Bảo hiểm bắt buộc</option>
                            </select>
                        </div>
                        
                    </div>

                    <button type="submit" class="btn btn-info">Thêm bệnh nhân</button>
                </form>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

@endsection
