<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }
    public function listPatient() {
        $this->authLogin();
        $list_patient = DB::table('patients')
        ->orderBy('patients.id', 'desc')  
        ->paginate(5);
        return view('admin.listpatient')->with('list_patient', $list_patient);
    }
    public function addPatient(){
        $this->authLogin();
        return view('admin.addpatient');
    }
    public function savePatient(Request $request) {
        $this->authLogin();
        // Kiểm tra dữ liệu đầu vào
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
        // bảng patients
        $patient_id = DB::table('patients')->insertGetId([
            'name' => $request->patient_name,
            'gender' => $request->patient_gender,
            'birth_date' => $request->patient_birth,
            'address' => $request->patient_address,
            'patient_condition' => $request->patient_condition,
            'date_in' => $request->patient_datein,
            'status' => 1,
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
                'patient_id' => $patient_id,
                'patient_name' => $request->patient_name,
                'card_number' => $request->card_number,
                'issue_date' => $request->issue_date,
                'expiry_date' => $request->expiry_date,
                'insurance_type' => $request->insurance_type,
                'coverage_rate' => $coverage_rate,
                'status' =>  $status, 
                
            ]);
        }
        Session::put('message', 'Thêm bệnh nhân thành công');
        return Redirect::to('list-patient');
    }
    public function editPatient($patient_id) {
        $this->authLogin();
        $edit_patient = DB::table('patients')->where('id', $patient_id)->first();
        return view('admin.editpatient', compact('edit_patient'));
    }   
    public function updatePatient(Request $request, $patient_id) {
        $this->authLogin();
        $data = [
            'name' => $request->patient_name,
            'gender' => $request->patient_gender,
            'birth_date' => $request->patient_birth,
            'address' => $request->patient_address,
            'status' => 1,
        ];
        DB::table('patients')->where('id', $patient_id)->update($data);
        DB::table('health_insurances')->where('patient_id', $patient_id)->update([
            'patient_name' => $request->patient_name,
        ]);
        Session::put('message', 'Cập nhật bệnh nhân thành công');
        return Redirect::to('list-patient');
    }
    public function deletePatient($patient_id) {
        $this->authLogin();
        DB::table('patients')->where('id', $patient_id)->delete();
        Session::put('message', 'xóa bệnh nhân thành công');
        return Redirect::to('list-patient');
    }
    //cập nhật trạng thái bệnh nhân ra viện hay chưa
    public function outPatient($patient_update_id){
        $this->authLogin();
    
        DB::table('patients')
            ->where('id', $patient_update_id)
            ->update([
                'status' => 0,
                'date_out' => Carbon::now()->toDateString()
            ]);
    
        Session::put('message', 'Cập nhật trạng thái thành công');
        return Redirect::to('list-patient');
    }
    public function inPatient($patient_update_id){
        $this->authLogin();

        DB::table('patients')
        ->where('id', $patient_update_id)
        ->update([
            'status' => 1,
            'date_out' => null
        ]);
        Session::put('message', 'Cập nhật trạng thái thành công');
        return Redirect::to('list-patient');
    }
    // tìm kiếm bệnh nhân
    public function searchPatient(Request $request) 
    {
       $this->authLogin();
        $keyword = $request->input('keyword');
        $list_patient = DB::table('patients')
                        ->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('id', 'LIKE', "%$keyword%")
                        ->paginate(5);
        return view('admin.listpatient', compact('list_patient'));
    }
}