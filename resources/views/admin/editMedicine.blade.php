@extends('admin_layout')
@section('admin_content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Cập nhật thuốc</h4>
                </div>
                <div class="card-body">
                    @if(Session::has('message'))
                        <div class="alert alert-success">
                            {{ Session::get('message') }}
                        </div>
                        {{ Session::put('message', null) }}
                    @endif

                    @foreach ($edit_medicine as $edit_value)
                    <form action="{{ url('/update-medicine/'.$edit_value->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="Codemedicine" class="form-label">Mã thuốc</label>
                            <input type="text" value="{{ $edit_value->code }}" name="medicine_code" class="form-control" id="Codemedicine">
                        </div>
                        <div class="mb-3">
                            <label for="Namemedicine" class="form-label">Tên thuốc</label>
                            <input type="text" value="{{ $edit_value->name }}" name="medicine_name" class="form-control" id="Namemedicine">
                        </div>
                        <div class="mb-3">
                            <label for="Typemedicine" class="form-label">Loại thuốc</label>
                            <input type="text" value="{{ $edit_value->type }}" name="medicine_type" class="form-control" id="Typemedicine">
                        </div>
                        <div class="mb-3">
                            <label for="Pricemedicine" class="form-label">Giá nhập</label>
                            <input type="text" value="{{ $edit_value->price }}" name="medicine_price" class="form-control" id="Pricemedicine">
                        </div>
                        <div class="mb-3">
                            <label for="Pricemedicine_out" class="form-label">Giá bán</label>
                            <input type="text" value="{{ $edit_value->sale_price }}" name="medicine_price_out" class="form-control" id="Pricemedicine_out">
                        </div>
                        <div class="mb-3">
                            <label for="Quantitymedicine" class="form-label">Số lượng</label>
                            <input type="number" value="{{ $edit_value->quantity }}" name="medicine_quantity" class="form-control" id="Quantitymedicine" min="1">
                        </div>
                        <div class="mb-3">
                            <label for="Medicineunit" class="form-label">Đơn vị</label>
                            <select name="medicine_unit" id="medicine_unit" class="form-control">
                                <option value="Viên" {{ $edit_value->medicine_unit == 'Viên' ? 'selected' : '' }}>Viên</option>
                                <option value="Vỉ" {{ $edit_value->medicine_unit == 'Vỉ' ? 'selected' : '' }} >Vỉ</option>
                                <option value="Hộp" {{ $edit_value->medicine_unit == 'Hộp' ? 'selected' : '' }} >Hộp</option>
                                <option value="Lọ" {{ $edit_value->medicine_unit == 'Lọ' ? 'selected' : '' }} >Lọ</option>
                                <option value="Chai" {{ $edit_value->medicine_unit == 'Chai' ? 'selected' : '' }} >Chai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="Descriptionmedicine" class="form-label">Mô tả</label>
                            <input type="text" value="{{ $edit_value->description }}" name="medicine_description" class="form-control" id="Descriptionmedicine">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                        </div>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
