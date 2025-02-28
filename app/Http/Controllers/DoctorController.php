<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DoctorController extends Controller
{
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function listDoctor(){
        $this->authLogin();
        $list_doctor = DB::table('doctors')
            ->leftJoin('rooms', 'doctors.room_id', '=', 'rooms.id')
            ->select('doctors.*', 'rooms.name as room_name') 
            ->get();
    
        return view('admin.listdoctor')->with('list_doctor', $list_doctor);
    }

    public function addDoctor(){
        $this->authLogin();
        $rooms = DB::table('rooms')->get(); 
        return view('admin.adddoctor', compact('rooms'));
    }
    
    public function saveDoctor(Request $request) {
        $this->authLogin();
        $data = [
            'name' => $request->doctor_name,
            'gender' => $request->doctor_gender,
            'room_id' => $request->doctor_room,
            'position' => $request->position,
        ];
        DB::table('doctors')->insert($data);
        Session::put('message', 'Thêm bác sĩ thành công');
        return Redirect::to('list-doctor');
    }

    public function editDoctor($doctor_id) {
        $this->authLogin();
        $edit_doctor = DB::table('doctors')->where('id', $doctor_id)->first();
        $rooms = DB::table('rooms')->get(); 
        $positions = ['Trưởng khoa', 'Phó trưởng khoa', 'Bác sĩ chuyên khoa', 'Bác sĩ đa khoa', 'Bác sĩ phẫu thuật', 'Điều dưỡng', 'Kỹ thuật viên'];
    
        return view('admin.editdoctor', compact('edit_doctor', 'rooms', 'positions'));
    }

    public function deleteDoctor($doctor_id){
        $this->authLogin();
        DB::table('doctors')->where('id', $doctor_id)->delete();
        return Redirect::to('list-doctor');
    }
    
}