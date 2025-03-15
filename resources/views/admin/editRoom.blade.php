@extends('admin_layout')
@section('admin_content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Cập nhật phòng khám</h4>
                </div>
                <div class="card-body">
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

                    @foreach ($edit_room as $edit_value)
                    <form action="{{ url('/update-room/'.$edit_value->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="CodeRoom" class="form-label">Mã phòng</label>
                            <input type="text" value="{{ $edit_value->code }}" name="room_code" class="form-control" id="CodeRoom">
                        </div>
                        <div class="mb-3">
                            <label for="NameRoom" class="form-label">Tên phòng</label>
                            <input type="text" value="{{ $edit_value->name }}" name="room_name" class="form-control" id="NameRoom">
                        </div>
                        <div class="mb-3">
                            <label for="TypeRoom" class="form-label">Loại phòng</label>
                            <select name="room_type" class="form-control" id="TypeRoom">
                                <option value="1" @if($edit_value->room_type == 1) selected @endif>Phòng khám</option>
                                <option value="2" @if($edit_value->room_type == 2) selected @endif>Phòng bệnh loại 1</option>
                                <option value="3" @if($edit_value->room_type == 3) selected @endif>Phòng vip</option>
                                <option value="4" @if($edit_value->room_type == 4) selected @endif>Phòng chức năng</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                        </div>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
