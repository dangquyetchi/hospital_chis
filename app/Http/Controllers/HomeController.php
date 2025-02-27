<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Session; 
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Redirect;
    class HomeController extends Controller {
        // public function index(){
        //     $category_product = DB::table('tbl_category_product')
        //     ->where('category_status', '0')
        //     ->orderBy('category_id', 'desc')
        //     ->get();
        //     $brand_product = DB::table('tbl_brand_product')->orderBy('brand_id', 'desc')->get();
        //     return view('pages.home');
        // }
    }