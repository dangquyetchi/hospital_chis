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
                        <label>Họ và Tên</label>
                        <input type="text" name="patient_name" class="form-control" placeholder="Nhập tên bệnh nhân" required>
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
                        <input type="date" name="birth_date" class="form-control" required>
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

                    {{-- <div class="form-group">
                        <label>Số thẻ BHYT</label>
                        <input type="text" name="insurance_number" class="form-control" placeholder="Nhập số thẻ (nếu có)">
                    </div> --}}

                    {{-- <div class="form-group">
                        <label>Dịch vụ</label>
                        <select name="service_id" class="form-control">
                            <option value="">Chọn dịch vụ</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}

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

@endsection
