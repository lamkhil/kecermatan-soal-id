@extends('web.template')

@section('title','Member')


@section('konten')


            <div class="container">
				<div class="page-inner">
					<div class="page-header">
						<h4 class="page-title"><i class="fas fa-users"></i> Member</h4>
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
								<a href="{{url('/member')}}">Member</a>
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
												<i class="fa fa-user-plus"></i>
											</span>
											Tambah Member
										</button>
                                    </h4>
									
								</div>
								<div class="card-body">

                                 <div class="table-responsive">
										<table id="basic-datatables" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th>No</th>
													<th>Nama</th>
													<th>Email</th>
													<th>No HP</th>
													<th>Role</th>
													<th>Paket | Masa Aktif</th>
													<th>Tanggal Daftar</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th>No</th>
													<th>Nama</th>
													<th>Email</th>
													<th>No HP</th>
													<th>Role</th>
													<th>Paket | Masa Aktif</th>
													<th>Tanggal Daftar</th>
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
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-user-plus"></i> Tambah Member</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form id="create_member">
								<div class="form-group">
									<label for="">Nama</label>
									<input type="text" name="name" class="form-control">
								</div>
								<div class="form-group">
									<label for="">Email</label>
									<input type="email" name="email" class="form-control">
								</div>
								<div class="form-group">
									<label for="">No HP</label>
									<input type="number" name="no_hp" class="form-control">
								</div>
								<div class="form-group">
									<label for="">Paket</label>
									<select name="paket" class="form-control">
									<option value="">--Pilih Paket--</option>
									@foreach($pkg as $p)
									<option value="{{encrypt($p->id)}}">{{$p->nama_paket_bundle}}</option>
									@endforeach
									</select>
								</div>
								<div class="form-group">
									<label for="">Password</label>
									<input type="text" name="password" class="form-control">
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
			<div class="modal fade" id="exampleModalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalEditLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalEditLabel"><i class="fa fa-user-edit"></i> Edit Member</h5>
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
	  $(function () {
      
	  const table = $('#basic-datatables').DataTable({
		  processing: true,
		  serverSide: true,
		  ajax: "{{ url('/member')}}",
		  columns: [
			  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
			  {data: 'name', name: 'name'},
			  {data: 'email', name: 'email'},
			  {data: 'no_hp', name: 'no_hp'},
			  {data: 'role', name: 'role'},
			  {data: 'package', name: 'package'},
			  {data: 'created_at', name: 'created_at'},
			  {data: 'aksi', name: 'aksi', orderable: true, searchable: true},
		  ]
	  });


	  $('#create_member').submit(function(e){
		e.preventDefault();
		const data = $(this).serialize();
		$.ajax({
			url: "{{url('/member')}}",
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

					swal("Berhasil!", "Member berhasil ditambah!", {
							icon : "success",
							buttons: {        			
								confirm: {
									className : 'btn btn-primary btn-round'
								}
							},
					});

					$('#create_member')[0].reset();
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
					text: "Anda akan menghapus member ini!",
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
							url: "{{url('/member')}}",
							method: "DELETE",
							data: {data:data},
							success:function(res){
								swal("Berhasil! Member berhasil dihapus!", {
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
		
		
		$('body').on('click','.extend',function(){

				const data = $(this).attr('data-id');

				swal({
					icon: 'warning',
					title: 'Apakah Anda yakin?',
					text: "Anda akan memperpanjang durasi paket member ini!",
					buttons:{
						cancel: {
							visible: true,
							text : 'Batal',
							className: 'btn btn-danger btn-round'
						},        			
						confirm: {
							text : 'Perpanjang',
							className : 'btn btn-success btn-round'
						}
					}
				}).then((willDelete) => {
					if (willDelete) {

						$.ajax({
							url: "{{url('/member/extend')}}",
							method: "POST",
							data: {data:data},
							success:function(res){
								swal("Berhasil! Durasi paket berhasil diperpanjang!", {
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
				url: "{{url('/member/detail')}}",
				method: "POST",
				data: {data:data},
				success:function(res){
					$('#exampleModalEdit .modal-body').html(res);
					$('#exampleModalEdit').modal('show');
				}
			});
		});


		$('body').on('submit','#edit_member',function(e){
			  e.preventDefault();
			  const data = $(this).serialize();
			  $.ajax({
				url: "{{url('/member')}}",
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

						swal("Berhasil!", "Member berhasil diedit!", {
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