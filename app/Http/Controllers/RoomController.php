<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class RoomController extends Controller
{
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function listRoom(){
        $this->authLogin();
        $list_room = DB::table('rooms')->get(); 
        return view('admin.listroom')->with('list_room', $list_room); 
    }

    public function addRoom(){
        $this->authLogin();
        return view('admin.addroom');
    }

    public function saveRoom(Request $request) {
        $this->authLogin();
        $data = [
            'code' => $request->room_code,
            'name' => $request->room_name,
        ];
        DB::table('rooms')->insert($data);
        Session::put('message', 'Thêm phòng thành công');
        return Redirect::to('add-room');
    }

    public function editRoom($room_id){
        $this->authLogin();
        $edit_room = DB::table('rooms')->where('id', $room_id)->get();
        return view('admin.editroom')->with('edit_room', $edit_room);
    }

    public function updateRoom(Request $request, $room_id){
        $this->authLogin();
        $data = [
            'code' => $request->room_code,
            'name' => $request->room_name,
        ];
        DB::table('rooms')->where('id', $room_id)->update($data);
        Session::put('message', 'Cập nhật phòng thành công');
        return Redirect::to('list-room');
    }

    public function deleteRoom($room_id){
        $this->authLogin();
        DB::table('rooms')->where('id', $room_id)->delete();
        Session::put('message', 'Xóa phòng thành công');
        return Redirect::to('list-room');
    }
}