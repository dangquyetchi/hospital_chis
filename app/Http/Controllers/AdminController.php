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
    
        // Doanh thu theo ngày
        $dailyRevenue = DB::table('payments')
            ->selectRaw('DATE(created_at) as date, SUM(price_medical + price_service + price_prescription) as total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderByDesc('date')
            ->get();
    
        // Số lượng bệnh nhân theo ngày
        $patientCount = DB::table('medical_records')
            ->selectRaw('DATE(examination_date	) as date, COUNT(*) as count')
            ->groupBy(DB::raw('DATE(examination_date	)'))
            ->orderByDesc('date')
            ->get();
    
        // Doanh thu theo tháng
        $monthlyRevenue = DB::table('payments')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(price_medical + price_service + price_prescription) as total')
            ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();
    
        // Số lượng bệnh nhân theo tháng
        $monthlyPatientCount = DB::table('medical_records')
            ->selectRaw('YEAR(examination_date	) as year, MONTH(examination_date) as month, COUNT(*) as count')
            ->groupBy(DB::raw('YEAR(examination_date	), MONTH(examination_date	)'))
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();
    
        // Doanh thu theo năm
        $yearlyRevenue = DB::table('payments')
            ->selectRaw('YEAR(created_at) as year, SUM(price_medical + price_service + price_prescription) as total')
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderByDesc('year')
            ->get();
    
        // Số lượng bệnh nhân theo năm
        $yearlyPatientCount = DB::table('medical_records')
            ->selectRaw('YEAR(examination_date) as year, COUNT(*) as count')
            ->groupBy(DB::raw('YEAR(examination_date	)'))
            ->orderByDesc('year')
            ->get();
    
        return view('admin.dashboard', compact('dailyRevenue', 'patientCount', 'monthlyRevenue', 'monthlyPatientCount', 'yearlyRevenue', 'yearlyPatientCount'));
    }
    // trang chủ admin
    public function dashboard(Request $request)
    {
        $request->validate([
            'admin_email' => 'required|email',
            'admin_password' => 'required'
        ]);

        $email = $request->input('admin_email');
        $password = $request->input('admin_password');

        $admin = DB::table('users')->where('email', $email)->first();

        if ($admin && Hash::check($password, $admin->password)) {
            Session::put('admin_id', $admin->id);
            Session::put('role', $admin->role);
            Session::put('admin_name', $admin->username); 
            return redirect('/dashboard');
        } else {
            return redirect()->back()->with('error', 'Email hoặc mật khẩu không chính xác!');
        }
    }

    public function logout()
    {
        $this->authLogin();
        Session::flush();
        Auth::logout();
        return redirect('/admin')->with('success', 'Đăng xuất thành công!');
    }
    
}