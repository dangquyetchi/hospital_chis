@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
    <div class="panel panel-default">
      <div class="panel-heading">
        Danh sách giường bệnh
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
          <a href="{{ url('/add-bed')}}" class="btn btn-primary">Thêm giường</a>             
        </div>
        <div class="col-sm-4">  
        </div>
        <div class="col-sm-3">
          <form action="{{ url('/search-room') }}" method="GET">
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
              <th>Mã giường</th>
              <th>Tên giường</th>
              <th>Thuộc phòng</th>
              <th>Tình trạng</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($list_bed as $key => $bed)
              <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $bed->code }}</td>
                  <td>{{ $bed->name_bed }}</td>
                  <td>
                    {{ $bed->room_name }}
                  </td>
                  <td>
                      @if($bed->tinhtrang == 1)
                          <span class="text-success">Đang sử dụng</span>
                      @else
                          <span class="text-danger">Chưa sử dụng</span>
                      @endif
                  <td>
                      <a href="{{ url('/edit-bed/' . $bed->id) }}" class="btn btn-sm btn-info">Sửa</a>
                      <a href="javascript:void(0);" onclick="confirmDelete({{ $bed->id }})" class="btn btn-sm btn-danger">Xóa</a>
                  </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <footer class="panel-footer">
        <div class="row">
          <div class="col-sm-5 text-center">
            <small class="text-muted inline m-t-sm m-b-sm">Hiển thị {{ $list_bed->count() }} giường</small>
          </div>
          <div class="col-sm-7 text-right text-center-xs" style="font-size: 10px;  padding: 3px 8px;">                
            <div class="pagination">
              {{ $list_bed->links('pagination::bootstrap-4') }}
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
                window.location.href = "/delete-bed/" + id;
            }
        });
    }
  </script>
@endsection
