@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
    <div class="panel panel-default">
      <div class="panel-heading">
        Danh sách bác sĩ
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
          <a href="{{ url('/add-doctor')}}" class="btn btn-primary">Thêm bác sĩ</a>             
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-3">
          <div class="input-group">
            <input type="text" class="input-sm form-control" placeholder="Tìm kiếm bác sĩ">
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
                      <a onclick="return confirm('Xác nhận xóa?')" href="{{ url('/delete-doctor/' . $doctor->id) }}" class="btn btn-sm btn-danger">Xóa</a>
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
@endsection
