@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
    <div class="panel panel-default">
      <div class="panel-heading">
        Danh sách giấy khám bệnh
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
          <a href="{{ url('/add-clinic')}}" class="btn btn-primary" >Thêm giấy khám</a>             
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-3">
          <div class="input-group">
            <input type="text" class="input-sm form-control" placeholder="Search">
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
              <th style="width:20px;">
                <label class="i-checks m-b-none">
                  <input type="checkbox"><i></i>
                </label>
              </th>
              <th>STT</th>
              {{-- <th>Mã</th> --}}
              <th>Tên bệnh nhân</th>
              <th>Giới tính</th>
              <th>Triệu trứng</th>
              <th>Phòng khám</th>
              <th>Ngày khám</th>
              <th>Giá khám</th>
              <th>Trạng thái</th>
              <th>Thanh toán</th>
              <th>Hàng động</th>
              <th style="width:30px;"></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($list_clinic as $key => $record)
              <tr>
                  <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
                  <td>{{ $loop->iteration }}</td>
                  {{-- <td>{{ $record->id }}</td> --}}
                  <td>{{ $record->patient_name }}</td>
                  <td>{{ $record->gender }}</td>
                  <td>{{ $record->diagnosis }}</td>
                  <td>{{ $record->room_name ?? 'Chưa có' }}</td>
                  <td>{{ $record->examination_date }}</td>
                  <td>{{ $record->price_exam }}</td>
                  <td>
                      @if($record->status == 0)
                          <span class="badge bg-warning">Chưa khám</span>
                      @else
                          <span class="badge bg-success">Đã khám</span>
                      @endif
                  </td>
                  <td>
                      @if($record->payment_status == 0)
                          <span class="badge bg-danger">Chưa thanh toán</span>
                      @else
                          <span class="badge bg-primary">Đã thanh toán</span>
                      @endif
                  </td>
                  <td>
                      <a href="{{ url('/edit-clinic/' . $record->id) }}" class="btn btn-sm btn-info">Sửa</a>
                      <a onclick="return confirm('Xác nhận xóa?')" href="{{ url('/delete-clinic/' . $record->id) }}" class="btn btn-sm btn-danger">Xóa</a>
                  </td>
              </tr>
            @endforeach

            
          </tbody>
        </table>
      </div>
      <footer class="panel-footer">
        {{-- <div class="row">
          
          <div class="col-sm-5 text-center">
            <small class="text-muted inline m-t-sm m-b-sm">showing 20-30 of 50 items</small>
          </div>
          <div class="col-sm-7 text-right text-center-xs">                
            <ul class="pagination pagination-sm m-t-none m-b-none">
              <li><a href=""><i class="fa fa-chevron-left"></i></a></li>
              <li><a href="">1</a></li>
              <li><a href="">2</a></li>
              <li><a href="">3</a></li>
              <li><a href="">4</a></li>
              <li><a href=""><i class="fa fa-chevron-right"></i></a></li>
            </ul>
          </div>
        </div> --}}
      </footer>
    </div>
  </div>
@endsection