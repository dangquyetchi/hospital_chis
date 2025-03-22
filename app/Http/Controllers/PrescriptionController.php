<?php

namespace App\Http\Controllers;

use Faker\Extension\CompanyExtension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Barryvdh\DomPDF\Facade\Pdf;


class PrescriptionController extends Controller

{   
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function listPrescription() {
        $this->authLogin();
        $list_prescription = DB::table('prescriptions')
        ->leftJoin('doctors', 'prescriptions.doctor_id', '=', 'doctors.id')
        ->leftJoin('medical_records', 'prescriptions.medical_id', '=', 'medical_records.id')
        ->select('prescriptions.*',
                           'doctors.name as doctor_name',
                           'medical_records.patient_name as patient_name',
                           'medical_records.birth_date as birth_date') 
        ->paginate(5);
        return view('admin.listprescription')->with('list_prescription', $list_prescription);
    }

    public function detailPrescription($id) {
        $prescriptions = DB::table('prescriptions')->where('id', $id)->first();
        // $medicines_prescriptions = DB::table('medicines')->where('id', $id)->first();
        $medicines = DB::table('medicines')->get(); 
        $details = DB::table('prescription_medicines')
            ->join('medicines', 'prescription_medicines.medicine_id', '=', 'medicines.id')
            ->where('prescription_id', $id)
            ->select('prescription_medicines.*', 'medicines.name as medicine_name')
            ->get();
    
        return view('admin.detailprescription', compact('prescriptions', 'details', 'medicines'));
    }
    
    public function addPrescription() {
        $this->authLogin();
        $doctors = DB::table('doctors')->get();
        $patients = DB::table('medical_records')
                    ->where('payment_status', 1)
                    ->get();
        return view('admin.addprescription', compact('doctors', 'patients'));
    }

    public function savePrescription(Request $request) {
        $this->authLogin();
        $prescription = DB::table('prescriptions')->where('medical_id', $request->name_id)->first();
        if($prescription) {
            return redirect()->back()->with('error', 'Bệnh nhân đã có đơn thuốc!');
        }
        $data = [
            'doctor_id' => $request->doctor_room,
            'medical_id' => $request->name_id,
            'price' => 0,
            'status' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        DB::table('prescriptions')->insert($data);
        DB::table('service_records')
        ->where('id', $request->medical_id)
        ->update(['status' => 1]);
        Session::put('message', 'Thêm đơn thuốc thành công');
        return Redirect::to('list-prescription');
    }

    public function editPrescription($prescription_id) {
        $this->authLogin();
        $edit_prescription = DB::table('prescriptions')->where('id', $prescription_id)->first();
        $doctors = DB::table('doctors')->get();
        $patients = DB::table('medical_records')
                    ->where('payment_status', 1)
                    ->get();
        if (!$edit_prescription) {
            return redirect()->back()->with('message', 'Không tìm thấy đơn thuốc.');
        }
        return view('admin.editprescription', compact("edit_prescription", "doctors", "patients"));
    }

    public function updatePrescription(Request $request, $precription_id) {
        $this->authLogin();
        $data = [
            'doctor_id' => $request->doctor_id,
            'medical_id' => $request->medical_id,
            'status' => 0,
        ];
        DB::table('prescriptions')->where('id', $precription_id)->update($data);
        Session::put('message', 'Cập nhật đơn thuốc thành công');
        return Redirect::to('list-prescription');
    }

    public function deletePrescription($prescription_id) {
        $this->authLogin();
        $prescription = DB::table('prescriptions')->where('id', $prescription_id)->first();
        if (!$prescription) {
            Session::put('error', 'Không tìm thấy đơn thuốc!');
            return Redirect::back();
        }
        DB::table('prescriptions')->where('id', $prescription_id)->delete();
        Session::put('message', 'Xóa đơn thuốc thành công!');
        return Redirect::to('list-prescription');
    }

    public function searchPrescription(Request $request) {
        $this->authLogin();
        $search = $request->search;
        $prescriptions = DB::table('prescriptions')
            ->leftJoin('doctors', 'prescriptions.doctor_id', '=', 'doctors.id')
            ->leftJoin('patients', 'prescriptions.patient_id', '=', 'patients.id')
            ->select('prescriptions.*',
                'doctors.name as doctor_name',
                'patients.name as patient_name',
                'patients.birth_date as patient_date')
            ->where('patients.name', 'like', '%' . $search . '%')
            ->paginate(5);
        return view('admin.listprescription')->with('list_prescription', $prescriptions);
    }
    public function printPrescription($id): mixed
    {
        $prescription = DB::table('prescriptions')->where('id', $id)->first();

        $pres = DB::table('prescriptions')
        ->join('doctors', 'prescriptions.doctor_id', '=', 'doctors.id')
        ->join('patients', 'prescriptions.patient_id', '=', 'patients.id')
        ->where('prescriptions.id', $id)
        ->select('prescriptions.*', 'doctors.name as doctor_name', 'patients.name as patient_name')
        ->first();

        $details = DB::table('prescription_medicines')
        ->join('medicines', 'prescription_medicines.medicine_id', '=', 'medicines.id')
        ->where('prescription_medicines.prescription_id', $id)
        ->select('prescription_medicines.*',
                            'medicines.name as medicine_name')
        ->get();
        
        if (!$prescription) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn thuốc!');
        }
        $pdf = PDF::loadView('admin.printprescription', compact( 'details', 'pres'));
        return $pdf->stream('don-thuoc.pdf'); 
    }

}