<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as Controller;

class MasterDataController extends Controller
{
    public function ProductHome(){
        $data['tittle'] = 'Porting - Dashboard';
        $data['list_product'] = DB::table('l_products')
                                ->GET();
        return view('pages.master_data.v_h_product',$data);
    }
    public function ProductCreate(){
        $data['tittle'] = 'Porting - Dashboard';

        return view('pages.master_data.v_h_product_add',$data);
    }
    public function ProductCreateAct(Request $request){
        $data['tittle'] = 'Porting - Dashboard';
        $query = DB::table('l_products')
                  ->insert(['product_type'=>$request['product_type'],
                            'product_number'=>$request['product_number'],
                            'product_name'=>$request['product_name'],
                            'product_desc'=>$request['product_desc'],
                            'product_brand'=>$request['product_brand'],
                            'product_base_price'=>$request['product_base_price'],
                            'product_code'=>$request['product_code'], 
                            'product_unit'=>$request['product_unit'],  
                     ]);
        return redirect()->route('master.product');
    }
    public function ProductEdit(Request $request){
        $data['tittle'] = 'Porting - Dashboard';
        $data['list_product'] = DB::table('l_products')
                                ->where('product_id',$request['id'])
                                ->get();
        return view('pages.master_data.v_h_product_edit',$data);
    }
    public function ProductEditAct(Request $request){
        $data['tittle'] = 'Porting - Dashboard';
        $query = DB::table('l_products')
                  ->where('product_id',$request['id'])
                  ->update(['product_type'=>$request['product_type'],
                            'product_number'=>$request['product_number'],
                            'product_name'=>$request['product_name'],
                            'product_desc'=>$request['product_desc'],
                            'product_brand'=>$request['product_brand'],
                            'product_base_price'=>$request['product_base_price'],
                            'product_code'=>$request['product_code'], 
                            'product_unit'=>$request['product_unit'],  
                     ]);
        return redirect()->route('master.product');
    }
    public function ProductDelete(Request $request){
        $data['tittle'] = 'Porting - Dashboard';
        $data['list_product'] = DB::table('l_products')
                                ->where('product_id',$request['id'])
                                ->get();
        return view('pages.master_data.v_h_product_delete',$data);
    }
    public function ProductDeleteAct(Request $request){
        $data['tittle'] = 'Porting - Dashboard';
        $query = DB::table('l_products')
                  ->where('product_id',$request['id'])
                  ->delete();
        return redirect()->route('master.product');
    }
    public function CustomerHome(){
        $data['tittle'] = 'Porting - Dashboard';
        $data['list_customer'] = DB::table('l_customers')
                                ->GET();
        return view('pages.master_data.v_h_customer',$data);
    }
    public function CustomerCreate(){
        $data['tittle'] = 'Porting - Dashboard';

        return view('pages.master_data.v_h_customer_add',$data);
    }
    public function CustomerCreateAct(Request $request){
        $data['tittle'] = 'Porting - Dashboard';
        $query = DB::table('l_customers')
                  ->insert(['customer_type'=>$request['customer_type'],
                            'customer_company'=>$request['customer_company'],
                            'customer_name'=>$request['customer_name'],
                            'customer_address'=>$request['customer_address'],
                            'customer_email'=>$request['customer_email'],
                            'customer_website'=>$request['customer_website'],
                            'customer_number'=>$request['customer_number'],  
                     ]);
        return redirect()->route('master.customer');
    }
    public function CustomerEdit(Request $request){
        $data['tittle'] = 'Porting - Dashboard';
        $data['list_product'] = DB::table('l_customers')
                                ->where('customer_id',$request['id'])
                                ->get();
        return view('pages.master_data.v_h_customer_edit',$data);
    }
    public function CustomerEditAct(Request $request){
        $data['tittle'] = 'Porting - Dashboard';
        $query = DB::table('l_customers')
                  ->where('customer_id',$request['id'])
                  ->update(['customer_type'=>$request['customer_type'],
                            'customer_company'=>$request['customer_company'],
                            'customer_name'=>$request['customer_name'],
                            'customer_address'=>$request['customer_address'],
                            'customer_email'=>$request['customer_email'],
                            'customer_website'=>$request['customer_website'],
                            'customer_number'=>$request['customer_number'],  
                     ]);
        return redirect()->route('master.customer');
    }
    public function CustomerDelete(Request $request){
        $data['tittle'] = 'Porting - Dashboard';
        $data['list_product'] = DB::table('l_customers')
                                ->where('customer_id',$request['id'])
                                ->get();
        return view('pages.master_data.v_h_customer_delete',$data);
    }
    public function CustomerDeleteAct(Request $request){
        $data['tittle'] = 'Porting - Dashboard';
        $query = DB::table('l_customers')
                  ->where('customer_id',$request['id'])
                  ->delete();
        return redirect()->route('master.customer');
    }
}
