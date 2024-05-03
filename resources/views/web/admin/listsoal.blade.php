@extends('web.template')

@section('title','List Soal')


@section('konten')


            <div class="container">
				<div class="page-inner">
					<div class="page-header">
						<h4 class="page-title"><i class="fas fa-file-alt"></i> List Soal</h4>
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
								<a href="{{url('/list/soal')}}">List Soal</a>
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
											Tambah Soal
										</button>
                                    </h4>
									
								</div>
								<div class="card-body">

                                 <div class="table-responsive">
										<table id="basic-datatables" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th>No</th>
													<th>Judul Soal</th>
                                                    <th>Bab Soal</th>
                                                    <th>Jumlah Baris Soal</th>
													<th>Waktu Persoal (Detik)</th>
                                                    <th></th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
                                                    <th>No</th>
													<th>Judul Soal</th>
                                                    <th>Bab Soal</th>
                                                    <th>Jumlah Baris Soal</th>
													<th>Waktu Persoal (Detik)</th>
                                                    <th></th>
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
							<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Tambah Soal</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form id="create_soal">
								<div class="form-group">
									<label for="">Judul Soal</label>
									<input type="text" name="judul_soal" class="form-control">
								</div>
                                <div class="form-group">
									<label for="">Bab Soal</label>
									<select name="bab_soal" class="form-control">
                                      <option value="">--Pilih Bab Soal--</option>
                                      @foreach($bab as $b)
                                      <option value="{{encrypt($b->id)}}">{{$b->nama_bab}}</option>
                                      @endforeach
                                    </select>
								</div>
								<div class="form-group">
									<label for="">Waktu Persoal</label>
									<input type="number" value="60" name="waktu_soal" class="form-control">
									<small class="text-danger">*Waktu persoal minimal 5 detik. Isikan dengan detik</small>
								</div>
								<div class="form-group">
										<button type="submit" class="btn btn-primary btn-create w-100 btn-round">Simpan</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>


			<!-- Modal -->
			<div class="modal fade" id="exampleModalEdit" role="dialog" aria-labelledby="exampleModalEditLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalEditLabel"><i class="fa fa-edit"></i> Edit Soal</h5>
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

        $('#multiple1').select2({
			theme: "bootstrap",
			dropdownParent: $('#exampleModal')
		});

      
	  $(function () {
      
	  const table = $('#basic-datatables').DataTable({
		  processing: true,
		  serverSide: true,
		  ajax: "{{ url('/list/soal')}}",
		  columns: [
			  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
			  {data: 'judul_soal', name: 'judul_soal'},
              {data: 'bab_soal', name: 'bab_soal'},
              {data: 'jumlah_baris_soal', name: 'jumlah_baris_soal'},
			  {data: 'waktu_soal', name: 'waktu_soal'},
              {data: 'buat_soal', name: 'buat_soal'},
			  {data: 'aksi', name: 'aksi', orderable: true, searchable: true},
		  ]
	  });


	  $('#create_soal').submit(function(e){
		e.preventDefault();
		const data = $(this).serialize();
		$.ajax({
			url: "{{url('/list/soal')}}",
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

					swal("Berhasil!", "Soal berhasil ditambah!", {
							icon : "success",
							buttons: {        			
								confirm: {
									className : 'btn btn-primary btn-round'
								}
							},
					});

					$('#create_soal')[0].reset();
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
					text: "Anda akan menghapus soal ini!",
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
							url: "{{url('/list/soal')}}",
							method: "DELETE",
							data: {data:data},
							success:function(res){
								swal("Berhasil! Soal berhasil dihapus!", {
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
		
		


		$('body').on('click','.edit',function(){
			const data = $(this).attr('data-id');
			$.ajax({
				url: "{{url('/list/soal/detail')}}",
				method: "POST",
				data: {data:data},
				success:function(res){
					$('#exampleModalEdit .modal-body').html(res);
					$('#exampleModalEdit').modal('show');
                    $('#multiple2').select2({
                        theme: "bootstrap",
						dropdownParent: $('#exampleModalEdit')
                    });
				}
			});
		});


		$('body').on('submit','#edit_soal',function(e){
			  e.preventDefault();
			  const data = $(this).serialize();
			  $.ajax({
				url: "{{url('/list/soal')}}",
				method: "PUT",
				data: data,
				beforeSend:function(){
					$('.btn-edit').attr('disabled',true);
					$('.btn-edit').text('Loading...');
				},
				complete:function(){
					$('.btn-edit').attr('disabled',false);
					$('.btn-edit').text('Simpan');
				},
				success:function(res){

					if($.isEmptyObject(res.error)){


						$('.modal').modal('hide');

						swal("Berhasil!", "Soal berhasil diedit!", {
								icon : "success",
								buttons: {        			
									confirm: {
										className : 'btn btn-primary btn-round'
									}
								},
						});

						table.ajax.reload( null, false );
						
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




		
	});

</script>


@endpush

@endsection