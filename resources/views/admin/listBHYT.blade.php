@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
    <div class="panel panel-default">
      <div class="panel-heading">
        Danh sách BHYT
      </div>
      <div class="row w3-res-tb">
        <?php
        $message = Session::get('message');
        if($message){
            echo '<span class="text-bold">'.$message. '</span>';
            Session::put('message', null);
        } 
        ?>
        {{-- <div class="col-sm-5 m-b-xs">
          <a href="{{ url('/add-bhyt')}}" class="btn btn-primary">Thêm bảo hiểm</a>             
        </div> --}}
        {{-- <div class="col-sm-4">
        </div> --}}
        <div class="col-sm-3">
          <div class="input-group">
            <input type="text" class="input-sm form-control" placeholder="Tìm kiếm BHYT ">
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
              <th>Mã số</th>
              <th>Họ và Tên</th>
              <th>Ngày cấp</th>
              <th>Ngày hết hạn</th>
              <th>Loại thẻ</th>
              <th>Trạng thái</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($list_bhyt as $key => $bhyt)
              <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $bhyt->card_number }}</td>
                  <td>{{ $bhyt->patient_name}}</td>
                  <td>{{ $bhyt->issue_date }}</td>
                  <td>{{ $bhyt->expiry_date }}</td>
                  <td>{{ $bhyt->insurance_type }}</td>
                  <td>
                    @if($bhyt->status == 1)
                      <span class="badge bg-warning">Còn hạn</span>
                    @else
                      <span class="badge bg-success">Hết hạn</span>
                    @endif
                  </td>
                  <td>
                      <a href="{{ url('/edit-bhyt/' . $bhyt->id) }}" class="btn btn-sm btn-info">Sửa</a>
                      <a href="javascript:void(0);" onclick="confirmDelete({{ $bhyt->id }})" class="btn btn-sm btn-danger">Xóa</a>
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
                window.location.href = "/delete-bhyt/" + id;
            }
        });
    }
  </script>
@endsection
