<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="考勤管理系统">
    <meta name="keywords" content="移动, 考勤, 商户, 位置, 里程">
    <link rel="shortcut icon" href="{{ asset('img/ico/favicon.png') }}">
    <title>重置密码</title>
    <!-- Base Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-responsive.css') }}" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.min.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-body">
    <div class="login-logo"><img src="{{ asset('img/login_logo.png') }}" alt=""/></div>
    <h2 class="form-heading">重置密码</h2>
    <div class="container log-row">
        <form class="form-signin" action="{{ route('AdminPostReset') }}" method="post">
            <input type="hidden" value="{{ $token }}" name="token" />
            <input type="text" name="email" class="form-control" placeholder="邮箱地址" required autofocus>
            <input type="password" name="password" class="form-control" placeholder="新密码" required>
            <input type="password" name="password_confirmation" class="form-control" placeholder="确认新密码" required>
            <button class="btn btn-lg btn-success btn-block" type="submit" id="submitBtn">重置密码</button>
            <div class="registration m-t-20 m-b-20"> 您已经有帐号了。 <a class="" href="{{ route('AdminGetLogin') }}">开始登录</a></div>
        </form>
    </div>
    <!--jquery-1.10.2.min-->
    <script src="{{ asset('js/jquery-1.11.1.min.js') }}"></script>
    <!--Bootstrap Js-->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jrespond..min.js') }}"></script>
</body>
</html>
