<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>蜜蜂管理中心</title>
</head>
<body>
<!--组织与员工-管理员-ldap.html-->
<div class="contHead">
	<span class="title01 rightLine">组织管理</span><span class="title03">管理员</span>
    <div class="contHead-right">
        <div class="fr rightLine"><a class="btnSet" onclick="toggleMenu('menu1',event)"  ></a></div>
       
        <div class="headSearch rightLine">
            <div class="combo searchBox">
                <b class="bgR"></b>
                <a class="icon" ></a>
                <label class="label">请输入查询条件</label>
                <input class="input" />
            </div>
        </div>
        
        <ul class="menu" id="menu1">
            <li><a  onclick="loadCont('组织与员工_批量导入.html')">员工标签管理</a></li>
            <!-- <li><a  onclick="loadCont('组织与帐号_LDAP同步1.html')">LDAP设置</a></li> -->
        </ul>
    </div>
</div>
<!-- end contHead -->
<div class="contMiddle">
    <!-- end conTabs -->
    
    <div class="contRight" style="margin-left: 0">
      
           
            <!-- end bread -->
          <div class="tabToolBar">
            	<a class="back fl"  onclick="location.href='ldap-layout.html#group'; document.location.reload()" title="返回">&nbsp;</a>
                <a class="btnBlue fr" href="javascript:showDialog('弹窗_添加管理员.html');"><span class="text">新增管理员</span><b class="bgR"></b></a>
                <div class="tabToolBox" style="display:none;">
                  <a class="btnGray btn btnMoveManage"  onclick="showDialog('弹窗_删除管理员权限.html')"><span class="text">删除管理员</span><b class="bgR"></b></a>
                </div>
            </div>
            <!-- end tabToolBar -->
            <table class="table">
                <thead>
                    <tr>
                        <th width="6%"><label class="checkbox" style="display: none"><input type="checkbox" /></label></th>
                        <th width="14%" style="text-align:left; text-indent: 24px;">姓名</th>
                        <th>权限</th>
                        <th>蜜蜂帐号</th>
                        <th width="16%">手机</th>
                        <th width="18%">上次登录</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>&nbsp;</td>
                        <td align="center" class="tl"><a class="userName manage ellipsis" href="javascript:loadCont('组织与员工_员工信息权限.html');">李想</a></td>
                        <td align="center">组织管理...</td>
                        <td align="center"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>2013/2/25&nbsp;13:55</td>
                    </tr>
                    
                </tbody>
            </table>
            <!-- end table -->
            <div class="page" style="display: none">
                <a class="disabled" >首页</a>
                <a class="disabled" >上一页</a>
                <a class="num selected" >1</a>
                <a class="num " >2</a>
                <a class="num " >3</a>
                <a class="" >下一页</a>
                <a class="" >尾页</a>
                <span class="text ml10">第</span>
                <input class="page-input"  type="text" value="" size="2" />
                <span class="text">页/3</span>
          </div>
    
        
    </div>
    <!-- end contRight -->
</div>



<script type="text/javascript">
	$(function(){
		
		//checkbox();
		
		$(".table thead input[type='checkbox']").click(function(){
			if($(this).is(":checked")){
				$(".table tbody input[type='checkbox']").attr("checked","checked");
				$(".table tbody .checkbox").addClass("checked");
				var len = $(".table tbody .checked").length;
				if(len>0){
					$(".tabToolBar .tabToolBox").show();
				}
				else {
					$(".tabToolBar .tabToolBox").hide();
				}
			}
			else {
				$(".table tbody input[type='checkbox']").removeAttr("checked");
				$(".table tbody .checkbox").removeClass("checked");
				$(".tabToolBar .tabToolBox").hide();
			}
		})
		
		$(".table tbody input[type='checkbox']").live("click",function(){
			var len = $(".table tbody .checkbox").length;
	
			if($(this).is(":checked")){
				$(".tabToolBar .tabToolBox").show();
				var checkLen = $(".table tbody .checked").length;
				
				if(len == checkLen+1) {
					$(".table thead .checkbox").addClass("checked");
					$(".table thead input[type='checkbox']").attr("checked","checked");
				}
			}
			else {
				$(".table thead .checkbox").removeClass("checked");
				$(".table thead input[type='checkbox']").removeAttr("checked");
				var checkLen = $(".table tbody .checked").length;
				
				if(checkLen == 1) {
					$(".tabToolBar .tabToolBox").hide();
				}
			}
		})

		
		//批量导入提示气泡
		if(login){
			$('.poptip').hide();
		}else{
			$('.poptip').show();
		}
		$('.poptip .btn_iKnow').click(function(){
			$('.poptip').animate({'opacity':0},300,function(){
				$('.poptip').hide();
				$('.poptip2').show();
			});
		});
		$('.poptip2 .btn_iKnow').click(function(){
			$('.poptip2').animate({'opacity':0},300,function(){
				$('.poptip2').hide();
			});
			login = 1;
		});
		
	});
</script>
</body>
</html>