@extends('admin_layout')

@section('admin_content')
<div class="container">
    <h2 class="text-center">Quản lý chi tiết đơn thuốc</h2>
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

    <form action="{{ url('/save-detailprescription/'.$prescriptions->id) }}" method="POST">
        @csrf
        <input type="hidden" name="prescription_id" value="{{ $prescriptions->id }}">
        <input type="hidden" name="id" id="edit_id">
        <div class="form-group">
            <label>Thuốc</label>
            <select name="medicine_id" id="medicine_id" class="form-control" required>
                <option value="">Chọn thuốc</option>
                @foreach($medicines as $medicine)
                    <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Số lượng</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
        </div>

        <div class="form-group">
            <label>Cách dùng</label>
            <textarea name="usage_instruction" id="usage_instruction" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>

    <h3 class="mt-4">Danh sách chi tiết đơn thuốc</h3>
    <table class="table table-bordered" style="background-color: white;">
        <thead>
            <tr>
                <td>STT</td>
                <th>Thuốc</th>
                <th>Số lượng</th>
                <th>Đơn vị</th>
                <th>Cách dùng</th>
                <th>Giá</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $key => $pm)
                <tr>
                    {{-- <td>{{ $pm->prescription_id }}</td> --}}
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pm->medicine_name }}</td>
                    <td>{{ $pm->quantity }}</td>
                    <td>{{ $pm->medicine_unit }}</td>
                    <td>{{ $pm->usage_instruction }}</td>
                    <td>{{ $pm->price }}</td>
                    <td>
                        <a href="javascript:void(0);" onclick="editMedicine({{ $pm->id }})" class="btn btn-sm btn-info">Sửa</a>
                        <a href="javascript:void(0);" onclick="confirmDelete({{ $pm->id }})" class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function editMedicine(id) {
    fetch(`/edit-prescription-detail/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_id').value = data.id; // Thêm ID
            document.getElementById('medicine_id').value = data.medicine_id;
            document.getElementById('quantity').value = data.quantity;
            document.getElementById('usage_instruction').value = data.usage_instruction;
        })
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
                window.location.href = "/delete-prescription-detail/" + id;
            }
        });
    }
</script>
@endsection
