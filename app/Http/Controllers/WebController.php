<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Validator;

class WebController extends Controller
{
    public function __construct()
    {
        $this->middleware('cekpaket');
    }

    public function dashboard()
    {
        $bab = \App\Models\BabSoal::orderBy('id','desc')->get();
        $soal = \App\Models\Soal::orderBy('id','desc')->get();

        return view('web.member.dashboard',compact('bab','soal'));
    }

    public function profile()
    {
        return view('web.member.profile');
    }

    public function saveProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.Auth()->user()->id,
            'no_hp' => 'required|numeric',

        ],
        [
            'name.required' => 'Nama harus diisi!',
            'email.required' => 'Email harus diisi!',
            'email.email'   => 'Email tidak valid!',
            'email.unique'  => 'Email sudah digunakan!',
            'no_hp.required' => 'No HP harus diisi!',
            'no_hp.numeric' => 'No HP tidak valid!',
        ]
        );
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        $update = [

            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,

        ];

        if(!empty($request->password)){
            $update['password'] = bcrypt($request->password);
        }

        \App\Models\User::where('id',Auth()->user()->id)->update($update);

        return response()->json(['success' => 'Profile berhasil diupdate!']);
    }

    public function listTryout()
    {
        $bab = \App\Models\BabSoal::orderBy('id','desc')->get();
        $soal = \App\Models\Soal::orderBy('id','desc')->get();

        return view('web.member.listtryout',compact('bab','soal'));
    }

    public function listPaket(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\Payment::with(['package','user'])->where('id_users',Auth()->user()->id)->orderBy('id','desc')->get();
            return Datatables::of($data)
                    ->addColumn('invoice', function($row){
                        return '#'.$row->id ;
                    })
                    ->addColumn('nama_paket', function($row){
                        if(!empty($row->package->nama_paket_bundle)){
                            return $row->package->nama_paket_bundle ;
                        }

                        return '-' ;
                    })
                    ->addColumn('durasi_paket', function($row){
                        if(!empty($row->package->durasi_paket_bundle)){
                            return $row->package->durasi_paket_bundle.' Hari' ;
                        }

                        return '-' ;
                    })
                    ->addColumn('status', function($row){
                        if($row->status_bayar == 'UNPAID'){
                            return '<span class="badge badge-danger">UNPAID</span>';
                        }

                        return '<span class="badge badge-success">PAID</span>';
                    })
                    ->addColumn('tanggal', function($row){
                        return date('d-m-Y H:i:s', strtotime($row->created_at)) ;
                    })
                    ->addColumn('bayar', function($row){
                        if($row->status_bayar == 'UNPAID'){
                            return '<a class="btn btn-detail-bayar btn-round btn-danger btn-sm" href="javascript:void(0)">BAYAR</a>';
                        }
                    })
                    ->rawColumns(['status','bayar'])
                    ->make(true);
        }

        // $paket = \App\Models\Package::where('id','!=',1)->where('id','!=',Auth()->user()->id_package)->get();
        $paket = \App\Models\PackageBundle::where('id','!=',1)->where('id','!=',Auth()->user()->id_package)->get();
        return view('web.member.listpaket',compact('paket'));
    }

    public function listPaketDetail(Request $request)
    {
        
        // $data = \App\Models\Package::where('id',decrypt($request->data))->first();

        $data = \App\Models\PackageBundle::where('id',decrypt($request->data))->first();

        $durasi = ($data->durasi_paket_bundle < 1 ? 'Tidak ada masa aktif' : $data->durasi_paket_bundle.' Hari');
        

        return response()->json([
            'des_paket' => $data->deskripsi_paket_bundle,
            'durasi_paket' => $durasi,
            'harga_paket' => "Rp " . number_format($data->harga_paket_bundle,0,',','.')
        ]);
    }

    public function upgradePaket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paket' => 'required',
        ],
        [
            'paket.required' => 'Silahkan pilih paket terlebih dahulu!',
        ]
        );
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        // $paket = \App\Models\Package::where('id',decrypt($request->paket))->first();

        $paket = \App\Models\PackageBundle::where('id',decrypt($request->paket))->first();

        \App\Models\Payment::create([
            'id_users' => Auth()->user()->id,
            'id_package' => $paket->id,
            'jumlah_bayar' => $paket->harga_paket_bundle,
        ]);

        return response()->json(['success' => 

            'Halo+Kak+Okta%2C+%0ASaya+ingin+mendaftar+paket+KECERMATAN+CAT+POLRI%0A%0ANama+%3A+'.urlencode(Auth()->user()->name).'%0AEmail+%3A+'.Auth()->user()->email.'%0ANama+Paket+yang+dipilih+%3A+'.urlencode($paket->nama_paket_bundle).'%0ADurasi+paket+yang+dipilih+%3A+'.$paket->durasi_paket_bundle.'+Hari'
    
        ]);

    }

    public function viewSoal(Request $request)
    {
        // $soal = \App\Models\Soal::where('id',decrypt($request->data))->first();

       

        $trial = \App\Models\PackageBundleList::where('id_soal',decrypt($request->data))->where('id_package_bundle',1)->first();

        
        
        if(empty($trial)){

            $pkg = \App\Models\PackageBundleList::where('id_package_bundle',Auth()->user()->id_package)->where('id_soal',decrypt($request->data))->first();     

            if(empty($pkg)){

                return response()->json([
                    'error' => 'Tidak dapat mengakses soal. Pastikan paket Anda sesuai untuk mengakses soal ini'
                ]);
    
            }

        }


        
        // $pch = explode(',',$soal->id_package);

        // if(!in_array(Auth()->user()->id_package,$pch)){
            
        //     return response()->json([
        //         'error' => 'Tidak dapat mengakses soal. Pastikan paket Anda sesuai untuk mengakses soal ini'
        //     ]);

        // }


        $res = '<p>Casisjuara akan mengerjakan Try Out Kecermatan ini. Tekan tombol "MULAI" untuk mulai mengerjakan</p>
        
                <a class="btn btn-primary w-100 btn-round" href="'.url('/soal/kerjakan').'/'.$request->data.'">Mulai</a>
        
               ';

        return response()->json([
            'success' => $res
        ]);
        
    
    }

    public function kerjakanSoal($id)
    {
        $soal = \App\Models\Soal::with(['soalBaris'])->where('id',decrypt($id))->firstOrFail();

        // $pch = explode(',',$soal->id_package);

        // if(!in_array(Auth()->user()->id_package,$pch)){
            
        //     return redirect('/list/tryout');

        // }

        $trial = \App\Models\PackageBundleList::where('id_soal',decrypt($id))->where('id_package_bundle',1)->first();

        
        if(empty($trial)){

            $pkg = \App\Models\PackageBundleList::where('id_package_bundle',Auth()->user()->id_package)->where('id_soal',decrypt($id))->first();     

            if(empty($pkg)){

                return redirect('/list/tryout');

            }

        }


       


        $soalusers = \App\Models\SoalUser::create([
            'id_users' => Auth()->user()->id,
            'id_soal' => $soal->id
        ]);


        $userssoal = $soalusers->id;


        return view('web.member.kerjakansoal',compact('soal','userssoal'));


    }

    public function getSoalBaris(Request $request,$id,$soalusers)
    {
        $soalbaris = \App\Models\SoalBaris::where('id','>',decrypt($request->soalbaris))->where('id_soal',decrypt($id))->orderBy('id','asc')->first();

        if(!empty($soalbaris)){

            $pch = explode(',',$soalbaris->soal_baris); 

            $kolom = \App\Models\SoalKolom::where('id_soal_baris',$soalbaris->id)->orderBy('id','asc')->first();
            $pch2 = explode(',',$kolom->soal_kolom); 

           $baris =   '<th class="pt-0 pb-1 pl-1 pr-1"><span>'.$pch[0].'</span></th>
                       <th class="pt-0 pb-1 pl-1 pr-1"><span>'.$pch[1].'</span></th>
                       <th class="pt-0 pb-1 pl-1 pr-1"><span>'.$pch[2].'</span></th>
                       <th class="pt-0 pb-1 pl-1 pr-1"><span>'.$pch[3].'</span></th>
                       <th class="pt-0 pb-1 pl-1 pr-1"><span>'.$pch[4].'</span></th>';
            
           $kolomsoal = implode('',$pch2);

           return response()->json([
                'incomplete' => [

                    'kolom' => $kolomsoal,
                    'idkolom' => encrypt($kolom->id),
                    'baris' => $baris,
                    'idbaris' => encrypt($soalbaris->id),
                    'nobaris' => $request->nobaris + 1,
                    // 'nokolom' => 0
                ]
           ]);

        }

                return response()->json([
                    'complete' => [

                        'url' => ''.url('/riwayat/tryout').'/'.$soalusers.'',

                    ]
                 ]);
       
    }

    public function jawabSoal(Request $request, $id)
    {
        $cek = \App\Models\Jawab::where('id_soal_users',decrypt($request->soalusers))->where('id_soal_kolom',decrypt($request->kolom))->first();

        if(empty($cek)){

            \App\Models\Jawab::create([
                'id_soal_users' => decrypt($request->soalusers),
                'id_soal_kolom' => decrypt($request->kolom),
                'jawaban' => decrypt($request->jawab)
            ]);

        }

        $soalkolom = \App\Models\SoalKolom::where('id','>',decrypt($request->kolom))->where('id_soal_baris',decrypt($request->baris))->orderBy('id','asc')->first();

        if(!empty($soalkolom)){

            $pch2 = explode(',',$soalkolom->soal_kolom);

            $kolom = implode('',$pch2);

            return response()->json([
                'kolom' => [

                    'kolom' => $kolom,
                    'idkolom' => encrypt($soalkolom->id),
                    // 'nosoalkolom' => $request->nosoalkolom + 1 
                ]
            ]);

        }


    }   


    public function riwayatTryout(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\SoalUser::with(['soal'])->where('id_users',Auth()->user()->id)->orderBy('id','desc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('nama_tryout', function($row){
                        return $row->soal->judul_soal ;
                    })
                    ->addColumn('tanggal', function($row){
                        return date('d-m-Y H:i:s', strtotime($row->created_at)) ;
                    })
                    ->addColumn('detail', function($row){
                        return '<a class="btn btn-round detail-hasil btn-primary btn-sm" href="'.url('riwayat/tryout').'/'.encrypt($row->id).'">Lihat Hasil</a>';
                    })
                    ->rawColumns(['detail'])
                    ->make(true);
        }


        return view('web.member.riwayattryout');
    }


    public function detailTryout($id)
    {
        $data = \App\Models\SoalUser::with(['soal'])->where('id',decrypt($id))->where('id_users',Auth()->user()->id)->firstOrFail();

        $jawab = \App\Models\Jawab::where('id_soal_users',$data->id)->get();

        return view('web.member.hasiltryout',compact('data','jawab'));
    }

    
}