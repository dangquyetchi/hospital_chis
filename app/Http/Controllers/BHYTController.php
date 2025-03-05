<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BHYTController extends Controller
{
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function listBhyt() {
        $this->authLogin();
        $list_bhyt = DB::table('health_insurances')->get();
        return view ('admin.listbhyt')->with('list_bhyt', $list_bhyt);
    }

    public function editBhyt($bhyt_id) {
        $this->authLogin();
        $edit_bhyt = DB::table('health_insurances')->where('id', $bhyt_id)->first();
        return view('admin.editbhyt', compact('edit_bhyt'));
    }

    public function updateBhyt(Request $request, $bhyt_id) {
        $this->authLogin();
        $exists = DB::table('health_insurances')
            ->where('card_number', $request->card_number)
            ->where('id', '!=', $bhyt_id) 
            ->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['card_number' => 'Số thẻ BHYT này đã tồn tại trong hệ thống.']);
        }
        DB::table('health_insurances')->where('id', $bhyt_id)->update([
            'card_number' => $request->card_number,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
        ]);
        Session::put('message', 'Cập nhật thông tin thành công');
        return Redirect::to('list-bhyt');
    }

    public function deleteBhyt($bhyt_id)
    {
        $this->authLogin();
        DB::table('health_insurances')->where('id', $bhyt_id)->delete();
        return Redirect::to('list-bhyt');
    }

    public function searchBHYT(Request $request) 
    {
        $keyword = $request->input('keyword');

        $list_bhyt = DB::table('health_insurances')
                        ->where('card_number', 'LIKE', "%$keyword%")
                        ->orWhere('patient_name', 'LIKE', "%$keyword%")
                        ->get();

        return view('admin.listbhyt', compact('list_bhyt'));
    }
    
}