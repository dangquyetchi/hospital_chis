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
                <form role="form" action="{{ url(path: '/save-bed') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Mã giường</label>
                        <input type="text" name="bed_code" class="form-control" placeholder="Nhập mã giường" required>
                    </div>

                    <div class="form-group">
                        <label>Tên giường</label>
                        <select name="bed_name" class="form-control input-sm m-bot15">
                            <option value="1">Chọn tên giường</option>
                            <option value="Giường 1">Giường 1</option>
                            <option value="Giường 2">Giường 2</option>
                            <option value="Giường 3">Giường 3</option>
                            <option value="Giường vip">Giường vip</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Thuộc phòng</label>
                        <select name="room_id" class="form-control input-sm m-bot15">
                            <option value="">Chọn phòng</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>                    

                    <button type="submit" class="btn btn-info">Thêm giường</button>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection
