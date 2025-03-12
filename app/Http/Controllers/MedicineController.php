<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class MedicineController extends Controller
{
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function listMedicine(){
        $this->authLogin();
        $list_medicine = DB::table('medicines')->get(); 
        return view('admin.listmedicine')->with('list_medicine', $list_medicine); 
    }

    public function addMedicine(){
        $this->authLogin();
        return view('admin.addmedicine');
    }

    public function saveMedicine(Request $request) {
        $this->authLogin();
        $medicine_exist = DB::table('medicines')->where('code', $request->medicine_code)->first();
        if ($medicine_exist) {
            return Redirect::back()->with('error', 'Mã thuốc đã tồn tại!');
        }
        $data = [
            'code' => $request->medicine_code,
            'name' => $request->medicine_name,
            'type' => $request->medicine_type,
            'price' => $request->medicine_price,
            'sale_price' => $request->medicine_price_out,
            'quantity' => $request->medicine_quantity,
            'medicine_unit' => $request->medicine_unit,
            'description' => $request->medicine_description,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        DB::table('medicines')->insert($data);
        Session::put('message', 'Thêm thuốc thành công');
        return Redirect::to('list-medicine');
    }

    public function editMedicine($medicine_id){
        $this->authLogin();
        $edit_medicine = DB::table('medicines')->where('id', $medicine_id)->get();
        return view('admin.editmedicine')->with('edit_medicine', $edit_medicine);
    }

    public function updateMedicine(Request $request, $medicine_id){
        $this->authLogin();
        $exists = DB::table('medicines')
            ->where('code', $request->medicine_code)
            ->where('id', '!=', $medicine_id) 
            ->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['medicine_code' => 'Mã thuốc đã tồn tại trong hệ thống.']);
        }
        $data = [
            'code' => $request->medicine_code,
            'name' => $request->medicine_name,
            'type' => $request->medicine_type,
            'price' => $request->medicine_price,
            'sale_price' => $request->medicine_price_out,
            'quantity' => $request->medicine_quantity,
            'medicine_unit' => $request->medicine_unit,
            'description' => $request->medicine_description,
        ];
        DB::table('medicines')->where('id', $medicine_id)->update($data);
        Session::put('message', 'Cập nhật thuốc thành công');
        return Redirect::to('list-medicine');
    }

    public function deleteMedicine($medicine_id){
        $this->authLogin();
        DB::table('medicines')->where('id', $medicine_id)->delete();
        Session::put('message', 'Xóa thuốc thành công');
        return Redirect::to('list-medicine');
    }

    public function searchMedicine(Request $request){
        $this->authLogin();
        $keyword = $request->input('keyword');
        $search_medicine = DB::table('medicines')
        ->where('name', 'like', '%'.$keyword.'%')
        ->orWhere('code', 'like', '%'.$keyword.'%')->get();
        return view('admin.listmedicine')->with('list_medicine', $search_medicine);
    }
}