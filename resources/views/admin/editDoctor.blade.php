@extends('admin_layout')
@section('admin_content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Cập nhật thông tin bác sĩ</h4>
                </div>
                <div class="card-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success">
                            {{ Session::get('message') }}
                        </div>
                        {{ Session::put('message', null) }}
                    @endif

                    <form action="{{ url('/update-doctor/'.$edit_doctor->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="Doctorname" class="form-label">Họ và Tên</label>
                            <input type="text" value="{{ $edit_doctor->name }}" name="doctor_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giới tính</label>
                            <select name="doctor_gender" class="form-control" required>
                                <option value="Nam" {{ $edit_doctor->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ $edit_doctor->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                <option value="Khác" {{ $edit_doctor->gender == 'Khác' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phòng</label>
                            <select name="doctor_room" class="form-control" required>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}" {{ $edit_doctor->room_id == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Chức vụ</label>
                            <select name="position" class="form-control" required>
                                @foreach ($positions as $pos)
                                    <option value="{{ $pos }}" {{ $edit_doctor->position == $pos ? 'selected' : '' }}>
                                        {{ $pos }}
                                    </option>
                                @endforeach
                            </select>
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
