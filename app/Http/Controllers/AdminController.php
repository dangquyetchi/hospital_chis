<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller {

    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }   
    
    public function index() {
        return view('admin_login');
    }

    public function show_dashboard() {
        $this->authLogin();
        return view('admin.dashboard');
    }
    // trang chu admin
    public function dashboard(Request $request) {
        $email = $request->input('admin_email');
        $password = $request->input('admin_password');

        // Truy vấn để lấy admin dựa trên email
        $admin = DB::table('users')->where('email', $email)->first();
        
        // Kiểm tra xem admin có tồn tại và mật khẩu có khớp không
        if ($admin && Hash::check($password, $admin->password)) {
            // Đăng nhập thành công
            Session::put('admin_id', $admin->id);
            Session::put('admin_role', $admin->role);
            return view('admin.dashboard');
        } else {
            // Đăng nhập thất bại, quay lại trang đăng nhập với thông báo lỗi
            return redirect()->back()->with('error', 'Email hoặc mật khẩu không chính xác!');
        }
    }

    public function logout()
    {
        $this->authLogin();
        // Xóa session liên quan đến người dùng
        Auth::logout();
        // Chuyển hướng về trang đăng nhập với thông báo
        return redirect('/admin')->with('success', 'Đăng xuất thành công!');
    }
    
}