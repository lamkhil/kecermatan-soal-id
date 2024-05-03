@extends('web.template')

@section('title','Lihat Hasil')

@section('konten')

       
            <div class="container">
				<div class="panel-header bg-primary-gradient">
					<div class="page-inner py-5">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white pb-2 fw-bold">Lihat Hasil</h2>
								<h5 class="text-white op-7 mb-2">{{$data->soal->judul_soal}}</h5>
                                <h5 class="text-white op-7 mb-2">{{date('d-m-Y H:i:s', strtotime($data->created_at))}}</h5>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner">

                    
                    <div class="row">
                        <div class="col-md-6">
                            
                            <div class="card">

                                <div class="card-body">
                                
                                     <div style="overflow-x: auto">
                                        
                                        <div class="chart-container">
										    <canvas id="barChart"></canvas>
									    </div>

                                    </div>


                                </div>
                            </div>
                            

                        </div>
                        <div class="col-md-6">
                             <div class="card">
                                 <div class="card-body">
                                        
                                    <div style="overflow-x: auto">
                                     <table id="basic-datatables" class="table text-center table-bordered font-weight-bold table-head-bg-primary table-bordered-bd-primary" >
											<thead>
												<tr>
													<th>No Kolom</th>
                                                    <th>Terjawab</th>
													<th>B</th>
													<th>S</th>
                                                    <th>Jumlah Soal</th>
												</tr>
											</thead>
											<tbody>
                                            @foreach($data->soal->soalBaris()->orderBy('id','asc')->get() as $d)
												<tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>

                                                        @php

                                                        $terjawab = 0;

                                                        foreach($d->soalKolom()->get() as $bs){


                                                          if(count($jawab->where('id_soal_kolom',$bs->id)) > 0){

                                                            $terjawab = $terjawab + 1;


                                                          }



                                                            
                                                        }

                                                        @endphp

                                                        {{$terjawab}}

                                                    </td>
                                                    <td class="benar">
                                                       @php
                                                       
                                                       $benar = 0;

                                                       foreach($d->soalKolom()->get() as $bs){

                                                            foreach($jawab->where('id_soal_kolom',$bs->id) as $j){

                                                                if($j->jawaban == $bs->kunci){

                                                                    $benar = $benar + 1;

                                                                }



                                                            }

                                                       }


                                        

                                                       @endphp

                                                       {{$benar}}
                                                    </td>
                                                    <td>
                                                    @php
                                                       
                                                       $salah = 0;

                                                       foreach($d->soalKolom()->get() as $bs){


                                                          if(count($jawab->where('id_soal_kolom',$bs->id)) > 0){

                                                              
                                                            foreach($jawab->where('id_soal_kolom',$bs->id) as $j){

                                                                if($j->jawaban !== $bs->kunci){

                                                                    $salah = $salah + 1;

                                                                }



                                                          }



                                                          }

                                                            
                                                       }



                                        

                                                       @endphp
                                                       {{$salah}}
                                                    </td>
                                                    <td>
                                                        {{count($d->soalKolom()->get())}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                	<td colspan="5" class="jumlah_benar"></td>
											</tr>
											</tbody>
										</table>
                                    </div>

                                 </div>
                             </div>
                        </div>
                    </div>
					
					
				</div>
			</div>



        

@push('scripts')


<script>
    
    $(function(){
        let TotalValue = 0;
        $(".benar").each(function(){
            TotalValue += parseInt($(this).text());
        });
        $('.jumlah_benar').text('Jumlah Benar = '+TotalValue);
   });
    
    barChart = document.getElementById('barChart').getContext('2d');

    var myBarChart = new Chart(barChart, {
			type: 'bar',
			data: {
				labels: [
                    @foreach($data->soal->soalBaris()->orderBy('id','asc')->get() as $d)
                      '{{"Kolom ".$loop->iteration}}',
                    @endforeach
                ],
				datasets : [

                {
					label: "Jumlah Soal",
					backgroundColor: 'rgb(23, 125, 255)',
					borderColor: '#eaeaea',
					data: [
                        @foreach($data->soal->soalBaris()->orderBy('id','asc')->get() as $d)
                            {{count($d->soalKolom()->get())}},
                        @endforeach
                    ],
				},{
					label: "Terjawab",
					backgroundColor: '#fdaf4b',
					borderColor: '#eaeaea',
					data: [
                        @foreach($data->soal->soalBaris()->orderBy('id','asc')->get() as $d)
                             @php

                                    $terjawab = 0;

                                    foreach($d->soalKolom()->get() as $bs){


                                        if(count($jawab->where('id_soal_kolom',$bs->id)) > 0){

                                            $terjawab = $terjawab + 1;


                                        }



                                                            
                                    }

                            @endphp

                                {{$terjawab}},

                        @endforeach
                    ],
				},
                {
					label: "Benar",
					backgroundColor: '#59d05d',
					borderColor: '#eaeaea',
					data: [

                        @foreach($data->soal->soalBaris()->orderBy('id','asc')->get() as $d)
                             @php
                                                       
                                    $benar = 0;

                                    foreach($d->soalKolom()->get() as $bs){

                                        foreach($jawab->where('id_soal_kolom',$bs->id) as $j){

                                            if($j->jawaban == $bs->kunci){

                                                    $benar = $benar + 1;

                                            }



                                        }

                                    }


                                        

                            @endphp

                                {{$benar}},
                                
                        @endforeach

                    ],
				},
                {
					label: "Salah",
					backgroundColor: '#f0190a',
					borderColor: '#eaeaea',
					data: [

                        @foreach($data->soal->soalBaris()->orderBy('id','asc')->get() as $d)
                                @php
                                                       
                                    $salah = 0;

                                    foreach($d->soalKolom()->get() as $bs){


                                        if(count($jawab->where('id_soal_kolom',$bs->id)) > 0){

                                                              
                                            foreach($jawab->where('id_soal_kolom',$bs->id) as $j){

                                                    if($j->jawaban !== $bs->kunci){

                                                         $salah = $salah + 1;

                                                    }



                                            }



                                        }

                                                            
                                    }



                                        

                                @endphp

                                {{$salah}},
                                
                        @endforeach

                    ],
				}
            
             ],
			},
			options: {
				responsive: true, 
                legend: {
					position : 'bottom'
				},
				maintainAspectRatio: false,
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true
						}
					}]
				},
			}
		});





  


</script>

    
@endpush


@endsection