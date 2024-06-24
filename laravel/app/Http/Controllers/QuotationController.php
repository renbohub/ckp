<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as Controller;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Session;
use PDF;

class QuotationController extends Controller
{
    public function Dashboard(){
        $data['tittle'] = 'Porting - Dashboard';
        $iduser = Session::get('Users')['id'];
        $data['data'] = DB::table('t_quotations')
                        ->selectRaw('*,(quotation_price*quotation_qty*((100-l_quotations.quotation_disc)/100)) as total_price')
                        ->join('l_quotations','l_quotations.quotation_id','t_quotations.quotation_id')
                        ->where('l_quotations.create_by',$iduser)
                        ->get();
       
        return view('pages.quotations.v_h_quotation_dashboard',$data);
    }
    public function Home(){
        $data['tittle'] = 'Porting - Dashboard';
        $iduser = Session::get('Users')['id'];
        $idrole = Session::get('Users')['role_id'];
        if ( $idrole <= 2) {
            $data['list_product'] = DB::table('l_quotations')
            ->join('l_customers','l_customers.customer_id','l_quotations.customer_id')
            ->orderBy('quotation_id','desc')
            ->where('quotation_status','<',4)
            ->GET();
        }else{
            $data['list_product'] = DB::table('l_quotations')
            ->join('l_customers','l_customers.customer_id','l_quotations.customer_id')
            ->orderBy('quotation_id','desc')
            ->where('quotation_status','<',4)
            ->where('create_by',$iduser)
            ->GET();
        }
       
        return view('pages.quotations.v_h_quotation',$data);
    }
    public function Create(){
        $data['tittle'] = 'Porting - Dashboard';
        $data['list_product'] = DB::table('l_quotations')
                                ->join('l_customers','l_customers.customer_id','l_quotations.customer_id')
                                ->GET();
        $data['list_options'] = DB::table('l_products')
                                ->GET(); 
        $date = DB::select('select month(now()) as date, year(now()) - 2000 as yr, date(now()) as date_start, date(date_add(now(), interval 30 day)) as date_exp');
        $number = $date[0]->date;
        $start = $date[0]->date_start;
        $expired = $date[0]->date_exp;
        $year = $date[0]->yr;
        if ($number == 1) {
            $rum = 'I';
        }elseif($number == 2){
            $rum = 'II';
        }elseif($number == 3){
            $rum = 'III';
        }elseif($number == 4){
            $rum = 'IV';
        }elseif($number == 5){
            $rum = 'V';
        }elseif($number == 6){
            $rum = 'VI';
        }elseif($number == 7){
            $rum = 'VII';
        }elseif($number == 8){
            $rum = 'VIII';
        }elseif($number == 9){
            $rum = 'IX';
        }elseif($number == 10){
            $rum = 'X';
        }elseif($number == 11){
            $rum = 'XI';
        }elseif($number == 12){
            $rum = 'XII';
        }
        $count = DB::select('select count(*) + 1 as total from l_quotations where month(quotation_date) = month(now())');
        $total = $count[0]->total; 
        if($total<10){
            $ttl = '000'.$total.'';
        }elseif($total>=10 && $total<100){
            $ttl = '00'.$total.'';
        }elseif($total>=100 && $total<1000){
            $ttl = '0'.$total.'';
        }elseif($total>=1000 && $total<10000){
            $ttl = ''.$total.'';
        }
        $sign = Str::random(30);
        $quot_no = 'QUOT/'.$year.'/'.$rum.'/'.$ttl.'';
        $iduser = Session::get('Users')['id'];
        $query = DB::table('l_quotations')
                    ->insert(['quotation_number'=>$quot_no,
                              'quotation_date'=>$start,
                              'quotation_exp'=>$expired,
                              'sign_validator' => $sign,
                              'create_by' => $iduser
                    ]);
        $data['quoatations'] = DB::table('l_quotations')
                                ->orderBy('quotation_id','DESC')
                                ->limit(1)
                                ->get();
        $q= DB::table('l_quotations')
                                ->orderBy('quotation_id','DESC')
                                ->limit(1)
                                ->first();
        $d_id = $q;
        $id = $d_id->quotation_id;
        return redirect()->route('quotation.edit',$id);
    }
    public function Copy($copy){
        $data['tittle'] = 'Porting - Dashboard';
        $data['list_product'] = DB::table('l_quotations')
                                ->join('l_customers','l_customers.customer_id','l_quotations.customer_id')
                                ->GET();
        $data['list_options'] = DB::table('l_products')
                                ->GET(); 
        $date = DB::select('select month(now()) as date, year(now()) - 2000 as yr, date(now()) as date_start, date(date_add(now(), interval 30 day)) as date_exp');
        $number = $date[0]->date;
        $start = $date[0]->date_start;
        $expired = $date[0]->date_exp;
        $year = $date[0]->yr;
        if ($number == 1) {
            $rum = 'I';
        }elseif($number == 2){
            $rum = 'II';
        }elseif($number == 3){
            $rum = 'III';
        }elseif($number == 4){
            $rum = 'IV';
        }elseif($number == 5){
            $rum = 'V';
        }elseif($number == 6){
            $rum = 'VI';
        }elseif($number == 7){
            $rum = 'VII';
        }elseif($number == 8){
            $rum = 'VIII';
        }elseif($number == 9){
            $rum = 'IX';
        }elseif($number == 10){
            $rum = 'X';
        }elseif($number == 11){
            $rum = 'XI';
        }elseif($number == 12){
            $rum = 'XII';
        }
        $count = DB::select('select count(*) + 1 as total from l_quotations where month(quotation_date) = month(now())');
        $total = $count[0]->total; 
        if($total<10){
            $ttl = '000'.$total.'';
        }elseif($total>=10 && $total<100){
            $ttl = '00'.$total.'';
        }elseif($total>=100 && $total<1000){
            $ttl = '0'.$total.'';
        }elseif($total>=1000 && $total<10000){
            $ttl = ''.$total.'';
        }
        $sign = Str::random(30);
        $quot_no = 'QUOT/'.$year.'/'.$rum.'/'.$ttl.'';
        $iduser = Session::get('Users')['id'];
        $query = DB::table('l_quotations')
                    ->insert(['quotation_number'=>$quot_no,
                              'quotation_date'=>$start,
                              'quotation_exp'=>$expired,
                              'sign_validator' => $sign,
                              'create_by' => $iduser
                    ]);
        $data['quoatations'] = DB::table('l_quotations')
                                ->orderBy('quotation_id','DESC')
                                ->limit(1)
                                ->get();
        $q= DB::table('l_quotations')
                                ->orderBy('quotation_id','DESC')
                                ->limit(1)
                                ->first();
        $d_id = $q;
        $id = $d_id->quotation_id;
        $c_query = DB::table('t_quotations')
                    ->selectRaw('*')
                    ->where('quotation_id',$copy)
                    ->get();
        foreach ($c_query as $row) {
                        $row->quotation_id = $id;
                        $row->id = null;
                        unset($row->change_id); // Remove the 'change_id' column if not needed
                    
                        DB::table('t_quotations')->insert((array) $row);
        }
        return redirect()->route('quotation.edit',$id);
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
        
        return view('pages.quotations.v_h_quotation_create',$data);
    }

    public function CreateAct(Request $request){
        $all = $request->all();
        $value = $all['value'];
        $error_id = 0;
        for ($i=0; $i < count($value); $i++) { 
            if($value[$i]['l_type'] == 2 && $value[$i]['p_id']==0){
                $error_id++;
            }
        }
        if($error_id > 0){
            return response()->json(['data' => $value,'error' => '402']);
        }
        $refresh = DB::table('t_quotations')
                    -> where('quotation_id',$value[0]['q_id'])
                    ->delete();
        for ($i2=0; $i2 < count($value); $i2++) { 
            $insert = DB::table('t_quotations')
                    ->insert([
                        'quotation_id' => $value[$i2]['q_id'],
                        'quotation_order' => $value[$i2]['q_order'],
                        'group_no' => $value[$i2]['g_no'],
                        'product_id' => $value[$i2]['p_id'],
                        'quotation_price' => $value[$i2]['q_price'],
                        'quotation_qty' => $value[$i2]['q_qty'],
                        'quotation_desc' => $value[$i2]['q_desc'],
                        'line_type' => $value[$i2]['l_type'],
                        'quotation_lead_time' => $value[$i2]['q_lead_time'],
                        'quotation_total' => $value[$i2]['q_total'],
                        'quotation_uom' => $value[$i2]['q_uom'],
                     ]);
        }
        $group = DB::table('t_quotations')
                    ->selectRaw('sum(quotation_total) as total, group_no as no')
                    ->where('quotation_id',$value[0]['q_id'])
                    ->groupBy('group_no')
                    ->get();

            for ($g=0; $g < count($group); $g++) { 
            $data_no  = $group[$g]->no;
            $data_total  = $group[$g]->total;

            $edit_group = DB::table('t_quotations')
                            ->where('quotation_id',$value[0]['q_id'])
                            ->where('group_no',$data_no)
                            ->where('line_type',1)
                            ->update([
                                    'quotation_total' => $data_total
                            ]);
            }
        return response()->json(['data' => $value,'error' => '0']);
    }
    public function EditTittle(Request $request){
        $all = $request->all();
        $id  = $all['id'];
        $value = $all['value'];
        $insert = DB::table('l_quotations')
                        -> where('quotation_id',$id)
                        -> update([
                            'quotation_name' => $value
                        ]);
        return response()->json(['data' => $id]);
    }
    public function EditCust(Request $request){
        $all = $request->all();
        $id  = $all['id'];
        $value = $all['value'];
        $insert = DB::table('l_quotations')
                        -> where('quotation_id',$id)
                        -> update([
                            'customer_id' => $value
                        ]);
        $get = DB::table('l_quotations')
                        ->join('l_customers','l_customers.customer_id','l_quotations.customer_id')
                        ->where('quotation_id',$id)
                        ->first();
        return response()->json(['data' => $get]);
    }
    public function preview($id){
        $data['quotation_head'] = DB::table('l_quotations')
                                ->join('l_customers','l_customers.customer_id','l_quotations.customer_id')
                                ->join('l_rekening','l_rekening.rekening_id','l_quotations.rekening_id')
                                ->join('l_remark','l_remark.remark_id','l_quotations.remark_id')
                                ->join('l_users','l_users.id','l_quotations.create_by')
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
        $data['total_quotation'] = DB::table('t_quotations')
                                    ->selectRaw('sum(quotation_price * quotation_qty) as total')
                                    ->where('quotation_id',$id)
                                    ->first();
        $data['qr_code'] = QrCode::size(100)
                                ->format('svg')
                                ->merge('/../sites/img/pm.png')
                                ->errorCorrection('M')
                                ->generate('https://renbo.co.id/sign/'.$data['quotation_head']->sign_validator.'');

        // $data = QrCode::size(512)
        // ->format('png')
        // ->merge('/storage/app/twitter.jpg')
        // ->errorCorrection('M')
        // ->generate(
        //     'https://twitter.com/HarryKir',
        // );

        return view('pages.quotations.v_h_quotation_pdf',$data);
    }
    public function EditTax(Request $request){
        $all = $request->all();
        $id  = $all['id'];
        $value = $all['value'];
        $insert = DB::table('l_quotations')
                        -> where('quotation_id',$id)
                        -> update([
                            'quotation_tax' => $value
                        ]);
        return response()->json(['data' => $id]);
    }
    public function EditDisc(Request $request){
        $all = $request->all();
        $id  = $all['id'];
        $value = $all['value'];
        $insert = DB::table('l_quotations')
                        -> where('quotation_id',$id)
                        -> update([
                            'quotation_disc' => $value
                        ]);
        return response()->json(['data' => $id]);
    }
    public function printPdf($id){
        $data['quotation_head'] = DB::table('l_quotations')
                                ->join('l_customers','l_customers.customer_id','l_quotations.customer_id')
                                ->join('l_rekening','l_rekening.rekening_id','l_quotations.rekening_id')
                                ->join('l_remark','l_remark.remark_id','l_quotations.remark_id')
                                ->join('l_users','l_users.id','l_quotations.create_by')
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
        $data['total_quotation'] = DB::table('t_quotations')
                                    ->selectRaw('sum(quotation_price * quotation_qty) as total')
                                    ->where('quotation_id',$id)
                                    ->first();
         $svg = QrCode::generate('https://renbo.co.id/sign='.$data['quotation_head']->sign_validator.'');
         $data['qr_code'] = '<img src="data:image/svg+xml;base64,'.base64_encode($svg).'"  width="60" height="60" />';
        $pdf = PDF::loadview('pages.quotations.v_h_quotation_pdf',$data)->setOption([
            'fontDir' => storage_path('fonts/'),
            'font_cache' => storage_path('fonts/')
        ]); ;
        $title = "". $data['quotation_head']->quotation_number."-".$data['quotation_head']->quotation_name."";
        return $pdf->download(''.$title.'.pdf');
    }
    public function ReqApprove(Request $request){
        $all = $request->all();
        $id  = $all['id'];
        $value = $all['value'];
        if ($value ==4 ) {
            $date = DB::select('select month(now()) as date, year(now()) - 2000 as yr, date(now()) as date_start, date(date_add(now(), interval 30 day)) as date_exp');
            $year = $date[0]->yr;
            $count = DB::select('select count(*) + 1 as total from l_quotations where quotation_status > 3');
            $total = $count[0]->total; 
            $get = $date[0]->date_start; 
            if($total<10){
                $ttl = '000'.$total.'';
            }elseif($total>=10 && $total<100){
                $ttl = '00'.$total.'';
            }elseif($total>=100 && $total<1000){
                $ttl = '0'.$total.'';
            }elseif($total>=1000 && $total<10000){
                $ttl = ''.$total.'';
            }
            $so_no = 'S'.$year.''.$ttl.'';
            $insert1 = DB::table('l_quotations')
                        -> where('quotation_id',$id)
                        -> update([
                            'so_number' => $so_no,
                            'so_date' => $get
                        ]);
            

        }
        $insert = DB::table('l_quotations')
                        -> where('quotation_id',$id)
                        -> update([
                            'quotation_status' => $value
                        ]);
        return response()->json(['data' => $id]);
    }
    public function EditNote(Request $request){
        $all = $request->all();
        $id  = $all['id'];
        $value = $all['value'];
        $insert = DB::table('l_quotations')
                        -> where('quotation_id',$id)
                        -> update([
                            'quotation_note' => $value
                        ]);
        return response()->json(['data' => $id]);
    }
    public function EditRemark(Request $request){
        $all = $request->all();
        $id  = $all['id'];
        $value = $all['value'];
        
        $insert = DB::table('l_quotations')
                        -> where('quotation_id',$id)
                        -> update([
                            'remark_id' => $value
                        ]);
        return response()->json(['data' => $id]);
    }

}
