<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\Paginator;
use Barryvdh\DomPDF\Facade\Pdf;



class ServiceRecordController extends Controller
{
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function listRecordService() {
        $this->authLogin();
        $list_record_service = DB::table('service_records')
        ->leftJoin('doctors', 'service_records.doctor_id', '=', 'doctors.id')
        ->leftJoin('rooms', 'service_records.room_id', '=', 'rooms.id')
        ->leftJoin('patients', 'service_records.patient_id', '=', 'patients.id')
        ->select('service_records.*',
                           'doctors.name as doctor_name',
                            'rooms.name as room_name',
                           'patients.name as patient_name',
                           'patients.birth_date as patient_date') 
        ->get();                
        return view('admin.servicerecord')->with('list_record_service', $list_record_service);
    }

    public function addServiceRecord() {
        $this->authLogin();
        $doctors = DB::table('doctors')->get();
        $patients = DB::table('patients')->get();
        $rooms = DB::table('rooms')->get();
        $services = DB::table('services')->get();
        return view('admin.addservicerecord', compact('doctors', 'patients', 'rooms', 'services'));
    }

    public function saveServiceRecord(Request $request) {
        $this->authLogin();
        $data = [
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'room_id' => $request->room_id,
            'price' => 0,
            'payment_status' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        DB::table('service_records')->insert($data);
        Session::put('message', 'Thêm phiếu dịch vụ thành công');
        return Redirect::to('list-record-service');
    }

    public function editServiceRecord($service_record_id) {
        $this->authLogin();
        $doctors = DB::table('doctors')->get();
        $patients = DB::table('patients')->get();
        $rooms = DB::table('rooms')->get();
        $edit_service_record = DB::table('service_records')->where('id', $service_record_id)->first();
        return view('admin.editservicerecord', compact('doctors', 'patients', 'rooms', 'edit_service_record'));
    }

    public function updateServiceRecord(Request $request, $service_record_id) {
        $this->authLogin();
        $data = [
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'room_id' => $request->room_id,
            'price' => 0,
            'payment_status' => 0,
        ];
        DB::table('service_records')->where('id', $service_record_id)->update($data);
        Session::put('message', 'Cập nhật phiếu dịch vụ thành công');
        return Redirect::to('list-record-service');
    }

    public function deleteServiceRecord($service_record_id) {
        $this->authLogin();
        DB::table('service_records')->where('id', $service_record_id)->delete();
        Session::put('message', 'Xóa phiếu dịch vụ thành công');
        return Redirect::to('list-record-service');
    }

    public function detailService ($id) {
        $this->authLogin();
        $services = DB::table('services')->where('id', $id)->first();
        $servicess = DB::table('services')->get();
        $rooms = DB::table('rooms')->get();
        $service_records = DB::table('service_detail')
        ->leftJoin('rooms', 'service_detail.room_id', '=', 'rooms.id')
        ->leftJoin('services', 'service_detail.service_id', '=', 'services.id')
        ->where('service_record_id', $id)
        ->select('service_detail.*',
                            'services.name as service_name',
                            'rooms.name as room_name'
                           ) 
        ->get();
        return view('admin.detailservice', compact('services', 'servicess', 'rooms', 'service_records'));
    }

    public function printService($service_record_id) {
        $this->authLogin();

        $service_record = DB::table('service_records')
        ->leftJoin('doctors', 'service_records.doctor_id', '=', 'doctors.id')
        ->leftJoin('rooms', 'service_records.room_id', '=', 'rooms.id')
        ->leftJoin('patients', 'service_records.patient_id', '=', 'patients.id')
        ->select('service_records.*',
                           'doctors.name as doctor_name',
                            'rooms.name as room_name',
                           'patients.name as patient_name',
                           'patients.birth_date as patient_date') 
        ->where('service_records.id', $service_record_id)
        ->first();
        
        $service_detail = DB::table('service_detail')
        ->leftJoin('services', 'service_detail.service_id', '=', 'services.id')
        ->leftJoin('rooms', 'service_detail.room_id', '=', 'rooms.id')
        ->select('service_detail.*',
                           'services.name as service_name',
                            'rooms.name as room_name') 
        ->where('service_detail.service_record_id', $service_record_id)
        ->get();

        $pdf = PDF::loadView('admin.printservicerecord', compact( 'service_detail', 'service_record'));
        return $pdf->stream('phieu-dich-vu.pdf'); 
    }
}   