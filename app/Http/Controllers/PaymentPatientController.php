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

class PaymentPatientController extends Controller
{
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }
    public function listPaymentPatient() {
        $this->authLogin();
            $list_payment = DB::table(table: 'payment_inpatient')
            ->leftJoin('patients', 'payment_inpatient.patient_id', '=', 'patients.id')
            ->leftJoin('health_insurances', 'patients.medical_id', '=', 'health_insurances.medical_id')
            ->select(
                
                'health_insurances.coverage_rate as coverage_rate',
                'patients.name as patient_name',
                'patients.birth_date as birth_date',
                'patients.date_in as date_in',
                'patients.date_out as date_out',
                'payment_inpatient.*'
            )
            ->orderBy('payment_inpatient.id', 'desc') 
            ->paginate(5);
        return view('admin.payment_patient')->with('list_payment', $list_payment);
    }
    public function ProcessPaymentPatient(Request $request) {
        $request->validate([
            'pay_patient_id' => 'required|integer',
            'payment_method' => 'required|string'
        ]);
        
        $payment = DB::table('payment_inpatient')->where('id', $request->pay_patient_id)->first();
        if (!$payment) {
            return response()->json(['error' => 'Không tìm thấy thông tin thanh toán!'], 404);
        }
        DB::table('payment_history_patient')->insert([
            'pay_patient_id' => $request->pay_patient_id,
            'patient_id' => $payment->patient_id,
            'amount_paid' => $payment->total_amount,
            'payment_method' => $request->payment_method,   
            'payment_date' => now(),
            'created_at' => now(),
        ]);
        DB::table('payment_inpatient')->where('id', $request->pay_patient_id)->update([
            'payment_status' => 1,
        ]);
        return response()->json(['message' => 'Thanh toán thành công!']);
    }
    public function printInvoice(Request $request)
    {
        // Lấy thông tin thanh toán
        $payment = DB::table('payment_inpatient')
        ->leftJoin('patients', 'patients.id', '=', 'payment_inpatient.patient_id')
        ->leftJoin('payment_history_patient', 'payment_history_patient.pay_patient_id', '=', 'payment_inpatient.id')
        ->leftJoin('health_insurances', 'patients.medical_id', '=', 'health_insurances.medical_id')
        ->where('payment_inpatient.id', $request->id)
        ->select(
            'payment_inpatient.*', 
            'patients.name as patient_name', 
            'patients.birth_date as birth_date',
            'payment_history_patient.payment_method as payment_method',
            'health_insurances.coverage_rate as coverage_rate',
        )
        ->first();
        if (!$payment) {
            return redirect()->back()->with('error', 'Không tìm thấy hóa đơn');
        }
        $pdf = PDF::loadView('admin.printbenhnhan', compact('payment'));
        return $pdf->stream('hoa_don_noi_tru.pdf');
    }
}