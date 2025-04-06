<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BedController extends Controller
{
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function listBed() {
        $this->authLogin();
        
        $list_bed = DB::table('bed_patient')
            ->join('rooms', 'bed_patient.room_id', '=', 'rooms.id')
            ->select('bed_patient.*', 'rooms.name as room_name')
            ->paginate(5);
    
        return view('admin.listbed')->with('list_bed', $list_bed); 
    }
    

    public function addBed(){
        $this->authLogin();
        $rooms = DB::table('rooms')->get();
        return view('admin.addbed')->with('rooms', $rooms);
    }

    public function saveBed(Request $request) {
        $this->authLogin();
        $exist = DB::table('bed_patient')->where('code', $request->bed_code)->first();
        if ($exist) {
            return Redirect::back()->with('error', 'Mã giường đã tồn tại!');
        }
        $data = [
            'code' => $request->bed_code,
            'name_bed' => $request->bed_name,
            'room_id' => $request->room_id,
            'tinhtrang' => 0,

        ];
        DB::table('bed_patient')->insert($data);
        Session::put('message', 'Thêm giường thành công');
        return Redirect::to('list-bed');
    }

    public function editBed($bed_id){
        $this->authLogin();
        $edit_bed = DB::table('bed_patient')->where('id', $bed_id)->get();
        $rooms = DB::table('rooms')->get();
        return view('admin.editbed')->with('edit_bed', $edit_bed)->with('rooms', $rooms);
    }
    public function updateBed(Request $request, $bed_id){
        $this->authLogin();
        $exist = DB::table('bed_patient')
        ->where('code', $request->bed_code)
        ->where('id', '!=', $bed_id)->first();
        if ($exist) {
            return Redirect::back()->with('error', 'Mã giường đã tồn tại!');
        }
        $data = [
            'code' => $request->bed_code,
            'name_bed' => $request->bed_name,
            'room_id' => $request->room_id,
        ];
        DB::table('bed_patient')->where('id', $bed_id)->update($data);
        Session::put('message', 'Cập nhật giường thành công');
        return Redirect::to('list-bed');
    }
    public function deleteBed($bed_id){
        $this->authLogin();
        DB::table('bed_patient')->where('id', $bed_id)->delete();
        Session::put('message', 'Xóa giường thành công');
        return Redirect::to('list-bed');
    }
    public function searchBed(Request $request) {
        $this->authLogin();
        $search = $request->input('search');
        $list_bed = DB::table('bed_patient')
            ->join('rooms', 'bed_patient.room_id', '=', 'rooms.id')
            ->select('bed_patient.*', 'rooms.name as room_name')
            ->where('bed_patient.code', 'like', '%' . $search . '%')
            ->orWhere('bed_patient.name_bed', 'like', '%' . $search . '%')
            ->orWhere('rooms.name', 'like', '%' . $search . '%')
            ->paginate(5);
    
        return view('admin.listbed')->with('list_bed', $list_bed); 
    }

}