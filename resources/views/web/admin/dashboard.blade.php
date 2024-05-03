@extends('web.template')

@section('title','Dashboard')

@section('konten')

       
            <div class="container">
				<div class="panel-header bg-primary-gradient">
					<div class="page-inner py-5">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white pb-2 fw-bold">Dashboard</h2>
								<h5 class="text-white op-7 mb-2">Welcome, {{Auth()->user()->name}}</h5>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner">

                   <div class="row">

				     <div class="col-md-3">

					 				<div class="card card-stats card-primary card-round">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<div class="icon-big text-center">
														<i class="flaticon-users"></i>
													</div>
												</div>
												<div class="col-7 col-stats">
													<div class="numbers">
														<p class="card-category">Member</p>
														<h4 class="card-title">{{$ds['member']}}</h4>
													</div>
												</div>
											</div>
										</div>
									</div>
									
					 </div>

					 <div class="col-md-3">

					 				<div class="card card-stats card-success card-round">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<div class="icon-big text-center">
														<i class="flaticon-box-2"></i>
													</div>
												</div>
												<div class="col-7 col-stats">
													<div class="numbers">
														<p class="card-category">Paket</p>
														<h4 class="card-title">{{$ds['paket']}}</h4>
													</div>
												</div>
											</div>
										</div>
									</div>					 				
					 </div>

					 <div class="col-md-3">

					 				<div class="card card-stats card-secondary card-round">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<div class="icon-big text-center">
														<i class="flaticon-file-1"></i>
													</div>
												</div>
												<div class="col-7 col-stats">
													<div class="numbers">
														<p class="card-category">Bab Soal</p>
														<h4 class="card-title">{{$ds['babsoal']}}</h4>
													</div>
												</div>
											</div>
										</div>
									</div>					 				
					 </div>

					 <div class="col-md-3">

					 				<div class="card card-stats card-warning card-round">
										<div class="card-body">
											<div class="row">
												<div class="col-5">
													<div class="icon-big text-center">
														<i class="flaticon-list"></i>
													</div>
												</div>
												<div class="col-7 col-stats">
													<div class="numbers">
														<p class="card-category">List Soal</p>
														<h4 class="card-title">{{$ds['listsoal']}}</h4>
													</div>
												</div>
											</div>
										</div>
									</div>					 				
					 </div>

					


				   </div>

				   
					
				</div>
			</div>

        

@push('scripts')


   

@endpush


@endsection