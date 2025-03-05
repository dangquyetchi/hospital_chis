@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
    <div class="panel panel-default">
      <div class="panel-heading">
        Danh sách bác sĩ
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
          <a href="{{ url('/add-doctor')}}" class="btn btn-primary">Thêm bác sĩ</a>             
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-3">
          <form action="{{ url('/search-doctor') }}" method="GET">
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
              <th>Họ và Tên</th>
              <th>Giới tính</th>
              <th>Phòng</th>
              <th>Chức vụ</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($list_doctor as $key => $doctor)
              <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $doctor->name }}</td>
                  <td>{{ $doctor->gender }}</td>
                  <td>{{ $doctor->room_name }}</td>
                  <td>{{ $doctor->position }}</td>
                  <td>
                      <a href="{{ url('/edit-doctor/' . $doctor->id) }}" class="btn btn-sm btn-info">Sửa</a>
                      <a href="javascript:void(0);" onclick="confirmDelete({{ $doctor->id }})" class="btn btn-sm btn-danger">Xóa</a>
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
                window.location.href = "/delete-doctor/" + id;
            }
        });
    }
  </script>
@endsection
