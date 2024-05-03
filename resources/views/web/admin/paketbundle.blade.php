@extends('web.template')

@section('title','Paket Bundle')


@section('konten')


            <div class="container">
				<div class="page-inner">
					<div class="page-header">
						<h4 class="page-title"><i class="fas fa-cubes"></i> Paket Bundle</h4>
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
								<a href="{{url('/paket/bundle')}}">Paket Bundle</a>
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
											Tambah Paket Bundle
										</button>
                                    </h4>
									
								</div>
								<div class="card-body">

                                 <div class="table-responsive">
										<table id="basic-datatables" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th>No</th>
													<th>Nama Paket Bundle</th>
													<th>Deskripsi Paket Bundle</th>
                                                    <th>List Soal</th>
                                                    <th>Durasi Paket Bundle (Hari)</th>
                                                    <th>Harga Paket Bundle</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
                                                    <th>No</th>
													<th>Nama Paket Bundle</th>
													<th>Deskripsi Paket Bundle</th>
                                                    <th>List Soal</th>
                                                    <th>Durasi Paket Bundle (Hari)</th>
                                                    <th>Harga Paket Bundle</th>
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
							<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Tambah Paket Bundle</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form id="create_paketbundle">
								<div class="form-group">
									<label for="">Nama Paket Bundle</label>
									<input type="text" name="nama_paket_bundle" class="form-control">
								</div>
								<div class="form-group">
									<label for="">Deskripsi Paket Bundle</label>
									<textarea name="deskripsi_paket_bundle" class="form-control"></textarea>
								</div>
                                <div class="form-group">
									<label for="">List Soal</label>
                                      <select id="multiple1" name="soal[]" class="form-control" multiple="multiple">
                                        @foreach($soal as $s)
                                        <option value="{{encrypt($s->id)}}">{{$s->judul_soal}}</option>
                                        @endforeach
									   </select>          
								</div>
                                <div class="form-group">
									<label for="">Durasi Paket Bundle (Hari)</label>
									<input type="number" name="durasi_paket_bundle" class="form-control" value="0">
                                    <small class="text-danger">*Ketikan 0 jika durasi paket tidak terbatas</small>
								</div>
                                <div class="form-group">
									<label for="">Harga Paket Bundle</label>
									<input type="number" name="harga_paket_bundle" class="form-control">
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
							<h5 class="modal-title" id="exampleModalEditLabel"><i class="fa fa-edit"></i> Edit Paket Bundle</h5>
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
		  ajax: "{{ url('/paket/bundle')}}",
		  columns: [
			  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
			  {data: 'nama_paket_bundle', name: 'nama_paket_bundle'},
			  {data: 'deskripsi_paket_bundle', name: 'deskripsi_paket_bundle'},
              {data: 'list_soal', name: 'list_soal'},
              {data: 'durasi_paket_bundle', name: 'durasi_paket_bundle'},
              {data: 'harga_paket_bundle', name: 'harga_paket_bundle'},
			  {data: 'aksi', name: 'aksi', orderable: true, searchable: true},
		  ]
	  });


	  $('#create_paketbundle').submit(function(e){
		e.preventDefault();
		const data = $(this).serialize();
		$.ajax({
			url: "{{url('/paket/bundle')}}",
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

					swal("Berhasil!", "Paket bundle berhasil ditambah!", {
							icon : "success",
							buttons: {        			
								confirm: {
									className : 'btn btn-primary btn-round'
								}
							},
					});

					$('#create_paketbundle')[0].reset();
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
					text: "Anda akan menghapus paket bundle ini!",
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
							url: "{{url('/paket/bundle')}}",
							method: "DELETE",
							data: {data:data},
							success:function(res){
								swal("Berhasil! Paket bundle berhasil dihapus!", {
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
				url: "{{url('/paket/bundle/detail')}}",
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


		$('body').on('submit','#edit_paketbundle',function(e){
			  e.preventDefault();
			  const data = $(this).serialize();
			  $.ajax({
				url: "{{url('/paket/bundle')}}",
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

						swal("Berhasil!", "Paket bundle berhasil diedit!", {
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