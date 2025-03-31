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
        ->orderBy('medical_records.id', 'desc')  
        ->paginate(5); 
        return view('admin.listclinic')->with('list_clinic', $list_clinic); 
    }
    public function saveClinic(Request $request){
        $this->authLogin();
        $request->validate([
            'card_number' => ['nullable', 'regex:/^[1-3][0-9]{14}$/'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:today'],
            'issue_date' => ['nullable', 'date', 'before_or_equal:today', 'before:expiry_date'],
            'date_in' => ['date', 'before_or_equal:today'],
        ], [
            'card_number.regex' => 'Số thẻ BHYT phải có đúng 15 số và bắt đầu bằng 1, 2 hoặc 3.',
            'expiry_date.after_or_equal' => 'Ngày hết hạn phải lớn hơn hoặc bằng hôm nay.',
            'issue_date.before_or_equal' => 'Ngày cấp phải nhỏ hơn hoặc bằng hôm nay.',
            'date_in.before_or_equal' => 'Ngày nhập viện phải nhỏ hơn hoặc bằng hôm nay.',
            'issue_date.before' => 'Ngày cấp phải nhỏ hơn ngày hết hạn.',
        ]);
         // Kiểm tra số thẻ BHYT đã tồn tại chưa
        if ($request->card_number) {
            $exists = DB::table('health_insurances')
                        ->where('card_number', $request->card_number)
                        ->exists();
            
            if ($exists) {
                return redirect()->back()->withInput()->withErrors(['card_number' => 'Số thẻ BHYT này đã tồn tại.']);
            }
        }
        $medical_id = DB::table('medical_records')->insertGetId([
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
        ]);
    
        DB::table('payments')->insert([
            'medical_id' => $medical_id,
            'price_medical' => $request->price_exam,
            'price_service' => 0,
            'price_prescription' => 0,
            'status' => 0, 
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if ($request->card_number) {
            $first_digit = substr($request->card_number, 0, 1); 
            $coverage_rate = 0; 
    
            if ($first_digit == '1') {
                $coverage_rate = 100;
            } elseif ($first_digit == '2') {
                $coverage_rate = 80;
            } elseif ($first_digit == '3') {
                $coverage_rate = 60;
            }

            $status = (strtotime($request->expiry_date) < strtotime(date('Y-m-d'))) ? 0 : 1;
            // bảng health_insurances
            DB::table('health_insurances')->insert([
                'medical_id' => $medical_id,
                'patient_name' => $request->patient_name,
                'card_number' => $request->card_number,
                'issue_date' => $request->issue_date,
                'expiry_date' => $request->expiry_date,
                'insurance_type' => $request->insurance_type,
                'coverage_rate' => $coverage_rate,
                'status' =>  $status, 
                
            ]);
        }
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
    
        $existsInPrescriptions = DB::table('prescriptions')->where('medical_id', $clinic_id)->exists();
        $existsInServiceRecords = DB::table('service_records')->where('medical_id', $clinic_id)->exists();
    
        if ($existsInPrescriptions || $existsInServiceRecords) {
            return redirect()->back()->with('error', 'Không thể xóa vì giấy khám đã được sử dụng trong đơn thuốc hoặc phiếu dịch vụ!');
        }
    
        DB::table('medical_records')->where('id', $clinic_id)->delete();
        Session::put('message', 'Xóa giấy khám thành công');
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