@extends('admin_layout')
@section('admin_content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Cập nhật đơn thuốc</h4>
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

                    @foreach ($edit_service as $edit_value)
                    <form action="{{ url('/update-service/'.$edit_value->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="Codeservice" class="form-label">Mã dịch vụ</label>
                            <input type="text" value="{{ $edit_value->code }}" name="service_code" class="form-control" id="Codeservice">
                        </div>
                        <div class="mb-3">
                            <label for="Nameservice" class="form-label">Tên dịch vụ</label>
                            <input type="text" value="{{ $edit_value->name }}" name="service_name" class="form-control" id="Nameservice">
                        </div>
                        <div class="mb-3">
                            <label for="Nameservice" class="form-label">giá</label>
                            <input type="text" value="{{ $edit_value->price }}" name="service_name" class="form-control" id="Nameservice">
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
