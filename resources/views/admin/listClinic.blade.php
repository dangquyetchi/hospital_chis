@extends('admin_layout')
@section('admin_content')
<style>
  .popup-container {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.popup {
    background: white;
    padding: 80px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
}
</style>
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
        <div class="col-sm-3">
        </div>
        <form action="/search-clinic" method="GET">
          <input type="text"  id="queryInput"  name="query" placeholder="Nhập từ khóa">
          <select name="search_type" id="searchType" onchange="changeInputType()">
              <option value="patient_name">Tên bệnh nhân</option>
              <option value="gender">Giới tính</option>
              <option value="diagnosis">Chẩn đoán</option>
              <option value="room_name">Phòng khám</option>
              <option value="examination_date">Ngày khám</option>
              <option value="status">Trạng thái</option>
              <option value="payment_status">Thanh toán</option>
          </select>
          <button type="submit">Tìm kiếm</button>
        </form>
      </div>
      <div class="table-responsive">
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              {{-- <th style="width:20px;">
                <label class="i-checks m-b-none">
                  <input type="checkbox"><i></i>
                </label>
              </th> --}}
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
                  {{-- <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td> --}}
                  <td>{{ $loop->iteration }}</td>
                  {{-- <td>{{ $record->id }}</td> --}}
                  <td>{{ $record->patient_name }}</td>
                  <td>{{ $record->gender }}</td>
                  <td>{{ $record->diagnosis }}</td>
                  <td>{{ $record->room_name ?? 'Chưa có' }}</td>
                  <td>{{ date('d-m-Y', strtotime($record->examination_date ))}}</td>
                  <td>{{ number_format($record->price_exam) }} VNĐ</td>
                  <td>
                      @if($record->status == 0)
                          <span class="badge bg-warning">Chưa khám</span>
                      @else
                          <span class="badge bg-success">Đã khám</span>
                      @endif
                  </td>
                  <td>
                    @if($record->payment_status == 0)
                        <a href="javascript:void(0);" 
                           class="badge bg-danger" 
                           onclick="confirmPaymentPopup({{ $record->id }}, 1)">
                            Chưa thanh toán
                        </a>
                    @else
                        <a href="javascript:void(0);" 
                           class="badge bg-primary" 
                           onclick="confirmPaymentPopup({{ $record->id }}, 0)">
                            Đã thanh toán
                        </a>
                    @endif
                </td>
                <td>
                    <a href="{{ url('/edit-clinic/' . $record->id) }}" class="btn btn-sm btn-info">Sửa</a>
                    <a href="javascript:void(0);" onclick="confirmDelete({{ $record->id }})" class="btn btn-sm btn-danger">Xóa</a>
                    <a href="{{ url('/print-clinic/' . $record->id) }}">
                      <i class="fa-solid fa-print" style="font-size: 20px;"></i>
                    </a>                    
                </td>
              </tr>
            @endforeach

            
          </tbody>
        </table>
      </div>
      <footer class="panel-footer">
        <div class="row">
          <div class="col-sm-5 text-center">
            <small class="text-muted inline m-t-sm m-b-sm">Hiển thị {{ $list_clinic->count() }} giấy</small>
          </div>
          <div class="col-sm-7 text-right text-center-xs" style="font-size: 10px;  padding: 3px 8px;">                
            <div class="pagination">
              {{ $list_clinic->links('pagination::bootstrap-4') }}
          </div>           
          </div>
        </div>
      </footer>
      
    </div>
  </div>
</div>
<div id="confirmPopup" class="popup-container">
  <div class="popup">
      <p id="popupMessage"></p>
      <button class="btn btn-primary" onclick="proceedPayment()">Xác nhận</button>
      <button class="btn btn-danger" onclick="closePopup()">Hủy</button>
  </div>
</div>

@endsection
  <script>
    let currentClinicId, currentStatus;
    function confirmPaymentPopup(clinicId, newStatus) {
        currentClinicId = clinicId;
        currentStatus = newStatus;
        let message = newStatus === 1 
            ? "Bạn có chắc muốn xác nhận đã thanh toán không?" 
            : "Bạn có chắc muốn hủy trạng thái thanh toán không?";

        document.getElementById("popupMessage").innerText = message;
        document.getElementById("confirmPopup").style.display = "flex";
    }
    function proceedPayment() {
        window.location.href = "/clinic/payment/" + currentClinicId + "/" + currentStatus;
    }
    function closePopup() { 
        document.getElementById("confirmPopup").style.display = "none";
    }

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
                window.location.href = "/delete-clinic/" + id;
            }
        });
    }
</script>

<script>
  function changeInputType() {
      let searchType = document.getElementById("searchType").value;
      let queryInput = document.getElementById("queryInput");
  
      // Xóa input cũ
      queryInput.outerHTML = '';
  
      // Tạo input mới
      let newInput;
      if (searchType === "gender") {
          newInput = `<select id="queryInput" name="query">
                          <option value="Nam">Nam</option>
                          <option value="Nữ">Nữ</option>
                      </select>`;
      } else if (searchType === "examination_date") {
          newInput = `<input type="date" id="queryInput" name="query">`;
      } else if (searchType === "status") {
          newInput = `<select id="queryInput" name="query">
                          <option value="1">Đã khám</option>
                          <option value="0">Chưa khám</option>
                      </select>`;
      } else if (searchType === "payment_status") {
          newInput = `<select id="queryInput" name="query">
                          <option value="1">Đã thanh toán</option>
                          <option value="0">Chưa thanh toán</option>
                      </select>`;
      } else {
          newInput = `<input type="text" id="queryInput" name="query" placeholder="Nhập từ khóa">`;
      }
  
      // Thêm input mới vào form
      document.querySelector("form").insertAdjacentHTML("afterbegin", newInput);
  }
  </script>
