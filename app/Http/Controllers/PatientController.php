<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Barryvdh\DomPDF\Facade\Pdf;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\Png;
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
        $medicalRecords = DB::table('medical_records')->select( 'id', 'patient_name', 'gender', 'birth_date')->get();
        $rooms = DB::table('rooms')->orderBy('id', 'desc')->get();
        $beds = DB::table('bed_patient')
        ->where('tinhtrang', 0)
        ->orderBy('id', 'desc')
        ->get();
        return view('admin.addpatient', compact('medicalRecords', 'rooms', 'beds'));
    }
    public function getBedsByRoom($room_id)
    {
        $beds = DB::table('bed_patient')
            ->where('room_id', $room_id)
            ->where('tinhtrang', 0)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($beds);
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
        
        $exists = DB::table('patients')
        ->where('medical_id', $request->medical_id)
        ->exists();
        if ($exists) {
        return redirect()->back()->withInput()->withErrors(['medical_id' => 'Bệnh nhân đã tồn tại!']);
        }

        $medicalRecord = DB::table('medical_records')->where('id', $request->medical_id)->first();
        // bảng patients
        $patient_id = DB::table('patients')->insertGetId([
            'medical_id' => $request->medical_id,
            'name' => $medicalRecord->patient_name,
            'gender' => $request->patient_gender,
            'birth_date' => $request->patient_birth,
            'address' => $request->patient_address,
            'patient_condition' => $request->patient_condition,
            'date_in' => $request->patient_datein,
            'room_id' => $request->room_id,
            'bed_id' => $request->bed_id,
            'status' => 1,
        ]);

        if ($request->bed_id) {
            DB::table('bed_patient')->where('id', $request->bed_id)->update([
                'tinhtrang' => 1,
            ]);
        }
        
        DB::table('health_insurances')
        ->where('medical_id', $request->medical_id)
        ->update([
            'patient_id' => $patient_id,
        ]);
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
            'out_hospital' => $request->out_hospital,
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
    public function outPatient($patient_update_id) {
        $this->authLogin();
    
        DB::table('patients')
            ->where('id', $patient_update_id)
            ->update([
                'status' => 0,
                'date_out' => Carbon::now()->toDateString()
            ]);
        
        $bed_id = DB::table('patients')
        ->where('id', $patient_update_id)
        ->value('bed_id');
        
        if ($bed_id) {
            DB::table('bed_patient')
                ->where('id', $bed_id)
                ->update(['tinhtrang' => 0]);
        }
    
        $coverage_rate = DB::table('health_insurances')
            ->where('medical_id', $patient_update_id)
            ->value('coverage_rate');
        $room_data = DB::table('patients')
            ->join('rooms', 'patients.room_id', '=', 'rooms.id')
            ->where('patients.id', $patient_update_id)
            ->select('rooms.room_type', 'patients.date_in')
            ->first(); 
    
        if (!$room_data) {
            Session::put('message', 'Không tìm thấy thông tin phòng của bệnh nhân.');
            return Redirect::to('list-patient');
        }
        $room_price = 0;
        if ($room_data->room_type == '2') {
            $room_price = 100000;
        } else if ($room_data->room_type == '3') {
            $room_price = 200000;
        }
    
        $date_in = Carbon::parse($room_data->date_in);
        $date_out = Carbon::parse(Carbon::now()->toDateString());
        $days_in_hospital = $date_out->diffInDays($date_in);
        if ($coverage_rate === null) {
            $total_amount = $room_price * $days_in_hospital;
        } else {
            $total_amount = $room_price * (1 - $coverage_rate / 100) * $days_in_hospital;
            dd($total_amount);
        }

        DB::table('payment_inpatient')->insert([
            'patient_id' => $patient_update_id,
            'total_amount' => $total_amount, 
            'payment_status' => 0,
            'payment_method' => 'Tiền mặt',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
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
    // khóa hồ sơ bệnh nhân
    public function lockPatient($patient_id) {
        $this->authLogin();
        DB::table('patients')->where('id', $patient_id)->update(['lock_hoso' => 1]);
        Session::put('message', 'Khóa hồ sơ bệnh nhân thành công');
        return Redirect::to('list-patient');
    }
    // in bệnh án
    public function printfBenhAn(Request $request) {
        $this->authLogin();
        $patient = DB::table('patients')
            ->join('rooms', 'patients.room_id', '=', 'rooms.id')
            ->join('bed_patient', 'patients.bed_id', '=', 'bed_patient.id')
            ->select(
                'patients.*',
                'rooms.name as room_name',
                'bed_patient.name_bed as bed_name',
            )
            ->where('patients.id', $request->id)
            ->first();
            $birthDate = Carbon::parse($patient->birth_date); 
            $formattedBirthDate = $birthDate->format('d-m-Y'); 
            $age = $birthDate->age; 
            if (!$patient) {
                return redirect()->back()->with('error', 'Không tìm thấy hóa đơn');
            }
            $pdf = PDF::loadView('admin.printbenhan', compact('patient', 'age', 'formattedBirthDate'));
            return $pdf->stream('benh_an.pdf');
    }
}