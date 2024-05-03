<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use Validator;
use DataTables;


class AdminController extends Controller
{

    public function dashboard()
    {
        $ds = [
            'member' => \App\Models\User::where('role','member')->count(),
            // 'paket'  => \App\Models\Package::count(),
            'paket' => \App\Models\PackageBundle::count(),
            'babsoal' => \App\Models\BabSoal::count(),
            'listsoal' => \App\Models\Soal::count()
        ];

        return view('web.admin.dashboard',compact('ds'));
    }

    public function profile()
    {
        return view('web.admin.profile');
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
    
    public function member(Request $request)
    {     
        if ($request->ajax()) {

            $data = \App\Models\User::with(['package'])->where('role','member')->orderBy('id','desc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('role', function($row){
                        return 'Member';
                    })
                    ->addColumn('package', function($row){
                        return '<span class="badge badge-secondary">'.$row->package->nama_paket_bundle.'</span><br><span class="badge badge-success mt-2">Active : '.(!empty($row->expired_at) ? date('d-m-Y H:i:s', strtotime($row->expired_at)) : '-' ).'</span>';
                    })
                    ->addColumn('created_at', function($row){
                        return $row->created_at->format('d-m-Y H:i:s');
                    })
                    ->addColumn('aksi', function($row){

                            $btn = '<div class="d-flex">';

                            if(!empty($row->expired_at)){

                                if($row->package->durasi_paket_bundle !== 0){

                                    $btn .= '<a href="javascript:void(0)" data-id="'.encrypt($row->id).'" class="extend btn btn-success mr-2 btn-sm">
                                    
                                    <i class="fas fa-history"></i>
                                    
                                    </a>';
    
                                }

                            }
       
                            $btn .= '<a href="javascript:void(0)" data-id="'.encrypt($row->id).'" class="delete btn btn-danger mr-2 btn-sm">
                            
                            <i class="fas fa-trash"></i>
                            
                            </a>';

                            $btn .= '<a href="javascript:void(0)" data-id="'.encrypt($row->id).'" class="edit btn btn-primary btn-sm">
                            
                            <i class="fas fa-edit"></i>
                            
                            </a>';

                            $btn .= '</div>';
      
                            return $btn;
                    })
                    ->rawColumns(['aksi','package'])
                    ->make(true);
        }

        // $pkg = \App\Models\Package::all();

         $pkg = \App\Models\PackageBundle::all();

        return view('web.admin.member',compact('pkg'));
    }

    public function saveMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|numeric',
            'paket' => 'required',
            'password' => 'required',

        ],
        [
            'name.required' => 'Nama harus diisi!',
            'email.required' => 'Email harus diisi!',
            'email.email'   => 'Email tidak valid!',
            'email.unique'  => 'Email sudah digunakan!',
            'no_hp.required' => 'No HP harus diisi!',
            'no_hp.numeric' => 'No HP tidak valid!',
            'paket.required' => 'Silahkan pilih paket!',
            'password.required' => 'Password harus diisi!'
        ]
        );
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        // $pkg = \App\Models\Package::where('id',decrypt($request->paket))->first();

        $pkg = \App\Models\PackageBundle::where('id',decrypt($request->paket))->first();

        if($pkg->durasi_paket_bundle < 1){

            $exp = NULL;

        }else{

            $exp = date('Y-m-d H:i:s',strtotime('+'.$pkg->durasi_paket_bundle.' days',strtotime(now())));

        }


        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'id_package' => decrypt($request->paket),
            'password' => bcrypt($request->password),
            'expired_at' => $exp
        ]);

        return response()->json(['success' => 'Member berhasil ditambah!']);


    }

    public function deleteMember(Request $request)
    {
        \App\Models\User::where('id',decrypt($request->data))->delete();
    }

    public function detailMember(Request $request)
    {
        $data = \App\Models\User::where('id',decrypt($request->data))->first();
        // $paket = \App\Models\Package::all();
        $paket = \App\Models\PackageBundle::all();
        
        

       $p = '';

       foreach($paket as $pkg){
          $p .= '<option value="'.encrypt($pkg->id).'" '.($pkg->id == $data->id_package ? 'selected' : '').'>'.$pkg->nama_paket_bundle.'</option>';
       }
       

        $res = '<form id="edit_member">
                    <input type="hidden" name="data" value="'.encrypt($data->id).'">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" value="'.$data->name.'" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" value="'.$data->email.'" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">No HP</label>
                        <input type="number" value="'.$data->no_hp.'" name="no_hp" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Paket</label>
                        <select name="paket" class="form-control">
                        <option value="">--Pilih Paket--</option>
                        '.$p.'
                     </select>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="text" name="password" class="form-control">
                        <small class="text-danger">*Kosongkan jika tidak ingin mengganti password</small>
                    </div>
                    <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-edit w-100 btn-round">Simpan</button>
                    </div>
                </form>';

        return $res;
        
    }


    public function editMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.decrypt($request->data),
            'no_hp' => 'required|numeric',
            'paket' => 'required',

        ],
        [
            'name.required' => 'Nama harus diisi!',
            'email.required' => 'Email harus diisi!',
            'email.email'   => 'Email tidak valid!',
            'email.unique'  => 'Email sudah digunakan!',
            'no_hp.required' => 'No HP harus diisi!',
            'no_hp.numeric' => 'No HP tidak valid!',
            'paket.required' => 'Silahkan pilih paket!',
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
            'id_package' => decrypt($request->paket)

        ];
        
        $cek = \App\Models\User::where('id',decrypt($request->data))->first();
        // $pkg = \App\Models\Package::where('id',decrypt($request->paket))->first();
        $pkg = \App\Models\PackageBundle::where('id',decrypt($request->paket))->first();

        if($cek->id_package !== decrypt($request->paket)){

            if($pkg->durasi_paket_bundle < 1){

                $update['expired_at'] = NULL;
    
            }else{
    
                $update['expired_at'] = date('Y-m-d H:i:s',strtotime('+'.$pkg->durasi_paket_bundle.' days',strtotime(now())));
    
            }

        }

        if(!empty($request->password)){
            $update['password'] = bcrypt($request->password);
        }

        \App\Models\User::where('id',decrypt($request->data))->update($update);


        return response()->json(['success' => 'Member berhasil diedit!']);
    }


    public function extendMember(Request $request)
    {
        $data = \App\Models\User::with(['package'])->where('id',decrypt($request->data))->first();

        if(!empty($data->expired_at)){

            if($data->package->durasi_paket_bundle < 1){

                $exp = NULL;
    
            }else{
    
                $exp = date('Y-m-d H:i:s',strtotime('+'.$data->package->durasi_paket_bundle.' days',strtotime($data->expired_at)));
    
            }
    
            $data->update(['expired_at' => $exp]);

        }
      


    }


    // public function paket(Request $request)
    // {
    //     if ($request->ajax()) {

    //         $data = \App\Models\Package::orderBy('id','desc')->get();
    //         return Datatables::of($data)
    //                 ->addIndexColumn()
    //                 ->addColumn('harga_paket', function($row){
    //                     return "Rp " . number_format($row->harga_paket,0,',','.');
    //                 })
    //                 ->addColumn('aksi', function($row){

    //                         $btn = '<div class="d-flex">';
       
    //                         if($row->id !== 1){

    //                             $btn .= '<a href="javascript:void(0)" data-id="'.encrypt($row->id).'" class="delete btn btn-danger mr-2 btn-sm">
                            
    //                             <i class="fas fa-trash"></i>
                                
    //                             </a>';
    //                         }

    //                         $btn .= '<a href="javascript:void(0)" data-id="'.encrypt($row->id).'" class="edit btn btn-primary btn-sm">
                            
    //                         <i class="fas fa-edit"></i>
                            
    //                         </a>';

    //                         $btn .= '</div>';
      
    //                         return $btn;
    //                 })
    //                 ->rawColumns(['aksi'])
    //                 ->make(true);
    //     }


    //     return view('web.admin.paket');
    // }


    // public function savePaket(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'nama_paket' => 'required',
    //         'deskripsi_paket' => 'required',
    //         'durasi_paket' => 'required|numeric',
    //         'harga_paket' => 'required|numeric'
    //     ],
    //     [
    //         'nama_paket.required' => 'Nama paket harus diisi!',
    //         'deskripsi_paket.required' => 'Deskripsi paket harus diisi!',
    //         'durasi_paket.required' => 'Durasi paket harus diisi!',
    //         'durasi_paket.numeric' => 'Durasi paket tidak valid!',
    //         'harga_paket.required' => 'Harga paket harus diisi!',
    //         'harga_paket.numeric' => 'Harga paket tidak valid!'
    //     ]
    //     );
  
    //     if ($validator->fails()) {
    //         return response()->json([
    //                     'error' => $validator->errors()->all()
    //                 ]);
    //     }

    //     \App\Models\Package::create([
    //         'nama_paket' => $request->nama_paket,
    //         'deskripsi_paket' => $request->deskripsi_paket,
    //         'durasi_paket' => $request->durasi_paket,
    //         'harga_paket' => $request->harga_paket
    //     ]);

    //     return response()->json(['success' => 'Paket berhasil ditambah!']);
    // }


    // public function deletePaket(Request $request)
    // {
    //     \App\Models\User::where('id_package',decrypt($request->data))->update([
    //         'id_package' => 1,
    //         'expired_at' => NULL
    //     ]);

    //     \App\Models\Package::where('id',decrypt($request->data))->delete();
    // }

    // public function detailPaket(Request $request)
    // {   

    //     $data = \App\Models\Package::where('id',decrypt($request->data))->first();
       

    //     $res = '<form id="edit_paket">
    //                             <input type="hidden" name="data" value="'.encrypt($data->id).'">
    //                             <div class="form-group">
	// 								<label for="">Nama Paket</label>
	// 								<input type="text" name="nama_paket" value="'.$data->nama_paket.'" class="form-control">
	// 							</div>
	// 							<div class="form-group">
	// 								<label for="">Deskripsi Paket</label>
	// 								<textarea name="deskripsi_paket" class="form-control">'.$data->deskripsi_paket.'</textarea>
	// 							</div>
    //                             <div class="form-group">
	// 								<label for="">Durasi Paket (Hari)</label>
	// 								<input type="number" name="durasi_paket" value="'.$data->durasi_paket.'" class="form-control" value="0">
    //                                 <small class="text-danger">*Ketikan 0 jika durasi paket tidak terbatas</small>
	// 							</div>
    //                             <div class="form-group">
	// 								<label for="">Harga Paket</label>
	// 								<input type="number" name="harga_paket" value="'.$data->harga_paket.'" class="form-control">
	// 							</div>
    //                             <div class="form-group">
    //                                     <button type="submit" class="btn btn-primary btn-edit w-100 btn-round">Simpan</button>
    //                             </div>
    //             </form>';

    //     return $res;

    // }


    // public function editPaket(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'nama_paket' => 'required',
    //         'deskripsi_paket' => 'required',
    //         'durasi_paket' => 'required|numeric',
    //         'harga_paket' => 'required|numeric'
    //     ],
    //     [
    //         'nama_paket.required' => 'Nama paket harus diisi!',
    //         'deskripsi_paket.required' => 'Deskripsi paket harus diisi!',
    //         'durasi_paket.required' => 'Durasi paket harus diisi!',
    //         'durasi_paket.numeric' => 'Durasi paket tidak valid!',
    //         'harga_paket.required' => 'Harga paket harus diisi!',
    //         'harga_paket.numeric' => 'Harga paket tidak valid!'
    //     ]
    //     );
  
    //     if ($validator->fails()) {
    //         return response()->json([
    //                     'error' => $validator->errors()->all()
    //                 ]);
    //     }

    //     \App\Models\Package::where('id',decrypt($request->data))->update([
    //         'nama_paket' => $request->nama_paket,
    //         'deskripsi_paket' => $request->deskripsi_paket,
    //         'durasi_paket' => $request->durasi_paket,
    //         'harga_paket' => $request->harga_paket
    //     ]);

    //     return response()->json(['success' => 'Paket berhasil diedit!']);
    // }


    public function pembayaran(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\Payment::with(['user','package'])->orderBy('id','desc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('id_pembayaran', function($row){
                        return '#'.$row->id;
                    })->addColumn('nama', function($row){
                        if(empty($row->id_users)){
                            return '-';
                        }
                         return $row->user->name;
                    })
                    ->addColumn('email', function($row){
                        if(empty($row->id_users)){
                            return '-';
                        }
                        return $row->user->email;
                    })
                    ->addColumn('no_hp', function($row){
                        if(empty($row->id_users)){
                            return '-';
                        }
                        return $row->user->no_hp;
                    })
                    ->addColumn('paket', function($row){
                        if(empty($row->id_package)){
                            return '-';
                        }
                        return $row->package->nama_paket_bundle;
                    })
                    ->addColumn('jumlah_bayar', function($row){
                        return "Rp " . number_format($row->jumlah_bayar,0,',','.');
                    })
                    ->addColumn('status_bayar', function($row){
                       if($row->status_bayar == 'PAID'){
                          return '<span class="badge badge-success">PAID</span>';
                       }
                           return '<span class="badge badge-danger">UNPAID</span>';
                    })
                    ->addColumn('created_at', function($row){
                        return $row->created_at->format('d-m-Y H:i:s');
                    })
                    ->addColumn('aksi', function($row){

                            $btn = '<div class="d-flex">';
       
                            $btn .= '<a href="javascript:void(0)" data-id="'.encrypt($row->id).'" class="delete btn btn-danger mr-2 btn-sm">
                            
                            <i class="fas fa-trash"></i>
                            
                            </a>';

                            $btn .= '<a href="javascript:void(0)" data-id="'.encrypt($row->id).'" class="confirm btn btn-primary btn-sm">
                            
                            <i class="fas fa-sync-alt"></i>
                            
                            </a>';

                            $btn .= '</div>';
      
                            return $btn;
                    })
                    ->rawColumns(['aksi','status_bayar'])
                    ->make(true);
        }

    
        
        return view('web.admin.pembayaran');
    }


    public function deletePembayaran(Request $request)
    {
        \App\Models\Payment::where('id',decrypt($request->data))->delete();
    }

    public function statusPembayaran(Request $request)
    {
        $data = \App\Models\Payment::with(['user','package'])->where('id',decrypt($request->data))->first();

    
        if($data->status_bayar == 'PAID'){

            $data->update(['status_bayar' => 'UNPAID']);

        }else{


            if(!empty($data->id_users) && !empty($data->id_package)){


                if($data->package->durasi_paket_bundle < 1){

                    $exp = NULL;

                }else{

                    $exp = date('Y-m-d H:i:s',strtotime('+'.$data->package->durasi_paket_bundle.' days',strtotime($data->created_at)));

                }


                $data->user->update(['id_package' => $data->id_package,'expired_at' => $exp]);
                
    
            }
    
            $data->update(['status_bayar' => 'PAID']);
        }

       
        
    }

    
    public function babSoal(Request $request)
    {

        if ($request->ajax()) {

            $data = \App\Models\BabSoal::orderBy('id','desc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('aksi', function($row){

                            $btn = '<div class="d-flex">';
       
                            $btn .= '<a href="javascript:void(0)" data-id="'.encrypt($row->id).'" class="delete btn btn-danger mr-2 btn-sm">
                            
                            <i class="fas fa-trash"></i>
                            
                            </a>';

                            $btn .= '<a href="javascript:void(0)" data-id="'.encrypt($row->id).'" class="edit btn btn-primary btn-sm">
                            
                            <i class="fas fa-edit"></i>
                            
                            </a>';

                            $btn .= '</div>';
      
                            return $btn;
                    })
                    ->rawColumns(['aksi'])
                    ->make(true);
        }


        return view('web.admin.babsoal');

    }

    public function saveBabSoal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bab' => 'required',
        ],
        [
            'nama_bab.required' => 'Nama bab soal harus diisi!',
        ]
        );
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        \App\Models\BabSoal::create([
            'nama_bab' => $request->nama_bab,
        ]);

        return response()->json(['success' => 'Bab soal berhasil ditambah!']);
    }

    public function deleteBabSoal(Request $request)
    {
        \App\Models\BabSoal::where('id',decrypt($request->data))->delete();
    }

    public function detailBabSoal(Request $request)
    {
        $data = \App\Models\BabSoal::where('id',decrypt($request->data))->first();
       

        $res = '<form id="edit_babsoal">
                                <input type="hidden" name="data" value="'.encrypt($data->id).'">
                                <div class="form-group">
									<label for="">Nama Bab Soal</label>
									<input type="text" name="nama_bab" value="'.$data->nama_bab.'" class="form-control">
								</div>
                                <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-edit w-100 btn-round">Simpan</button>
                                </div>
                </form>';

        return $res;
    }

    public function editBabSoal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bab' => 'required',
        ],
        [
            'nama_bab.required' => 'Nama bab soal harus diisi!',
        ]
        );
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        \App\Models\BabSoal::where('id',decrypt($request->data))->update([
            'nama_bab' => $request->nama_bab,
        ]);

        return response()->json(['success' => 'Bab soal berhasil diedit!']);
    }


    public function listSoal(Request $request)
    {
        if ($request->ajax()) {

            $data = \App\Models\Soal::with(['babSoal','soalBaris'])->orderBy('id','desc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('bab_soal',function($row){
                        
                        if(!empty($row->babSoal->nama_bab)){

                            return $row->babSoal->nama_bab;

                        }

                        return '-';
                        
                    })
                    ->addColumn('jumlah_baris_soal',function($row){
                        
                       
                        return $row->soalBaris()->count();

                    })
                    ->addColumn('buat_soal',function($row){
                        $btn = '<a href="'.url('/list/soal/detail').'/'.encrypt($row->id).'" class="btn btn-success mr-2 btn-sm">
                            
                        <i class="fas fa-eye"></i> Detail Soal
                        
                        </a>';

                        return $btn;
                    })
                    ->addColumn('aksi', function($row){

                            $btn = '<div class="d-flex">';
       
                            $btn .= '<a href="javascript:void(0)" data-id="'.encrypt($row->id).'" class="delete btn btn-danger mr-2 btn-sm">
                            
                            <i class="fas fa-trash"></i>
                            
                            </a>';

                            $btn .= '<a href="javascript:void(0)" data-id="'.encrypt($row->id).'" class="edit btn btn-primary btn-sm">
                            
                            <i class="fas fa-edit"></i>
                            
                            </a>';

                            $btn .= '</div>';
      
                            return $btn;
                    })
                    ->rawColumns(['buat_soal','aksi','paket'])
                    ->make(true);
        }

        // $pkg = \App\Models\Package::all();
        $bab = \App\Models\BabSoal::all();
        return view('web.admin.listsoal',compact('bab'));
    }

    public function saveSoal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_soal' => 'required',
            'bab_soal' => 'required',
            // 'paket' => 'required',
            'waktu_soal' => 'required|numeric|min:5'
        ],
        [
            'judul_soal.required' => 'Judul soal harus diisi!',
            'bab_soal.required' => 'Bab soal harus diisi!',
            // 'paket.required' => 'Hak akses paket harus diisi!',
            'waktu_soal.required' => 'Waktu persoal harus diisi!',
            'waktu_soal.numeric' => 'Waktu persoal tidak valid!',
            'waktu_soal.min' => 'Waktu persoal minimal 5 detik!'
        ]
        );
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

    

        // $pkg = '';

        // foreach($request->paket as $p){

        //     if(count($request->paket) > 1){

        //         $pkg .= decrypt($p).",";

        //     }else{

        //         $pkg .= decrypt($p);
        //     }

        // }



        \App\Models\Soal::create([
            'judul_soal' => $request->judul_soal,
            'id_bab_soal' => decrypt($request->bab_soal),
            // 'id_package' => rtrim($pkg, ","),
            'waktu_soal' => $request->waktu_soal
        ]);

        return response()->json(['success' => 'Soal berhasil ditambah!']);
    }


    public function deleteSoal(Request $request)
    {
        \App\Models\Soal::where('id',decrypt($request->data))->delete();
    }


    public function detailSoal(Request $request)
    {
        $data = \App\Models\Soal::where('id',decrypt($request->data))->first();

        $bab = \App\Models\BabSoal::all();

        $pkg = \App\Models\Package::all();

        $b = '';
        $p = '';

        foreach($bab as $bs){

            $b .= '<option value="'.encrypt($bs->id).'" '.($bs->id == $data->id_bab_soal ? 'selected' : '').'>'.$bs->nama_bab.'</option>';

        }

        $pkg2 = explode(",",$data->id_package);
    

        foreach($pkg as $pkt){



            $p .= '<option value="'.encrypt($pkt->id).'" '.(in_array($pkt->id,$pkg2) ? 'selected' : '').'>'.$pkt->nama_paket.'</option>';

            
        }
       

        $res = '<form id="edit_soal">
                                <input type="hidden" name="data" value="'.encrypt($data->id).'">
                                <div class="form-group">
									<label for="">Judul Soal</label>
									<input type="text" name="judul_soal" value="'.$data->judul_soal.'" class="form-control">
								</div>
                                <div class="form-group">
									<label for="">Bab Soal</label>
									<select name="bab_soal" class="form-control">
                                      <option value="">--Pilih Bab Soal--</option>
                                      '.$b.'
                                    </select>
								</div>
                                <div class="form-group">
									<label for="">Hak Akses Paket</label>
                                      <select id="multiple2" name="paket[]" class="form-control" multiple="multiple">
                                        '.$p.'
									   </select>          
								</div>
                                <div class="form-group">
									<label for="">Waktu Persoal</label>
									<input type="number" value="'.$data->waktu_soal.'" name="waktu_soal" class="form-control">
									<small class="text-danger">*Waktu persoal minimal 5 detik. Isikan dengan detik</small>
								</div>
                                <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-edit w-100 btn-round">Simpan</button>
                                </div>
                </form>';

        return $res;
    }


    public function editSoal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_soal' => 'required',
            'bab_soal' => 'required',
            'paket' => 'required',
            'waktu_soal' => 'required|numeric|min:5'
        ],
        [
            'judul_soal.required' => 'Judul soal harus diisi!',
            'bab_soal.required' => 'Bab soal harus diisi!',
            'paket.required' => 'Hak akses paket harus diisi!',
            'waktu_soal.required' => 'Waktu persoal harus diisi!',
            'waktu_soal.numeric' => 'Waktu persoal tidak valid!',
            'waktu_soal.min' => 'Waktu persoal minimal 5 detik!'
        ]
        );
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        $pkg = '';

        foreach($request->paket as $p){

            if(count($request->paket) > 1){

                $pkg .= decrypt($p).",";

            }else{

                $pkg .= decrypt($p);
            }

        }



        \App\Models\Soal::where('id',decrypt($request->data))->update([
            'judul_soal' => $request->judul_soal,
            'id_bab_soal' => decrypt($request->bab_soal),
            'id_package' => rtrim($pkg, ","),
            'waktu_soal' => $request->waktu_soal
        ]);

        return response()->json(['success' => 'Soal berhasil diedit!']);
    }


    public function viewSoal(Request $request,$id)
    {
        try {


            if ($request->ajax()) {

                $data = \App\Models\SoalBaris::with(['soalKolom'])->where('id_soal',decrypt($id))->orderBy('id','asc')->get();
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('jumlah_soal_kolom',function($row){
                        
                            return $row->soalKolom()->count();
                            
                        })
                        ->addColumn('aksi', function($row){
    
                                $btn = '<div class="d-flex">';
           
                                $btn .= '<a href="javascript:void(0)" data-id="'.encrypt($row->id).'" class="delete btn btn-danger mr-2 btn-sm">
                                
                                <i class="fas fa-trash"></i>
                                
                                </a>';
    
                                $btn .= '</div>';
          
                                return $btn;
                        })
                        ->rawColumns(['aksi'])
                        ->make(true);
            }
    

            $data = \App\Models\Soal::where('id',decrypt($id))->firstOrFail();
        
            return view('web.admin.detailsoal',compact('data'));

        } catch (DecryptException $e) {
            
            return abort(404);

        }   
    }


    public function saveSoalBaris(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'soal_baris' => 'required|max:9|min:9|regex:/^\S*$/u',
            'jumlah_soal_kolom' => 'required|min:1|max:100|numeric',
        ],
        [
            'soal_baris.required' => 'Soal baris harus diisi!',
            'soal_baris.max' => 'Soal baris minimal dan maksimal adalah 5 karakter!',
            'soal_baris.min' => 'Soal baris minimal dan maksimal adalah 5 karakter!',
            'soal_baris.regex' => 'Soal baris tidak boleh mengandung spasi!',
            'jumlah_soal_kolom.required' => 'Jumlah generate soal kolom harus diisi!',
            'jumlah_soal_kolom.min' => 'jumlah generate soal kolom minimal 1!',
            'jumlah_soal_kolom.max' => 'Jumlah generate soal kolom maksimal 100!',
            'jumlah_soal_kolom.numeric' => 'Jumlah generate soal kolom tidak vali!',
        ]
        );
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }


        $soalcek1 = ltrim($request->soal_baris,',');
        $soalcek2 = rtrim($soalcek1,',');

        $pch = explode(',',$soalcek2);

        if(count($pch) < 5){

            return response()->json([
                'error' => ['Soal baris minimal dan maksimal adalah 5 karakter!']
            ]);

        }   

        

        $baris =  \App\Models\SoalBaris::create([
            'id_soal' => decrypt($id),
            'soal_baris' => $request->soal_baris
        ]);



        
    
        for($i=1;$i<=$request->jumlah_soal_kolom;$i++){

            $kolom = $pch;
            
            shuffle($kolom);

            $pilihjawaban = array_rand($kolom,1);
    
            $jawaban = $kolom[$pilihjawaban];
    
            if($kolom[$pilihjawaban] == $pch[0]){
                $kunci = 'A';
            }elseif($kolom[$pilihjawaban] == $pch[1]){
                $kunci = 'B';
            }elseif($kolom[$pilihjawaban] == $pch[2]){
                $kunci = 'C';
            }elseif($kolom[$pilihjawaban] == $pch[3]){
                $kunci = 'D';
            }else{
                $kunci = 'E';
            }

        
    
            unset($kolom[$pilihjawaban]);

            \App\Models\SoalKolom::create([
                'id_soal_baris' => $baris->id,
                'soal_kolom' => implode(",",$kolom),
                'jawaban' => $jawaban,
                'kunci' => $kunci
            ]);
    

        }


        return response()->json(['success' => 'Soal baris berhasil ditambah!']);
    }

    public function deleteSoalBaris(Request $request)
    {
        \App\Models\SoalBaris::where('id',decrypt($request->data))->delete();
    }

    public function settingWeb()
    {
        $data = \App\Models\SettingWeb::where('id',1)->first();
        return view('web.admin.settingweb',compact('data'));
    }

    public function saveSettingWeb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_web' => 'required',
            'meta_deskripsi_web' => 'required',
            'deskripsi_bayar' => 'required',
            'no_wa' => 'required|numeric',
            'copyright_web' => 'required',
            'icon_web' => 'image:mimes:jpg,jpeg,png,gif|max:2048',
            'logo_web' => 'image:mimes:jpg,jpeg,png,gif|max:2048'
        ],
        [
            'judul_web.required' => 'Judul web harus diisi!',
            'meta_deskripsi_web.required' => 'Meta deskripsi web harus diisi!',
            'deskripsi_bayar.required' => 'Deskripsi detail pembayaran harus diisi!',
            'no_wa.required' => 'No WhatsApp harus diisi!',
            'no_wa.numeric' => 'No WhatsApp tidak valid!',
            'copyright_web.required' => 'Copyright web harus diisi!',
            'icon_web.image' => 'Gambar icon web tidak valid!',
            'icon_web.mimes' => 'Format gambar icon web tidak diizinkan!',
            'icon_web.max' => 'Gambar icon web maksimal 2 MB!',
            'logo_web.image' => 'Gambar logo web tidak valid!',
            'logo_web.mimes' => 'Format gambar logo web tidak diizinkan!',
            'logo_web.max' => 'Gambar logo web maksimal 2 MB!',
        ]
        );
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        $cek = \App\Models\SettingWeb::where('id',1)->first();
        
       
        $icon = ($cek !== null ? $cek->icon_web : '');
        $logo = ($cek !== null ? $cek->logo_web : ' ');
        
        $icon_upl = '';
        $logo_upl = '';

        
        if($request->file('icon_web')){

            if(!empty($icon)){

                if(file_exists(public_path('assets/img/').$icon)){
                    unlink(public_path('assets/img/').$icon);
                }    

            }

            
            $icon_upl = $request->file('icon_web');

            $icon = uniqid().'.'.$icon_upl->getClientOriginalExtension();

        }

        if($request->file('logo_web')){

            if(!empty($logo)){

                if(file_exists(public_path('assets/img/').$logo)){
                    unlink(public_path('assets/img/').$logo);
                }

            }

            

            $logo_upl = $request->file('logo_web');

            $logo = uniqid().'.'.$logo_upl->getClientOriginalExtension();

        }


        $data = [

            'judul_web' => $request->judul_web,
            'meta_desc_web' => $request->meta_deskripsi_web,
            'desc_detail_bayar' => $request->deskripsi_bayar,
            'copyright' => $request->copyright_web,
            'no_wa' => $request->no_wa
        ];

        $data['icon_web'] = $icon;
        $data['logo_web'] = $logo;
        

        \App\Models\SettingWeb::truncate();

        \App\Models\SettingWeb::create($data,[(!empty($icon_upl) ? $icon_upl->move(public_path('assets/img'),$icon) : ''),(!empty($logo_upl) ? $logo_upl->move(public_path('assets/img'),$logo) : '')]);


        return response()->json(['success' => 'Setting web berhasil!']);

    }


    public function paketBundle(Request $request)
    {

        if ($request->ajax()) {

            $data = \App\Models\PackageBundle::with(['listsoal'])->orderBy('id','desc')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('harga_paket_bundle', function($row){
                        return "Rp " . number_format($row->harga_paket_bundle,0,',','.');
                    })
                    ->addColumn('list_soal', function($row){
                        $s = '';
                        foreach($row->listsoal()->get() as $l){

                            foreach($l->soal()->get() as $n){

                                $s .= '<span class="badge badge-secondary m-1">'.$n->judul_soal.'</span><br>';

                            }
                           

                        }

                        return $s;
                        
                    })
                    ->addColumn('aksi', function($row){

                            $btn = '<div class="d-flex">';
       
                            if($row->id !== 1){

                                 $btn .= '<a href="javascript:void(0)" data-id="'.encrypt($row->id).'" class="delete btn btn-danger mr-2 btn-sm">
                            
                                <i class="fas fa-trash"></i>
                                
                                </a>';

                            }

                            $btn .= '<a href="javascript:void(0)" data-id="'.encrypt($row->id).'" class="edit btn btn-primary btn-sm">
                            
                            <i class="fas fa-edit"></i>
                            
                            </a>';

                            $btn .= '</div>';
      
                            return $btn;
                    })
                    ->rawColumns(['aksi','list_soal'])
                    ->make(true);
        }

        $soal = \App\Models\Soal::all();
        return view('web.admin.paketbundle',compact('soal'));
    }


    public function savePaketBundle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_paket_bundle' => 'required',
            'deskripsi_paket_bundle' => 'required',
            'soal' => 'required',
            'durasi_paket_bundle' => 'required|numeric',
            'harga_paket_bundle' => 'required|numeric'
        ],
        [
            'nama_paket_bundle.required' => 'Nama paket bundle harus diisi!',
            'deskripsi_paket_bundle.required' => 'Deskripsi paket bundle harus diisi!',
            'soal.required' => 'List soal harus diisi!',
            'durasi_paket_bundle.required' => 'Durasi paket bundle harus diisi!',
            'durasi_paket_bundle.numeric' => 'Durasi paket bundle tidak valid!',
            'harga_paket_bundle.required' => 'Harga paket bundle harus diisi!',
            'harga_paket_bundle.numeric' => 'Harga paket bundle tidak valid!'
        ]
        );
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        $bdl = \App\Models\PackageBundle::create([
            'nama_paket_bundle' => $request->nama_paket_bundle,
            'deskripsi_paket_bundle' => $request->deskripsi_paket_bundle,
            'durasi_paket_bundle' => $request->durasi_paket_bundle,
            'harga_paket_bundle' => $request->harga_paket_bundle
        ]);

        foreach($request->soal as $s){
        
            \App\Models\PackageBundleList::create([
                'id_package_bundle' => $bdl->id,
                'id_soal' => decrypt($s)
            ]);

        }


        return response()->json(['success' => 'Paket bundle berhasil ditambah!']);
    }


    public function deletePaketBundle(Request $request)
    {
        \App\Models\User::where('id_package',decrypt($request->data))->update([
            'id_package' => 1,
            'expired_at' => NULL
        ]);

        \App\Models\PackageBundle::where('id',decrypt($request->data))->delete();
    }

    public function detailPaketBundle(Request $request)
    {
        $data = \App\Models\PackageBundle::where('id',decrypt($request->data))->first();


        $soal = \App\Models\Soal::all();

        $bdl = \App\Models\PackageBundleList::where('id_package_bundle',decrypt($request->data))->get();

        $s = '';
        
        $nv = [];
        
        foreach($bdl as $lbd){

            array_push($nv,$lbd->id_soal);

        }

        foreach($soal as $also){

            $s .= '<option value="'.encrypt($also->id).'" '.(in_array($also->id,$nv) ? "selected" : "").'>'.$also->judul_soal.'</option>';

        }
       

        $res = '<form id="edit_paketbundle">
                                <input type="hidden" name="data" value="'.encrypt($data->id).'">
                                <div class="form-group">
									<label for="">Nama Paket Bundle</label>
									<input type="text" name="nama_paket_bundle" value="'.$data->nama_paket_bundle.'" class="form-control">
								</div>
								<div class="form-group">
									<label for="">Deskripsi Paket Bundle</label>
									<textarea name="deskripsi_paket_bundle" class="form-control">'.$data->deskripsi_paket_bundle.'</textarea>
								</div>
                                <div class="form-group">
                                <label for="">List Soal</label>
                                  <select id="multiple2" name="soal[]" class="form-control" multiple="multiple">
                                    '.$s.'
                                   </select>          
                                 </div>
                                <div class="form-group">
									<label for="">Durasi Paket Bundle (Hari)</label>
									<input type="number" name="durasi_paket_bundle" value="'.$data->durasi_paket_bundle.'" class="form-control" value="0" '.($data->id == 1 ? 'readonly' : '').'>
                                    <small class="text-danger">*Ketikan 0 jika durasi paket tidak terbatas</small>
								</div>
                                <div class="form-group">
									<label for="">Harga Paket Bundle</label>
									<input type="number" name="harga_paket_bundle" value="'.$data->harga_paket_bundle.'" class="form-control" '.($data->id == 1 ? 'readonly' : '').'>
								</div>
                                <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-edit w-100 btn-round">Simpan</button>
                                </div>
                </form>';

        return $res;
    }

    public function editPaketBundle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_paket_bundle' => 'required',
            'deskripsi_paket_bundle' => 'required',
            'soal' => 'required',
            'durasi_paket_bundle' => 'required|numeric',
            'harga_paket_bundle' => 'required|numeric'
        ],
        [
            'nama_paket_bundle.required' => 'Nama paket bundle harus diisi!',
            'deskripsi_paket_bundle.required' => 'Deskripsi paket bundle harus diisi!',
            'soal.required' => 'List soal harus diisi!',
            'durasi_paket_bundle.required' => 'Durasi paket bundle harus diisi!',
            'durasi_paket_bundle.numeric' => 'Durasi paket bundle tidak valid!',
            'harga_paket_bundle.required' => 'Harga paket bundle harus diisi!',
            'harga_paket_bundle.numeric' => 'Harga paket bundle tidak valid!'
        ]
        );
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }

        \App\Models\PackageBundleList::where('id_package_bundle',decrypt($request->data))->delete();

        foreach($request->soal as $s){
               
            \App\Models\PackageBundleList::create([
                'id_package_bundle' => decrypt($request->data),
                'id_soal' => decrypt($s)
            ]);

        }


        \App\Models\PackageBundle::where('id',decrypt($request->data))->update([
            'nama_paket_bundle' => $request->nama_paket_bundle,
            'deskripsi_paket_bundle' => $request->deskripsi_paket_bundle,
            'durasi_paket_bundle' => $request->durasi_paket_bundle,
            'harga_paket_bundle' => $request->harga_paket_bundle
        ]);

        return response()->json(['success' => 'Paket bundle berhasil diedit!']);


    }


}
