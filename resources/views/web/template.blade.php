
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>@yield('title') | {{$config['web'] !== null ? $config['web']->judul_web : ''}}</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="description" content="{{$config['web'] !== null ? $config['web']->meta_desc_web : ''}}">
	<link rel="icon" href="{{asset('assets/img')}}/{{$config['web'] !== null ? $config['web']->icon_web : ''}}">

	<!-- Fonts and icons -->
	<script src="{{asset('assets/js/plugin/webfont/webfont.min.js')}}"></script>
	<script>
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['{{asset("assets/css/fonts.min.css")}}']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/js/plugin/countdown/css/jquery.countdown.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/atlantis.css')}}">
   

    <style>
        /* .ph-item{
            border-radius: 5px;
            background-color: #ffffff;
            margin-bottom: 30px;
            -webkit-box-shadow: 2px 6px 15px 0px rgb(69 65 78 / 10%);
            -moz-box-shadow: 2px 6px 15px 0px rgba(69, 65, 78, 0.1);
            box-shadow: 2px 6px 15px 0px rgb(69 65 78 / 10%);
            border: 0px;
                } */

			.select2-container{
			width: 100%!important;
			}
			.select2-search--dropdown .select2-search__field {
			width: 98%;
			}

		.is-countdown{
			border: none;
			background-color: transparent;
		}
		.jawab{
			cursor: pointer;
		}

		/*.jawab:hover{*/
		/*	background-color: #eaeaea;*/
		/*}*/
		
		/*#barissoal td{ */
		/*	font-size: 7rem;*/
		/*}*/

		.sidebar.sidebar-style-2 .nav.nav-primary > .nav-item a:hover p {
			color: #fff !important;
		}

		.sidebar.sidebar-style-2 .nav.nav-primary > .nav-item a:hover i {
			color: #fff !important;
		}

		.sidebar.sidebar-style-2 .nav .nav-item a[data-toggle=collapse][aria-expanded=true] p {
			color: #fff !important;
		}

		.sidebar.sidebar-style-2 .nav .nav-item a[data-toggle=collapse][aria-expanded=true] i {
			color: #fff !important;
		}

		.sidebar.sidebar-style-2 .nav .nav-item a:focus p{
			color: #fff !important;
		}

		.sidebar.sidebar-style-2 .nav .nav-item a:focus i{
			color: #fff !important;
		}

		.sidebar.sidebar-style-2 .nav.nav-primary > .nav-item {
			padding-top: 0.25rem !important;
			padding-bottom: 0.25rem !important;
		}


		.sidebar.sidebar-style-2 .nav.nav-primary > .nav-item > a {
			background: #dc3545 !important;
			box-shadow: 4px 4px 10px 0 rgb(0 0 0 / 10%), 4px 4px 15px -5px rgb(21 114 232 / 40%);
		}

		.sidebar.sidebar-style-2 .nav.nav-primary > .nav-item p {
			color: #fff !important;
		}

		.sidebar.sidebar-style-2 .nav.nav-primary > .nav-item i {
			color: #fff !important;
		}

    </style>

	
</head>
<body>
	<div class="wrapper">
		<div class="main-header">
			<!-- Logo Header -->
			<div class="logo-header" data-background-color="blue">
				<a href="" class="logo">
            		<img src="{{asset('assets/img')}}/{{$config['web'] !== null ? $config['web']->logo_web : ''}}" alt="navbar brand" class="navbar-brand img-fluid" width="120" style="margin-left:25px">
        		</a>
				<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon">
						<i class="icon-menu"></i>
					</span>
				</button>
				<button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
				<div class="nav-toggle">
					<button class="btn btn-toggle toggle-sidebar">
						<i class="icon-menu"></i>
					</button>
				</div>
			</div>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">
				<div class="container-fluid">
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<li class="nav-item dropdown hidden-caret">
                            <a href="javascript:void(0);" class="btn btn-white btn-border btn-round mr-2">
								{{now()->format('d F Y')}} | 
								<span id="display-waktu"></span>
							</a>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
								<div class="avatar-sm">
									<img src="{{asset('assets/img/user.png')}}" alt="..." class="avatar-img rounded-circle">
								</div>
							</a>
							<ul class="dropdown-menu dropdown-user animated fadeIn">
								<div class="dropdown-user-scroll scrollbar-outer">
									<li>
										<div class="user-box">
											<!-- <div class="avatar-lg"><img src="{{asset('assets/img/user.png')}}" alt="image profile" class="avatar-img rounded"></div> -->
											<div class="u-text">
												<h4>{{Str::limit(Auth()->user()->name, 11)}}</h4>
												<p class="text-muted">{{Auth()->user()->email}}</p>
                                                @if(Auth()->user()->role == 'member')
												<a href="javascript:void(0);" class="btn btn-xs btn-secondary btn-sm">
													{{Auth()->user()->package->nama_paket_bundle}}
												</a>
												<br>
												<a href="javascript:void(0);" class="btn btn-xs btn-success btn-sm mt-2">
													Active Until: {{!empty(Auth()->user()->expired_at) ? date('d-m-Y' ,strtotime(Auth()->user()->expired_at)) : '-'}}
												</a>
												@endif
											</div>
										</div>
									</li>
									<li>
										<div class="dropdown-divider"></div>
										@if(Auth()->user()->role == 'admin')
										<a class="dropdown-item" href="{{url('/profile')}}">My Profile</a>
										@endif
										@if(Auth()->user()->role == 'member')
										<a class="dropdown-item" href="{{url('/member/profile')}}">My Profile</a>
										@endif
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="{{url('/logout')}}">Logout</a>
									</li>
								</div>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
			<!-- End Navbar -->
		</div>

		<!-- Sidebar -->
		<div class="sidebar sidebar-style-2">			
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<div class="user">
						<div class="avatar-sm float-left mr-2">
							<img src="{{asset('assets/img/user.png')}}" alt="..." class="avatar-img rounded-circle">
						</div>
						<div class="info">
							<a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
								<span>
									{{Str::limit(Auth()->user()->name, 11)}}
									<span class="user-level">
                                        {{Auth()->user()->role == 'admin' ? 'Administrator' : 'Member' }}
                                    </span>
									<span class="caret"></span>
								</span>
							</a>
							<div class="clearfix"></div>

							<div class="collapse in" id="collapseExample">
								<ul class="nav">
									@if(Auth()->user()->role == 'admin')
									<li>
										<a href="{{url('/profile')}}">
											<span class="link-collapse">My Profile</span>
										</a>
									</li>
									@endif
									@if(Auth()->user()->role == 'member')
									<li>
										<a href="{{url('/member/profile')}}">
											<span class="link-collapse">My Profile</span>
										</a>
									</li>
									@endif
								</ul>
							</div>
						</div>
					</div>
					<ul class="nav nav-primary">
						@if(Auth()->user()->role == 'admin')
						<li class="nav-item">
							<a href="{{url('/admin/dashboard')}}">
								<i class="fas fa-home"></i>
								<p>Dashboard</p>
							</a>
						</li>
						@endif
						@if(Auth()->user()->role == 'member')
						<li class="nav-item">
							<a href="{{url('/member/dashboard')}}">
								<i class="fas fa-home"></i>
								<p>Dashboard</p>
							</a>
						</li>
						@endif
						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
							<h4 class="text-section">Menu</h4>
						</li>
						@if(Auth()->user()->role == 'admin')
						<!-- <li class="nav-item">
							<a href="{{url('/paket')}}">
								<i class="fas fa-cubes"></i>
								<p>Paket</p>
							</a>
						</li> -->
						<li class="nav-item">
							<a href="{{url('/paket/bundle')}}">
								<i class="fas fa-cubes"></i>
								<p>Paket Bundle</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{url('/member')}}">
								<i class="fas fa-users"></i>
								<p>Member</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{url('/pembayaran')}}">
								<i class="fas fa-wallet"></i>
								<p>Pembayaran</p>
							</a>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#basesoal">
								<i class="fas fa-edit"></i>
								<p>Soal Try Out</p>
								<span class="caret text-white"></span>
							</a>
							<div class="collapse {{Request()->is('bab/soal') ? 'show' : ''}}{{Request()->is('list/soal') ? 'show' : ''}}{{Request()->is('list/soal/detail/*') ? 'show' : ''}}" id="basesoal">
								<ul class="nav nav-collapse">
									<li class="{{Request()->is('bab/soal') ? 'active' : ''}}">
										<a href="{{url('/bab/soal')}}">
											<span class="sub-item">Bab Soal</span>
										</a>
									</li>
									<li class="{{Request()->is('list/soal') ? 'active' : ''}}{{Request()->is('list/soal/detail/*') ? 'active' : ''}}">
										<a href="{{url('/list/soal')}}">
											<span class="sub-item">List Soal</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a href="{{url('/setting/web')}}">
								<i class="fas fa-cogs"></i>
								<p>Setting Web</p>
							</a>
						</li>
						@endif
						@if(Auth()->user()->role == 'member')
						<li class="nav-item ">
							<a href="{{url('/list/tryout')}}">
								<i class="fas fa-file-alt"></i>
								<p>List Try Out</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{url('/list/paket')}}">
								<i class="fas fa-cubes"></i>
								<p>Paket</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{url('/riwayat/tryout')}}">
								<i class="fas fa-file-signature"></i>
								<p>Riwayat Try Out</p>
							</a>
						</li>
						@endif
					</ul>
				</div>
			</div>
		</div>
		<!-- End Sidebar -->

		<div class="main-panel">

			@yield('konten')

			<footer class="footer">
				<div class="container-fluid">
                    <nav class="pull-left">
						<ul class="nav">
							<li class="nav-item">
								<a class="nav-link" href="javascript:void(0)">
							Page rendered in 0.1 seconds</a>
							</li>
						</ul>
					</nav>
					<div class="copyright ml-auto">
						{{$config['web'] !== null ? $config['web']->copyright : ''}}
					</div>				
				</div>
			</footer>
		</div>
		
		
	</div>
	<!--   Core JS Files   -->
	<script src="{{asset('assets/js/core/jquery.3.2.1.min.js')}}"></script>
	<script src="{{asset('assets/js/core/popper.min.js')}}"></script>
	<script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>

	<!-- jQuery UI -->
	<script src="{{asset('assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js')}}"></script>
	<script src="{{asset('assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js')}}"></script>

	<!-- jQuery Scrollbar -->
	<script src="{{asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>

	<!-- Moment JS -->
	<script src="{{asset('assets/js/plugin/moment/moment.min.js')}}"></script>

	<!-- Datatables -->
	<script src="{{asset('assets/js/plugin/datatables/datatables.min.js')}}"></script>

	<!-- Bootstrap Notify -->
	<script src="{{asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js')}}"></script>

	<!-- Bootstrap Toggle -->
	<script src="{{asset('assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js')}}"></script>

	<!-- Fullcalendar -->
	<script src="{{asset('assets/js/plugin/fullcalendar/fullcalendar.min.js')}}"></script>

	<!-- DateTimePicker -->
	<script src="{{asset('assets/js/plugin/datepicker/bootstrap-datetimepicker.min.js')}}"></script>

	<!-- Bootstrap Tagsinput -->
	<script src="{{asset('assets/js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>

	<!-- Select2 -->
	<script src="{{asset('assets/js/plugin/select2/select2.full.min.js')}}"></script>

	<!-- Sweet Alert -->
	<script src="{{asset('assets/js/plugin/sweetalert/sweetalert.min.js')}}"></script>

	<!-- Owl Carousel -->
	<script src="{{asset('assets/js/plugin/owl-carousel/owl.carousel.min.js')}}"></script>

	<!-- Atlantis JS -->
	<script src="{{asset('assets/js/atlantis.min.js')}}"></script>

	<script src="{{asset('assets/js/plugin/countdown/js/jquery.plugin.min.js')}}"></script>

	<script src="{{asset('assets/js/plugin/countdown/js/jquery.countdown.min.js')}}"></script>

	<!-- Chart JS -->
	<script src="{{asset('assets/js/plugin/chart.js/chart.min.js')}}"></script>

	<script src="{{asset('assets/js/plugin/fittext/jquery.fittext.js')}}"></script>
	
	<script>
		window.onload = function() { jam(); }

		function jam() {
		var e = document.getElementById('display-waktu'),
		d = new Date(), h, m, s;
		h = d.getHours();
		m = set(d.getMinutes());
		s = set(d.getSeconds());

		e.innerHTML = h +':'+ m +':'+ s;

		setTimeout('jam()', 1000);
		}

		function set(e) {
		e = e < 10 ? '0'+ e : e;
		return e;
		}
	</script>	

	<script>
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	</script>

	@stack('scripts')

	
</body>
</html>
