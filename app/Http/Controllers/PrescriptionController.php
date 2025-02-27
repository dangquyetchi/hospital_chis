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
        ->leftJoin('patients', 'prescriptions.patient_id', '=', 'patients.id')
        ->select('prescriptions.*',
                           'doctors.name as doctor_name',
                           'patients.name as patient_name',
                           'patients.birth_date as patient_date') 
        ->get();
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
        $patients = DB::table('patients')->get();
        return view('admin.addprescription', compact('doctors', 'patients'));
    }

    public function savePrescription(Request $request) {
        $this->authLogin();
        $data = [
            'doctor_id' => $request->doctor_room,
            'patient_id' => $request->name_id,
            'status' => 0,
        ];
        DB::table('prescriptions')->insert($data);
        Session::put('message', 'Thêm đơn thuốc thành công');
        return Redirect::to('list-prescription');
    }

    public function editPrescription($prescription_id) {
        $this->authLogin();
        $edit_prescription = DB::table('prescriptions')->where('id', $prescription_id)->first();
        $doctors = DB::table('doctors')->get();
        $patients = DB::table('patients')->get();
        if (!$edit_prescription) {
            return redirect()->back()->with('message', 'Không tìm thấy đơn thuốc.');
        }
        return view('admin.editprescription', compact("edit_prescription", "doctors", "patients"));
    }

    public function updatePrescription(Request $request, $precription_id) {
        $this->authLogin();
        $data = [
            'doctor_id' => $request->doctor_id,
            'patient_id' => $request->patient_id,
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

}