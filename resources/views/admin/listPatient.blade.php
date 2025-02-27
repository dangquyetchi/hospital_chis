@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
    <div class="panel panel-default">
      <div class="panel-heading">
        Danh sách bệnh nhân
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
          <a href="{{ url('/add-patient')}}" class="btn btn-primary">Thêm bệnh nhân</a>             
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-3">
          <div class="input-group">
            <input type="text" class="input-sm form-control" placeholder="Tìm kiếm bệnh nhân">
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
              <th>Giới tính</th>
              <th>Ngày sinh</th>
              <th>Địa chỉ</th>
              <th>Tình trạng</th>
              <th>Ngày vào viện</th>
              <th>Ngày ra viện</th>
              <th>Trạng thái</th>              
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($list_patient as $key => $patient)
              <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $patient->name }}</td>
                  <td>{{ $patient->gender }}</td>
                  <td>{{ $patient->birth_date }}</td>
                  <td>{{ $patient->address }}</td>
                  <td>{{ $patient->patient_condition }}</td>
                  <td>{{ $patient->date_in }}</td>
                  <td>{{ $patient->date_out ?? "Chưa ra viện" }}</td>
                  <td>
                    <span class="text-ellipsis">
                        @if($patient->status == 1)
                            <a href="{{ url('/out-patient/' . $patient->id) }}" class="update-status text-warning fw-bold">
                                <span class="badge bg-warning">Chưa xuất viện</span>
                            </a>
                        @else
                            <a href="{{ url('/in-patient/' . $patient->id) }}" class="update-status text-success fw-bold">
                                <span class="badge bg-success">Đã xuất viện</span>
                            </a>
                        @endif
                    </span>
                </td>
                
                  <td>
                      <a href="{{ url('/edit-patient/' . $patient->id) }}" class="btn btn-sm btn-info">Sửa</a>
                      <a onclick="return confirm('Xác nhận xóa?')" href="{{ url('/delete-patient/' . $patient->id) }}" class="btn btn-sm btn-danger">Xóa</a>
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
