@extends('admin_layout')
@section('admin_content')

<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            Thêm phòng khám
        </header>
        @if(Session::has('message'))
            <span class="text-bold">{{ Session::get('message') }}</span>
            {{ Session::put('message', null) }}
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

                    <button type="submit" class="btn btn-info">Thêm phòng</button>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection
