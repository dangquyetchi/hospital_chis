<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class DetailController extends Controller
{
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function editPrescriptionDetail($id){
        $detail = DB::table('prescription_medicines')
        ->join('medicines', 'prescription_medicines.medicine_id', '=', 'medicines.id')
        ->where('prescription_medicines.id', $id)
        ->select('prescription_medicines.*', 'medicines.name as medicine_name')
        ->first();
        return response()->json($detail);
    }

    public function deletePrescriptionDetail($detail_id) {
        $this->authLogin();
        $prescription_detail = DB::table('prescription_medicines')->where('id', $detail_id)->first();
        if ($prescription_detail) {
            DB::table('medicines')->where('id', $prescription_detail->medicine_id)
                ->increment('quantity', $prescription_detail->quantity);
    
            DB::table('prescription_medicines')->where('id', $detail_id)->delete();
            return redirect()->back()->with('message', 'Xóa thuốc thành công');
        }
        return redirect()->back()->with('error', 'Không tìm thấy thuốc cần xóa!');
    }

    public function saveDetailPrescription(Request $request)
    {
        $medicine = DB::table('medicines')->where('id', $request->input('medicine_id'))->first();
        if (!$medicine) {
            return redirect()->back()->with('error', 'Thuốc không tồn tại!');
        }
        $quantity_prescription = $request->input('quantity');
        $id = $request->input('id');
        $prescription_id = $request->input('prescription_id');
        $old_quantity = 0;
        
        if ($id) {
            $old_record = DB::table('prescription_medicines')->where('id', $id)->first();
            if ($old_record) {
                $old_quantity = $old_record->quantity;
            }
        }

        $available_quantity = $medicine->quantity + $old_quantity;
        if ($quantity_prescription > $available_quantity) {
            return redirect()->back()->with('error', 'Số lượng thuốc trong kho không đủ!');
        }

        $price = $quantity_prescription * $medicine->sale_price;

        if ($id) {
            DB::table('medicines')->where('id', $old_record->medicine_id)
                ->increment('quantity', $old_quantity);
            // update
            DB::table('prescription_medicines')->where('id', $id)->update([
                'medicine_id' => $request->input('medicine_id'),
                'quantity' => $quantity_prescription,
                'usage_instruction' => $request->input('usage_instruction'),
                'medicine_unit' => $medicine->medicine_unit,
                'price' => $price,
            ]);
        } else {
            // Create
            DB::table('prescription_medicines')->insert([
                'prescription_id' => $prescription_id,
                'medicine_id' => $request->input('medicine_id'),
                'quantity' => $quantity_prescription,
                'usage_instruction' => $request->input('usage_instruction'),
                'medicine_unit' => $medicine->medicine_unit,
                'price' => $price,
            ]);
        }

        DB::table('medicines')->where('id', $request->input('medicine_id'))
            ->decrement('quantity', $quantity_prescription);

        // total_medicine
        $total_medicine = DB::table('prescription_medicines')
            ->where('prescription_id', $prescription_id)
            ->sum('price');

        DB::table('prescriptions')->where('id', $prescription_id)
            ->update(['total_medicine' => $total_medicine]);

        return redirect()->back()->with('message', $id ? 'Cập nhật thành công!' : 'Thêm mới thành công!');
    }

    public function saveDetailRecordService(Request $request) {
        $this->authLogin();
        $id = $request->input('id');
        $service_record_id = $request->input('service_record_id');
        $data = [
            'service_id' => $request->service_id,
            'room_id' => $request->room_id,
        ];
    
        if ($id) { 
            // Nếu có ID -> Cập nhật
            DB::table('service_detail')->where('id', $id)->update($data);
            $message = 'Cập nhật thành công!';
        } else { 
            // Nếu không có ID -> Thêm mới
            DB::table('service_detail')->insert([
                'service_record_id' => $service_record_id,  
                'service_id' => $request->service_id,
                'room_id' => $request->room_id,
            ]);
            $message = 'Thêm mới thành công!';
        }
    
        Session::put('message', $message);
        return redirect()->back()->with('message', $message);
    }
    

    public function editDetailRecordService($id){
        $detail = DB::table('service_detail')
        ->join('services', 'service_detail.service_id', '=', 'services.id')
        ->join('rooms', 'service_detail.room_id', '=', 'rooms.id')
        ->where('service_detail.id', $id)
        ->select('service_detail.*', 'services.name as service_name', 'rooms.name as room_name')
        ->first();
        return response()->json($detail);
    }

    public function deleteDetailRecordService($id) {
        $this->authLogin();
        $service_detail = DB::table('service_detail')->where('id', $id)->first();
        if ($service_detail) {
            DB::table('service_detail')->where('id', $id)->delete();
            return redirect()->back()->with('message', 'Xóa chi tiết dịch vụ thành công');
        }
        return redirect()->back()->with('error', 'Không tìm thấy chi tiết dịch vụ cần xóa!');
    }
}