@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
    <div class="panel panel-default">
      <div class="panel-heading">
        Danh sách dịch vụ khám
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
          <a href="{{ url('/add-service')}}" class="btn btn-primary">Thêm dịch vụ khám</a>             
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-3">
          <form action="{{ url('/search-service') }}" method="GET">
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
              <th>Mã dịch vụ</th>
              <th>Dịch vụ khám</th>
              <th>Giá</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($list_service as $key => $service)
              <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $service->code }}</td>
                  <td>{{ $service->name }}</td>
                  <td>{{ number_format($service->price) }} VNĐ</td>
                  <td>
                      <a href="{{ url('/edit-service/' . $service->id) }}" class="btn btn-sm btn-info">Sửa</a>
                      <a href="javascript:void(0);" onclick="confirmDelete({{ $service->id }})" class="btn btn-sm btn-danger">Xóa</a>
                  </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <footer class="panel-footer">
        {{-- <div class="row">
          <div class="col-sm-5 text-center">
            <small class="text-muted inline m-t-sm m-b-sm">Hiển thị {{ $list_room->count() }} phòng</small>
          </div>
          {{-- <div class="col-sm-7 text-right text-center-xs">                
            {{ $list_room->links() }}
          </div>
        </div> --}}
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
                window.location.href = "/delete-service/" + id;
            }
        });
    }
  </script>
@endsection
