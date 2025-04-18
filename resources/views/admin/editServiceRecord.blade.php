@extends('admin_layout')
@section('admin_content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Cập nhật phiếu</h4>
                </div>
                <div class="card-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success">
                            {{ Session::get('message') }}
                        </div>
                        {{ Session::put('message', null) }}
                    @endif

                    <form action="{{ url('/update-record-service/'.$edit_service_record->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label>Tên bệnh nhân</label>
                            <select name="medical_id" id="patient_select" class="form-control" required>
                                <option value="">Chọn bệnh nhân</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" 
                                        {{ $patient->id == $edit_service_record->medical_id ? 'selected' : '' }}
                                        data-birth="{{ $patient->birth_date }}">
                                        {{ $patient->patient_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Ngày sinh</label>
                            <input type="date" name="patient_date" id="patient_date" class="form-control" 
                                value="{{ old('patient_date', $edit_service_record->birth_date ?? '') }}" required>
                        </div>

                        <script>
                            document.getElementById('patient_select').addEventListener('change', function () {
                                let selectedOption = this.options[this.selectedIndex];
                                let birthDate = selectedOption.getAttribute('data-birth');
                                document.getElementById('patient_date').value = birthDate || '';
                            });
                        </script>

                        <div class="mb-3">
                            <label>Bác sĩ chỉ định</label>
                            <select name="doctor_id" class="form-control" required>
                                <option value="">Chọn bác sĩ</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" 
                                        {{ $doctor->id == $edit_service_record->doctor_id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Phòng khám</label>
                            <select name="room_id" class="form-control" required>
                                <option value="">Chọn bác sĩ</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" 
                                        {{ $room->id == $edit_service_record->room_id ? 'selected' : '' }}>
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="examination_results" class="form-label">Chẩn đoán</label>
                            <textarea name="examination_results" id="examination_results" class="form-control" rows="4">{{ old('examination_results', $patient->examination_results ?? '') }}</textarea>
                        </div>                        

                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                            <a href="{{ url('/list-record-service') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
