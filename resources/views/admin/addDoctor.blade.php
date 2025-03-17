@extends('admin_layout')
@section('admin_content')

<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            Thêm nhân viên
        </header>
        @if(Session::has('message'))
            <span class="text-bold">{{ Session::get('message') }}</span>
            {{ Session::put('message', null) }}
        @endif
        <div class="panel-body">
            <div class="position-center">
                <form role="form" action="{{ url(path: '/save-doctor') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Họ và Tên</label>
                        <input type="text" name="doctor_name" class="form-control" placeholder="Nhập tên thuốc" required>
                    </div>
                    <div class="form-group">
                        <label>Giới tính</label>
                        <select name="doctor_gender" class="form-control" required>
                            <option value="Chọn giới tính">Chọn giới tính</option>
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phòng</label>
                        <select name="doctor_room" class="form-control" required>
                            <option value="">Chọn phòng</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Chức vụ</label>
                        <select name="position" class="form-control" required>
                            <option value="">Chọn chức vụ</option>
                            <option value="Trưởng khoa">Trưởng khoa</option>
                            <option value="Phó trưởng khoa">Phó trưởng khoa</option>
                            <option value="Bác sĩ chuyên khoa">Bác sĩ chuyên khoa</option>
                            <option value="Bác sĩ đa khoa">Bác sĩ đa khoa</option>
                            <option value="Bác sĩ phẫu thuật">Bác sĩ phẫu thuật</option>
                            <option value="Điều dưỡng">Điều dưỡng</option>
                            <option value="Kỹ thuật viên">Kỹ thuật viên</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-info">Thêm </button>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection
