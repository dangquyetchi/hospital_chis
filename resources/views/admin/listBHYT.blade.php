@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
    <div class="panel panel-default">
      <div class="panel-heading">
        Danh sách BHYT
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
        
        <div class="col-sm-3">
          <form action="{{ url('/search-bhyt') }}" method="GET">
            <div class="input-group">
                <input type="text" name="keyword" class="input-sm form-control" placeholder="Tìm kiếm theo mã thẻ hoặc tên">
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
              <th>Mã thẻ</th>
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
                  <td>{{ date('d-m-Y', strtotime($bhyt->issue_date))}}</td>
                  <td>{{ date('d-m-Y', strtotime($bhyt->expiry_date))}}</td>
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
        <div class="row">
          <div class="col-sm-5 text-center">
            <small class="text-muted inline m-t-sm m-b-sm"> </small>
          </div>
          <div class="col-sm-7 text-right text-center-xs" style="font-size: 10px;  padding: 3px 8px;">                
            <div class="pagination">
              {{ $list_bhyt->links('pagination::bootstrap-4') }}
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
                window.location.href = "/delete-bhyt/" + id;
            }
        });
    }
  </script>
@endsection
