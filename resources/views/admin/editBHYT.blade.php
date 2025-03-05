@extends('admin_layout')
@section('admin_content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Cập nhật thông tin BHYT</h4>
                </div>
                <div class="card-body">
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
                    <form action="{{ url('/update-bhyt/'.$edit_bhyt->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="card_number" class="form-label">Số thẻ BHYT</label>
                            <input type="text" value="{{ $edit_bhyt->card_number }}" name="card_number" class="form-control">
                        </div>
                        
                        <div class="mb-3">
                            <label for="issue_date" class="form-label">Ngày cấp</label>
                            <input type="date" value="{{ $edit_bhyt->issue_date }}" name="issue_date" class="form-control" id="issue_date">
                        </div>

                        <div class="mb-3">
                            <label for="expiry_date" class="form-label">Ngày hết hạn</label>
                            <input type="date" value="{{ $edit_bhyt->expiry_date }}" name="expiry_date" class="form-control" id="issue_date">
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
