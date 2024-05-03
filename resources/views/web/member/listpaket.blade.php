@extends('web.template')

@section('title','Paket')

@section('konten')

       
            <div class="container">
				<div class="panel-header bg-primary-gradient">
					<div class="page-inner py-5">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white pb-2 fw-bold">Paket</h2>
								<h5 class="text-white op-7 mb-2">Paket Try Out yang tersedia</h5>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner">

                    
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            
                        <div class="card">
                            <div class="card-header">
                                    <h4 class="card-title">
                                        <button class="btn btn-primary btn-round" data-toggle="modal" data-target="#exampleModal">
											<span class="btn-label">
												<i class="fa fa-plus"></i>
											</span>
											Upgrade Paket
										</button>
                                    </h4>
                            </div>
                            <div class="card-body">

                                    <div class="table-responsive">
										<table id="basic-datatables" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th>Invoice</th>
													<th>Nama Paket</th>
													<th>Durasi Paket</th>
                                                    <th>Status</th>
													<th>Tanggal</th>
                                                    <th></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
                                                    <th>Invoice</th>
													<th>Nama Paket</th>
													<th>Durasi Paket</th>
                                                    <th>Status</th>
													<th>Tanggal</th>
                                                    <th></th>
												</tr>
											</tfoot>
											<tbody>
												
											</tbody>
										</table>
									</div>



                            </div>
                        </div>
                          

                        </div>
                    </div>
					
					
				</div>
			</div>


            <!-- Modal -->
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Upgrade Paket</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form id="create_upgradepaket">
								<div class="form-group">
									<label for="">Pilih Paket</label>
									<select name="paket" id="paket" class="form-control">
                                        <option value="">--Pilih Paket--</option>
                                        @foreach($paket as $p)
                                        <option value="{{encrypt($p->id)}}">{{$p->nama_paket_bundle}}</option>
                                        @endforeach
                                    </select>
								</div>
                                <div class="form-group">
									<label for="">Deskripsi Paket</label>
									<p id="des_paket">-</p>
								</div>
                                <div class="form-group">
									<label for="">Durasi Paket</label>
									<p id="durasi_paket">-</p>
								</div>
                                <div class="form-group">
									<label for="">Harga Paket</label>
									<p id="harga_paket">-</p>
								</div>
								<div class="form-group">
										<button type="submit" class="btn btn-primary btn-create w-100 btn-round">Upgrade</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>



			
             <!-- Modal -->
			 <div class="modal fade" id="exampleModalBayar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-money-bill-wave"></i> Detail Bayar</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
						<p>{{$config['web'] !== null ? $config['web']->desc_detail_bayar : ''}}</p>
						<a href="javascript:void(0)" class="btn btn-primary btn-pay-info btn-round w-100">Konfirmasi</a>
						</div>
					</div>
				</div>
			</div>



        

@push('scripts')


<script>
   
   $(function () {
      
	  const table = $('#basic-datatables').DataTable({
		  processing: true,
		  serverSide: true,
		  ajax: "{{ url('/list/paket')}}",
		  columns: [
			  {data: 'invoice', name: 'invoice'},
              {data: 'nama_paket', name: 'nama_paket'},
			  {data: 'durasi_paket', name: 'durasi_paket'},
              {data: 'status', name: 'status'},
              {data: 'tanggal', name: 'tanggal'},
			  {data: 'bayar', name: 'bayar', orderable: true, searchable: true},
		  ]
	  });


      $('#paket').on('change',function(){
          const data = $(this).val();
          $.ajax({
              url: "{{url('/list/paket/detail')}}",
              method: "POST",
              data: {data:data},
              success:function(res){
                 $('#des_paket').text(res.des_paket);
                 $('#durasi_paket').text(res.durasi_paket);
                 $('#harga_paket').text(res.harga_paket);
              }
          });
      });


	  $('#create_upgradepaket').submit(function(e){
		e.preventDefault();
		const data = $(this).serialize();
		$.ajax({
			url: "{{url('/list/paket')}}",
			method: "POST",
			data: data,
			beforeSend:function(){
				$('.btn-create').attr('disabled',true);
				$('.btn-create').text('Loading...');
			},
			complete:function(){
				$('.btn-create').attr('disabled',false);
				$('.btn-create').text('Upgrade');
			},
			success:function(res){

				if($.isEmptyObject(res.error)){


					$('.modal').modal('hide');

					swal("Berhasil!", "Upgrade berhasil berhasil dibuat. Silahkan melakukan pembayaran dan konfirmasi jika sudah membayar", {
							icon : "success",
							buttons: {        			
								confirm: {
									className : 'btn btn-primary btn-round confirm-pay'
								}
							},
					}).then(function(){
						location.href = "https://wa.me/{{$config['web'] !== null ? $config['web']->no_wa : ''}}?text="+res.success;
					});

					$('#create_upgradepaket')[0].reset();
					table.ajax.reload();
                    
                }else{
                    
					$.each( res.error, function( key, value ) {
						
						
							$.notify({

								title: 'Error',
								message: value,
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

					});
					
                }
			
			},
			error:function(){

				$.notify({

					title: 'Error',
					message: 'Terjadi kesalahan silahkan coba lagi!',
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
		});


	});




	$('body').on('click','.btn-detail-bayar',function(){

	
		 var currentRow=$(this).closest("tr"); 
         
         var col1=currentRow.find("td:eq(0)").text(); 
         var col2=currentRow.find("td:eq(1)").text(); 
         var col3=currentRow.find("td:eq(2)").text(); 
         var data="https://wa.me/{{$config['web'] !== null ? $config['web']->no_wa : ''}}?text=Halo+Kak+Afa%2C+%0ASaya+ingin+mendaftar+paket+KECERMATAN+CAT+POLRI%0A%0ANama+%3A+{{urlencode(Auth()->user()->name)}}%0AEmail+%3A+{{Auth()->user()->email}}%0ANama+Paket+yang+dipilih+%3A+"+encodeURIComponent(col2)+"%0ADurasi+paket+yang+dipilih+%3A+"+encodeURIComponent(col3);
         
         $('.btn-pay-info').attr('href',data);

		$('#exampleModalBayar').modal('show');
    });

		



		
	});


</script>

    
@endpush


@endsection