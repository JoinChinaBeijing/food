<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"C:\phpStudy\PHPTutorial\WWW\tp\public/../application/index\view\login\login.html";i:1560519117;}*/ ?>
<!DOCTYPE html>
<html lang="zh"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>后台管理</title>
	<link rel="stylesheet" href="/css/admin/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/css/admin/login.css">
</head>

<body class="demo form-bg">
	<div class="sucaihuo-container">

		<div class="demo form-bg" style="padding: 20px 0;">
		        <div class="container">
		            <div class="row">
		                <div class="col-md-offset-3 col-md-6">
		                    <form class="form-horizontal" action="<?php echo url('login/login'); ?>"  method="post">
		                        <span class="heading">用户登录</span>
		                        <div class="form-group">
		                            <input type="text" class="form-control" id="inputEmail3" placeholder="用户名或手机号" name="name">
		                        </div>
		                        <div class="form-group help">
		                            <input type="password" class="form-control" id="inputPassword3" placeholder="密　码" name="password">
		                        </div>
		                        <div class="form-group">
		                            <!-- <div class="main-checkbox">
		                                <input type="checkbox" value="None" id="checkbox1" name="check">
		                                <label for="checkbox1"></label>
		                            </div>
		                            <span class="text">Remember me</span> -->
		                            <button type="submit" class="btn btn-default">登录</button>
		                        </div>
		                    </form>
		                </div>
		            </div>
		        </div>
		    </div>

	</div>


</body></html>