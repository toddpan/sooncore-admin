<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ucadmin密码重置</title>

<!-- Bootstrap -->
<link
	href="http://cdn.bootcss.com/bootstrap/3.3.2/css/bootstrap.min.css"
	rel="stylesheet">
</head>
<body>
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist" style="margin-top: 20px">
		<li role="presentation" class="active"><a href="#home"
			aria-controls="home" role="tab" data-toggle="tab">登录名</a></li>
		<li role="presentation"><a href="#profile" aria-controls="profile"
			role="tab" data-toggle="tab">组织ID</a></li>
		<li role="presentation"><a href="#theme" aria-controls="theme"
			role="tab" data-toggle="tab">用户信息</a></li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="home" style="margin-top:10px">
			<div class="alert alert-danger" role="alert">注意：多个登录名以逗号分隔。密码统一重置为8个1</div>
			<form class="form-horizontal">

				<div class="form-group">
					<label for="inputPassword" class="col-sm-1 control-label">登录名</label>
					<div class="col-sm-5">
						<textarea class="form-control" id="loginName" rows="3"></textarea>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-1 control-label">密码</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" value="11111111"
							id="loginName_pwd" placeholder="11111111" readonly>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-10">
						<button type="button" onclick="javascript:resetpwd(this,1);"
							class="btn btn-primary resetpwd">重置</button>
					</div>
				</div>
			</form>
		</div>

		<div role="tabpanel" class="tab-pane" id="profile" style="margin-top:10px">
			<div class="alert alert-danger" role="alert">注意：此操作会重置该组织以及该组织的子组织下所有用户的密码</div>
			<form class="form-horizontal">
				<div class="form-group">
					<label for="inputPassword" class="col-sm-1 control-label">组织ID</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" value="" id="org">
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-1 control-label">密码</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" value="11111111"
							id="org_pwd" placeholder="11111111" readonly>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-10">
						<button type="button" onclick="javascript:resetpwd(this,2)"
							class="btn btn-primary resetpwd">重置</button>
					</div>
				</div>
			</form>

		</div>



		<div role="tabpanel" class="tab-pane" id="theme" style="margin-top:10px">
			<form class="form-horizontal">
				<div class="form-group">
					<label for="inputPassword" class="col-sm-1 control-label">登录名</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" value="" id="_loginName">
					</div>
				</div>

				<div class="form-group">
					<label for="inputPassword" class="col-sm-1 control-label">手机号码</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" value="" id="_phone">
					</div>
				</div>

				<div class="form-group">
					<label for="inputPassword" class="col-sm-1 control-label">邮箱</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" value="" id="_email">
					</div>
				</div>
				
				
				<div class="form-group">
					<label for="inputPassword" class="col-sm-1 control-label">名字</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" value="" id="_firstName">
					</div>
				</div>
				
				<div class="form-group">
					<label for="inputPassword" class="col-sm-1 control-label">姓</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" value="" id="_lastName">
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-10">
						<button type="button" onclick="javascript:resetUserInfo(this)"
							class="btn btn-primary resetpwd">修改</button>
					</div>
				</div>
			</form>
		</div>
	</div>




	<script src="http://cdn.bootcss.com/jquery/2.1.3/jquery.js"></script>
	<script src="http://cdn.bootcss.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script>
	function resetpwd(obj,type){
		var data = new Object();
		if(type == 1){
			data.resetType = 1;
			data.loginName = $('#loginName').val();
			data.password  = $('#loginName_pwd').val();
		}else if(type == 2){
			data.resetType = 2;
			data.orgId = $('#org').val();
			data.password = $('#org_pwd').val();
		}
		$(obj).html("重置中...");
		$.post("<?php echo site_url('password/resetPasswordTmp');?>", data, function(data){
			data = JSON.parse(data);
			if(data.code == 0){
				$(obj).html("重置");
			}else{
				alert(data.msg);
			}
		});
	}

	function resetUserInfo(obj){
		var data = new Object();
		data.loginName = $('#_loginName').val();
		data.phone     = $('#_phone').val();
		data.email     = $('#_email').val();
		data.firstName    = $('#_firstName').val();
		data.lastName     = $('#_lastName').val();

		$(obj).html("修改中...");
		$.post("<?php echo site_url('password/resetUserInfoTmp');?>", data, function(data){
			data = JSON.parse(data);
			if(data.code == 0){
				$(obj).html("修改");
			}else{
				alert(data.msg);
			}
		});
	}
</script>
</body>
</html>