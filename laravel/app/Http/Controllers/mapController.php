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
class mapController extends Controller
{
    public function index(){
        
            $data['title'] = 'Admin';
            $features2 = [];
            //data_2
                $izinPerusahaan2 = DB::table('data_wiup')
                    ->select([
                        'data_wiup.objectid',
                        'data_wiup.data_id',
                        'data_perusahaan.jenis_badan_usaha',
                        'data_perusahaan.nama_perusahaan',
                        'data_wiup.sk_iup',
                        'data_wiup.jenis_izin',
                        'data_wiup.kegiatan',
                        'data_wiup.komoditas',
                        'data_wiup.luas_sk',
                        'data_wiup.tgl_berlaku',
                        'data_wiup.tgl_akhir',
                        'data_wiup.cnc',
                        'data_wiup.lokasi',
                        'data_wiup.kode_wiup',
                        'data_wiup.kode_golongan',
                        'coordinat.x',
                        'coordinat.y'
                    ])
                    ->leftJoin('coordinat','data_wiup.objectid','coordinat.object_id')
                    ->join('data_perusahaan','data_wiup.data_id','data_perusahaan.data_id')
                    ->get()
                    ->groupBy('objectid'); // Group by izin_id to process each izin_perusahaan separately
                $features2 = [];    
                foreach ($izinPerusahaan2 as $izinId2 => $items2) {
                    $item2 = $items2->first(); // Get the first item as the representative

                    // Collect coordinates
                    $coordinates2 = $items2->map(function($coord2) {
                        return [$coord2->x, $coord2->y];
                    })->filter()->all(); // Filter out null values

                    if (count($coordinates2) > 0) {
                        if      ($item2->jenis_izin == 'IPR' && ($item2->kode_golongan == '2' || $item2->kode_golongan == '5')) {
                            $symbol = 'symbol-1';
                        }elseif ($item2->jenis_izin == 'IUP' && ($item2->kode_golongan == '2' || $item2->kode_golongan == '6')){
                            $symbol = 'symbol-2';
                        }elseif ($item2->jenis_izin == 'IUP' && ($item2->kode_golongan == '3' || $item2->kode_golongan == '3')){
                            $symbol = 'symbol-3';
                        }elseif ($item2->jenis_izin == 'IUP' && ($item2->kode_golongan == '4' || $item2->kode_golongan == '4')){
                            $symbol = 'symbol-4';
                        }elseif ($item2->jenis_izin == 'IUP' && ($item2->kode_golongan == '5' || $item2->kode_golongan == '5')){
                            $symbol = 'symbol-5';
                        }elseif ($item2->jenis_izin == 'IUPK' && ($item2->kode_golongan == '2' || $item2->kode_golongan == '2')){
                            $symbol = 'symbol-6';
                        }elseif ($item2->jenis_izin == 'IUPK' && ($item2->kode_golongan == '3' || $item2->kode_golongan == '3')){
                            $symbol = 'symbol-7';
                        }elseif ($item2->jenis_izin == 'KK' && ($item2->kode_golongan == '2' || $item2->kode_golongan == '4')){
                            $symbol = 'symbol-8';
                        }elseif ($item2->jenis_izin == 'PKP2B' && ($item2->kode_golongan == '3' || $item2->kode_golongan == '3')){
                            $symbol = 'symbol-9';
                        }elseif ($item2->jenis_izin == 'WIUP' && ($item2->kode_golongan == '6' || $item2->kode_golongan == '6')){
                            $symbol = 'symbol-10';
                        }else{
                            $symbol = 'symbol-11';
                        }
                        $features2[] = [
                            "type" => "Feature",
                            "properties" => [
                                "name" => $item2->jenis_badan_usaha . "." . $item2->nama_perusahaan,
                                "sk_iup" => $item2->sk_iup,
                                "jenis_perizinan" => $item2->jenis_izin,
                                "tahapan_kegiatan" => $item2->kegiatan,
                                "komoditas" => $item2->komoditas,
                                "luas_ha" => $item2->luas_sk,
                                "tgl_mulai_berlaku" => $item2->tgl_berlaku,
                                "tgl_berakhir" => $item2->tgl_akhir,
                                "tahapan_cnc" => $item2->cnc,
                                "lokasi" => $item2->lokasi,
                                "kode_wiup" => $item2->kode_wiup,
                                "object_id" => $item2->objectid,
                                "symbol" => $symbol
                                
                            ],
                            "geometry" => [
                                "type" => "Polygon",
                                "coordinates" => [$coordinates2]
                            ],
                            "id" => $izinId2
                        ];
                    }
                }
            //end data_2
            $data["ft"] = ["type" => "FeatureCollection", "features" => $features2];
        
            return view('pages.v_map', $data);
        }
    public function data() {
        // Fetch the data with eager loading
        $izinPerusahaan = DB::table('data_wiup')
            ->join('data_perusahaan', 'data_perusahaan.data_id', '=', 'izin_perusahaan.data_id')
            ->leftJoin('coordinat', 'coordinat.sk_iup', '=', 'izin_perusahaan.nomor_perizinan')
            ->select(
                'izin_perusahaan.*',
                'data_perusahaan.jenis_badan_usaha',
                'data_perusahaan.nama_perusahaan',
                'coordinat.x as coord_x',
                'coordinat.y as coord_y',
                'coordinat.kode_wiup',
                'coordinat.object_id'
            )
            ->get()
            ->groupBy('izin_id'); // Group by izin_id to process each izin_perusahaan separately
    
        $features = [];
    
        foreach ($izinPerusahaan as $izinId => $items) {
            $item = $items->first(); // Get the first item as the representative
    
            // Collect coordinates
            $coordinates = $items->map(function($coord) {
                return [$coord->coord_x, $coord->coord_y];
            })->filter()->all(); // Filter out null values
    
            if (count($coordinates) > 0) {
                $features[] = [
                    "type" => "Feature",
                    "properties" => [
                        "name" => $item->jenis_badan_usaha . "." . $item->nama_perusahaan,
                        "sk_iup" => $item->nomor_perizinan,
                        "jenis_perizinan" => $item->jenis_perizinan,
                        "tahapan_kegiatan" => $item->tahapan_kegiatan,
                        "komoditas" => $item->komoditas,
                        "luas_ha" => $item->luas_ha,
                        "tgl_mulai_berlaku" => $item->tgl_mulai_berlaku,
                        "tgl_berakhir" => $item->tgl_berakhir,
                        "tahapan_cnc" => $item->tahapan_cnc,
                        "lokasi" => $item->lokasi,
                        "kode_wiup" => $item->kode_wiup,
                        "object_id" => $item->object_id
                    ],
                    "geometry" => [
                        "type" => "Polygon",
                        "coordinates" => [$coordinates]
                    ],
                    "id" => $izinId
                ];
            }
        }
    
        return response()->json([
            "type" => "FeatureCollection",
            "features" => $features
        ]);
    }
    
}
