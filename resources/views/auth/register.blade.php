<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>HospitalNote</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css') }}">


    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}">


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition register-page">

<div class="register-box">
    <div class="register-logo">
        <a href="{{ route('home') }}"><b>HOSPITAL</b>NOTE</a>
    </div>

    <div class="register-box-body">
        <p class="login-box-msg">Register a new membership</p>

        <form action="{{ route('register') }}" method="post">
            @csrf

            <div class="form-group has-feedback @error('business_name') has-error @enderror">
                <input type="text" name="business_name" class="form-control" value="{{ old('business_name') }}" placeholder="Business name" required>
                <span class="glyphicon glyphicon-briefcase form-control-feedback"></span>
                @error('business_name')
                <span class="help-block">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group has-feedback @error('username') has-error @enderror">
                <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="User name" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                <span class="help-block">
                    <strong>You will need this username along with password during login.</strong>
                </span>
                @error('username')
                <span class="help-block">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group has-feedback @error('firstname') has-error @enderror">
                <input type="text" name="firstname" class="form-control" value="{{ old('firstname') }}" placeholder="First name" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @error('firstname')
                <span class="help-block">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group has-feedback @error('lastname') has-error @enderror">
                <input type="text" name="lastname" class="form-control" value="{{ old('lastname') }}" placeholder="Last name" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @error('lastname')
                <span class="help-block">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            {{--<div class="form-group has-feedback @error('email') has-error @enderror">
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @error('email')
                <span class="help-block">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>--}}
            <div class="form-group has-feedback @error('password') has-error @enderror">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @error('password')
                <span class="help-block">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group has-feedback ">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Retype password" required>
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-flat">
                Register
            </button>
        </form>
        <br>
        <p>
            <a href="{{ route('login') }}" class="text-center">
                I already have a membership
            </a>
        </p>
    </div>
    <!-- /.form-box -->
</div><!-- /.register-box -->

<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>



</body>
</html>
