@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
    <div class="panel panel-default">
      <div class="panel-heading">
        Danh sách đơn thuốc
      </div>
      <div class="row w3-res-tb">
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
        <div class="col-sm-5 m-b-xs">
          <a href="{{ url('/add-prescription')}}" class="btn btn-primary">Thêm đơn thuốc</a>             
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-3">
          <form action="{{ url('/search-prescription') }}" method="GET">
            <div class="input-group">
                <input type="text" name="keyword" class="input-sm form-control" placeholder="Tìm kiếm theo tên">
                <span class="input-group-btn">
                    <button class="btn btn-sm btn-default" type="submit">Tìm kiếm</button>
                </span>
            </div>
          </form>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th>STT</th>
              <th>Tên bệnh nhân</th>
              <th>Ngày sinh</th>
              <th>Bác sĩ theo dõi</th>
              <th>Tổng tiền</th>
              <th>Trạng thái</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($list_prescription as $key => $prescription)
              <tr>
                  <td >{{ $loop->iteration }}</td>
                  <td>{{ $prescription->patient_name }}</td>
                  <td>{{ $prescription->patient_date }}</td>
                  <td>{{ $prescription->doctor_name }}</td>
                  <td>{{ number_format($prescription->total_medicine) }} VNĐ</td>
                  <td>
                    @if ($prescription->status == 0)
                      <span class="badge bg-warning">Chưa thanh toán</span>
                    @else
                      <span class="badge bg-success">Đã thanh toán</span>
                    @endif
                  </td>
                  <td>
                      <a href="{{ url('/edit-prescription/' . $prescription->id) }}" class="btn btn-sm btn-info">Sửa</a>
                      <a href="javascript:void(0);" onclick="confirmDelete({{ $prescription->id }})" class="btn btn-sm btn-danger">Xóa</a>
                      <a href="{{ url('/detail-prescription/' . $prescription->id) }}">
                        <i class="fa-solid fa-circle-info" style="font-size: 20px;"></i>
                      </a>
                    
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
                window.location.href = "/delete-prescription/" + id;
            }
        });
    }
  </script>
@endsection
