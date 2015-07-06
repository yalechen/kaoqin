<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>密码重置</h2>
		<div>
			<p>请您点击下面的链接进行密码找回操作:</p>
			<p><a href="{{ route('AdminGetReset',['token'=>$token]) }}" target="_blank">密码找回链接</a></p>
			<p>为了确保您的账号安全该链接仅60分钟内访问有效.</p>
			<br></br>
			<p><b>如果你无法点击按钮，可以将如下链接复制后粘贴（或键入）到你的浏览器：</b></p>
			<p>{{ url('password/reset/'.$token) }}</p>
		</div>
	</body>
</html>