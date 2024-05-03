@extends('web.template')

@section('title','Detail Soal')


@section('konten')


            <div class="container">
				<div class="page-inner">
					<div class="page-header">
						<h4 class="page-title"><i class="fas fa-file-alt"></i> Detail Soal ({{$data->judul_soal}})</h4>
						<ul class="breadcrumbs">
							<li class="nav-home">
								<a href="{{url('/dashboard')}}">
									<i class="flaticon-home"></i>
								</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="{{url('/list/soal')}}">Detail Soal</a>
							</li>
                            <li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
                            <li class="nav-item">
								<a href="">{{$data->judul_soal}}</a>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title">
                                        <button class="btn btn-primary btn-round" data-toggle="modal" data-target="#exampleModal">
											<span class="btn-label">
												<i class="fa fa-plus"></i>
											</span>
											Buat Soal Baris
										</button>
                                    </h4>
									
								</div>
								<div class="card-body">

                                 <div class="table-responsive">
										<table id="basic-datatables" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th>No Baris</th>
													<th>Soal Baris</th>
                                                    <th>Jumlah Generate Soal Kolom</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
                                                    <th>No Baris</th>
													<th>Soal Baris</th>
                                                    <th>Jumlah Generate Soal Kolom</th>
													<th>Aksi</th>
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
			<div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Buat Soal Baris</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form id="create_soalbaris">
								<div class="form-group">
									<label for="">Soal baris</label>
									<input type="text" name="soal_baris" minlength="9" maxlength="9" class="form-control">
                                    <small class="text-danger">*Soal baris minimal dan maksimal adalah 5 karakter. Tidak boleh mengandung spasi. Harus menggunakan koma perhuruf nya contoh A,B,C,D,E</small>
								</div>
                                <div class="form-group">
									<label for="">Jumlah Generate Soal Kolom</label>
									<input type="number" value="1" name="jumlah_soal_kolom" class="form-control">
                                    <small class="text-danger">*Jumlah soal kolom untuk baris ini. Soal kolom dan jawabannya akan otomatis di generate. Maksimal Soal kolom perbaris adalah 100</small>
								</div>
								<div class="form-group">
										<button type="submit" class="btn btn-primary btn-create w-100 btn-round">Simpan</button>
								</div>
							</form>
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
		  ajax: "{{ url('/list/soal/detail')}}/{{encrypt($data->id)}}",
		  columns: [
			  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
			  {data: 'soal_baris', name: 'soal_baris'},
              {data: 'jumlah_soal_kolom', name: 'jumlah_soal_kolom'},
			  {data: 'aksi', name: 'aksi', orderable: true, searchable: true},
		  ]
	  });


	  $('#create_soalbaris').submit(function(e){
		e.preventDefault();
		const data = $(this).serialize();
		$.ajax({
			url: "{{ url('/list/soal/detail')}}/{{encrypt($data->id)}}",
			method: "POST",
			data: data,
			beforeSend:function(){
				$('.btn-create').attr('disabled',true);
				$('.btn-create').text('Loading...');
			},
			complete:function(){
				$('.btn-create').attr('disabled',false);
				$('.btn-create').text('Simpan');
			},
			success:function(res){

				if($.isEmptyObject(res.error)){


					$('.modal').modal('hide');

					swal("Berhasil!", "Soal baris berhasil ditambah!", {
							icon : "success",
							buttons: {        			
								confirm: {
									className : 'btn btn-primary btn-round'
								}
							},
					});

					$('#create_soalbaris')[0].reset();
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



		$('body').on('click','.delete',function(){

				const data = $(this).attr('data-id');

				swal({
					icon: 'warning',
					title: 'Apakah Anda yakin?',
					text: "Anda akan menghapus soal baris ini!",
					buttons:{
						cancel: {
							visible: true,
							text : 'Batal',
							className: 'btn btn-danger btn-round'
						},        			
						confirm: {
							text : 'Hapus',
							className : 'btn btn-success btn-round'
						}
					}
				}).then((willDelete) => {
					if (willDelete) {

						$.ajax({
							url: "{{ url('/list/soal/detail')}}/{{encrypt($data->id)}}",
							method: "DELETE",
							data: {data:data},
							success:function(res){
								swal("Berhasil! Soal baris berhasil dihapus!", {
									icon: "success",
									buttons : {
										confirm : {
											className: 'btn btn-primary btn-round'
										}
									}
								});
								table.ajax.reload( null, false );
							},
							error:function(){
								swal("Terjadi kesalahan silahkan coba lagi!", {
									icon: "error",
									buttons : {
										confirm : {
											className: 'btn btn-primary btn-round'
										}
									}
								});
							}
						});
					}
				});

		});	
		
		


		


		

		
	});

</script>


@endpush

@endsection