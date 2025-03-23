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


class PaymentController extends Controller
{
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function listPayment() {
        $this->authLogin();
            $list_payment = DB::table('payments')
            ->leftJoin('medical_records', 'payments.medical_id', '=', 'medical_records.id')
            ->leftJoin('service_records', 'payments.medical_id', '=', 'service_records.id')
            ->leftJoin('prescriptions', 'payments.medical_id', '=', 'prescriptions.id')
            ->select(
                'medical_records.patient_name', 
                'medical_records.birth_date',
                'medical_records.price_exam', 
                'service_records.price as service_price', 
                'prescriptions.price as medicine_price',
                'payments.*'
            )
            ->orderBy('payments.id', 'desc') 
            ->paginate(5);
        return view('admin.payment')->with('list_payment', $list_payment);
    }

    public function ProcessPayment(Request $request) {
        $request->validate([
            'payment_id' => 'required|integer',
            'payment_method' => 'required|string'
        ]);

        $payment = DB::table('payments')->where('id', $request->payment_id)->first();

        if (!$payment) {
            return response()->json(['error' => 'Không tìm thấy thông tin thanh toán!'], 404);
        }
        DB::table('payment_history')->insert([
            'payment_id'         => $payment->id,
            'price_medical'      => $payment->price_medical,
            'price_service'      => $payment->price_service ?? 0,
            'price_prescription' => $payment->price_prescription ?? 0,
            'payment_method'     => $request->payment_method,
            'created_at'         => now()
        ]);

        DB::table('payments')
        ->where('id', $request->payment_id)
        ->update(['status' => 1]);
        
        $medicalId = DB::table('payments')->where('id', $request->payment_id)->value('medical_id');

            DB::table('medical_records')->where('id', $medicalId)->update(['payment_status' => 1]);
        
        return response()->json(['message' => 'Thanh toán thành công!']);
    }
    
    public function printInvoice(Request $request)
    {
        // Lấy thông tin thanh toán
        $payment = DB::table('payments')
        ->leftJoin('medical_records', 'payments.medical_id', '=', 'medical_records.id')
        ->where('payments.id', $request->id)
        ->select(
            'payments.*', 
            'medical_records.patient_name', 
            'medical_records.birth_date'
        )
        ->first();

        $method = DB::table('payment_history')
        ->where('payment_id', $request->id) // Đúng cột payment_id
        ->select('payment_method')
        ->first();

        if (!$payment) {
            return redirect()->back()->with('error', 'Không tìm thấy hóa đơn');
        }
        $pdf = PDF::loadView('admin.printkhambenh', compact('payment', 'method'));
        return $pdf->stream('hoa_don.pdf');
    }
}