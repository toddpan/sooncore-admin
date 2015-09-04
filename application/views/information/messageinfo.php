<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>无标题文档</title>
</head>
<?php 
$title = arr_unbound_value($msg_arr,'title',2,'');
$send_name = arr_unbound_value($msg_arr,'send_name',2,'');
$content = arr_unbound_value($msg_arr,'content',2,'');
$addtime = arr_unbound_value($msg_arr,'addtime',2,'');
$url_content = arr_unbound_value($msg_arr,'url_content',2,'');
?>
<body>
	<h2><a  class="back"><?php echo $title;?></a></h2>
	<div class="msg-content">
		<div class="send-info">
		发送人：<?php echo $send_name;?> <br />
		时间：<?php echo $addtime;?>
		</div>
		<div class="msg-main">
		   
			<!--<p>尊敬的客户，您好</p>-->
			<p><!--  您使用的版本马上就要到期，请点击以下链接进行更新<br />-->
			<a onclick="<?php echo site_url('bulkimport/downloadFailFile').'/'.$url_content; ?>"><?php echo $content;?></a></p>
		</div>
	</div>
</body>
</html>
