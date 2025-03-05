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
