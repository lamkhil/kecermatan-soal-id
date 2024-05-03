@extends('web.template')

@section('title','Profile')

@section('konten')

       
            <div class="container">
				<div class="panel-header bg-primary-gradient">
					<div class="page-inner py-5">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white pb-2 fw-bold">Profile</h2>
								<h5 class="text-white op-7 mb-2">Informasi profile</h5>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner">

                    
                    <div class="row">

                    <div class="col-md-8">
							<div class="card">
								<div class="card-body">
									<form id="update_profile">
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group form-group-default">
                                                    <label>Nama</label>
                                                    <input type="text" class="form-control" value="{{Auth()->user()->name}}" name="name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group form-group-default">
                                                    <label>Email</label>
                                                    <input type="email" class="form-control" value="{{Auth()->user()->email}}" name="email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group form-group-default">
                                                    <label>No HP</label>
                                                    <input type="number" class="form-control" value="{{Auth()->user()->no_hp}}" name="no_hp">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3 mb-1">
                                            <div class="col-md-12">
                                                <div class="form-group form-group-default">
                                                    <label>Password</label>
                                                    <input type="password" class="form-control" name="password">
                                                </div>
                                                <small class="text-danger">*Kosongkan jika tidak ingin mengganti password</small>
                                            </div>
                                        </div>
                                        <div class="text-right mt-3 mb-3">
                                            <button class="btn btn-create btn-primary btn-round">Simpan</button>
                                        </div>
                                    </form>
								</div>
							</div>
						</div>

                        <div class="col-md-4">
							<div class="card card-profile">
								<div class="card-header" style="background-image: url({{asset('assets/img/bg-abstract.png')}})">
									<div class="profile-picture">
										<div class="avatar avatar-xl">
											<img src="{{asset('assets/img/user.png')}}" alt="..." class="avatar-img rounded-circle">
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="user-profile text-center">
										<div class="name">{{Auth()->user()->name}}</div>
										<div class="job">{{Auth()->user()->email}}</div>
										<div class="desc">{{Auth()->user()->no_hp}}</div>
										<!-- <div class="social-media">
											<a class="btn btn-info btn-twitter btn-sm btn-link" href="#"> 
												<span class="btn-label just-icon"><i class="flaticon-twitter"></i> </span>
											</a>
											<a class="btn btn-danger btn-sm btn-link" rel="publisher" href="#"> 
												<span class="btn-label just-icon"><i class="flaticon-google-plus"></i> </span> 
											</a>
											<a class="btn btn-primary btn-sm btn-link" rel="publisher" href="#"> 
												<span class="btn-label just-icon"><i class="flaticon-facebook"></i> </span> 
											</a>
											<a class="btn btn-danger btn-sm btn-link" rel="publisher" href="#"> 
												<span class="btn-label just-icon"><i class="flaticon-dribbble"></i> </span> 
											</a>
										</div> -->
										<div class="view-profile">
											<a href="javascript:void(0)" class="btn btn-secondary btn-block">{{Auth()->user()->package->nama_paket_bundle}}</a>
										</div>
									</div>
								</div>
							</div>
						</div>
                        
                    </div>
					
					
				</div>
			</div>


        

@push('scripts')

<script>
     $('#update_profile').submit(function(e){
		e.preventDefault();
		const data = $(this).serialize();
		$.ajax({
			url: "{{url('/member/profile')}}",
			method: "PUT",
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

					swal("Berhasil!", "Update profile berhasil", {
							icon : "success",
							buttons: {        			
								confirm: {
									className : 'btn btn-primary btn-round'
								}
							},
					});
                    

                    setTimeout(() => {
                        location.reload();
                    }, 1500);
			
                    
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
</script>
    
@endpush


@endsection