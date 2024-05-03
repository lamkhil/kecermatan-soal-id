@extends('web.template')

@section('title','Riwayat Try Out')

@section('konten')

       
            <div class="container">
				<div class="panel-header bg-primary-gradient">
					<div class="page-inner py-5">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white pb-2 fw-bold">Riwayat Try Out</h2>
								<h5 class="text-white op-7 mb-2">Hasil Try Out yang pernah dikerjakan</h5>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner">

                    
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            
                        <div class="card">

                            <div class="card-body">
                            
                                    <div class="table-responsive">
										<table id="basic-datatables" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th>No</th>
													<th>Nama Try Out</th>
													<th>Tanggal</th>
                                                    <th></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
                                                    <th>No</th>
													<th>Nama Try Out</th>
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
                            <canvas id="barChart"></canvas>
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
		  ajax: "{{ url('/riwayat/tryout')}}",
		  columns: [
			  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'nama_tryout', name: 'nama_tryout'},
              {data: 'tanggal', name: 'tanggal'},
			  {data: 'detail', name: 'detail', orderable: true, searchable: true},
		  ]
	  });

		
	});


</script>

    
@endpush


@endsection