@extends('web.template')

@section('title','Setting Web')

@section('konten')

       
            <div class="container">
				
				<div class="page-inner">

                <div class="page-header">
						<h4 class="page-title"><i class="fas fa-cogs"></i> Setting Web</h4>
						<ul class="breadcrumbs">
							<li class="nav-home">
								<a href="{{url('/admin/dashboard')}}">
									<i class="flaticon-home"></i>
								</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="{{url('/setting/web')}}">Setting Web</a>
							</li>
						</ul>
					</div>
                    
                    <div class="row justify-content-center">

                    <div class="col-md-8">
							<div class="card">
								<div class="card-body">
									<form id="setting_web" enctype="multipart/form-data">
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group form-group-default">
                                                    <label>Judul Web</label>
                                                    <input type="text" value="{{$data !== null ? $data->judul_web : '' }}" class="form-control" name="judul_web">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group form-group-default">
                                                    <label>Meta Deskripsi Web</label>
                                                    <textarea name="meta_deskripsi_web" class="form-control">{{$data !== null ? $data->meta_desc_web : '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group form-group-default">
                                                    <label>Deskripsi Detail Pembayaran</label>
                                                    <textarea name="deskripsi_bayar" class="form-control">{{$data !== null ? $data->desc_detail_bayar : '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
										<div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group form-group-default">
                                                    <label>No WhatsApp</label>
                                                    <input type="number" class="form-control" name="no_wa" value="{{$data !== null ? $data->no_wa : '' }}">
                                                </div>
												<small class="text-danger">*Ganti angka 0 diawal dengan kode negara. Contoh : 628211277643</small>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group form-group-default">
                                                    <label>Copyright Website</label>
                                                    <input type="text" class="form-control" value="{{$data !== null ? $data->copyright : '' }}" name="copyright_web">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <img src="{{asset('assets/img')}}/{{$data !== null ? $data->logo_web : '' }}" width="300" class="img-thumbnail"> <br><br>
                                                <div class="form-group form-group-default">
                                                    <label>Logo Web</label>
                                                    <input type="file" class="form-control" name="logo_web">
                                                </div>
                                                <small class="text-danger">*Format gambar yang diizinkan : jpg,jpeg,png,gif. Ukuran maksimal 2 MB</small>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <img src="{{asset('assets/img')}}/{{$data !== null ? $data->icon_web : '' }}" width="300" class="img-thumbnail"> <br><br>
                                                <div class="form-group form-group-default">
                                                    <label>Icon Web</label>
                                                    <input type="file" class="form-control" name="icon_web">
                                                </div>
                                                <small class="text-danger">*Format gambar yang diizinkan : jpg,jpeg,png,gif. Ukuran maksimal 2 MB</small>
                                            </div>
                                        </div>
                                        <div class="text-right mt-3 mb-3">
                                            <button class="btn btn-create btn-primary btn-round">Simpan</button>
                                        </div>
                                    </form>
								</div>
							</div>
						</div>
                        
                    </div>
					
					
				</div>
			</div>


        

@push('scripts')

<script>
     $('#setting_web').submit(function(e){
		e.preventDefault();
        const form = $('#setting_web')[0];
		const data = new FormData(form);
		$.ajax({
			url: "{{url('/setting/web')}}",
			method: "POST",
            enctype: 'multipart/form-data',
			data: data,
            processData: false,
            contentType: false,
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

					swal("Berhasil!", "Setting web berhasil", {
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