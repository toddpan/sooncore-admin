<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>云企管理中心</title>
</head>
<body>
	<!--安全管理_日志管理.html-->
	<div class="contHead">
	  <span class="title01">安全管理</span>
	  <ul class="nav02">
	  	<?php if($this->p_role_id == SYSTEM_MANAGER){?>
		<li class="first"><a onclick="loadCont('password/PWDManagePage');">密码管理</a></li>
		<?php }?>
		<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER){?>
<!-- 		<li><a onclick="loadCont('sensitiveword/sensitiveWordPage/1');">敏感词管理</a></li> -->
		<?php }?>
		<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER){?>
		<li class="selected"><a >日志管理</a></li>
		<?php }?>
		<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER){?>
<!--  		<li class="last"><a onclick="loadCont('useraction/userActionPage');">用户活动查询</a></li> -->
		<?php }?>
	  </ul>
	</div>
	<div class="contTitle02">
	  <span class="text01 fl">日期筛选： 从</span>
	  <div class="inputBox dp fl" style="margin: 0 5px;">
		<a class="icon" ></a>
		<label class="label"></label>
		<input id="startTime" class="input" readonly="readonly" style="width: 100px;" />
	  </div>
	  <span class="text01 fl">到</span>
	  <div class="inputBox dp fl" style="margin: 0 5px;"> <span class="icon"></span>
		<label class="label"></label>
		<input id="endTime" class="input" readonly="readonly" style="width: 100px;" />
	  </div>
	  <div class="inputBox fl" style="margin: 0 5px;">
		<span class="icon"></span>
		<label class="label">输入姓名或账号查询</label>
		<input class="input" style="width: 200px;" id = 'keyword'/>
	  </div>
	  <a  class="btn yes okbtn" style="display: none" onclick="$(this).next().show();">
		<span class="text" style="margin-right:0">确定</span>
		<b class="bgR"></b>
	  </a>
	  <a  class="btn yes fr" style="display: none">
		<span class="text exitdangan" style="margin-right: 0;">导出档案</span>
		<b class="bgR"></b>
	  </a>
	</div>
    <!--日志列表-->
	<div id="day_agerment">
	</div>
	<!-- end table -->
</body>
<script type="text/javascript">
	$(function(){
		var dates = $('#startTime, #endTime').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: false,
			changeYear:false,
			numberOfMonths:1,
			maxDate:'+0d',
			onSelect: function(selectedDate) {//对于INPUT类，自动配置，选择后的回调函数
				var option = this.id == "startTime" ? "minDate" : "maxDate";
				var instance = $(this).data("datepicker");
				var date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
				dates.not(this).datepicker("option", option, date);
				if(dates.not(this).val()!=""){
					$("#endTime").parent().next().next(".btn").show();
				}
			}
		});
		$('#startTime').click(function()
		{
			$('#ui-datepicker-div').css('top','243px');
		})
		$('#endTime').click(function()
		{
			$('#ui-datepicker-div').css('top','243px');
		})
		//点击事件
		$('.okbtn').click(function()
		{ 
		  var startTime = $('#startTime').val();
		  var endTime = $('#endTime').val();
		  var keyword = $('#keyword').val();
//	 	  alert(startTime);
//	 	  alert(endTime);
//	 	  alert(keyword);
        // $('.table').move();
		  $('#day_agerment').next().remove()
		  var path = 'log/loglist';
		  var obj={
				//"type":1,//类型
				'bdate':startTime,//开始时间
				'edate':endTime,//结束时间
				'keyword':keyword//关键词
			}		
			$.post(path,obj,function(data){
				// alert(data);
				$('#day_agerment').nextAll("table").remove();
				$('#day_agerment').after(data);
				
			});
		})
		//导出档案点击事件
		$('.exitdangan').click(function()
		{ 
		  var startTime = $('#startTime').val();
		  var endTime = $('#endTime').val();
		  var keyword = $('#keyword').val();
		  var path = 'log/down_log/' + startTime + '/'  + endTime + '/' + keyword ;
		  window.location = path;
		 // alert(path);
//			var obj={
//				"type":2,//类型
//				'bdate':startTime,//开始时间
//				'edate':endTime,//结束时间
//				'keyword':keyword//关键词
//			}		
//			$.get(path,obj,function(data){
//			    alert(data);
//			});
		})

	});
</script>
</html>
