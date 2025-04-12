<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\CssSelector\Node\FunctionNode;
use Barryvdh\DomPDF\Facade\Pdf;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\Png;


class PaymentServiceController extends Controller
{

    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }
    public function listPaymentService() {
        $this->authLogin();
            $list_payment = DB::table('payments')
            ->leftJoin('medical_records', 'payments.medical_id', '=', 'medical_records.id')
            ->leftJoin('service_records', 'payments.medical_id', '=', 'service_records.id')
            ->leftJoin('prescriptions', 'payments.medical_id', '=', 'prescriptions.id')
            ->leftJoin('health_insurances', 'payments.medical_id', '=', 'health_insurances.medical_id')
            ->select(
                'medical_records.patient_name', 
                'medical_records.birth_date',
                'medical_records.price_exam', 
                'service_records.price as service_price', 
                'prescriptions.price as medicine_price',
                'health_insurances.coverage_rate as coverage_rate',
                'payments.*'
            )
            ->where('payments.price_service','>', 0)
            ->orderBy('payments.id', 'desc') 
            ->paginate(5);
        return view('admin.payment_service')->with('list_payment', $list_payment);
    }

    public function ProcessPaymentService(Request $request) {
        $request->validate([
            'payment_id' => 'required|integer',
            'payment_method' => 'required|string'
        ]);

        $payment = DB::table('payments')->where('id', $request->payment_id)->first();

        if (!$payment) {
            return response()->json(['error' => 'Không tìm thấy thông tin thanh toán!'], 404);
        }
        DB::table('payment_history')
        ->where('payment_id', $payment->id)
        ->update([
            'price_service' => $payment->price_service,
            'date_service' => now(),
        ]);
        DB::table('payments')->where('id', $request->payment_id)->update([
            'status'             => 2,
            'updated_at'         => now(),
        ]);

        $medicalId = DB::table('payments')->where('id', $request->payment_id)->value('medical_id');
        DB::table('service_records')->where('medical_id', $medicalId)->update([
            'payment_status' => 1,
        ]);
        return response()->json(['message' => 'Thanh toán thành công!']);

    }

    public function printInvoice(Request $request)
    {
        // Lấy thông tin thanh toán
        $payment_service = DB::table('payments')
        ->leftJoin('medical_records', 'medical_records.id', '=', 'payments.medical_id')
        ->leftJoin('payment_history', 'payment_history.payment_id', '=', 'payments.id')
        ->leftJoin('health_insurances', 'medical_records.id', '=', 'health_insurances.medical_id')
        ->where('payments.id', $request->id)
        ->select(
            'payments.*', 
            'medical_records.patient_name as patient_name', 
            'medical_records.birth_date as birth_date',
            'payment_history.payment_method as payment_method',
            'health_insurances.coverage_rate as coverage_rate',
        )
        ->first();

        if (!$payment_service) {
            return redirect()->back()->with('error', 'Không tìm thấy hóa đơn');
        }
        $pdf = PDF::loadView('admin.printservice', compact('payment_service'));
        return $pdf->stream('hoa_don_dich_vu.pdf');
    }
}