@extends('admin_layout')
@section('admin_content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Cập nhật thông tin bệnh nhân</h3>
                </div>
                <div class="card-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success">
                            {{ Session::get('message') }}
                        </div>
                        {{ Session::put('message', null) }}
                    @endif

                    <form action="{{ url('/update-patient/'.$edit_patient->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="patient_name" class="form-label">Họ và Tên</label>
                            <input type="text" value="{{ $edit_patient->name }}" name="patient_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giới tính</label>
                            <select name="patient_gender" class="form-control" required>
                                <option value="Nam" {{ $edit_patient->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ $edit_patient->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                <option value="Khác" {{ $edit_patient->gender == 'Khác' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="birth_date" class="form-label">Ngày sinh</label>
                            <input type="date" value="{{ $edit_patient->birth_date }}" name="patient_birth" class="form-control" id="patient_birth"
                            oninput="validateDate_birth()">
                            <small id="patient_birth_error" class="text-danger d-block mt-1"></small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="patient_address" class="form-label">Địa chỉ</label>
                            <input type="text" value="{{ $edit_patient->address }}" name="patient_address" class="form-control" id="patient_address">
                        </div>
                        <div class="mb-3">
                            <label for="patient_condition" class="form-label">Tình trạng</label>
                            <input type="text" value="{{ $edit_patient->patient_condition }}" name="patient_condition" class="form-control" id="patient_condition">
                        </div>  

                        <div class="text-center">
                            <button type="submit" class="btn btn-success" id="submitBtn" >Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
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
</script>
