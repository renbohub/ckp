<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as Controller;
use DataTables;
use Session;
class AdminController extends Controller
{
    public function index(){
        $data['tittle'] = 'Porting - Dashboard';
        $data['layer'] = DB::table('list_code')
                          ->get();
        return view('pages.v_main',$data);
    }
    public function indexLogin(){
        $data['tittle'] = 'Porting - Dashboard';
        return view('pages.v_login',$data);
    }
    public function postLogin(request $request){
        $email = $request['email'];
        $password = $request['password'];
        $user = DB::table('users')
                ->where('email', $email)
                ->first();
        if($user!==null){
            $hashedPassword = $user->password;
            if (Hash::check($password, $hashedPassword)) {
                $login = DB::table('users')
                         ->where('email', $email)
                         ->first();
            } else {
                $login = null;
            }
        }else{
            Session::flush();
            return redirect()->back()->withErrors("username tidak tersedia");
        }
       
        if($login==null){
            Session::flush();
            return redirect()->back()->withErrors("password salah");
        }
        
        session::put('Data',$login);
        return redirect('/');
    }
    public function server(request $req){

        if (empty($req->input('searchNama')) && empty($req->input('searchAkta'))) {
            $data = DB::table('data_perusahaan')
            ->get();
        } elseif(empty($req->input('searchNama'))&& !empty($req->input('searchAkta'))) {
            $search = $req->input('searchAkta');
            $data = DB::table('data_perusahaan')
                ->where('nomor_akte', 'LIKE', "%{$search}%")
                ->get();
        } elseif(!empty($req->input('searchNama'))&& empty($req->input('searchAkta'))) {
            $search = $req->input('searchNama');
            $data = DB::table('data_perusahaan')
                ->where('nama_perusahaan', 'LIKE', "%{$search}%")
                ->get();
        } else{
            $search1 = $req->input('searchNama');
            $search2 = $req->input('searchAkta');
            $data = DB::table('data_perusahaan')
                ->where('nama_perusahaan', 'LIKE', "%{$search1}%")
                ->where('nomor_akte', 'LIKE', "%{$search2}%")
                ->get();
        }
        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $editLink = "detailPerusahaan/".$row->kode_perusahaan."";
                $link = "<a href='{$editLink}'>
                                ".$row->nama_perusahaan."
                         </a>
                       ";
                return $link;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    public function detail($code){
        $main = DB::table('data_perusahaan')
                ->where('kode_perusahaan',$code)
                ->first();
        $alamat = DB::table('alamat_perusahaan')
                ->where('data_id',$main->data_id)
                ->orderBy('alamat_id','desc')
                ->first();
        $direksi = DB::table('direksi_perusahaan')
                ->where('data_id',$main->data_id)
                ->orderBy('direksi_id','desc')
                ->first();
        $data['ga'] = DB::table('alamat_perusahaan')
                ->select('header')
                ->where('data_id',$main->data_id)
                ->groupBy('header','hirarki')
                ->orderBy('hirarki','asc')
                ->get();
        $data['gd']= DB::table('direksi_perusahaan')
                ->select('header')
                ->where('data_id',$main->data_id)
                ->groupBy('header','hirarki')
                ->orderBy('hirarki','asc')
                ->get();
        
                
        if ($alamat==null) {
            $ah = 0;
        }else{
            $ah = $alamat->hirarki;
        }

        if ($direksi==null) {
            $dh = 0;
        }else{
            $dh = $direksi->hirarki;
        }
        $data['main'] = $main;
        $data['alamat'] =DB::table('alamat_perusahaan')
                        ->where('data_id',$main->data_id)
                        ->where('hirarki',$ah)
                        ->get();
        $data['alamat_all'] =DB::table('alamat_perusahaan')
                        ->where('data_id',$main->data_id)
                        ->get();

        $data['direksi'] =DB::table('direksi_perusahaan')
                        ->where('data_id',$main->data_id)
                        ->where('hirarki',$dh)
                        ->get();
        $data['direksi_all'] =DB::table('direksi_perusahaan')
                        ->where('data_id',$main->data_id)
                        ->get();
        $data['saham'] =DB::table('saham_perusahaan')
                        ->where('data_id',$main->data_id)
                        ->get();
        $data['npwp'] =DB::table('npwp_perusahaan')
                        ->where('data_id',$main->data_id)
                        ->get();
        $data['izin'] =DB::table('izin_perusahaan')
                        ->where('data_id',$main->data_id)
                        ->get();
        $data['data_wiup'] =DB::table('data_wiup')
                        ->where('data_id',$main->data_id)
                        ->get();
        $data['tittle'] = 'Porting - Dashboard';
        return view('pages.v_detail',$data);
    }
    public function logout(){
        Session::flush();
        return redirect('/login');
    }
    public function profilePost(request $request){
        $query = DB::table('data_perusahaan')
                 ->where('data_id',$request['data_id'])
                 ->update([
                    'nama_perusahaan' => $request['nama_perusahaan'],
                    'nomor_akte' => $request['nomor_akte'],
                    'tanggal_akte' => $request['tanggal_akte'],
                    'jenis_perizinan' => $request['jenis_perizinan'],
                    'kode_perusahaan' => $request['kode_perusahaan'],
                    'jenis_badan_usaha' => $request['jenis_badan_usaha']
                 ]);
      
        return redirect()->route('detail', ['code' => $request['kode_perusahaan']])->with('status', 'Profile updated!');
    }
    public function sahamEdit(request $request){
        $data['main'] = DB::table('saham_perusahaan')
                        ->where('saham_id',$request['saham_id'])
                        ->first();
        $data['judul'] = 'Edit Saham';
        $data['type'] = 0;
        $data['route'] = route('saham-edit-act');
        $data['button'] = 'btn-warning';
        $data['button_text'] = 'Save Change';
        $data['form'] = [
            [
                "name" => "saham_id",  
                "type" => "hidden"
            ],[
                "name" => "jenis",  
                "type" => "text"
            ],[
                "name" => "nama",  
                "type" => "text"
            ],[
                "name" => "asal_negara",  
                "type" => "text"
            ],[
                "name" => "presentase",  
                "type" => "number"
            ],[
                "name" => "keterangan",  
                "type" => "text"
            ],[
                "name" => "data_id",  
                "type" => "hidden"
            ],
        ];
        return view('edit.v_saham_edit',$data);
    }
    public function sahamEditAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('saham_perusahaan')
            ->where('saham_id',$request['saham_id'])
            ->update([
                'jenis' => $request['jenis'],
                'nama' => $request['nama'],
                'asal_negara' => $request['asal_negara'],
                'presentase' => $request['presentase'],
                'keterangan' => $request['keterangan']
            ]);
        return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('saham', 'Saham updated!');
    }
    public function sahamDelete(request $request){
            if(!empty($request['saham_id'])){
                $data['main'] = DB::table('saham_perusahaan')
                ->where('saham_id',$request['saham_id'])
                ->first();
            }
            $data['type'] = 0;
            $data['judul'] = 'Delete Saham';
            $data['route'] = route('saham-delete-act');
            $data['button'] = 'btn-danger';
            $data['button_text'] = 'Delete This Data';
            $data['form'] = [
                [
                "name" => "saham_id",  
                "type" => "hidden"
                ],[
                "name" => "jenis",  
                "type" => "text"
                ],[
                "name" => "nama",  
                "type" => "text"
                ],[
                "name" => "asal_negara",  
                "type" => "text"
                ],[
                "name" => "presentase",  
                "type" => "number"
                ],[
                "name" => "keterangan",  
                "type" => "text"
                ],[
                "name" => "data_id",  
                "type" => "hidden"
                ],
            ];
        return view('edit.v_saham_edit',$data);
    }
    public function sahamDeleteAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('saham_perusahaan')
            ->where('saham_id',$request['saham_id'])
            ->delete();
        return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('saham', 'Saham updated!');
    }
    public function sahamNew(request $request){
        if(!empty($request['saham_id'])){
            $data['main'] = DB::table('saham_perusahaan')
            ->where('saham_id',$request['saham_id'])
            ->first();
        }
        $data['type'] = 1;
        $data['data_id'] = $request['data_id'];
        $data['judul'] = 'Tambah Pemilik Saham';
        $data['route'] = route('saham-new-act');
        $data['button'] = 'btn-primary';
        $data['button_text'] = 'Tambah Pemilik Saham';
        $data['form'] = [
            [
            "name" => "jenis",  
            "type" => "text"
            ],[
            "name" => "nama",  
            "type" => "text"
            ],[
            "name" => "asal_negara",  
            "type" => "text"
            ],[
            "name" => "presentase",  
            "type" => "number"
            ],[
            "name" => "keterangan",  
            "type" => "text"
            ]
        ];
        return view('edit.v_saham_edit',$data);
    }
    public function sahamNewAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('saham_perusahaan')
            ->insert([
                'jenis' => $request['jenis'],
                'nama' => $request['nama'],
                'asal_negara' => $request['asal_negara'],
                'presentase' => $request['presentase'],
                'keterangan' => $request['keterangan'],
                'data_id' => $request['data_id']
            ]);
        return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('saham', 'Saham updated!');
    }
    public function npwpEdit(request $request){
        $data['main'] = DB::table('npwp_perusahaan')
                        ->where('npwp_id',$request['npwp_id'])
                        ->first();
        $data['judul'] = 'Edit NPWP';
        $data['type'] = 0;
        $data['route'] = route('npwp-edit-act');
        $data['button'] = 'btn-warning';
        $data['button_text'] = 'Save Change';
        $data['form'] = [
            [
                "name" => "npwp_id",  
                "type" => "hidden"
            ],[
                "name" => "nomor_npwp",  
                "type" => "text"
            ],[
                "name" => "nama_npwp",  
                "type" => "text"
            ],[
                "name" => "alamat_npwp",  
                "type" => "text"
            ],[
                "name" => "dokumen_npwp",  
                "type" => "text"
            ],[
                "name" => "keterangan",  
                "type" => "text"
            ],[
                "name" => "data_id",  
                "type" => "hidden"
            ],
        ];
        return view('edit.v_saham_edit',$data);
    }
    public function npwpEditAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('npwp_perusahaan')
            ->where('npwp_id',$request['npwp_id'])
            ->update([
                'nomor_npwp' => $request['nomor_npwp'],
                'nama_npwp' => $request['nama_npwp'],
                'alamat_npwp' => $request['alamat_npwp'],
                'dokumen_npwp' => $request['dokumen_npwp'],
                'keterangan' => $request['keterangan']
            ]);
        return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('npwp', 'NPWP updated!');
    }
    public function npwpDelete(request $request){
            if(!empty($request['npwp_id'])){
                $data['main'] = DB::table('npwp_perusahaan')
                ->where('npwp_id',$request['npwp_id'])
                ->first();
            }
            $data['type'] = 0;
            $data['judul'] = 'Delete npwp';
            $data['route'] = route('npwp-delete-act');
            $data['button'] = 'btn-danger';
            $data['button_text'] = 'Delete This Data';
            $data['form'] = [
                [
                    "name" => "npwp_id",  
                    "type" => "hidden"
                ],[
                    "name" => "nomor_npwp",  
                    "type" => "text"
                ],[
                    "name" => "nama_npwp",  
                    "type" => "text"
                ],[
                    "name" => "alamat_npwp",  
                    "type" => "text"
                ],[
                    "name" => "dokumen_npwp",  
                    "type" => "text"
                ],[
                    "name" => "keterangan",  
                    "type" => "text"
                ],[
                    "name" => "data_id",  
                    "type" => "hidden"
                ],
            ];
        return view('edit.v_saham_edit',$data);
    }
    public function npwpDeleteAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('npwp_perusahaan')
            ->where('npwp_id',$request['npwp_id'])
            ->delete();
        return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('npwp', 'NPWP updated!');
    }
    public function npwpNew(request $request){
        if(!empty($request['npwp_id'])){
            $data['main'] = DB::table('npwp_perusahaan')
            ->where('npwp_id',$request['npwp_id'])
            ->first();
        }
        $data['type'] = 1;
        $data['data_id'] = $request['data_id'];
        $data['judul'] = 'Tambah Pemilik npwp';
        $data['route'] = route('npwp-new-act');
        $data['button'] = 'btn-primary';
        $data['button_text'] = 'Tambah npwp';
        $data['form'] = [
            [
                "name" => "nomor_npwp",  
                "type" => "text"
            ],[
                "name" => "nama_npwp",  
                "type" => "text"
            ],[
                "name" => "alamat_npwp",  
                "type" => "text"
            ],[
                "name" => "dokumen_npwp",  
                "type" => "text"
            ],[
                "name" => "keterangan",  
                "type" => "text"
            ]
        ];
        return view('edit.v_saham_edit',$data);
    }
    public function npwpNewAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('npwp_perusahaan')
            ->insert([
                'nomor_npwp' => $request['nomor_npwp'],
                'nama_npwp' => $request['nama_npwp'],
                'alamat_npwp' => $request['alamat_npwp'],
                'dokumen_npwp' => $request['dokumen_npwp'],
                'keterangan' => $request['keterangan'],
                'data_id' => $request['data_id']
            ]);
        return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('npwp', 'NPWP updated!');
    }
    public function alamatEdit(request $request){
        $data['main'] = DB::table('alamat_perusahaan')
                        ->where('alamat_id',$request['alamat_id'])
                        ->first();
        $data['judul'] = 'Edit alamat';
        $data['type'] = 0;
        $data['route'] = route('alamat-edit-act');
        $data['button'] = 'btn-warning';
        $data['button_text'] = 'Save Change';
        $data['form'] = [
            [
                "name" => "alamat_id",  
                "type" => "hidden"
            ],[
                "name" => "peruntukan_alamat",  
                "type" => "text"
            ],[
                "name" => "alamat",  
                "type" => "text"
            ],[
                "name" => "contact_person",  
                "type" => "text"
            ],[
                "name" => "header",  
                "type" => "text"
            ],[
                "name" => "hirarki",  
                "type" => "number"
            ],[
                "name" => "data_id",  
                "type" => "hidden"
            ],
        ];
        return view('edit.v_saham_edit',$data);
    }
    public function alamatEditAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('alamat_perusahaan')
            ->where('alamat_id',$request['alamat_id'])
            ->update([
                'peruntukan_alamat' => $request['peruntukan_alamat'],
                'alamat' => $request['alamat'],
                'contact_person' => $request['contact_person'],
                'header' => $request['header'],
                'hirarki' => $request['hirarki']
            ]);
        return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('alamat', 'Alamat updated!');
    }
    public function alamatDelete(request $request){
            if(!empty($request['alamat_id'])){
                $data['main'] = DB::table('alamat_perusahaan')
                ->where('alamat_id',$request['alamat_id'])
                ->first();
            }
            $data['type'] = 0;
            $data['judul'] = 'Delete alamat';
            $data['route'] = route('alamat-delete-act');
            $data['button'] = 'btn-danger';
            $data['button_text'] = 'Delete This Data';
            $data['form'] = [
                [
                    "name" => "alamat_id",  
                    "type" => "hidden"
                ],[
                    "name" => "peruntukan_alamat",  
                    "type" => "text"
                ],[
                    "name" => "alamat",  
                    "type" => "text"
                ],[
                    "name" => "contact_person",  
                    "type" => "text"
                ],[
                    "name" => "header",  
                    "type" => "text"
                ],[
                    "name" => "hirarki",  
                    "type" => "number"
                ],[
                    "name" => "data_id",  
                    "type" => "hidden"
                ],
            ];
        return view('edit.v_saham_edit',$data);
    }
    public function alamatDeleteAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('alamat_perusahaan')
            ->where('alamat_id',$request['alamat_id'])
            ->delete();
        return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('alamat', 'Alamat updated!');
    }
    public function alamatNew(request $request){
        if(!empty($request['alamat_id'])){
            $data['main'] = DB::table('alamat_perusahaan')
            ->where('alamat_id',$request['alamat_id'])
            ->first();
        }
        $data['type'] = 1;
        $data['data_id'] = $request['data_id'];
        $data['judul'] = 'Tambah Pemilik alamat';
        $data['route'] = route('alamat-new-act');
        $data['button'] = 'btn-primary';
        $data['button_text'] = 'Tambah alamat';
        $data['form'] = [
           [
                "name" => "peruntukan_alamat",  
                "type" => "text"
            ],[
                "name" => "alamat",  
                "type" => "text"
            ],[
                "name" => "contact_person",  
                "type" => "text"
            ],[
                "name" => "header",  
                "type" => "text"
            ],[
                "name" => "hirarki",  
                "type" => "number"
            ],[
                "name" => "data_id",  
                "type" => "hidden"
            ],
        ];
        return view('edit.v_saham_edit',$data);
    }
    public function alamatNewAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('alamat_perusahaan')
            ->insert([
                'peruntukan_alamat' => $request['peruntukan_alamat'],
                'alamat' => $request['alamat'],
                'contact_person' => $request['contact_person'],
                'header' => $request['header'],
                'hirarki' => $request['hirarki'],
                'data_id' => $request['data_id']
            ]);
            return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('alamat', 'Alamat updated!');
    }
    public function direksiEdit(request $request){
        $data['main'] = DB::table('direksi_perusahaan')
                        ->where('direksi_id',$request['direksi_id'])
                        ->first();
        $data['judul'] = 'Edit direksi';
        $data['type'] = 0;
        $data['route'] = route('direksi-edit-act');
        $data['button'] = 'btn-warning';
        $data['button_text'] = 'Save Change';
        $data['form'] = [
            [
                "name" => "direksi_id",  
                "type" => "hidden"
            ],[
                "name" => "kode",  
                "type" => "text"
            ],[
                "name" => "nama",  
                "type" => "text"
            ],[
                "name" => "jabatan",  
                "type" => "text"
            ],[
                "name" => "periode",  
                "type" => "text"
            ],[
                "name" => "header",  
                "type" => "text"
            ],[
                "name" => "hirarki",  
                "type" => "number"
            ],[
                "name" => "data_id",  
                "type" => "hidden"
            ],
        ];
        return view('edit.v_saham_edit',$data);
    }
    public function direksiEditAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('direksi_perusahaan')
            ->where('direksi_id',$request['direksi_id'])
            ->update([
                'kode' => $request['kode'],
                'nama' => $request['nama'],
                'jabatan' => $request['jabatan'],
                'periode' => $request['periode'],
                'header' => $request['header'],
                'hirarki' => $request['hirarki']
            ]);
        return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('direksi', 'direksi updated!');
    }
    public function direksiDelete(request $request){
            if(!empty($request['direksi_id'])){
                $data['main'] = DB::table('direksi_perusahaan')
                ->where('direksi_id',$request['direksi_id'])
                ->first();
            }
            $data['type'] = 0;
            $data['judul'] = 'Delete direksi';
            $data['route'] = route('direksi-delete-act');
            $data['button'] = 'btn-danger';
            $data['button_text'] = 'Delete This Data';
            $data['form'] = [
                [
                    "name" => "direksi_id",  
                    "type" => "hidden"
                ],[
                    "name" => "kode",  
                    "type" => "text"
                ],[
                    "name" => "nama",  
                    "type" => "text"
                ],[
                    "name" => "jabatan",  
                    "type" => "text"
                ],[
                    "name" => "periode",  
                    "type" => "text"
                ],[
                    "name" => "header",  
                    "type" => "text"
                ],[
                    "name" => "hirarki",  
                    "type" => "number"
                ],[
                    "name" => "data_id",  
                    "type" => "hidden"
                ],
            ];
        return view('edit.v_saham_edit',$data);
    }
    public function direksiDeleteAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('direksi_perusahaan')
            ->where('direksi_id',$request['direksi_id'])
            ->delete();
        return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('direksi', 'direksi updated!');
    }
    public function direksiNew(request $request){
        if(!empty($request['direksi_id'])){
            $data['main'] = DB::table('direksi_perusahaan')
            ->where('direksi_id',$request['direksi_id'])
            ->first();
        }
        $data['type'] = 1;
        $data['data_id'] = $request['data_id'];
        $data['judul'] = 'Tambah Pemilik direksi';
        $data['route'] = route('direksi-new-act');
        $data['button'] = 'btn-primary';
        $data['button_text'] = 'Tambah direksi';
        $data['form'] = [
            [
                "name" => "kode",  
                "type" => "text"
            ],[
                "name" => "nama",  
                "type" => "text"
            ],[
                "name" => "jabatan",  
                "type" => "text"
            ],[
                "name" => "periode",  
                "type" => "text"
            ],[
                "name" => "header",  
                "type" => "text"
            ],[
                "name" => "hirarki",  
                "type" => "number"
            ],[
                "name" => "data_id",  
                "type" => "hidden"
            ],
        ];
        return view('edit.v_saham_edit',$data);
    }
    public function direksiNewAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('direksi_perusahaan')
            ->insert([
                'kode' => $request['kode'],
                'nama' => $request['nama'],
                'jabatan' => $request['jabatan'],
                'periode' => $request['periode'],
                'header' => $request['header'],
                'hirarki' => $request['hirarki'],
                'data_id' => $request['data_id']
            ]);
            return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('direksi', 'Alamat updated!');
    }
    public function izinEdit(request $request){
        $data['main'] = DB::table('izin_perusahaan')
                        ->where('izin_id',$request['izin_id'])
                        ->first();
        $data['judul'] = 'Edit izin';
        $data['type'] = 0;
        $data['route'] = route('izin-edit-act');
        $data['button'] = 'btn-warning';
        $data['button_text'] = 'Save Change';
        $data['form'] = [
            [
                "name" => "izin_id",  
                "type" => "hidden"
            ],[
                "name" => "jenis_perizinan",  
                "type" => "text"
            ],[
                "name" => "nomor_perizinan",  
                "type" => "text"
            ],[
                "name" => "tahapan_kegiatan",  
                "type" => "text"
            ],[
                "name" => "kode_wiup",  
                "type" => "text"
            ],[
                "name" => "komoditas",  
                "type" => "text"
            ],[
                "name" => "luas_ha",  
                "type" => "text"
            ],[
                "name" => "tgl_mulai_berlaku",  
                "type" => "text"
            ],[
                "name" => "tgl_berakhir",  
                "type" => "text"
            ],[
                "name" => "tahapan_cnc",  
                "type" => "text"
            ],[
                "name" => "lokasi",  
                "type" => "text"
            ],[
                "name" => "data_id",  
                "type" => "hidden"
            ],
        ];
        return view('edit.v_saham_edit',$data);
    }
    public function izinEditAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('izin_perusahaan')
            ->where('izin_id',$request['izin_id'])
            ->update([
                'jenis_perizinan' => $request['jenis_perizinan'],
                'nomor_perizinan' => $request['nomor_perizinan'],
                'tahapan_kegiatan' => $request['tahapan_kegiatan'],
                'kode_wiup' => $request['kode_wiup'],
                'komoditas' => $request['komoditas'],
                'luas_ha' => $request['luas_ha'],
                'tgl_mulai_berlaku' => $request['tgl_mulai_berlaku'],
                'tgl_berakhir' => $request['tgl_berakhir'],
                'tahapan_cnc' => $request['tahapan_cnc'],
                'lokasi' => $request['lokasi']
            ]);
        return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('izin', 'izin updated!');
    }
    public function izinDelete(request $request){
            if(!empty($request['izin_id'])){
                $data['main'] = DB::table('izin_perusahaan')
                ->where('izin_id',$request['izin_id'])
                ->first();
            }
            $data['type'] = 0;
            $data['judul'] = 'Delete izin';
            $data['route'] = route('izin-delete-act');
            $data['button'] = 'btn-danger';
            $data['button_text'] = 'Delete This Data';
            $data['form'] = [
                [
                    "name" => "izin_id",  
                    "type" => "hidden"
                ],[
                    "name" => "jenis_perizinan",  
                    "type" => "text"
                ],[
                    "name" => "nomor_perizinan",  
                    "type" => "text"
                ],[
                    "name" => "tahapan_kegiatan",  
                    "type" => "text"
                ],[
                    "name" => "kode_wiup",  
                    "type" => "text"
                ],[
                    "name" => "komoditas",  
                    "type" => "text"
                ],[
                    "name" => "luas_ha",  
                    "type" => "text"
                ],[
                    "name" => "tgl_mulai_berlaku",  
                    "type" => "text"
                ],[
                    "name" => "tgl_berakhir",  
                    "type" => "text"
                ],[
                    "name" => "tahapan_cnc",  
                    "type" => "text"
                ],[
                    "name" => "lokasi",  
                    "type" => "text"
                ],[
                    "name" => "data_id",  
                    "type" => "hidden"
                ],
            ];
        return view('edit.v_saham_edit',$data);
    }
    public function izinDeleteAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('izin_perusahaan')
            ->where('izin_id',$request['izin_id'])
            ->delete();
        return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('izin', 'izin updated!');
    }
    public function izinNew(request $request){
        if(!empty($request['izin_id'])){
            $data['main'] = DB::table('izin_perusahaan')
            ->where('izin_id',$request['izin_id'])
            ->first();
        }
        $data['type'] = 1;
        $data['data_id'] = $request['data_id'];
        $data['judul'] = 'Tambah Pemilik izin';
        $data['route'] = route('izin-new-act');
        $data['button'] = 'btn-primary';
        $data['button_text'] = 'Tambah izin';
        $data['form'] = [
            [
                "name" => "jenis_perizinan",  
                "type" => "text"
            ],[
                "name" => "nomor_perizinan",  
                "type" => "text"
            ],[
                "name" => "tahapan_kegiatan",  
                "type" => "text"
            ],[
                "name" => "kode_wiup",  
                "type" => "text"
            ],[
                "name" => "komoditas",  
                "type" => "text"
            ],[
                "name" => "luas_ha",  
                "type" => "text"
            ],[
                "name" => "tgl_mulai_berlaku",  
                "type" => "text"
            ],[
                "name" => "tgl_berakhir",  
                "type" => "text"
            ],[
                "name" => "tahapan_cnc",  
                "type" => "text"
            ],[
                "name" => "lokasi",  
                "type" => "text"
            ],[
                "name" => "data_id",  
                "type" => "hidden"
            ],
        ];
        return view('edit.v_saham_edit',$data);
    }
    public function izinNewAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('izin_perusahaan')
            ->insert([
                'jenis_perizinan' => $request['jenis_perizinan'],
                'nomor_perizinan' => $request['nomor_perizinan'],
                'tahapan_kegiatan' => $request['tahapan_kegiatan'],
                'kode_wiup' => $request['kode_wiup'],
                'komoditas' => $request['komoditas'],
                'luas_ha' => $request['luas_ha'],
                'tgl_mulai_berlaku' => $request['tgl_mulai_berlaku'],
                'tgl_berakhir' => $request['tgl_berakhir'],
                'tahapan_cnc' => $request['tahapan_cnc'],
                'lokasi' => $request['lokasi'],
                'data_id' => $request['data_id']
            ]);
            return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('izin', 'Alamat updated!');
    }
    public function wiupEdit(request $request){
        $data['main'] = DB::table('data_wiup')
                        ->where('objectid',$request['objectid'])
                        ->first();
        $data['judul'] = 'Edit izin';
        $data['type'] = 0;
        $data['route'] = route('wiup-edit-act');
        $data['button'] = 'btn-warning';
        $data['button_text'] = 'Save Change';
        $data['form'] = [
            [
                "name" => "objectid",  
                "type" => "hidden"
            ],[
                "name" => "pulau",  
                "type" => "text"
            ],[
                "name" => "pejabat",  
                "type" => "text"
            ],[
                "name" => "id_prov",  
                "type" => "text"
            ],[
                "name" => "nama_prov",  
                "type" => "text"
            ],[
                "name" => "id_kab",  
                "type" => "text"
            ],[
                "name" => "nama_kab",  
                "type" => "text"
            ],[
                "name" => "jenis_izin",  
                "type" => "text"
            ],[
                "name" => "badan_usaha",  
                "type" => "text"
            ],[
                "name" => "nama_usaha",  
                "type" => "text"
            ],[
                "name" => "kode_wiup",  
                "type" => "text"
            ],[
                "name" => "sk_iup",  
                "type" => "text"
            ],[
                "name" => "tgl_berlaku",  
                "type" => "date"
            ],[
                "name" => "tgl_akhir",  
                "type" => "date"
            ],[
                "name" => "kegiatan",  
                "type" => "text"
            ],[
                "name" => "luas_sk",  
                "type" => "number"
            ],[
                "name" => "komoditas",  
                "type" => "text"
            ],[
                "name" => "kode_golongan",  
                "type" => "text"
            ],[
                "name" => "kode_jnskom",  
                "type" => "text"
            ],[
                "name" => "cnc",  
                "type" => "text"
            ],[
                "name" => "generasi",  
                "type" => "text"
            ],[
                "name" => "kode_wil",  
                "type" => "text"
            ],[
                "name" => "lokasi",  
                "type" => "text"
            ],[
                "name" => "data_id",  
                "type" => "hidden"
            ],
        ];
        return view('edit.v_saham_edit',$data);
    }
    public function wiupEditAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('data_wiup')
            ->where('objectid',$request['objectid'])
            ->update([
                "pulau" => $request['pulau'],
                "pejabat" => $request['pejabat'],
                "id_prov" => $request['id_prov'],
                "nama_prov" => $request['nama_prov'],
                "id_kab" => $request['id_kab'],
                "nama_kab" => $request['nama_kab'],
                "jenis_izin" => $request['jenis_izin'],
                "badan_usaha" => $request['badan_usaha'],
                "nama_usaha" => $request['nama_usaha'],
                "kode_wiup" => $request['kode_wiup'],
                "sk_iup" => $request['sk_iup'],
                "tgl_berlaku" => $request['tgl_berlaku'],
                "tgl_akhir" => $request['tgl_akhir'],
                "kegiatan" => $request['kegiatan'],
                "luas_sk" => $request['luas_sk'],
                "komoditas" => $request['komoditas'],
                "kode_golongan" => $request['kode_golongan'],
                "kode_jnskom" => $request['kode_jnskom'],
                "cnc" => $request['cnc'],
                "generasi" => $request['generasi'],
                "kode_wil" => $request['kode_wil'],
                "lokasi" => $request['lokasi'],
                "data_id" => $request['data_id']
            ]);
        return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('izin', 'izin updated!');
    }
    public function wiupDelete(request $request){
            if(!empty($request['objectid'])){
                $data['main'] = DB::table('data_wiup')
                ->where('objectid',$request['objectid'])
                ->first();
            }
            $data['type'] = 0;
            $data['judul'] = 'Delete wiup';
            $data['route'] = route('wiup-delete-act');
            $data['button'] = 'btn-danger';
            $data['button_text'] = 'Delete This Data';
            $data['form'] = [
                [
                    "name" => "objectid",  
                    "type" => "hidden"
                ],[
                    "name" => "pulau",  
                    "type" => "text"
                ],[
                    "name" => "pejabat",  
                    "type" => "text"
                ],[
                    "name" => "id_prov",  
                    "type" => "text"
                ],[
                    "name" => "nama_prov",  
                    "type" => "text"
                ],[
                    "name" => "id_kab",  
                    "type" => "text"
                ],[
                    "name" => "nama_kab",  
                    "type" => "text"
                ],[
                    "name" => "jenis_izin",  
                    "type" => "text"
                ],[
                    "name" => "badan_usaha",  
                    "type" => "text"
                ],[
                    "name" => "nama_usaha",  
                    "type" => "text"
                ],[
                    "name" => "kode_wiup",  
                    "type" => "text"
                ],[
                    "name" => "sk_iup",  
                    "type" => "text"
                ],[
                    "name" => "tgl_berlaku",  
                    "type" => "date"
                ],[
                    "name" => "tgl_akhir",  
                    "type" => "date"
                ],[
                    "name" => "kegiatan",  
                    "type" => "text"
                ],[
                    "name" => "luas_sk",  
                    "type" => "number"
                ],[
                    "name" => "komoditas",  
                    "type" => "text"
                ],[
                    "name" => "kode_golongan",  
                    "type" => "text"
                ],[
                    "name" => "kode_jnskom",  
                    "type" => "text"
                ],[
                    "name" => "cnc",  
                    "type" => "text"
                ],[
                    "name" => "generasi",  
                    "type" => "text"
                ],[
                    "name" => "kode_wil",  
                    "type" => "text"
                ],[
                    "name" => "lokasi",  
                    "type" => "text"
                ],[
                    "name" => "data_id",  
                    "type" => "hidden"
                ],
            ];
        return view('edit.v_saham_edit',$data);
    }
    public function wiupDeleteAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('data_wiup')
            ->where('objectid',$request['objectid'])
            ->delete();
        return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('izin', 'izin updated!');
    }
    public function wiupNew(request $request){
        if(!empty($request['objectid'])){
            $data['main'] = DB::table('data_wiup')
            ->where('objectid',$request['objectid'])
            ->first();
        }
        $data['type'] = 1;
        $data['data_id'] = $request['data_id'];
        $data['judul'] = 'Tambah Pemilik izin';
        $data['route'] = route('wiup-new-act');
        $data['button'] = 'btn-primary';
        $data['button_text'] = 'Tambah izin';
        $data['form'] = [
            [
                "name" => "objectid",  
                "type" => "hidden"
            ],[
                "name" => "pulau",  
                "type" => "text"
            ],[
                "name" => "pejabat",  
                "type" => "text"
            ],[
                "name" => "id_prov",  
                "type" => "text"
            ],[
                "name" => "nama_prov",  
                "type" => "text"
            ],[
                "name" => "id_kab",  
                "type" => "text"
            ],[
                "name" => "nama_kab",  
                "type" => "text"
            ],[
                "name" => "jenis_izin",  
                "type" => "text"
            ],[
                "name" => "badan_usaha",  
                "type" => "text"
            ],[
                "name" => "nama_usaha",  
                "type" => "text"
            ],[
                "name" => "kode_wiup",  
                "type" => "text"
            ],[
                "name" => "sk_iup",  
                "type" => "text"
            ],[
                "name" => "tgl_berlaku",  
                "type" => "date"
            ],[
                "name" => "tgl_akhir",  
                "type" => "date"
            ],[
                "name" => "kegiatan",  
                "type" => "text"
            ],[
                "name" => "luas_sk",  
                "type" => "number"
            ],[
                "name" => "komoditas",  
                "type" => "text"
            ],[
                "name" => "kode_golongan",  
                "type" => "text"
            ],[
                "name" => "kode_jnskom",  
                "type" => "text"
            ],[
                "name" => "cnc",  
                "type" => "text"
            ],[
                "name" => "generasi",  
                "type" => "text"
            ],[
                "name" => "kode_wil",  
                "type" => "text"
            ],[
                "name" => "lokasi",  
                "type" => "text"
            ],[
                "name" => "data_id",  
                "type" => "hidden"
            ],
        ];
        return view('edit.v_saham_edit',$data);
    }
    public function wiupNewAct(request $request){
        $query = DB::table('data_perusahaan')
            ->where('data_id',$request['data_id'])
            ->first();
        $update = DB::table('data_wiup')
            ->insert([
                "pulau" => $request['pulau'],
                "pejabat" => $request['pejabat'],
                "id_prov" => $request['id_prov'],
                "nama_prov" => $request['nama_prov'],
                "id_kab" => $request['id_kab'],
                "nama_kab" => $request['nama_kab'],
                "jenis_izin" => $request['jenis_izin'],
                "badan_usaha" => $request['badan_usaha'],
                "nama_usaha" => $request['nama_usaha'],
                "kode_wiup" => $request['kode_wiup'],
                "sk_iup" => $request['sk_iup'],
                "tgl_berlaku" => $request['tgl_berlaku'],
                "tgl_akhir" => $request['tgl_akhir'],
                "kegiatan" => $request['kegiatan'],
                "luas_sk" => $request['luas_sk'],
                "komoditas" => $request['komoditas'],
                "kode_golongan" => $request['kode_golongan'],
                "kode_jnskom" => $request['kode_jnskom'],
                "cnc" => $request['cnc'],
                "generasi" => $request['generasi'],
                "kode_wil" => $request['kode_wil'],
                "lokasi" => $request['lokasi'],
                "data_id" => $request['data_id']
            ]);
            return redirect()->route('detail', ['code' => $query->kode_perusahaan])->with('izin', 'Alamat updated!');
    }
    public function register(){  
        return view('edit.v_tambah_akun');
    }
    public function registerAct(request $request){
    
        $hashedPassword = Hash::make($request['password']);
        $query = DB::table('users')
                ->insert([
                    'email' => $request['email'],
                    'password' => $hashedPassword,
                    'role' => 'guest',
                    'company_name' => $request['company_name']
                ]);
        return redirect()->route('main');
    }
    public function coordinat($objectid){  
        $id = $objectid;
        $data['wiup'] = DB::table('data_wiup')
            ->where('objectid',$objectid)
            ->first();
        $data['all'] = DB::table('coordinat')
            ->join('data_wiup','data_wiup.objectid','coordinat.object_id')
            ->join('data_perusahaan','data_perusahaan.data_id','data_wiup.data_id')
            ->where('object_id',$objectid)
            ->get();

        return view('pages.v_coordinat',$data);
    }
    public function coordinatEdit(request $request){
        $data['main'] = DB::table('coordinat')
                        ->where('coordinat_id',$request['coordinat_id'])
                        ->first();
        $data['judul'] = 'Edit Coordinat';
        $data['type'] = 0;
        $data['route'] = route('coordinat-edit-act');
        $data['button'] = 'btn-warning';
        $data['button_text'] = 'Save Change';
        $data['form'] = [
            [
                "name" => "x",  
                "type" => "number"
            ],[
                "name" => "y",  
                "type" => "number"
            ],[
                "name" => "coordinat_id",  
                "type" => "hidden"
            ],[
                "name" => "object_id",  
                "type" => "hidden"
            ],
        ];
        return view('edit.v_saham_edit',$data);
    }
    public function coordinatEditAct(request $request){
        $query = DB::table('data_wiup')
            ->where('objectid',$request['object_id'])
            ->first();
        $update = DB::table('coordinat')
            ->where('coordinat_id',$request['coordinat_id'])
            ->update([
                "x" => $request['x'],
                "y" => $request['y'],
            ]);
        return redirect()->route('coordinat', ['objectid' => $query->objectid])->with('izin', 'izin updated!');
    }
    public function coordinatDelete(request $request){
            if(!empty($request['coordinat_id'])){
                $data['main'] = DB::table('coordinat')
                ->where('coordinat_id',$request['coordinat_id'])
                ->first();
            }
            $data['type'] = 0;
            $data['judul'] = 'Delete coordinat';
            $data['route'] = route('coordinat-delete-act');
            $data['button'] = 'btn-danger';
            $data['button_text'] = 'Delete This Data';
            $data['form'] = [
                [
                    "name" => "x",  
                    "type" => "number"
                ],[
                    "name" => "y",  
                    "type" => "number"
                ],[
                    "name" => "coordinat_id",  
                    "type" => "hidden"
                ],[
                    "name" => "object_id",  
                    "type" => "hidden"
                ],
            ];
        return view('edit.v_saham_edit',$data);
    }
    public function coordinatDeleteAct(request $request){
        $query = DB::table('data_wiup')
            ->where('objectid',$request['object_id'])
            ->first();
        $update = DB::table('coordinat')
            ->where('coordinat_id',$request['coordinat_id'])
            ->delete();
        return redirect()->route('coordinat', ['objectid' => $query->objectid])->with('izin', 'izin updated!');
    }
    public function coordinatNew(request $request){
        if(!empty($request['coordinat_id'])){
            $data['main'] = DB::table('coordinat')
            ->where('coordinat_id',$request['coordinat_id'])
            ->first();
        }
        $data['type'] = 1;
        $data['judul'] = 'Tambah Coordinat';
        $data['data_id'] = $request['objectid'];
        $data['route'] = route('coordinat-new-act');
        $data['button'] = 'btn-primary';
        $data['button_text'] = 'Tambah Coordinat';
        $data['form'] = [
            [
                "name" => "x",  
                "type" => "number"
            ],[
                "name" => "y",  
                "type" => "number"
            ],[
                "name" => "coordinat_id",  
                "type" => "hidden"
            ],[
                "name" => "object_id",  
                "type" => "hidden"
            ],
        ];
        return view('edit.v_saham_edit',$data);
    }
    public function coordinatNewAct(request $request){
        $query = DB::table('data_wiup')
            ->where('objectid',$request['data_id'])
            ->first();

        $update = DB::table('coordinat')
            ->insert([
                "x" => $request['x'],
                "y" => $request['y'],
                "object_id" => $request['data_id'],
            ]);
        return redirect()->route('coordinat',['objectid' => $request['data_id']])->with('izin', 'izin updated!');
    }
}
