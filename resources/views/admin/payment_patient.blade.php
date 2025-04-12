@extends('admin_layout')
@section('admin_content')

<div class="table-agile-info">
    <div class="panel panel-default">
        <div class="panel-heading">
            Thanh toán nội trú
        </div>

        <div class="table-responsive">
            <table class="table table-striped b-t b-light">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Họ và Tên</th>
                        <th>Ngày sinh</th>
                        <th>Phương thức</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list_payment as $key => $payment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $payment->patient_name }}</td>
                        <td>{{ date('d-m-Y', strtotime($payment->birth_date)) }}</td>
                        <td>
                            <span id="status-{{ $payment->id }}">
                                @if ($payment->payment_status == 0)
                                    <span class="text-danger">Chưa thanh toán</span>
                                @else
                                    <span class="text-success">Đã thanh toán</span>
                                @endif
                            </span>
                        </td>
                        <td>
                            @if ($payment->payment_status == 0)
                            <button onclick="openPaymentPopup(
                                {{ $payment->id }}, 
                                '{{ $payment->patient_name }}', 
                                '{{ date('d-m-Y', strtotime($payment->birth_date)) }}', 
                                '{{ date('d-m-Y', strtotime($payment->date_in)) }}',
                                '{{ date('d-m-Y', strtotime($payment->date_out)) }}',
                               @if($payment->coverage_rate > 0)
                                    '{{ number_format($payment->total_amount - ($payment->total_amount * ($payment->coverage_rate/100)), 0, ',', '.') }} VNĐ'
                                @else
                                    '{{ number_format($payment->total_amount, 0, ',', '.') }} VNĐ'
                                @endif
                                )" 
                                class="btn btn-success btn-sm">
                                Thanh Toán
                            </button>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>Đã thanh toán</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <footer class="panel-footer">
            <div class="row">
                <div class="col-sm-5 text-center">
                    <small class="text-muted inline m-t-sm m-b-sm"></small>
                </div>
                <div class="col-sm-7 text-right text-center-xs" style="font-size: 10px;  padding: 3px 8px;">                
                    <div class="pagination">
                      {{ $list_payment->links('pagination::bootstrap-4') }}
                    </div>           
                  </div>
            </div>
        </footer>
    </div>
</div>


<!-- Popup thanh toán -->
<div id="paymentPopup" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Xác nhận thanh toán</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <p><strong>Họ và Tên:</strong> <span id="popupPatientName"></span></p>
            <p><strong>Ngày Sinh:</strong> <span id="popupBirthDate"></span></p>
            <p><strong>Ngày vào viện:</strong> <span id="popupDateIn"></span></p>
            <p><strong>Ngày ra viện:</strong> <span id="popupDateOut"></span></p>
            <p><strong>Tổng tiền:</strong> <span id="popupPriceMedical"></span></p>
              <div class="form-group">
                <label for="paymentMethod"><strong>Phương thức thanh toán:</strong></label>
                <select id="paymentMethod" class="form-control">
                    <option value="Tiền mặt">Tiền mặt</option>
                    <option value="QRCODE">QRCODE</option>
                </select>
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
              <button id="confirmPaymentBtn" class="btn btn-primary">Xác nhận thanh toán</button>
          </div>
      </div>
  </div>
</div>

<script>
    function openPaymentPopup(paymentId, patientName, birthDate, dateIn, dateOut, priceMedical) {
        document.getElementById('popupPatientName').innerText = patientName;
        document.getElementById('popupBirthDate').innerText = birthDate;
        document.getElementById('popupDateIn').innerText = dateIn ;
        document.getElementById('popupDateOut').innerText = dateOut;
        document.getElementById('popupPriceMedical').innerText = priceMedical;
        document.getElementById('confirmPaymentBtn').setAttribute('onclick', 'processPayment(' + paymentId + ')');
        
        $('#paymentPopup').modal('show'); 
    }

    function processPayment(paymentId) {
    let paymentMethod = document.getElementById('paymentMethod').value;
    let confirmBtn = document.getElementById('confirmPaymentBtn');

    $.ajax({
        url: '/process-payment-patient',
        type: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            pay_patient_id: paymentId,
            payment_method: paymentMethod
        },
        success: function(response) {
            alert(response.message);

            if (paymentMethod === 'Tiền mặt') {
                confirmBtn.innerText = "In hóa đơn";
                confirmBtn.setAttribute('onclick', `printInvoice(${paymentId})`);
            } else if (paymentMethod === 'QRCODE') {
                confirmBtn.innerText = "Tạo QR Code";
                confirmBtn.setAttribute('onclick', `generateQrCode(${paymentId})`);
            } else {
                $('#paymentPopup').modal('hide');
                location.reload();
            }
        },
        error: function(xhr) {
            alert(xhr.responseJSON.message);
        }
    });
}

    // Hàm in hóa đơn
    function printInvoice(paymentId) {
        window.open('/print-invoice-patient/' + paymentId, '_blank');
        setTimeout(function() {
        location.reload(); 
    }, 1000); 
    }

    function generateQrCode(paymentId) {
    window.location.href = '/view-qrcode/' + paymentId;
}

</script>

@endsection
