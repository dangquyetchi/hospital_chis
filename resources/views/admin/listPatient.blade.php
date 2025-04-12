@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
    <div class="panel panel-default">
      <div class="panel-heading">
        Danh sách bệnh nhân
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
          <a href="{{ url('/add-patient')}}" class="btn btn-primary">Thêm bệnh nhân</a>             
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-3">
          <form action="{{ url('/search-patient') }}" method="GET">
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
                  <td>{{ date('d-m-Y', strtotime($patient->birth_date))}}</td>
                  <td>{{ $patient->address }}</td>
                  <td>{{ $patient->patient_condition }}</td>
                  <td>{{ date('d-m-Y', strtotime($patient->date_in)) }}</td>
                  <td>{{ $patient->date_out ? date('d-m-Y', strtotime($patient->date_out)) : '' }}</td>

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
                      <a href="javascript:void(0);" onclick="confirmDelete({{ $patient->id }})" class="btn btn-sm btn-danger">Xóa</a>
                  </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <footer class="panel-footer">
        <div class="row">
          <div class="col-sm-5 text-center">
            <small class="text-muted inline m-t-sm m-b-sm">Đang có {{ $list_patient->count() }} bệnh nhân</small>
          </div>
          <div class="col-sm-7 text-right text-center-xs" style="font-size: 10px;  padding: 3px 8px;">                
            <div class="pagination">
              {{ $list_patient->links('pagination::bootstrap-4') }}
            </div>           
          </div>
        </div>
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
                window.location.href = "/delete-patient/" + id;
            }
        });
    }
  </script>
@endsection
