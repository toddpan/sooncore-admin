<div class="contHead">
	<span class="title01">安全管理</span>
	<ul class="nav02">
		<?php if($this->functions['PasswordManage']){?>
			<li class="first"><a onclick="loadCont('password/PWDManagePage');">密码管理</a></li>
		<?php }?>
		<!-- <li><a>敏感词管理</a></li> -->
		<?php if($this->functions['LogManage']){?>
			<li class="log" onclick="loadCont('log/logPage');"><a >日志管理</a></li>
		<?php }?>
		<?php if($this->functions['UserActionManage']){?>
			<!--<li class="last"><a onclick="loadCont('useraction/userActionPage');">用户活动查询</a></li> -->
		<?php }?>
	</ul>
</div>
<div class="con-wrapper" style="display: none">
	<p style="font-size: 13px; color: #565656; margin-bottom: 20px;">请设置您企业的敏感词，每天系统会将涉及到敏感词的内容发送您的消息中。</p>
    <p><a class="btn yes"  onclick="showDialog('sensitiveword/addSensitiveWordPage')"><span class="text">新建敏感词</span><b class="bgR"></b></a></p>
</div>
<div class="con-wrapper">
            <div class="toolBar3">
            	
              <a  id="hm_manage" class="fr btn yes"><span class="text">豁免管理</span> <b class="bgR"></b></a>
              <a class="btn yes fr new_sesitive"  style="margin-right:5px"><span class="text">新建敏感词</span><b class="bgR"></b></a>
              <div class="combo searchBox fr"  style="margin-right:5px">
                    <b class="bgR"></b>
                    <a class="icon"></a>
                    <label class="label">输入敏感词搜索</label>
                    <input class="input" />
                </div>
              
            </div>
    
            <table class="table">
                <thead>
                    <tr>
                        <th>序号</th>
                        <th>敏感词</th>
                        <th>添加时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
<?php
//$i = 0;
foreach ($sensitiveArr as $v1){
	//$i++;
                 ?>
                    <tr>
                      <td><?php echo $v1['id']; ?></td>
                      <td><?php echo $v1['Word']; ?></td>
                      <td><?php echo $v1['time']; ?></td>
                      <td><?php if($v1['type'] == 2){ ?><a class="dele_sesitive">删除</a><?php } ?></td>
                    </tr>
                    <?php
                }
                ?>
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
            <!-- end table -->
        
</div>
<div class="con-wrapper"  style="display: none">
  <h2><a class="back"  onclick="$('.con-wrapper').eq(1).show().siblings('.con-wrapper').hide();">豁免设置</a></h2>
  <p style="font-size: 13px; color: #565656; margin: 20px 0 0;" id="hmMsg">设置不受敏感词限制的员工，保护必要的隐私。</p>
  <p style="margin: 20px 0;"><a class="btn yes"  onclick="addHmyg()"><span class="text">添加豁免员工</span><b class="bgR"></b></a></p>
    <div id="hm_staff">
	</div>
</div>
<script type="text/javascript" src="public/js/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="public/js/self_tree.js"></script>
<script type="text/javascript">
function addHmyg() {
		//showDialog("<?php //echo site_url('exemptuser/exemptUserPage');?>");
		showDialog('ecologycompany/ecologyManagerPage' + '/4');
	$('#dialog .btn_confirm').die('click');
	$('#dialog .btn_confirm').live('click',function()
	{
		var a=$('.treeRight a');
		if(a.length==0)
		{
			alert("请指定要调入的员工")
			return false;
		}
		var users='';
		$('.treeRight a').each(function()
		{
			users=users+'{"userid":'+$(this).attr("id")+',"user_name":"'+$(this).text()+'","orgid":'+$(this).attr("class")+',"org_name":"'+$(this).attr("name")+'","org_pid":"'+$(this).attr("orgpid")+'","org_code":"'+$(this).attr("orgcode")+'"},';//加user_name
		});
		users=DelLastComma(users);
			//alert(Node[0].id)
			var obj={
				//"org_pid":Node[0].pId,//新建组织的父id
				//"org_id":Node[0].id,//新建组织id
				//"org_code":org_code,//新组的id串
				//"org_name":Node[0].name,//新建组织名称
				"user_id":users
			};
			//alert(Node[0].id)
			//alert(users)
			//alert(1)
			var path_fold_staff = "exemptuser/saveExemptUser'); ";
			$.post(path_fold_staff,obj,function(data)
			{
				//alert(111)
				//alert(data)
			   //alert(data)
				var json=$.parseJSON(data);
				if(json.code==0)
				{	
					var path="exemptuser/exemptUserPage";
					$.post(path,[],function(data)
					{
						//alert(data)
						$('#hmMsg').hide();
						$('#hmMsg').next().css("text-align","right");
						$('#hm_staff').find("table").remove();
						$('#hm_staff').append(data);
					})
					//alert(data)
					//var a=$('.treeRight a').text();
					//alert(a)
					//$('span.dotEdit').text(a);
					/*var objN={
							"parent_orgid":Node[0].pId,
							 "org_id":Node[0].id
					 }
					
					//load_staff(objN,path_user,path_mag);*/
					hideDialog();
					
				}
				else
				{
					alert(json.prompt_text)	
				}
			})
		})
	}
	
	$(function(){
	
	//点击豁免管理
	$('#hm_manage').click(function()
	{
		//alert(1111)
		var path="exemptuser/exemptUserPage";
		$.post(path,[],function(data)
		{
			//alert(data)
			if(data)
			{
				$('.con-wrapper').eq(2).show().siblings('.con-wrapper').hide();
				$('#hmMsg').hide();
				$('#hmMsg').next().css("text-align","right");
				$('#hm_staff').find("table").remove();
				$('#hm_staff').append(data);
			}
			else
			{
				$('.con-wrapper').eq(2).show().siblings('.con-wrapper').hide();
			}
		})
	})
	//点击搜索敏感词
	$('.con-wrapper .toolBar3 .searchBox .icon').click(function()
	{
		var text=$(this).next().next().val();
		var reg=/\s/g;
		text=text.replace(reg,'');		
		if(text=="")
		{
			alert("请输入需要查询的信息");
			return false;
	    }
		else
		{
			var path='sensitiveword/searchSensitiveWord';
			var obj={
				"word":text
			};
			$.post(path,obj,function(data)
			{
				//var json=$.parseJSON(data);
					//alert(data)
					$('.con-wrapper .table').remove();
					//alert(3333)
					//$('.con-wrapper .page').remove();
					$('.con-wrapper .toolBar3').after(data);
			
			})
		}
	})
	//点击删除
		$('.con-wrapper .dele_sesitive').click(function()
		{
			var id=$(this).parent().parent().find("td:eq(0)").text();
			//alert(id)
			var _this=$(this).parent().parent();
			showDialog('sensitiveword/showDelSensitiveWordPage' + '/' + id);
			$('#dialog #del_sensitive').die("click");
			$('#dialog #del_sensitive').live("click",function()
			{
				//alert(121)
				var path="sensitiveword/delSensitiveWord"+ '/' + id;
				var obj={
					"SensitiveId":id
				        };
				$.post(path,obj,function(data)
				{
					if(data.code==0)
					{
						_this.remove();
						hideDialog();
					}
					else
					{
						alert(data.prompt_text)	
					}
					
					//alert(data);
					
				},'json')
			})
		})
		//点击新建敏感词
		$('.con-wrapper .new_sesitive').click(function()
		{
			showDialog('sensitiveword/addSensitiveWordPage');
			$("#dialog .btn_confirm").die("click");
			$("#dialog .btn_confirm").live("click",function(){
				var text=$('textarea').val();
				//alert(text)
				var path="sensitiveword/saveSensitiveWord";
				var obj={
						"word":text
						};	
				 //alert(text);	
				$.post(path,obj,function(data)
				{
				 // alert(data)
				  var json = $.parseJSON(data);
				   if(json.code==0)
				   {
					 
					  var _this = $('.con-wrapper').eq(1);
					  _this.show().siblings('.con-wrapper').hide();
					  var path="sensitiveword/sensitiveWordPage+'/2'";
					  $.post(path,[],function(data)
						{
					 		$('.con-wrapper .table').remove();
							$('.con-wrapper .toolBar3').after(data);
						 }) 
					hideDialog();
				   }
				  else
					{
						alert(json.prompt_text);
						return false;	
					}
				});
			});	
		})
		$('.infoNav li').click(function(){
			var ind = $(this).index();
			$(this).addClass('selected').siblings().removeClass('selected');
			$('.infoCont02 > dd').eq(ind).show().siblings().hide();
		});
		
	});
</script>
