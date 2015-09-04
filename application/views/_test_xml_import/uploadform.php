<html>
<head>
<title>Upload Form</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
<link href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap-theme.min.css" rel="stylesheet">
<script src="http://cdn.bootcss.com/json2/20140204/json2.min.js"></script>
<script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/jquery.form/3.51/jquery.form.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container-fluid">
<div class="row-fluid">
<div class="span12">
<h2 contenteditable="true">蜜蜂xml账号自动导入</h2>
<section id="request">
<form id="upload" class="form-horizontal" method="post" action="">
  <div class="form-group">
    <label for="type" class="col-sm-2 control-label">类型</label>
    <div class="col-sm-10">
      <input type="text" name="type" class="form-control" id="type" placeholder="xml 或 xsl">
    </div>
  </div>
  <div class="form-group">
    <label for="loginname" class="col-sm-2 control-label">帐号</label>
    <div class="col-sm-10">
      <input type="text" name="loginname" class="form-control" id="loginname" placeholder="your_name@your_domain">
    </div>
  </div>
  <div class="form-group">
    <label for="password" class="col-sm-2 control-label">密码</label>
    <div class="col-sm-10">
      <input type="text" name="password" class="form-control" id="password" placeholder="password">
    </div>
  </div>
  <div class="form-group">
    <label for="userfile" class="col-sm-2 control-label">选择文件</label>
    <div class="col-sm-10">
      <input type="file" name="userfile" class="btn btn-default" id="upload_file" placeholder="upload_file" />
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default" id="submit">开始上传</button>
    </div>
  </div>
</form>
</section>

<section id="response">
<div id="response_content"></div>
</section>
</div>
</div>
</div>
</body>

<script type="text/javascript">
$(function() {
	var options = {
		type        : 'post',
		url         : 'http://testcloud.quanshi.com/ucadmin/interface/xmlimport/upload',
		beforeSubmit: showWait,
		success     : showResult,
		resetForm   : false,
		dataType    : 'json'
	};

// 	$('#upload').ajaxSubmit(options);

	$('#upload').submit(function(e) {
		e.preventDefault();
		$(this).ajaxSubmit(options);
	});
});

function showResult(responseText, statusText) {
// 	$('#response_content').html(JSON.stringify(responseText));
	if (responseText.code.result == true) {
		$('#response_content').html('<blockquote><strong>上传成功。</strong></blockquote>');
	} else {
		$('#response_content').html('<blockquote><strong>' + responseText.code.result + '</strong></blockquote>');
	}
}

function showWait() {
	$('#response_content').html('<blockquote><strong>正在上传中，请稍稍的等待......</strong></blockquote>');
}
</script>
</html>
