	<!--安全管理_日志管理.html-->
	<div class="contHead">
		<span class="title01">安全管理</span>
  		<ul class="nav02">
  			<?php if($this->functions['PasswordManage']){?>
				<li class="first"><a onclick="loadCont('password/PWDManagePage');">密码管理</a></li>
			<?php }?>
			<?php if($this->functions['SensitiveWord']){?>
				<!-- <li><a onclick="loadCont('sensitiveword/sensitiveWordPage/1');">敏感词管理</a></li> -->
			<?php }?>
			<li class="log selected"><a >日志管理</a></li>
			<?php if($this->functions['UserActionManage']){?>
				<!--<li class="last"><a onclick="loadCont('useraction/userActionPage');">用户活动查询</a></li> -->
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
	  	<div class="inputBox dp fl" style="margin: 0 5px;">
	  		<span class="icon"></span>
			<label class="label"></label>
			<input id="endTime" class="input" readonly="readonly" style="width: 100px;" />
	  	</div>
	  	<div class="inputBox fl" style="margin: 0 5px;">
			<span class="icon"></span>
			<label class="label">输入姓名或账号查询</label>
			<input class="input" style="width: 200px;" id = 'keyword'/>
	  	</div>
	  	<a class="btn yes okbtn" style="display: none" onclick="$(this).next().show();" id="log_confirm">
			<span class="text" style="margin-right:0">确定</span>
			<b class="bgR"></b>
	 	</a>
	  	<a  class="btn yes fr" style="display: none">
			<span class="text exitdangan" style="margin-right: 0;">导出档案</span>
			<b class="bgR"></b>
	  	</a>
	</div>
    <!--日志列表-->
	<div id="day_agerment"></div>
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

	// 开始日期点击事件
	$('#startTime').click(function(){
		$('#ui-datepicker-div').css('top','243px');
	});

	// 结束日期点击事件
	$('#endTime').click(function(){
		$('#ui-datepicker-div').css('top','243px');
	});
		
	// 点击搜索
	$('.okbtn').click(function(){
		$('#day_agerment').next().remove();
		
	  	var startTime 	= $('#startTime').val();
	  	var endTime 	= $('#endTime').val();
	  	var keyword 	= $('#keyword').val();
	  	var path 		= 'log/loglist';
	  	var obj 		= {
				'bdate':startTime,	// 开始时间
				'edate':endTime,	// 结束时间
				'keyword':keyword	// 关键词
		};
				
		$.post(path, obj, function(data){
			$('#day_agerment').nextAll("table").remove();
			$('#day_agerment').after(data);
		});
	});
		
	// 回车搜索
	$('#keyword').keydown(function(e){

		// 如果是回车键
		if(e.keyCode == 13){
			
			// 判断是否提交数据
			if($('#log_confirm').is(":hidden")){
				return false;
			}
			
			$('#day_agerment').next().remove();
			
			var startTime 	= $('#startTime').val();
		  	var endTime 	= $('#endTime').val();
		  	var keyword 	= $('#keyword').val();
		  	var path 		= 'log/loglist';
		 	var obj 		= {
					'bdate':startTime,	// 开始时间
					'edate':endTime,	// 结束时间
					'keyword':keyword	// 关键词
				};
					
			$.post(path, obj, function(data){
				$('#day_agerment').nextAll("table").remove();
				$('#day_agerment').after(data);
			});
	 }
	});		
		
	//导出档案点击事件
	$('.exitdangan').click(function(){ 
		var startTime 	= $('#startTime').val();
	  	var endTime 	= $('#endTime').val();
	  	var keyword 	= $('#keyword').val();
	  	window.location.href = '<?php echo site_url("log/down_log");?>' + '/'+ startTime + '/'  + endTime + '/' + keyword ;
	});
});
</script>
