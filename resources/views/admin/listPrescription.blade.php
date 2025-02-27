@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
    <div class="panel panel-default">
      <div class="panel-heading">
        Danh sách đơn thuốc
      </div>
      <div class="row w3-res-tb">
        <?php
        $message = Session::get('message');
        if($message){
            echo '<span class="text-bold">'.$message. '</span>';
            Session::put('message', null);
        } 
        ?>
        <div class="col-sm-5 m-b-xs">
          <a href="{{ url('/add-prescription')}}" class="btn btn-primary">Thêm đơn thuốc</a>             
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-3">
          <div class="input-group">
            <input type="text" class="input-sm form-control" placeholder="Tìm kiếm đơn thuốc">
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
              <th>Tên bệnh nhân</th>
              <th>Ngày sinh</th>
              <th>Bác sĩ theo dõi</th>
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
