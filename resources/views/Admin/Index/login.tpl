<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Template">
    <meta name="keywords" content="admin dashboard, admin, flat, flat ui, ui kit, app, web app, responsive">
    <link rel="shortcut icon" href="{asset('img/ico/favicon.png')}">
    <title>管理员登录</title>
    <!-- Base Styles -->
    <link href="{asset('css/style.css')}" rel="stylesheet">
    <link href="{asset('css/style-responsive.css')}" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.min.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

  <body class="login-body">
      <div class="login-logo">
          <img src="{asset('img/login_logo.png')}" alt=""/>
      </div>
      <h2 class="form-heading">考勤管理系统后台登录</h2>
      <div class="container log-row">
          <form class="form-signin" action="{route('AdminPostLogin')}" method="post">
              <div class="login-wrap">
                  <input type="text" name="username" class="form-control" placeholder="用户名" autofocus>
                  <input type="password" name="password" class="form-control" placeholder="密码">
                  <button class="btn btn-lg btn-success btn-block" type="submit">登录</button>
                  <label class="checkbox-custom check-success">
                      <input type="checkbox" value="remember-me" id="checkbox1" name="remember_me"> <label for="checkbox1">Remember me</label>
                      <a class="pull-right" data-toggle="modal" href="#forgotPass"> 忘记密码?</a>
                  </label>
                  <!-- <div class="registration"> 还没有注册账户?<a class="" href="registration.html">立即注册</a></div> -->
              </div>

              <!-- Modal -->
              <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id  ="forgotPass" class="modal fade">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              <h4 class="modal-title">忘记密码 ?</h4>
                          </div>
                          <div class="modal-body">
                              <p>请输入你的Email地址以便重置密码.</p>
                              <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">

                          </div>
                          <div class="modal-footer">
                              <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                              <button class="btn btn-success" type="button">提交</button>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- modal -->
          </form>
      </div>
      <!--jquery-1.10.2.min-->
      <script src="{asset('js/jquery-1.11.1.min.js')}"></script>
      <!--Bootstrap Js-->
      <script src="{asset('js/bootstrap.min.js')}"></script>
      <script src="{asset('js/jrespond..min.js')}"></script>
  </body>
</html>