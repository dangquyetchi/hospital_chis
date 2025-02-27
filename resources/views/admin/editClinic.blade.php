@extends('admin_layout')
@section('admin_content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Cập nhật giấy khám bệnh</h4>
                </div>
                <div class="card-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success">
                            {{ Session::get('message') }}
                        </div>
                        {{ Session::put('message', null) }}
                    @endif

                    <form action="{{ url('/update-clinic/'.$edit_clinic->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="patient_name" class="form-label">Họ và Tên</label>
                            <input type="text" value="{{ $edit_clinic->patient_name }}" name="patient_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giới tính</label>
                            <select name="patient_gender" class="form-control" required>
                                <option value="Nam" {{ $edit_clinic->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ $edit_clinic->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                <option value="Khác" {{ $edit_clinic->gender == 'Khác' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="examination_date" class="form-label">Ngày khám</label>
                            <input type="date" value="{{ $edit_clinic->examination_date }}" name="examination_date" class="form-control" id="examination_date">
                        </div>
                        
                        <div class="mb-3">
                            <label for="price_exam" class="form-label">Giá khám</label>
                            <input type="number" value="{{ $edit_clinic->price_exam }}" name="price_exam" class="form-control" id="price_exam" min="1">
                        </div>
                        <div class="mb-3">
                            <label for="diagnosis" class="form-label">Triệu chứng</label>
                            <input type="text" value="{{ $edit_clinic->diagnosis }}" name="diagnosis" class="form-control" id="diagnosis">
                        </div>  

                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
