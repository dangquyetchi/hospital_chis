<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
class ProductController extends Controller
{
    public function authLogin(){
        $admin_id  = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function addProduct(){
        $this->authLogin();

        $category_product = DB::table('tbl_category_product')->orderBy('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand_product')->orderBy('brand_id', 'desc')->get();
        return view('admin.addproduct')->with('category_product', $category_product)->with('brand_product', $brand_product);

    }
    public function listProduct(){
        $this->authLogin();

        $all_product = DB::table('tbl_product')
            ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
            ->join('tbl_brand_product', 'tbl_brand_product.brand_id', '=', 'tbl_product.brand_id')
            ->select('tbl_product.*', 'tbl_category_product.category_name', 'tbl_brand_product.brand_name')
            ->orderBy('tbl_product.product_id', 'desc')
            ->get();    
    
        return view('admin.listproduct')->with('list_product', $all_product); 
    }
    
    public function saveProduct(Request $request){
        $this->authLogin();

        $data = array();
        $data['product_name'] = $request->product_name;
        $data['product_desc'] = $request->product_description;
        $data['product_content'] = $request->product_content;
        $data['product_price'] = $request->product_price;
        $data['category_id'] = $request->product_category;
        $data['brand_id'] = $request->product_brand;
        $data['product_status'] = $request->product_status;

        $get_image = $request->file('product_image');
        if($get_image){
            $get_name_img = $get_image->getClientOriginalName();
            $name_img = current(explode('.', $get_name_img));
            $new_image = $name_img. rand(0,99). '.' .$get_image->getClientOriginalExtension() ;
            $get_image->move('public/upload/product', $new_image);
            $data['product_image'] = $new_image;    
            
            DB::table('tbl_product')->insert($data);
            Session::put('message', 'Thêm thành công');
            return Redirect::to('add-product');
        }
        $data['product_image'] = '';
        DB::table('tbl_product')->insert($data);
        Session::put('message', 'Thêm thành công');
        return Redirect::to('list-product');

    }
    public function active_Product($product_update_id){
        $this->authLogin();

        DB::table('tbl_product')
            ->where('product_id', operator: $product_update_id)
            ->update(['product_status' => 0]);
            Session::put('message', 'Cập nhật trạng thái thành công');
            return Redirect::to('list-product');
    }

    public function unactive_Product($product_update_id){
        $this->authLogin();

        DB::table('tbl_product')
        ->where('product_id', $product_update_id)
        ->update(['product_status' => 1]);
        Session::put('message', 'Cập nhật trạng thái thành công');
        return Redirect::to('list-product');
    }

    public function editProduct($product_update_id){
        $this->authLogin();

        $category_product = DB::table('tbl_category_product')->orderBy('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand_product')->orderBy('brand_id', 'desc')->get();
        $edit_product = DB::table(table: 'tbl_product')->where('product_id', $product_update_id)->get(); 

        return view('admin.editproduct')
        ->with('edit_product', $edit_product)
        ->with('category_product', $category_product)
        ->with('brand_product', $brand_product);
        
    }

    public function updateProduct(Request $request, $product_id) {
        $this->authLogin();

        $data = array();
        $data['product_name'] = $request->product_name;
        $data['product_desc'] = $request->product_description;
        $data['product_content'] = $request->product_content;
        $data['product_price'] = $request->product_price;
        $data['category_id'] = $request->product_category;
        $data['brand_id'] = $request->product_brand;
        $data['product_status'] = $request->product_status;
        
        $get_image = $request->file('product_image');
        if($get_image){
            $get_name_img = $get_image->getClientOriginalName();
            $name_img = current(explode('.', $get_name_img));
            $new_image = $name_img. rand(0,99). '.' .$get_image->getClientOriginalExtension() ;
            $get_image->move('public/upload/product', $new_image);
            $data['product_image'] = $new_image;    
            
            DB::table('tbl_product')->where('product_id', $product_id)->update($data);
            Session::put('message', 'Cập nhật thành công');
            return Redirect::to('list-product');
        }
        DB::table('tbl_product')->where('product_id', $product_id)->update($data);
        Session::put('message', 'Thêm thành công');
        return Redirect::to('list-product');

    }

    public function deleteProduct($product_update_id){
        $this->authLogin();

        DB::table('tbl_product')->where('product_id', $product_update_id)->delete();
        Session::put('message', 'Xóa thành công');
        return Redirect::to('list-product');

    }
}