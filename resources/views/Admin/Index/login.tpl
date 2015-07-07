<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="考勤管理系统">
    <meta name="keywords" content="移动, 考勤, 商户, 位置, 里程">
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
          <form class="form-signin" action="" method="post">
              <div class="login-wrap">
                  <input type="text" name="username" id="username" value="{old('email')}" class="form-control" placeholder="用户名\邮箱\手机号" autofocus>
                  <input type="password" name="password" id="password" class="form-control" placeholder="密码">
                  <button class="btn btn-lg btn-success btn-block" id="login_user" type="button">登录</button>
                  <label class="checkbox-custom check-success">
                      <input type="checkbox" value="1" id="checkbox1" name="remember_me"> <label for="checkbox1">记住我</label>
                      <a class="pull-right" data-toggle="modal" href="#forgotPass"> 忘记密码?</a>
                  </label>
                  <!-- <div class="registration"> 还没有注册账户?<a class="" href="registration.html">立即注册</a></div> -->
              </div>

              <!-- Modal -->
			  <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			    <div class="modal-dialog">
			        <div class="modal-content">
				            <div class="modal-header">
				                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				                <h4 class="modal-title">提示</h4>
				            </div>
				            <div class="modal-body" id="alertMessage"></div>
				            <div class="modal-footer">
				                <button data-dismiss="modal" class="btn btn-success" type="button">确定</button>
				            </div>
			        </div>
			    </div>
			  </div>
			  <!-- modal -->
          </form>
          <!-- Modal -->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="forgotPass" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<form role="form" method="POST" action="{Route('AdminPostMail')}">
                       	<div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                           <h4 class="modal-title">忘记密码 ?</h4>
                       	</div>
                       	<div class="modal-body">
                           <p>请输入你的Email地址以便重置密码.</p>
                           <input type="text" name="email" id="email" placeholder="邮箱地址" autocomplete="off" class="form-control placeholder-no-fix" autofocus required />
                       	</div>
                       	<div class="modal-footer">
                           <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                           <button class="btn btn-success" type="submit" id="forgotPwBtn">提交</button>
						</div>
					</form>
				</div>
			</div>
		</div>
          <!-- modal -->
      </div>
      <!--jquery-1.10.2.min-->
      <script src="{asset('js/jquery-1.11.1.min.js')}"></script>
      <!--Bootstrap Js-->
      <script src="{asset('js/bootstrap.min.js')}"></script>
      <script src="{asset('js/jrespond..min.js')}"></script>
  </body>
</html>
<script type="text/javascript">
$("#login_user").click(function() {
    login();
});

$('#password,#username').keydown(function(e) {
    if (e.keyCode == 13) {
    	if($("#password").val()!='' && $("#username").val()!=''){
    		login();
    	}
    }
});

//登录
function login() {
    var username = $.trim($("#username").val());
    var password = $.trim($("#password").val());

    if (username && password) {
        $.ajax({
            type: 'POST',
            url: '{route('AdminPostLogin')}',
            data: { username : username, password : password },
            dataType: 'json',
            success: function(data) {
            	window.location.href = '{route('AdminIndex')}';
            },
            error: function(xhq) {
            	$("#alertMessage").html(xhq.responseText);
                $("#alertModal").modal('show');
            }
        });
    }
}

//忘记密码之发送邮件的提示框
var status="{Session::get('status')}";
if(status!=''){
	$("#alertMessage").html(status);
    $("#alertModal").modal('show');
}
var errors="{Session::has('errors')}";
if(errors){
	var content='';
	{foreach $errors->all() as $k=>$error}
		content+='{$error}'+'<br>';
	{/foreach}
	console.log('pa:'+content);
	$("#alertMessage").html(content);
    $("#alertModal").modal('show');
}

//忘记密码之重置页的提示框
var message_error="{Session::get('message_error')}";
var message_success="{Session::get('message_success')}";
if(message_error!=''){
	$("#alertMessage").html(message_error);
    $("#alertModal").modal('show');
}
if(message_success!=''){
	$("#alertMessage").html(message_success);
    $("#alertModal").modal('show');
}
</script>