@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
    <div class="panel panel-default">
      <div class="panel-heading">
        Danh sách kho thuốc
      </div>
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
      <div class="row w3-res-tb">
        <div class="col-sm-5 m-b-xs">
          <a href="{{ url('/add-medicine')}}" class="btn btn-primary">Thêm thuốc</a>             
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-3">
          <div class="input-group">
            <input type="text" class="input-sm form-control" placeholder="Tìm kiếm thuốc">
            <span class="input-group-btn">
              <button class="btn btn-sm btn-default" type="button">Tìm kiếm</button>
            </span>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th>STT</th>
              <th>Mã thuốc</th>
              <th>Tên thuốc</th>
              <th>Loại thuốc</th>
              <th>Giá nhập</th>
              <th>Giá bán</th>
              <th>Số lượng</th>
              <th>Đơn vị</th>
              <th>Mô tả</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($list_medicine as $key => $medicine)
              <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $medicine->code }}</td>
                  <td>{{ $medicine->name }}</td>
                  <td>{{ $medicine->type }}</td>
                  <td>{{ number_format($medicine->price) }} VNĐ</td>
                  <td>{{ number_format($medicine->sale_price) }} VNĐ</td>
                  <td>{{ $medicine->quantity }}</td>
                  <td>{{ $medicine->medicine_unit }}</td>
                  <td>{{ $medicine->description }}</td>
                  <td>
                      <a href="{{ url('/edit-medicine/' . $medicine->id) }}" class="btn btn-sm btn-info">Sửa</a>
                      <a href="javascript:void(0);" onclick="confirmDelete({{ $medicine->id }})" class="btn btn-sm btn-danger">Xóa</a>
                  </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <footer class="panel-footer">
      </footer>
    </div>
  </div>
  <script>
    function confirmDelete(id) {
        Swal.fire({
            title: "Bạn có chắc chắn muốn xóa?",
            text: "Hành động này không thể hoàn tác!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Xóa ngay!",
            cancelButtonText: "Hủy",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/delete-medicine/" + id;
            }
        });
    }
  </script>
@endsection
