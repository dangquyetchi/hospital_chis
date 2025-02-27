@extends('admin_layout')
@section('admin_content')

<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            Thêm dịch vụ khám
        </header>
        @if(Session::has('message'))
            <span class="text-bold">{{ Session::get('message') }}</span>
            {{ Session::put('message', null) }}
        @endif
        <div class="panel-body">
            <div class="position-center">
                <form role="form" action="{{ url(path: '/save-service') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Mã dịch vụ</label>
                        <input type="text" name="service_code" class="form-control" placeholder="Nhập mã dịch vụ" required>
                    </div>

                    <div class="form-group">
                        <label>Tên dịch vụ</label>
                        <input type="text" name="service_name" class="form-control" placeholder="Nhập tên dịch vụ" required>
                    </div>

                    <div class="form-group">
                        <label>Giá</label>
                        <input type="number" name="service_price" class="form-control" placeholder="Nhập giá dịch vụ" required>
                    </div>

                    <button type="submit" class="btn btn-info">Thêm dịch vụ</button>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection
