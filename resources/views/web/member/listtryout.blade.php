@extends('web.template')

@section('title','List Try Out')

@section('konten')

       
            <div class="container">
				<div class="panel-header bg-primary-gradient">
					<div class="page-inner py-5">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white pb-2 fw-bold">List Try Out</h2>
								<h5 class="text-white op-7 mb-2">List Try Out yang tersedia</h5>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner">

					<div class="row mb-3">
                        <div class="col-md-12">
                            <h3 class="text-center">Pilih Try Out dibawah ini!</h3>
                        </div>
                    </div>

                    
                    <div class="row justify-content-center">
                        <div class="col-md-8">

                        <div id="accordion">

                        @foreach($bab as $b)

                            <div class="card">
                                <div class="card-header p-1" id="heading{{$loop->iteration}}">
                                <h5 class="mb-0">
                                    <button class="btn btn-link text-dark" style="font-size: 16.6px" data-toggle="collapse" data-target="#collapse{{$loop->iteration}}" aria-expanded="true" aria-controls="collapse{{$loop->iteration}}">
                                    <i class="fas fa-file-alt mr-2"></i> {{$b->nama_bab}}
                                    </button>
                                </h5>
                                </div>

                                <div id="collapse{{$loop->iteration}}" class="collapse show" aria-labelledby="heading{{$loop->iteration}}" >
                                <div class="card-body p-1">
                                    <ul class="list-group list-group-flush">
                                        @foreach($soal->where('id_bab_soal',$b->id) as $s)
                                        @if($s->soalBaris()->count() > 0)
                                        <li class="list-group-item d-block">
                                            <h4><a href="javascript:void(0)" data-id="{{encrypt($s->id)}}" class="viewsoal text-primary font-weight-bold">{{$s->judul_soal}}</a></h4>
                                        </li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </div>
                                </div>
                            </div>
                        
                        @endforeach
    
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
							<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> Mulai Kerjakan Try Out</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							
						</div>
					</div>
				</div>
			</div>




        

@push('scripts')

<script>

    $('.viewsoal').click(function(){
        const data = $(this).attr('data-id');
        $.ajax({
            url: "{{url('/view/soal')}}",
            method: "POST",
            data: {data:data},
            success:function(res){
                
                if($.isEmptyObject(res.error)){

                    $('#exampleModal .modal-body').html(res.success);
                    $('#exampleModal').modal('show');
                   

                }else{

                            $.notify({

								title: 'Error',
								message: res.error,
								icon: 'fas fa-exclamation',
								allow_dismiss: true

							},{
								type: 'danger',
								placement: {
									from: 'top',
									align: 'right'
								},
								delay: 3000,
								timer: 1000,
								z_index: 1051
							});

                }

            }
        });
    });

   
</script>
    
@endpush


@endsection