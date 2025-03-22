@extends('admin_layout')
@section('admin_content')

<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            Thêm đơn thuốc
        </header>
            @if(Session::has('message'))
                <div class="alert alert-success">
                    {{ Session::get('message') }}
                </div>
                {{ Session::put('message', null) }}
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            @endif
        <div class="panel-body">
            <div class="position-center">
                <form role="form" action="{{ url(path: '/save-prescription') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Tên bệnh nhân</label>
                        <select name="name_id" id="patient_select" class="form-control" required>
                            <option value="">Chọn bệnh nhân</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" data-birth="{{ $patient->birth_date }}">{{ $patient->patient_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Ngày sinh</label>
                        <input type="date" name="patient_date" id="patient_date" class="form-control" placeholder="Nhập ngày sinh" required>
                    </div>
                    <script>
                        document.getElementById('patient_select').addEventListener('change', function () {
                            let selectedOption = this.options[this.selectedIndex];
                            let birthDate = selectedOption.getAttribute('data-birth');
                            document.getElementById('patient_date').value = birthDate || '';
                        });
                    </script>
                    

                    <div class="form-group">
                        <label>Bác sĩ</label>
                        <select name="doctor_room" class="form-control" required>
                            <option value="">Chọn bác sĩ</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-info">Thêm thuốc</button>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection
