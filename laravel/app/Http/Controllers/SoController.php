<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as Controller;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class SoController extends Controller
{
    public function Home(){
        $data['tittle'] = 'Porting - Dashboard';
        $data['list_product'] = DB::table('l_quotations')
                                ->join('l_customers','l_customers.customer_id','l_quotations.customer_id')
                                ->orderBy('quotation_id','desc')
                                ->where('quotation_status','>',3)
                                ->GET();
        return view('pages.so.v_h_so',$data);
    }
    public function Edit($id){
        $data['quotation_head'] = DB::table('l_quotations')
                                ->join('l_customers','l_customers.customer_id','l_quotations.customer_id')
                                ->join('l_rekening','l_rekening.rekening_id','l_quotations.rekening_id')
                                ->join('l_remark','l_remark.remark_id','l_quotations.remark_id')
                                ->where('quotation_id',$id)
                                ->first();
        $data['list_remark'] = DB::table('l_remark')
                                ->GET();
        $data['list_rekening'] = DB::table('l_rekening')
                                ->GET();  
        $data['quotation_body'] = DB::table('t_quotations')
                                ->where('quotation_id',$id)
                                ->get();
        $data['list_options'] = DB::table('l_products')
                                ->GET(); 
        $data['list_customer'] = DB::table('l_customers')
                                ->GET(); 
        
        return view('pages.so.v_h_so_create',$data);
    }
    public function EditPO(Request $request){
        $all = $request->all();
        $id  = $all['id'];
        $value = $all['value'];
        $insert = DB::table('l_quotations')
                        -> where('quotation_id',$id)
                        -> update([
                            'cust_po_number' => $value
                        ]);
        return response()->json(['data' => $id]);
    }
}
