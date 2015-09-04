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
<!--  		<li><a onclick="loadCont('sensitiveword/sensitiveWordPage/1');">敏感词管理</a></li>-->
		<?php }?>
		<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER){?>
		<li><a onclick="loadCont('log/logPage');">日志管理</a></li>
		<?php }?>
		<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER){?>
<!-- 		<li class="last selected"><a >用户活动查询</a></li> -->
		<?php }?>
	</ul>
</div>
<div class="contTitle02">
	<span class="text01 fl">日期筛选： 从</span>
	<div class="inputBox dp fl"  style="margin: 0 5px;">
		<a class="icon" ></a>
		<label class="label"></label>
		<input id="startTime" class="input" readonly="readonly" style="width: 100px;" />
	</div>
	<span class="text01 fl">到</span>
	<div class="inputBox dp fl"  style="margin: 0 5px;">
		<span class="icon"></span>
		<label class="label"></label>
		<input id="endTime" class="input" readonly="readonly" style="width: 100px;" />
	</div>
    <div class="inputBox fl" style="margin: 0 5px;">
		<span class="icon"></span>
		<label class="label">输入姓名、账号、手机号查询</label>
		<input class="input" style="width: 200px;" />
	</div>
    <a  class="btn yes" style="display: none" onclick="$('.cont-wrapper').show();"><span class="text" style="margin-right:0">确定</span><b class="bgR"></b></a>
    
    
</div>
<div class="cont-wrapper" style="display: none">
    <ul class="infoNav">
        <li class="first selected">聊天记录</li>
        <li>通话记录</li>
        <li>讨论组记录</li>
        <li>日程记录</li> 
    </ul>
    <div  class="report-content">
    	<div class="dataTable" style="margin-bottom: 0; margin-top: 20px;">
            <table class="table">
            <thead>
                <tr>
                    <th class="bgLine">聊天对象</th>
                    <th class="bgLine">数量</th>
                    <th class="bgLine">最后聊天时间</th>
                    <th class="bgLine">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>王明</td>
                    <td>234条</td>
                    <td>2012-2-23  上午11:12:36</td>
                    <td><a  onclick="showDialog('<?php echo site_url('chat/showChatPage');?>')">查看详情</a></td>
                </tr>
                <tr>
                    <td>李鹏</td>
                    <td>21条</td>
                    <td>2012-2-23  上午10:13:27</td>
                    <td><a  onclick="showDialog('<?php echo site_url('chat/showChatPage');?>')">查看详情</a></td>
                </tr>
               <tr>
                    <td>何黎明</td>
                    <td>1234条</td>
                    <td>2012-2-11  下午14:25:45</td>
                    <td><a  onclick="showDialog('<?php echo site_url('chat/showChatPage');?>')">查看详情</a></td>
                </tr>
                <tr>
                    <td>王兆民</td>
                    <td>204条</td>
                    <td>2012-2-10  上午11:12:36</td>
                    <td><a  onclick="showDialog('<?php echo site_url('chat/showChatPage');?>')">查看详情</a></td>
                </tr>
            </tbody>
        </table>
        	<div class="page">
                <a class="disabled" >首页</a>
                <a class="disabled" >上一页</a>
                <a class="num selected" >1</a>
                <a class="num " >2</a>
                <a class="num " >3</a>
                <a class="" >下一页</a>
                <a class="" >尾页</a>
                <span class="text ml10">第</span>
                <div class="inputBox">
                    <b class="bgR"></b>
                    <label class="label"></label>
                    <input class="input" value="">
                </div>
                <span class="text">页/3</span>
            </div> 
        </div>
    </div>
    <div  class="report-content" style="display: none">
    	<div class="dataTable" style="margin-bottom: 0; margin-top: 20px;">
            <table class="table">
            <thead>
                <tr>
                    <th class="bgLine">通话对象</th>
                    <th class="bgLine">通话时长</th>
                    <th class="bgLine">通话时间</th>
                   
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>王明</td>
                    <td>00:02:09</td>
                    <td>2012-2-23  上午11:12:36</td>
                   
                </tr>
                <tr>
                    <td>李鹏</td>
                    <td>00:02:09</td>
                    <td>2012-2-23  上午10:13:27</td>
                   
                </tr>
               <tr>
                    <td>何黎明</td>
                    <td>00:02:09</td>
                    <td>2012-2-11  下午14:25:45</td>
                  
                </tr>
                <tr>
                    <td>王兆民</td>
                    <td>00:02:09</td>
                    <td>2012-2-10  上午11:12:36</td>
                    
                </tr>
            </tbody>
        </table>
        	<div class="page">
                <a class="disabled" >首页</a>
                <a class="disabled" >上一页</a>
                <a class="num selected" >1</a>
                <a class="num " >2</a>
                <a class="num " >3</a>
                <a class="" >下一页</a>
                <a class="" >尾页</a>
                <span class="text ml10">第</span>
                <div class="inputBox">
                    <b class="bgR"></b>
                    <label class="label"></label>
                    <input class="input" value="">
                </div>
                <span class="text">页/3</span>
            </div> 
        </div>
    </div>
    <div  class="report-content" style="display: none">
    	<div class="dataTable" style="margin-bottom: 0; margin-top: 20px;">
            <table class="table">
            <thead>
                <tr>
                    <th class="bgLine">讨论组名称</th>
                    <th class="bgLine">加入时间</th>
                    <th class="bgLine">退出时间</th>
                    <th class="bgLine">最后一次消息发送时间</th>
                    <th class="bgLine">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>UC讨论组</td>
                    <td>2012-2-23  上午10:42:00</td>
                    <td>2012-2-23  上午11:12:36</td>
                    <td>2012-2-23  上午11:09:12</td>
                    <td><a  onclick="showDialog('<?php echo site_url('chat/showChatPage');?>')">查看详情</a></td>
                </tr>
                <tr>
                    <td>UC视觉讨论组</td>
                    <td>2012-2-23  上午10:42:00</td>
                    <td>2012-2-23  上午11:12:36</td>
                    <td>2012-2-23  上午11:09:12</td>
                    <td><a  onclick="showDialog('<?php echo site_url('chat/showChatPage');?>')">查看详情</a></td>
                </tr>
               <tr>
                    <td>UC前端讨论组</td>
                    <td>2012-2-23  上午10:42:00</td>
                    <td>2012-2-23  上午11:12:36</td>
                    <td>2012-2-23  上午11:09:12</td>
                    <td><a  onclick="showDialog('<?php echo site_url('chat/showChatPage');?>')">查看详情</a></td>
                </tr>
                <tr>
                    <td>UC后台讨论组</td>
                    <td>2012-2-23  上午10:42:00</td>
                    <td>2012-2-23  上午11:12:36</td>
                    <td>2012-2-23  上午11:09:12</td>
                    <td><a  onclick="showDialog('弹窗_聊天记录.html')">查看详情</a></td>
                </tr>
            </tbody>
        </table>
        	<div class="page">
                <a class="disabled" >首页</a>
                <a class="disabled" >上一页</a>
                <a class="num selected" >1</a>
                <a class="num " >2</a>
                <a class="num " >3</a>
                <a class="" >下一页</a>
                <a class="" >尾页</a>
                <span class="text ml10">第</span>
                <div class="inputBox">
                    <b class="bgR"></b>
                    <label class="label"></label>
                    <input class="input" value="">
                </div>
                <span class="text">页/3</span>
            </div> 
        </div>
    </div>
    <div  class="report-content" style="display: none">
    	<div class="dataTable" style="margin-bottom: 0; margin-top: 20px;">
            <table class="table">
            <thead>
                <tr>
                    <th class="bgLine">会议主题</th>
                    <th class="bgLine">召开时间</th>
                    <th class="bgLine">安排时间</th>
                    <th class="bgLine">参会人</th>
                    <th class="bgLine">状态</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>UC交互讨论</td>
                    <td>2012-2-23  上午10:42:00</td>
                    <td>2012-2-23  上午11:12:36</td>
                    <td>王明，李超</td>
                    <td>未召开</td>
                </tr>
                <tr>
                    <td>UC视觉讨论</td>
                    <td>2012-2-23  上午10:42:00</td>
                    <td>2012-2-23  上午11:12:36</td>
                    <td>李丹，大为，蒋小涵</td>
                    <td>正在进行</td>
                </tr>
               <tr class="end">
                    <td>UC交互讨论二</td>
                    <td>2012-2-23  上午10:42:00</td>
                    <td>2012-2-23  上午11:12:36</td>
                    <td>殷朝，袁强，韩健康</td>
                    <td>已结束</td>
                </tr>
                <tr class="end">
                    <td>UC后台讨论</td>
                    <td>2012-2-23  上午10:42:00</td>
                    <td>2012-2-23  上午11:12:36</td>
                    <td>郭军，袁强，韩健康</td>
                    <td>已结束</td>
                </tr>
            </tbody>
        </table>
        	<div class="page">
                <a class="disabled" >首页</a>
                <a class="disabled" >上一页</a>
                <a class="num selected" >1</a>
                <a class="num " >2</a>
                <a class="num " >3</a>
                <a class="" >下一页</a>
                <a class="" >尾页</a>
                <span class="text ml10">第</span>
                <div class="inputBox">
                    <b class="bgR"></b>
                    <label class="label"></label>
                    <input class="input" value="">
                </div>
                <span class="text">页/3</span>
            </div> 
        </div>
    </div>
</div>
<!-- end table -->
<script type="text/javascript">
	$(function(){
		var dates = $('#startTime, #endTime').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: false,
			numberOfMonths: 1,
			maxDate:'+0d',
			onSelect: function(selectedDate) {
				var option = this.id == "startTime" ? "minDate" : "maxDate";
				var instance = $(this).data("datepicker");
				var date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
				dates.not(this).datepicker("option", option, date);
				if(dates.not(this).val()!=""){
					$("#endTime").parent().next().next(".btn").show();
				}
			}
		});
		$(".infoNav li").click(function(){
			var index = $(this).index();
			$(this).addClass("selected").siblings().removeClass("selected");
			$(".report-content").eq(index).show().siblings(".report-content").hide();	
		})
	});
</script>
</body>
</html>