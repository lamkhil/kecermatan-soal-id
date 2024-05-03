@extends('web.template')

@section('title','Pembayaran')


@section('konten')


            <div class="container">
				<div class="page-inner">
					<div class="page-header">
						<h4 class="page-title"><i class="fas fa-wallet"></i> Pembayaran</h4>
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
								<a href="{{url('/pembayaran')}}">Pembayaran</a>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
		
								<div class="card-body">

                                 <div class="table-responsive">
										<table id="basic-datatables" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th>Invoice</th>
													<th>Nama</th>
													<th>Email</th>
                                                    <th>No HP</th>
                                                    <th>Paket</th>
                                                    <th>Jumlah Bayar</th>
                                                    <th>Tanggal</th>
                                                    <th>Status Bayar</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
                                                    <th>Invoice</th>
													<th>Nama</th>
													<th>Email</th>
                                                    <th>No HP</th>
                                                    <th>Paket</th>
                                                    <th>Jumlah Bayar</th>
                                                    <th>Tanggal</th>
                                                    <th>Status Bayar</th>
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


			
		
@push('scripts')

<script>
    $(function(){
        const table = $('#basic-datatables').DataTable({
		  processing: true,
		  serverSide: true,
		  ajax: "{{ url('/pembayaran')}}",
		  columns: [
			  {data: 'id_pembayaran', name: 'id_pembayaran'},
			  {data: 'nama', name: 'nama'},
			  {data: 'email', name: 'email'},
              {data: 'no_hp', name: 'no_hp'},
              {data: 'paket', name: 'paket'},
              {data: 'jumlah_bayar', name: 'jumlah_bayar'},
              {data: 'created_at', name: 'created_at'},
              {data: 'status_bayar', name: 'status_bayar'},
			  {data: 'aksi', name: 'aksi', orderable: true, searchable: true},
		  ]
	  });

        $('body').on('click','.delete',function(){

				const data = $(this).attr('data-id');

				swal({
					icon: 'warning',
					title: 'Apakah Anda yakin?',
					text: "Anda akan menghapus pembayaran ini!",
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
							url: "{{url('/pembayaran')}}",
							method: "DELETE",
							data: {data:data},
							success:function(res){
								swal("Berhasil! Pembayaran berhasil dihapus!", {
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
        
         $('body').on('click','.confirm',function(){

				const data = $(this).attr('data-id');

				swal({
					icon: 'warning',
					title: 'Apakah Anda yakin?',
					text: "Anda akan mengubah status pembayaran ini!",
					buttons:{
						cancel: {
							visible: true,
							text : 'Batal',
							className: 'btn btn-danger btn-round'
						},        			
						confirm: {
							text : 'Ubah',
							className : 'btn btn-success btn-round'
						}
					}
				}).then((willDelete) => {
					if (willDelete) {

						$.ajax({
							url: "{{url('/pembayaran')}}",
							method: "PUT",
							data: {data:data},
							success:function(res){
								swal("Berhasil! Status pembayaran berhasil diubah!", {
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