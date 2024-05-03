@extends('web.template')

@section('title',$soal->judul_soal)

@section('konten')

       
            <div class="container">
				<div class="panel-header bg-primary-gradient">
					<div class="page-inner py-3">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white pb-2 fw-bold">{{$soal->judul_soal}}</h2>
								<h5 class="text-white op-7 mb-2">Selamat mengerjakan</h5>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner">
                    
                    <div class="row justify-content-center">
                        <div class="col-md-10">

                          <div class="card">

                            <div class="card-body">
                                
                               <div class="info-timer mb-5">
                                    <div id="time-countdown"></div>
                               </div>
                            
                                 
                                 <table class="table font-weight-bold table-column-questions table-head-bg-primary table-bordered-bd-primary">
                                        <thead>
                                            <tr class="text-center">
                                                <th colspan="5" id="nobarissoal" data-no="1">
                                                    KOLOM 1
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $default1 = $soal->soalBaris()->where('id_soal',$soal->id)->orderBy('id','asc')->first();
                                                $idbaris = $default1->id;
                                                $pch1 = explode(',',$default1->soal_baris); 
                                            @endphp
                                            <tr class="text-center" id="barissoal" data-id="{{encrypt($idbaris)}}">
                                                <th class="pt-0 pb-1 pl-1 pr-1"><span>{{$pch1[0]}}</span></th>
                                                <th class="pt-0 pb-1 pl-1 pr-1"><span>{{$pch1[1]}}<span></th>
                                                <th class="pt-0 pb-1 pl-1 pr-1"><span>{{$pch1[2]}}<span></th>
                                                <th class="pt-0 pb-1 pl-1 pr-1"><span>{{$pch1[3]}}</span></th>
                                                <th class="pt-0 pb-1 pl-1 pr-1"><span>{{$pch1[4]}}<span></th>
                                            </tr>
                                            <tr class="text-center">
                                                <td class="pt-0 pb-0"><span>A</span></td>
                                                <td class="pt-0 pb-0"><span>B</span></td>
                                                <td class="pt-0 pb-0"><span>C</span></td>
                                                <td class="pt-0 pb-0"><span>D</span></td>
                                                <td class="pt-0 pb-0"><span>E</span></td>
                                            </tr>
                                        </tbody>
                                  </table>
                                 
                                 
                                 <table class="table table-questions font-weight-bold mt-5 table-bordered-bd-primary">
                                    <!-- <thead>
                                        <tr class="text-center">
                                            <th colspan="5" id="nokolomsoal" data-no="1">
                                                SOAL 1
                                            </th>
                                        </tr>
                                    </thead> -->
                                    <thead>
                                    <tr class="text-center">
                                                 @php
                                                    $default2 = \App\Models\SoalKolom::where('id_soal_baris',$idbaris)->orderBy('id','asc')->first();
                                                    $pch2 = explode(',',$default2->soal_kolom); 
                                                @endphp
                                            <th colspan="5" class="pt-0 pb-0 pl-0 pr-0" id="kolomsoal" data-id="{{encrypt($default2->id)}}">
                                               {{implode('',$pch2)}}
                                            </th>
                                     </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td class="jawab pt-0 pb-0" data-jawab="{{encrypt('A')}}">A</td>
                                            <td class="jawab pt-0 pb-0" data-jawab="{{encrypt('B')}}">B</td>
                                            <td class="jawab pt-0 pb-0" data-jawab="{{encrypt('C')}}">C</td>
                                            <td class="jawab pt-0 pb-0" data-jawab="{{encrypt('D')}}">D</td>
                                            <td class="jawab pt-0 pb-0" data-jawab="{{encrypt('E')}}">E</td>
                                        </tr>
                                    </tbody>
                                </table>
                    


                            </div>

                          </div>
                       

                        </div>
                    </div>
					
					
				</div>
			</div>


             <!-- Modal -->
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-clipboard-check"></i> Selesai</h5>
						</div>
						<div class="modal-body">
							<p>Terima kasih telah mengerjakan ujian ini. Silahkan cek hasil di menu riwayat try out.</p>
                            <a href="javascript:void(0)" class="btn link-tryout btn-primary btn-round w-100">Lihat Hasil</a>
						</div>
					</div>
				</div>
			</div>




        

@push('scripts')

<script>

$(function(){

    waktusoal = parseInt('{{$soal->waktu_soal}}');


    $('#time-countdown').countdown({until: waktusoal, format: 'HMS', padZeroes: true, onExpiry: gantiSoal});

    function gantiSoal(){

        const nobaris = $('#nobarissoal').attr('data-no');
        const soalbaris = $('#barissoal').attr('data-id');
      
        $.ajax({
            url: "{{url('/get/soalbaris')}}/{{encrypt($soal->id)}}/{{encrypt($userssoal)}}",
            method: "POST",
            data: {nobaris:nobaris,soalbaris:soalbaris},
            success:function(res){

                if(res.incomplete){

                    
                    $('#nobarissoal').text('KOLOM '+res.incomplete.nobaris);
                    $('#nobarissoal').attr('data-no',res.incomplete.nobaris);

                    // $('#nokolomsoal').text('SOAL 1');
                    // $('#nokolomsoal').attr('data-no',res.incomplete.nokolom);


                    $('#barissoal').attr('data-id',res.incomplete.idbaris);
                    $('#barissoal').html(res.incomplete.baris);

                    $('#kolomsoal').text(res.incomplete.kolom);

                    $('#time-countdown').countdown('option', {until: +waktusoal});
					
                }else{

                    $('#time-countdown').countdown('pause');

                    $('.link-tryout').attr('href',res.complete.url);

                    $("#exampleModal").modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                }


            }
        });


    }


    $('.jawab').click(function(){
        const baris = $('#barissoal').attr('data-id');
        const kolom = $('#kolomsoal').attr('data-id');
        const nosoalbaris = $('#nobarissoal').attr('data-no');
        const nosoalkolom = $('#nokolomsoal').attr('data-no');
        const jawab = $(this).attr('data-jawab');
        const soalusers = "{{encrypt($userssoal)}}";
        $(this).css('background-color','#eaeaea');
        $.ajax({
            url: "{{url('/soal/jawab')}}/{{encrypt($soal->id)}}",
            method: "POST",
            data: {baris:baris,kolom:kolom,nosoalkolom:nosoalkolom,jawab:jawab,soalusers,soalusers},
            beforeSend:function(){
              $('.jawab').css('pointer-events','none');  
            },
            complete:function(){
              $('.jawab').css('pointer-events','auto');
            },
            success:function(res){

                 if(res.kolom){

                    
                    // $('#nokolomsoal').text('SOAL '+res.kolom.nosoalkolom);
                    // $('#nokolomsoal').attr('data-no',res.kolom.nosoalkolom);


                    $('#kolomsoal').text(res.kolom.kolom);
                    $('#kolomsoal').attr('data-id',res.kolom.idkolom);
                    
                    $('.jawab').css('background-color','');

					
                }else{

                   gantiSoal();
                   
                   $('.jawab').css('background-color','');

                }


            }
        });
    })  

})
    
</script>
    
@endpush


@endsection