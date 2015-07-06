<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="考勤管理系统">
    <meta name="keywords" content="移动, 考勤, 商户, 位置, 里程">
    <link rel="shortcut icon" href="{asset('img/ico/favicon.png')}">
    <title>重置密码</title>
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
      <div class="login-logo"><img src="{asset('img/login_logo.png')}" alt=""/></div>
      <h2 class="form-heading">重置密码</h2>
      <div class="container log-row">
          <form class="form-signin" action="{route('AdminPostReset')}" method="post">
              <input type="text" name="email" class="form-control" placeholder="邮箱地址" required autofocus>
              <input type="password" name="password" class="form-control" placeholder="新密码" required>
              <input type="password" name="password_confirmation" class="form-control" placeholder="确认新密码" required>
              <button class="btn btn-lg btn-success btn-block" type="button" id="submitBtn">保存</button>
              <div class="registration m-t-20 m-b-20"> 您已经有帐号了。 <a class="" href="{route('AdminPostReset')}">重置密码</a></div>
          </form>

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
      </div>

    <!--jquery-1.10.2.min-->
    <script src="{asset('js/jquery-1.11.1.min.js')}"></script>
    <!--Bootstrap Js-->
    <script src="{asset('js/bootstrap.min.js')}"></script>
    <script src="{asset('js/jrespond..min.js')}"></script>
  </body>
</html>
<script type="text/javascript">
$("#submitBtn").click(function() {
	reset();
});
function reset() {
    var email = $.trim($('input[name="email"]').val());
    var password = $.trim($('input[name="password"]').val());
    var password_confirmation = $.trim($('input[name="password_confirmation"]').val());

    if (username && password && password_confirmation) {
        $.ajax({
            type: 'POST',
            url: '{route('AdminPostReset')}',
            data: { email : email, password : password, password_confirmation : password_confirmation },
            dataType: 'json',
            success: function(data) {
            	$("#alertMessage").html("重置成功");
                $("#alertModal").modal('show');
            	window.location.href = '{route('AdminGetLogin')}';
            },
            error: function(xhq) {
            	$("#alertMessage").html(xhq.responseText);
                $("#alertModal").modal('show');
            }
        });
    }
}
</script>
