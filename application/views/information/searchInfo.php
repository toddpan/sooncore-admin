<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>云企管理中心</title>
</head>
<body>
<!--搜索_消息管理.html-->
<div class="contHead">
	<span class="title01 rightLine">消息管理</span><span class="title03">搜索结果</span> 
    <div class="contHead-right">
	
	<div class="headSearch">
		<div class="combo searchBox">
			<b class="bgR"></b>
			<a class="icon" ></a>
			<label class="label">请输入查询条件</label>
			<input class="input" />
		</div>
	</div>
    
    </div>
</div>
<!-- end contHead -->
<a  class="back" onclick="loadCont('information/infoManPage')">返回</a>
<div class="cont-wrapper">
	<div class="msg-list"  style="display: block; min-height: 10px;">
    <div class="msg-bar">
      <div class="msg-bar-left">为您找到<span class="red">3</span>条相关消息</div>
    </div>
    <ul class="msg-li">
      <li class="new">王笑已被总管理员指派为员工管理员 <span class="time">17:35</span> </li>
      <li>陈志朋已被总管理员移除员工管理员角色 <span class="time">17:35</span> </li>
      <li>吴丽丽的管理范围已经变更 <span class="time">17:35</span> </li>
    </ul>
    <!--<div class="page"> <a class="disabled" >首页</a> <a class="disabled" >上一页</a> <a class="num selected" >1</a> <a class="num " >2</a> <a class="num " >3</a> <a class="" >下一页</a> <a class="" >尾页</a> <span class="text ml10">第</span>
                <input class="page-input"  type="text" value="" size="2" />
                <span class="text">页/3</span> </div>-->
  </div>
  
  
    <div class="msg-list"  style="display: block; min-height: 10px;">
    <div class="msg-bar">
      <div class="msg-bar-left">为您找到<span class="red">1</span>条相关任务</div>
      
    </div>
    <ul class="msg-li">
     
      <li><a  onclick="return false;" style="cursor:default">删除员工</a> <br />
        李想申请删除员工张效瑞 <span class="time">昨天 17:35</span>
        <div class="li-ml">已处理</div>
      </li>
    </ul>
    <!--<div class="page"> <a class="disabled" >首页</a> <a class="disabled" >上一页</a> <a class="num selected" >1</a> <a class="num " >2</a> <a class="num " >3</a> <a class="" >下一页</a> <a class="" >尾页</a> <span class="text ml10">第</span>
                <input class="page-input"  type="text" value="" size="2" />
                <span class="text">页/3</span> </div>-->
  </div>
  
  <div class="msg-list" style="display: block; min-height: 10px">
    <div class="msg-bar">
      <div class="msg-bar-left">为您找到<span class="red">1</span>条相关通知</div>
      
    </div>
    <ul class="msg-li">
      
      <li><a  onclick="showDetailPage('msg1.html',this)">欢迎使用全时云企</a> <span class="time">17:35</span> </li>
    </ul>
    <!--<div class="page"> <a class="disabled" >首页</a> <a class="disabled" >上一页</a> <a class="num selected" >1</a> <a class="num " >2</a> <a class="num " >3</a> <a class="" >下一页</a> <a class="" >尾页</a> <span class="text ml10">第</span>
                <input class="page-input"  type="text" value="" size="2" />
                <span class="text">页/3</span> </div>-->
  </div>
    <!-- end conTabs -->
        
            
            
  
        
        
    
    <!-- end contRight -->
</div>



<script type="text/javascript">
	
	$(function(){
		//checkbox();
		
		//组织结构 表格全选
		//checkall('.table thead .checkbox', '.table tbody .checkbox', '.table .checkbox', toolBarSet);

		//组织结构 表格操作条显隐及操作按钮显隐
		function toolBarSet(){
			var checked = $('.table .checkbox').filter(function(){return $(this).hasClass('checked');});
			if(checked.length){
				$('.tabToolBox').show();
			}else{
				$('.tabToolBox').hide();
			}
			
		}
		
		
		//删除员工
		$('.btnDeleUser').click(function(){
			
			var _checked = $('.table tbody .checked');
			var _name = _checked.parent().next().find('.userName');
			
			
			showDialog('弹窗_提醒_删除员工.html');
			
			
			
		});
		
		
		
		$(".checkbox").click(function(){
			$(".toolBar2").show();
		})
		
		
	});
</script>
</body>
</html>