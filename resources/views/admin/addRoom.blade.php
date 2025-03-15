@extends('admin_layout')
@section('admin_content')

<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            Thêm phòng khám
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
                <form role="form" action="{{ url(path: '/save-room') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Mã phòng</label>
                        <input type="text" name="room_code" class="form-control" placeholder="Nhập mã phòng" required>
                    </div>

                    <div class="form-group">
                        <label>Tên phòng</label>
                        <input type="text" name="room_name" class="form-control" placeholder="Nhập tên phòng" required>
                    </div>

                    <div class="form-group">
                        <label>Loại phòng</label>
                        <select name="room_type" class="form-control input-sm m-bot15">
                            <option value="1">Phòng khám</option>
                            <option value="2">Phòng bệnh loại 1</option>
                            <option value="3">Phòng vip</option>
                            <option value="4">Phòng chức năng</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-info">Thêm phòng</button>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection
