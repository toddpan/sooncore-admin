<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>xml导入测试</title>
</head>
<body>
<form action="xmlResult" method="post">
	地址：<input type="url" name="url" /><br /><br />
	站点ID：<input type="number" name="site_id" /><br /><br />
<!-- 	组织ID：<input type="number" name="org_id" /><br /><br />
	客户编码：<input type="text" name="customer_code" /><br /><br /> -->
	
<!-- 	类型：<select name="type"> -->
	<?php 
// 	foreach ($types as $type) {
// 		echo '<option value ="' . $type . '">' . $type . '</option>';
// 	}
	?>
<!-- 	</select> -->
	
	格式：<select name="format">
	<?php 
	
	foreach ($formats as $format) {
		echo '<option value ="' . $format . '">' . $format . '</option>';
	}
	?>
	</select>
	<input type="submit" value="submit" />
</form>
</body>
</html>

