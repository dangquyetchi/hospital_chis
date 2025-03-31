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



class PaymentPresController extends Controller
{
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function listPaymentPrescription() {
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
            ->where('payments.price_prescription','>', 0)
            ->orderBy('payments.id', 'desc') 
            ->paginate(5);
        return view('admin.payment_prescription')->with('list_payment', $list_payment);
    }

    public function ProcessPaymentPrescription(Request $request) {
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
            'payment_method' => $request->payment_method,
            'price_prescription' => $payment->price_prescription,
            'date_precription' => now(),
        ]);

        DB::table('payments')->where('id', $request->payment_id)->update([
            'status'      => 3,
            'updated_at'  => now(),
        ]);

        $medicalId = DB::table('payments')->where('id', $request->payment_id)->value('medical_id');
        DB::table('prescriptions')->where('medical_id', $medicalId)->update([
            'status' => 1,
        ]);
        return response()->json(['message' => 'Thanh toán thành công!']);
    }
}