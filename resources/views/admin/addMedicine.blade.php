@extends('admin_layout')
@section('admin_content')

<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            Thêm thuốc
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
                <form role="form" action="{{ url(path: '/save-medicine') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Mã thuốc</label>
                        <input type="text" name="medicine_code" class="form-control" placeholder="Nhập mã thuốc" required>
                    </div>

                    <div class="form-group">
                        <label>Tên thuốc</label>
                        <input type="text" name="medicine_name" class="form-control" placeholder="Nhập tên thuốc" required>
                    </div>

                    <div class="form-group">
                        <label>Loại thuốc</label>
                        <input type="text" name="medicine_type" class="form-control" placeholder="Nhập loại thuốc" required>
                    </div>

                    <div class="form-group">
                        <label>Giá nhập</label>
                        <input type="number" name="medicine_price" class="form-control" placeholder="Nhập giá thuốc" required>
                    </div>

                    <div class="form-group">
                        <label>Giá bán</label>
                        <input type="number" name="medicine_price_out" class="form-control" placeholder="Nhập giá thuốc" required>
                    </div>

                    <div class="form-group">
                        <label>Đơn vị</label>
                        <select name="medicine_unit" class="form-control" required>
                            <option value="Chọn đơn vị">Chọn đơn vị</option>
                            <option value="Viên">Viên</option>
                            <option value="Vỉ">Vỉ</option>
                            <option value="Hộp">Hộp</option>
                            <option value="Lọ">Lọ</option>
                            <option value="Chai">Chai</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Số lượng</label>
                        <input type="number" name="medicine_quantity" class="form-control" placeholder="Nhập số lượng thuốc" required min="1">
                    </div>

                    <div class="form-group">
                        <label>Mô tả</label>
                        <input type="text" name="medicine_description" class="form-control" placeholder="Nhập mô tả" required>
                    </div>

                    <button type="submit" class="btn btn-info">Thêm thuốc</button>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection
