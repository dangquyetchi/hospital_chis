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
        $exist_service = DB::table('services')->where('code', $request->service_code)->first();
        if ($exist_service) {
            return Redirect::back()->with('error', 'Mã dịch vụ đã tồn tại!');
        }
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
        $exist_service = DB::table('services')
        ->where('code', $request->service_code)
        ->where('id', '!=', $service_id) 
        ->exists();
        if ($exist_service) {
            return Redirect::back()->with('error', 'Mã dịch vụ đã tồn tại!');
        }
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

    public function searchService(Request $request){
        $this->authLogin();
        $keywords = $request->input('keyword');
        $search_service = DB::table('services')
        ->where('name', 'like', '%'.$keywords.'%')
        ->orWhere('code', 'like', '%'.$keywords.'%')
        ->get();
        return view('admin.listservice')->with('list_service', $search_service);
    }
}