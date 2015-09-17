<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sooncore平台管理中心</title>
</head>
<body>
<!--系统设置_站点应用设置.html-->
<div class="contHead">
	<span class="title01">系统设置</span>
	<ul class="nav02">
		<li><a onclick="loadCont('setsystem/setSystemPage');">企业信息设置</a></li>
		<li class="last selected"><a >站点应用设置</a></li>
	</ul>
</div>

<div class="groupLimit2">
    <div class="toolBar2" style="position: absolute; top: -15px; right: 10px; display: none">
        <a class="btnBlue yes"  onclick="saveSuccess()"><span class="text">保存</span><b class="bgR"></b></a>
        <a class="btnGray btn"  onclick="$('.toolBar2').hide();"><span class="text">取消</span><b class="bgR"></b></a>
    </div>
    <!-- end tabToolBar -->

            
  <!--       <div class="toolBar2" style="display: none; clear: both">
            <a class="btnBlue yes"  onclick="saveSuccess()"><span class="text">保存</span><b class="bgR"></b></a>
            <a class="btnGray btn"  onclick="$('.toolBar2').hide();"><span class="text">取消</span><b class="bgR"></b></a>
         </div> -->
</div>

<script type="text/javascript">

	function saveSuccess() {
		$(".rightCont").append('<div class="successMsg">保存成功</div>');
		setTimeout(function(){
			$(".successMsg").remove();
			$(".toolBar2").hide();
		},2000)
	}
	$(function(){
		//checkbox();
		$(".checkbox").click(function(){
			$(".toolBar2").show();
		})
	});
</script>
</body>
</html>