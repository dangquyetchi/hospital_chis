<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\CssSelector\Node\FunctionNode;

session_start();

class ClinicController extends Controller
{
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }
    public function addClinic(){
        $this->authLogin();

        $services = DB::table('services')->orderBy('id', 'desc')->get();
        $rooms = DB::table('rooms')->orderBy('id', 'desc')->get();
        $doctors = DB::table('doctors')->orderBy('id', 'desc')->get();
        return view('admin.addClinic')->with(compact('services', 'rooms', 'doctors'));

    }
    public function listClinic(){
        $this->authLogin();
        $list_clinic = DB::table('medical_records') 
        ->leftJoin('rooms', 'medical_records.room_id', '=', 'rooms.id')
        ->select('medical_records.*', 'rooms.name as room_name') 
        ->paginate(5); 
        return view('admin.listclinic')->with('list_clinic', $list_clinic); 
    }
    public function saveClinic(Request $request){
        $this->authLogin();

            // $latestPatient = DB::table('patients')->orderBy('id', 'desc')->first();

            // if ($latestPatient) {
            //     $latestId = intval(substr($latestPatient->patient_id, 2)); // Cắt bỏ "BN"
            //     $newId = 'BN' . str_pad($latestId + 1, 2, '0', STR_PAD_LEFT); // Tăng lên 1
            // } else {
            //     $newId = 'BN01';    
            // }
        $data = [
            'patient_name' => $request->patient_name,
            'gender' => $request->patient_gender,
            'birth_date' => $request->birth_date,
            'examination_date' => $request->examination_date,
            'diagnosis' => $request->diagnosis,
            'price_exam' => $request->price_exam,
            'room_id' => $request->room_id, 
            'doctor_id' => $request->doctor_id,
            'status' => 0,
            'payment_status' => 0, 
        ];

        DB::table('medical_records')->insert($data);
        Session::put('message', 'Thêm giấy khám bệnh thành công');
        return Redirect::to('list-clinic');

    }
    public function editClinic($clinic_id) {
        $this->authLogin();
        $rooms = DB::table('rooms')->get(); 
        $edit_clinic = DB::table('medical_records')
        ->where('medical_records.id', $clinic_id)
        ->leftJoin('rooms', 'medical_records.room_id' , '=', 'rooms.id')
        ->select('medical_records.*', 'rooms.name as room_name')
        ->first();
        return view('admin.editclinic', compact('edit_clinic', 'rooms'));
    }
    public function updateClinic(Request $request, $clinic_id) {
        $this->authLogin();
        $data = [
            'patient_name' => $request->patient_name,
            'gender' => $request->patient_gender,
            'birth_date' => $request->birth_date,
            'examination_date' => $request->examination_date,
            'price_exam' => $request->price_exam,
            'diagnosis' => $request->diagnosis,
            'room_id' => $request->doctor_room,
        ];

        DB::table('medical_records')->where('id', $clinic_id)->update($data);
        Session::put('message', 'Cập nhật giấy khám bệnh thành công');
        return Redirect::to('list-clinic');
    }
    public function deleteClinic($clinic_id) {
        $this->authLogin();
        $del_clinic = DB::table('medical_records')->where('id', $clinic_id)->delete();
        Session::put('message', 'xóa giấy khám thành công');
        return Redirect::to('list-clinic');
    }
    public function printClinic($id)
    {
        $clinic = DB::table('medical_records')
            ->join('rooms', 'medical_records.room_id', '=', 'rooms.id')
            ->select('medical_records.*', 'rooms.name as room_name')
            ->where('medical_records.id', $id)
            ->first();

        if (!$clinic) {
            return redirect()->back()->with('error', 'Giấy khám bệnh không tồn tại!');
        }
        if ($clinic->payment_status == 0) {
            return redirect()->back()->with('error', 'Vui lòng cập nhật thanh toán trước khi in');
        }
        return view('admin.printClinic', compact('clinic'));
    }
    public function updatePaymentStatus($clinic_id, $status) {
        $this->authLogin();
        
        DB::table('medical_records')
            ->where('id', $clinic_id)
            ->update(['payment_status' => $status]);
    
        Session::put('message', 'Cập nhật trạng thái thành công');
        return Redirect::to('list-clinic');
    }

    public function searchClinic(Request $request)
    {
        $query = $request->input('query');
        $searchType = $request->input('search_type'); 

        $list_clinic = DB::table('medical_records')
            ->join('rooms', 'medical_records.room_id', '=', 'rooms.id')
            ->select('medical_records.*', 'rooms.name as room_name');

        // Kiểm tra search_type để tìm đúng cột
        if ($searchType == "patient_name") {
            $list_clinic->where('medical_records.patient_name', 'LIKE', "%$query%");
        } elseif ($searchType == "gender") {
            $list_clinic->where('medical_records.gender', 'LIKE', "%$query%");
        } elseif ($searchType == "diagnosis") {
            $list_clinic->where('medical_records.diagnosis', 'LIKE', "%$query%");
        } elseif ($searchType == "room_name") {
            $list_clinic->where('rooms.name', 'LIKE', "%$query%");
        } elseif ($searchType == "examination_date") {
            $list_clinic->where('medical_records.examination_date', 'LIKE', "%$query%");
        } elseif ($searchType == "status") {
            $list_clinic->where('medical_records.status', $query);
        } elseif ($searchType == "payment_status") {
            $list_clinic->where('medical_records.payment_status', $query);
        }
        $list_clinic = $list_clinic->paginate(5);
        return view('admin.listclinic', compact('list_clinic'));
    }

}