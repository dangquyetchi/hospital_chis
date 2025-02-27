<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class ServiceController extends Controller
{
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function listService(){
        $this->authLogin();
        $list_service = DB::table('services')->get(); 
        return view('admin.listservice')->with('list_service', $list_service); 
    }

    public function addService(){
        $this->authLogin();
        return view('admin.addservice');
    }

    public function saveService(Request $request){
        $this->authLogin();
        $data = array();
        $data['code'] = $request->service_code;
        $data['name'] = $request->service_name;
        $data['price'] = $request->service_price;
       
        DB::table('services')->insert($data);
        Session::put('message','Thêm dịch vụ thành công');
        return Redirect::to('list-service');
    }

    public function editService($service_id){
        $this->authLogin();
        $edit_service = DB::table('services')->where('id', $service_id)->get();
        return view('admin.editservice')->with('edit_service', $edit_service);
    }

    public function updateService(Request $request, $service_id){
        $this->authLogin();
        $data = [
            'code' => $request->service_code,
            'name' => $request->service_name,
            'price' => $request->service_price,
        ];
        DB::table('services')->where('id', $service_id)->update($data);
        Session::put('message', 'Cập nhật dịch vụ thành công');
        return Redirect::to('list-service');
    }

    public function deleteService($service_id){
        $this->authLogin();
        DB::table('services')->where('id', $service_id)->delete();
        Session::put('message', 'Xóa dịch vụ thành công');
        return Redirect::to('list-service');
    }
}