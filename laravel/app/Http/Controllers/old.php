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
        //data_0
            $izinPerusahaan = DB::table('data_wiup')
                ->leftJoin('coordinat','data_wiup.objectid','coordinat.object_id')
                ->join('data_perusahaan','data_wiup.data_id','data_perusahaan.data_id')
                ->where('jenis_izin','=','IPR')
                ->whereRaw('(kode_golongan=2 or kode_golongan=5)')
                ->get()
                ->groupBy('objectid'); // Group by izin_id to process each izin_perusahaan separately
            $features = [];    
            foreach ($izinPerusahaan as $izinId => $items) {
                $item = $items->first(); // Get the first item as the representative
        
                // Collect coordinates
                $coordinates = $items->map(function($coord) {
                    return [$coord->x, $coord->y];
                })->filter()->all(); // Filter out null values
        
                if (count($coordinates) > 0) {
                    $features[] = [
                        "type" => "Feature",
                        "properties" => [
                            "name" => $item->jenis_badan_usaha . "." . $item->nama_perusahaan,
                            "sk_iup" => $item->sk_iup,
                            "jenis_perizinan" => $item->jenis_izin,
                            "tahapan_kegiatan" => $item->kegiatan,
                            "komoditas" => $item->komoditas,
                            "luas_ha" => $item->luas_sk,
                            "tgl_mulai_berlaku" => $item->tgl_berlaku,
                            "tgl_berakhir" => $item->tgl_akhir,
                            "tahapan_cnc" => $item->cnc,
                            "lokasi" => $item->lokasi,
                            "kode_wiup" => $item->kode_wiup,
                            "object_id" => $item->objectid,
                             "symbol" => "symbol-1"
                        ],
                        "geometry" => [
                            "type" => "Polygon",
                            "coordinates" => [$coordinates]
                        ],
                        "id" => $izinId
                    ];
                }
            }
        //end data_0
        //data_1
            $izinPerusahaan1 = DB::table('data_wiup')
                ->leftJoin('coordinat','data_wiup.objectid','coordinat.object_id')
                ->join('data_perusahaan','data_wiup.data_id','data_perusahaan.data_id')
                ->where('jenis_izin','=','IUP')
                ->whereRaw('(kode_golongan=2 or kode_golongan=6)')
                ->get()
                ->groupBy('objectid'); // Group by izin_id to process each izin_perusahaan separately
            $features1 = [];    
            foreach ($izinPerusahaan1 as $izinId1 => $items1) {
                $item1 = $items1->first(); // Get the first item as the representative

                // Collect coordinates
                $coordinates1 = $items1->map(function($coord1) {
                    return [$coord1->x, $coord1->y];
                })->filter()->all(); // Filter out null values

                if (count($coordinates1) > 0) {
                    $features1[] = [
                        "type" => "Feature",
                        "properties" => [
                            "name" => $item1->jenis_badan_usaha . "." . $item1->nama_perusahaan,
                            "sk_iup" => $item1->sk_iup,
                            "jenis_perizinan" => $item1->jenis_izin,
                            "tahapan_kegiatan" => $item1->kegiatan,
                            "komoditas" => $item1->komoditas,
                            "luas_ha" => $item1->luas_sk,
                            "tgl_mulai_berlaku" => $item1->tgl_berlaku,
                            "tgl_berakhir" => $item1->tgl_akhir,
                            "tahapan_cnc" => $item1->cnc,
                            "lokasi" => $item1->lokasi,
                            "kode_wiup" => $item1->kode_wiup,
                            "object_id" => $item1->objectid,
                            "symbol" => "symbol-2"
                        ],
                        "geometry" => [
                            "type" => "Polygon",
                            "coordinates" => [$coordinates1]
                        ],
                        "id" => $izinId1
                    ];
                }
            }
        //end data_1
        //data_2
            $izinPerusahaan2 = DB::table('data_wiup')
                ->leftJoin('coordinat','data_wiup.objectid','coordinat.object_id')
                ->join('data_perusahaan','data_wiup.data_id','data_perusahaan.data_id')
                ->where('jenis_izin','=','IUP')
                ->whereRaw('kode_golongan=3')
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
                             "symbol" => "symbol-3"
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
        //data_3
            $izinPerusahaan3 = DB::table('data_wiup')
                ->leftJoin('coordinat','data_wiup.objectid','coordinat.object_id')
                ->join('data_perusahaan','data_wiup.data_id','data_perusahaan.data_id')
                ->where('jenis_izin','=','IUP')
                ->whereRaw('kode_golongan=4')
                ->get()
                ->groupBy('objectid'); // Group by izin_id to process each izin_perusahaan separately
            $features3 = [];    
            foreach ($izinPerusahaan3 as $izinId3 => $items3) {
                $item3 = $items3->first(); // Get the first item as the representative

                // Collect coordinates
                $coordinates3 = $items3->map(function($coord3) {
                    return [$coord3->x, $coord3->y];
                })->filter()->all(); // Filter out null values

                if (count($coordinates3) > 0) {
                    $features3[] = [
                        "type" => "Feature",
                        "properties" => [
                            "name" => $item3->jenis_badan_usaha . "." . $item3->nama_perusahaan,
                            "sk_iup" => $item3->sk_iup,
                            "jenis_perizinan" => $item3->jenis_izin,
                            "tahapan_kegiatan" => $item3->kegiatan,
                            "komoditas" => $item3->komoditas,
                            "luas_ha" => $item3->luas_sk,
                            "tgl_mulai_berlaku" => $item3->tgl_berlaku,
                            "tgl_berakhir" => $item3->tgl_akhir,
                            "tahapan_cnc" => $item3->cnc,
                            "lokasi" => $item3->lokasi,
                            "kode_wiup" => $item3->kode_wiup,
                            "object_id" => $item3->objectid,
                             "symbol" => "symbol-4"
                        ],
                        "geometry" => [
                            "type" => "Polygon",
                            "coordinates" => [$coordinates3]
                        ],
                        "id" => $izinId3
                    ];
                }
            }
        //end data_3
        //data_4
            // $izinPerusahaan4 = DB::table('data_wiup')
            //     ->leftJoin('coordinat','data_wiup.objectid','coordinat.object_id')
            //     ->join('data_perusahaan','data_wiup.data_id','data_perusahaan.data_id')
            //     ->where('jenis_izin','=','IUP')
            //     ->whereRaw('kode_golongan=5')
            //     ->get()
            //     ->groupBy('objectid'); // Group by izin_id to process each izin_perusahaan separately
            // $features4 = [];    
            // foreach ($izinPerusahaan4 as $izinId4 => $items4) {
            //     $item4 = $items4->first(); // Get the first item as the representative

            //     // Collect coordinates
            //     $coordinates4 = $items4->map(function($coord4) {
            //         return [$coord4->x, $coord4->y];
            //     })->filter()->all(); // Filter out null values

            //     if (count($coordinates4) > 0) {
            //         $features4[] = [
            //             "type" => "Feature",
            //             "properties" => [
            //                 "name" => $item4->jenis_badan_usaha . "." . $item4->nama_perusahaan,
            //                 "sk_iup" => $item4->sk_iup,
            //                 "jenis_perizinan" => $item4->jenis_izin,
            //                 "tahapan_kegiatan" => $item4->kegiatan,
            //                 "komoditas" => $item4->komoditas,
            //                 "luas_ha" => $item4->luas_sk,
            //                 "tgl_mulai_berlaku" => $item4->tgl_berlaku,
            //                 "tgl_berakhir" => $item4->tgl_akhir,
            //                 "tahapan_cnc" => $item4->cnc,
            //                 "lokasi" => $item4->lokasi,
            //                 "kode_wiup" => $item4->kode_wiup,
            //                 "object_id" => $item4->objectid,
            //                  "symbol" => "symbol-5"
            //             ],
            //             "geometry" => [
            //                 "type" => "Polygon",
            //                 "coordinates" => [$coordinates4]
            //             ],
            //             "id" => $izinId4
            //         ];
            //     }
            // }
        //end data_4
        // //data_5
        //     $izinPerusahaan5 = DB::table('data_wiup')
        //         ->leftJoin('coordinat','data_wiup.objectid','coordinat.object_id')
        //         ->join('data_perusahaan','data_wiup.data_id','data_perusahaan.data_id')
        //         ->where('jenis_izin','=','IUPK')
        //         ->whereRaw('kode_golongan=2')
        //         ->get()
        //         ->groupBy('objectid'); // Group by izin_id to process each izin_perusahaan separately
        //     $features5 = [];    
        //     foreach ($izinPerusahaan5 as $izinId5 => $items5) {
        //         $item5 = $items5->first(); // Get the first item as the representative

        //         // Collect coordinates
        //         $coordinates5 = $items5->map(function($coord5) {
        //             return [$coord5->x, $coord5->y];
        //         })->filter()->all(); // Filter out null values

        //         if (count($coordinates5) > 0) {
        //             $features5[] = [
        //                 "type" => "Feature",
        //                 "properties" => [
        //                     "name" => $item5->jenis_badan_usaha . "." . $item5->nama_perusahaan,
        //                     "sk_iup" => $item5->sk_iup,
        //                     "jenis_perizinan" => $item5->jenis_izin,
        //                     "tahapan_kegiatan" => $item5->kegiatan,
        //                     "komoditas" => $item5->komoditas,
        //                     "luas_ha" => $item5->luas_sk,
        //                     "tgl_mulai_berlaku" => $item5->tgl_berlaku,
        //                     "tgl_berakhir" => $item5->tgl_akhir,
        //                     "tahapan_cnc" => $item5->cnc,
        //                     "lokasi" => $item5->lokasi,
        //                     "kode_wiup" => $item5->kode_wiup,
        //                     "object_id" => $item5->objectid,
        //                      "symbol" => "symbol-6"
        //                 ],
        //                 "geometry" => [
        //                     "type" => "Polygon",
        //                     "coordinates" => [$coordinates5]
        //                 ],
        //                 "id" => $izinId5
        //             ];
        //         }
        //     }
        // //end data_5
        
            
        $data["ft"] =["type" => "FeatureCollection", "features" => $features];
        $data["ft"]["features"] = array_merge($data["ft"]["features"], $features1);
        $data["ft"]["features"] = array_merge($data["ft"]["features"], $features2);
        $data["ft"]["features"] = array_merge($data["ft"]["features"], $features3);
        // $data["ft"]["features"] = array_merge($data["ft"]["features"], $features4);
        // $data["ft"]["features"] = array_merge($data["ft"]["features"], $features5);
        
        return view('pages.v_map',$data);
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
