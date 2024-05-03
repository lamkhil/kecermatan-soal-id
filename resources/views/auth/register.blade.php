
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Daftar | {{$config['web'] !== null ? $config['web']->judul_web : ''}}</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
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
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/atlantis.css')}}">
</head>
<body class="login">
	<div class="wrapper wrapper-login">
		<div class="container container-login animated fadeIn">
            <div class="logo text-center mb-3">
                <img src="{{asset('assets/img')}}/{{$config['web'] !== null ? $config['web']->logo_web : ''}}" width="200" class="img-fluid">        
            </div>
			<p class="text-center">Daftar untuk menikmati layanan</p>
			<div class="login-form">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
				<form action="{{url('/register')}}" method="POST">
                    @csrf
                    <div class="form-group form-floating-label">
                        <input  id="fullname" value="{{old('fullname')}}" name="fullname" type="text" class="form-control input-border-bottom" required>
                        <label for="fullname" class="placeholder">Nama Lengkap</label>
                    </div>
                    <div class="form-group form-floating-label">
                        <input  id="email" value="{{old('email')}}" name="email" type="email" class="form-control input-border-bottom" required>
                        <label for="email" class="placeholder">Email</label>
                    </div>
                    <div class="form-group form-floating-label">
                        <input  id="no_hp" value="{{old('no_hp')}}" name="no_hp" type="number" class="form-control input-border-bottom" required>
                        <label for="no_hp" class="placeholder">No HP</label>
                    </div>
                    <div class="form-group form-floating-label">
                        <input  id="passwordsignin" name="password" type="password" class="form-control input-border-bottom" required>
                        <label for="passwordsignin" class="placeholder">Password</label>
                        <div class="show-password">
                            <i class="icon-eye"></i>
                        </div>
                    </div>
                    <div class="form-group form-floating-label">
                        <input  id="confirmpassword" name="password_confirmation" type="password" class="form-control input-border-bottom" required>
                        <label for="confirmpassword" class="placeholder">Konfirmasi Password</label>
                        <div class="show-password">
                            <i class="icon-eye"></i>
                        </div>
                    </div>
                    <div class="form-action">
                        <a href="{{url('/login')}}" class="btn btn-secondary btn-link btn-login mr-3">Login</a>
                        <button type="submit" class="btn btn-primary btn-rounded btn-login">Daftar</button>
                    </div>
                </form>
			</div>
		</div>

	</div>
	<script src="{{asset('assets/js/core/jquery.3.2.1.min.js')}}"></script>
	<script src="{{asset('assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js')}}"></script>
	<script src="{{asset('assets/js/core/popper.min.js')}}"></script>
	<script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>
	<script src="{{asset('assets/js/atlantis.min.js')}}"></script>
</body>
</html>