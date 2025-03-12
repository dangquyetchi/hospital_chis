@extends('admin_layout')

@section('admin_content')
<div class="container">
    <h2 class="text-center">Quản lý chi tiết phiếu dịch vụ</h2>
    
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

    <form action="{{ url('/save-detailservice/'.$services->id) }}" method="POST">
        @csrf
        <input type="hidden" name="id" id="edit_id">
        <input type="hidden" name="service_record_id" value="{{ $services->id }}">

        <div class="form-group">
            <label>Dịch vụ khám</label> 
            <select name="service_id" id="service_id" class="form-control select2" required>
                <option value="">Chọn dịch vụ</option>
                @foreach($servicess as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Phòng khám</label>
            <select name="room_id" id="room_id" class="form-control select2" required>
                <option value="">Chọn phòng khám</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
    <h3 style="padding-top: 10px" class="mt-4"></h3>
    <table class="table table-bordered" style="background-color: white;">
        <thead>
            <tr>
                <td>STT</td>
                <th>Dịch vụ</th>
                <th>Phòng khám</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($service_records as $key => $pm)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pm->service_name }}</td>
                    <td>{{ $pm->room_name }}</td>
                    <td>
                        <a href="javascript:void(0);" onclick="editMedicine({{ $pm->id }})" class="btn btn-sm btn-info">Sửa</a>
                        <a href="javascript:void(0);" onclick="confirmDelete({{ $pm->id }})" class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Thêm jQuery và Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<script>
    $(document).ready(function() {
        $('#service_id').select2({
            placeholder: "Chọn dịch vụ",
            allowClear: true
        });
    });

    function editMedicine(id) {
        fetch(`/edit-service-detail/${id}`)
            .then(response => response.json())
            .then(data => {
                $('#edit_id').val(data.id);
                $('#service_id').val(data.service_id).trigger('change');
                $('#room_id').val(data.room_id).trigger('change');})
            .catch(error => console.error('Lỗi khi đổ dữ liệu:', error));
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
                window.location.href = "/delete-service-detail/" + id;
            }
        });
    }
</script>
@endsection
