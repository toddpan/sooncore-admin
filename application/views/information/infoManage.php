<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>蜜蜂管理中心</title>
	</head>
	<body>
		<div class="contHead"> 
	  		<span class="title01">消息管理</span>
	 		<div class="contHead-right">
				<div class="headSearch">
		  			<div class="combo searchBox">
						<b class="bgR"></b>
						<a class="icon j-search" ></a>
						<label class="label">请输入查询条件</label>
						<input class="input" name="keyword"/>
		  			</div>
				</div>
	 		</div>
		</div>
		<div class="cont-wrapper">
	  		<ul class="infoNav" id="infor_list">
	  			<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER || $this->p_role_id == ACCOUNT_MANAGER){?>
				<li class="first selected" target="0">任务
					<span class="nums" style="display: none">
						<b><?php echo $task_sum;?></b>
					</span>
				</li>
				<?php }?>
				<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER || $this->p_role_id == ACCOUNT_MANAGER || $this->p_role_id == ECOLOGY_MANAGER){?>
				<li target="1">消息
					<span class="nums" style="display: none">
						<b><?php echo $notice_sum;?></b>
					</span>
				</li>
				<?php }?>
				<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER){?>
				<li target="2">通知
					<span class="nums" style="display: none">
						<b><?php echo $message_sum;?></b>
					</span>
				</li>
				<?php }?>
	  		</ul>
		</div>
	  	<div class="msg-list"  style="display: block">
			<div class="msg-bar">
				<div class="msg-bar-left" style="display:none">有
					<span class="red"><?php echo $task_sum;?></span>条未处理任务
				</div>
				<div class="msg-bar-right">
					<div class="select" onclick="toggleSelect(this,event)">
						<span>查看</span>
						<ul class="menu">
							<li>
								<a  onclick="all_show(this);">全部
								</a>
							</li>
							<li>
								<a  onclick="no_show(this)">未处理
								</a>
							</li>
							<li>
								<a  onclick="make_show(this);">已处理
								</a>
							</li>
						</ul>
					</div>
					<div class="fr rightLine">
						<a  onclick="refresh_page();" class="refresh">刷新
						</a>
					</div>
					<div class="fr rightLine">&nbsp;</div>
				</div>
			</div>
			<div class="infor_page"></div>
	  	</div>
		<script type="text/javascript" src="public/js/jquery.ztree.all-3.5.min.js"></script>
		<script type="text/javascript" src="public/js/self_tree.js"></script>
		<script type="text/javascript" src="public/js/jquery.ztree.exhide-3.5.js"></script>
		<script type="text/javascript">
		function all_show(t) // 全部消息
		{
			var index = $('#infor_list li.selected').attr("target"); 
			var path;

			if(index == 0)
			{
				path = "information/get_task"; // 任务
			}else if(index == 1){
				path = "information/get_notice";// 消息
			}else if(index == 2){
				path = "information/get_message";// 通知
			}
			
			var obj = 
				{
					"type":index
				};
			$.post(path,obj,function(data)
			{
				$('.infor_page').html('');
				$('.infor_page').html(data)
			})
		}
		function no_show(t) // 未读消息
		{
			var index = $('#infor_list li.selected').attr("target");
			var path;
			
			if(index == 0)
			{
				path = "information/get_task"; // 任务
			}else if(index == 1){
				path = "information/get_notice";// 消息
			}else if(index == 2){
				path = "information/get_message";// 通知
			}

			var obj = 
			{
				"type":index,
				"is_read":0 // 未读消息
			};
			$.post(path,obj,function(data)
			{
				$('.infor_page').html('');
				$('.infor_page').html(data)
			})
		}
		function make_show(t)
		{
			var index = $('#infor_list li.selected').attr("target");
			var path;

			if(index == 0)
			{
				path = "information/get_task"; // 任务
			}else if(index == 1){
				path = "information/get_notice";// 消息
			}else if(index == 2){
				path = "information/get_message";// 通知
			}

			var obj = 
			{
				"type":index,
				"is_read":1 // 已读消息
			};
			$.post(path,obj,function(data)
			{
				$('.infor_page').html('');
				$('.infor_page').html(data)
			})			
		}
		function refresh_page()
		{
			var index = $('#infor_list li.selected').attr("target");
			var path;
			
			if(index == 0)
			{
				path = "information/get_task"; // 任务
			}else if(index == 1){
				path = "information/get_notice";// 消息
			}else if(index == 2){
				path = "information/get_message";// 通知
			}

			var obj = 
			{
				"type":index
			};
			$.post(path,obj,function(data)
			{
				$('.infor_page').html('');
				$('.infor_page').html(data)
			})		
		}
		$('.headerLink li:eq(1)').addClass("bg");
		var val=<?php echo $task_sum;?>+<?php echo $message_sum;?>+<?php echo $notice_sum;?>;
		$('.header .email b').text(val);
		var sel = <?php echo $type ?>;
		
		if (sel == 1) {
			$('.msg-list .msg-bar-left').html('');
			$(".infoNav li").removeClass("selected");
			$('.infoNav li:eq(2)').addClass("selected");
			$('#infor_list li:eq(2) span').hide();
			var index = 1;
			var pageno = 1;
			var isread ='';
			var keyword ='';
			var path= "information/get_message";
			var obj = 
			{
				"type":index
			};
			$.post(path, obj,
			function(data) {
				//$('.msg-list:eq(' + index +')').show();
				$('.infor_page').html('');
				$('.infor_page').append(data);
				var text='有<span class="red"><?php echo $message_sum;?></span>条未读通知';
				if($('.infor_page ul.msg-li li').length==0)
				{
					$('.infor_page div.page').remove();
					text="还没有任何通知";
				}
				else if(<?php echo $message_sum;?> ==0)
				{
					text="目前没有需要查看的通知";
				}
				$('.msg-list .msg-bar-left').html(text);
				$('.msg-list .msg-bar-left').show();
			});
		} else if (sel == 2) {
			$('.msg-list .msg-bar-left').html('');
    		$(".infoNav li").removeClass("selected");
			$('.infoNav li:eq(1)').addClass("selected");
			$('#infor_list li:eq(1) span').hide();
			var index = 2;
			var pageno = 1;
			var isread ='';
			var keyword ='';
			path = "information/get_notice";// 消息
			var obj = 
				{
					"type":index
				};
    		$.post(path, obj,
    		function(data) {
				$('.infor_page').html('');
				$('.infor_page').append(data);
				var text='有<span class="red"><?php echo $notice_sum;?></span>条未读消息';
				if($('.infor_page ul.msg-li li').length==0)
				{
					$('.infor_page div.page').remove();
					text="还没有任何消息";
				}
				else if(<?php echo $notice_sum;?> ==0)
				{
					text="目前没有需要查看的消息";
				}
				$('.msg-list .msg-bar-left').html(text);
				$('.msg-list .msg-bar-left').show();
			});
		} else {
			var info_num = $('.headerLink .hlItem .email span.icon b').text();
			$('.msg-list .msg-bar-left').html('');
			$('#infor_list li:eq(0) span').hide();
			//alert(1)
			var index = 0;
			var pageno = 1;
			var isread ='';
			var keyword ='';
			var text='有<span class="red"><?php echo $task_sum;?></span>条未读任务';
			var path = "information/get_task";
			var obj = 
				{
					"type":index
				};
			$.post(path,obj,function(data) {
				$('.infor_page').html('');
				$('.infor_page').append(data);
				if($('.infor_page ul.msg-li li').length==0)
				{
					$('.infor_page div.page').remove();
					text="还没有任何任务";
				}
				else if(<?php echo $task_sum;?> ==0)
				{
					text="目前没有需要处理的任务";
				}
				
				//alert(text)
				$('.msg-list .msg-bar-left').html(text);
				$('.msg-list .msg-bar-left').show();
				//alert(2)
			});
		}
		var keyword =''; //关键词
		var task_state =''; //任务状态1客户端申请20管理员同意40管理员拒绝
		var notice_state =''; //消息状态0未读1已读
		var message_state =''; //通知状态0未读1已读
		//通知详情
		function showDetailPage(url, t) {
			var detailpage="<div class='cont-wrapper' id='detailMsg'></div>";
			$('.cont-wrapper').after(detailpage);
			$("#detailMsg").show().load(url).siblings(".cont-wrapper").hide(); //显示详情，并隐藏页面[除搜索]
			var num = $(t).parents(".msg-list").find(".msg-bar-left span.red").text(); //获得未处理的数量
			var num2 = $(".hlItem .email span.nums b").text(); //顶部的数量
			if (num - 1 > 0) { //还有
				$(".msg-bar-left span.red").text(num - 1); //重新设置未处理的数
			} else { //没有
				$(t).parents(".msg-list").find(".msg-bar-left").hide(); //隐藏
			}
			if (num2 - 1 > 0) { //有值
				$(".hlItem .email span.nums b").text(num2 - 1);
			} else { //没有
				$(".hlItem .email span.nums").hide(); //顶隐藏
				$(".infoNav li.selected span.nums b").text("0"); //重新设置当前标签数理为0
			}
			
			$(t).parents("li").removeClass("new") //指导最新状态移除
		}
		//详情页面的后退功能
		$("#detailMsg").find(".back").live("click",
			function() { //点击后退
				$("#detailMsg").remove();
				$(".cont-wrapper").show(); //隐藏详情，显示列表
				$('.msg-bar').show();
				$('.msg-bar-left').show();
				$('.infor_page').show();
			});
		//删除员工
		function showDeletDialog(t, id) {
			var _this = $(t);
			showDialog("information/delStaff" +'/' + id);
			$("#dialog #del_staff").die("click");
			$("#dialog #del_staff").live("click",
			function() {
				var obj = {
					"task_id": id
				};
				var path ="information/save_delstaff";
				$.post(path, obj,
				function(data) {
					var json = $.parseJSON(data);
					if (json.code == 0) {
						$(t).parents("li").removeClass("new");
						$(t).parents(".li-ml").html("已处理");
						info_num = info_num - 1;
						$('.headerLink .hlItem .email span.icon b').text(info_num);
						hideDialog();
					}
				})
		
			})
		}
		//调岗员工
		function showDgDialog(t, id) {
			var _this = $(t);
			showDialog("information/staffTransfer" +'/' + id);
			$("#dialog #transfer_staff").die("click");
			$("#dialog #transfer_staff").live("click",
			function() {
				//alert(1)
				var position=$('#dialog').find('input.input').val();
				//alert(2)
				var count=0;
				if(position=='')
				{
					count++;
					$('#dialog input.input').parent().addClass("error");
				}
				if(count!=0)
				{
					return false;
				}else
				{
					var is = 0;
					if ($('#dialog').find('label.checkbox').hasClass('checked')) {
						is = 1;
					}
					var obj = {
						"name": $('#dialog .infoTable label:eq(0)').text(),
						"department": $('#dialog .infoTable label:eq(1)').text(),
						"position": position,
						"ismanage": is,
						"task_id": id
					};
					var path ="information/save_staffTransfer";
					$.post(path, obj,
					function(data) {
						//alert(data);
						var json = $.parseJSON(data);
						if (json.code == 0) {
							$(t).parents("li").removeClass("new");
							$(t).parents(".li-ml").html("已处理");
							info_num = info_num - 1;
							$('.headerLink .hlItem .email span.icon b').text(info_num);
							hideDialog();
						}
					})
				}
				
		
			})
		}
		//新增员工
		function showAddDialog(t, id) {
			//onclick="<?php //echo $on_click; ?>"
		   var _this = $(t);
			$(t).attr("id", "addstaff" + id + "");
			showDialog("staff/add_staff_taskpage" +'/' + id)
		}
// 		function all_show(t)
// 		{
// 			var index = $('#infor_list li.selected').attr("target");
			//re_page('.infor_page',"<?php //echo site_url('information/get_list');?>"+'/1/' + index +'');
//			var path = "information/get_list";
// 			var obj = 
// 				{
// 					"type":index,
// 					"is_read":1
// 				};
// 			$.post(path,obj,function(data)
// 			{
// 				$('.infor_page').html('');
// 				$('.infor_page').html(data)
// 			})
// 		}
// 		function no_show(t)
// 		{
// 			var index=$('#infor_list li.selected').attr("target");
//			var path = "<?php //echo site_url('information/get_list'); ?>";
			
// 			if(index=='0')
// 			{
				//re_page('.infor_page',"<?php //echo site_url('information/get_list') .'/1/0/1';?>");
// 				var obj = 
// 				{
// 					"type":index,
// 					"is_read":1
// 				};
// 				$.post(path,obj,function(data)
// 				{
// 					$('.infor_page').html('');
// 					$('.infor_page').html(data)
// 				})
// 			}
// 			else
// 			{
				//re_page('.infor_page',"<?php //echo site_url('information/get_list');?>" +'/1/'+index+'/0')
// 				var obj = 
// 				{
// 					"type":index,
// 					"is_read":0
// 				};
// 				$.post(path,obj,function(data)
// 				{
// 					$('.infor_page').html('');
// 					$('.infor_page').html(data)
// 				})
// 			}
// 		}
// 		function make_show(t)
// 		{//alert(4444)
// 			var index=$('#infor_list li.selected').attr("target");
			//var path = "<?php //echo site_url('information/get_list'); ?>";
// 			//alert(index)
// 			if(index=='0')
// 			{
				//re_page('.infor_page',"<?php //echo site_url('information/get_list') .'/1/0/2';?>");
// 				var obj = 
// 				{
// 					"type":index,
// 					"is_read":2
// 				};
// 				$.post(path,obj,function(data)
// 				{alert(data)
// 					$('.infor_page').html('');
// 					$('.infor_page').html(data)
// 				})
				
// 			}
// 			else
// 			{
				//re_page('.infor_page',"<?php //echo site_url('information/get_list');?>"+'/1/'+index+'/1')
// 				var obj = 
// 				{
// 					"type":index,
// 					"is_read":1
// 				};
// 				$.post(path,obj,function(data)
// 				{//alert(data)
// 					$('.infor_page').html('');
// 					$('.infor_page').html(data)
// 				})
// 			}
// 		}
// 		function refresh_page()
// 		{
// 			var index=$('#infor_list li.selected').attr("target");
//			var path = "<?php //echo site_url('information/get_list'); ?>";
// 			var obj = 
// 				{
// 					"type":index,
// 					"is_read":1
// 				};
// 			$.post(path,obj,function(data)
// 			{
// 				$('.infor_page').html('');
// 				$('.infor_page').html(data)
// 			})
			//re_page('.infor_page',"<?php //echo site_url('information/get_list');?>"+'/1/' + index +'');
// 		}
		
		
		$(function() {
			$("#infor_list li").each(function()
				{
					if(!$(this).hasClass("selected") && $(this).find('span').text()==0)
					{
						$(this).find("span").hide();
					}
					else if(!$(this).hasClass("selected"))
					{
						$(this).find("span").show();
					}
			})
			$("#infor_list li").click(function() {
				$('.infor_page').html('');
				$('.msg-list .msg-bar-left').hide();
				$('.msg-list .msg-bar-left').html('');
				var val=<?php echo $task_sum;?>+<?php echo $message_sum;?>+<?php echo $notice_sum;?>;
				$('.header .email b').text(val);
				var index = $(this).index();
				var text;
				if(index==0)
				{
					 text='有<span class="red"><?php echo $task_sum;?></span>条未处理任务';
					
				}else if(index==1)
				{
					 text='有<span class="red"><?php echo $notice_sum;?></span>条未读消息';
					
				}else if(index==2)
				{
					 text='有<span class="red"><?php echo $message_sum;?></span>条未读通知';
					
				}
				$('.msg-list .msg-bar-left').html('');
				$('.msg-list .msg-bar-left').html(text);
				$(".msg-list").find("li").removeClass("new"); //去除列表每页，显示新的class
				$("#infor_list li").each(function()
				{
					if($(this).find('span').text()==0)
					{
						$(this).find("span").hide();
					}
					else
					{
						$(this).find("span").show();
					}
				})
				$(".infoNav li").eq(index).find("span.nums").hide(); //隐藏li后面的数量
				/*if($('.msg-bar-left').find('span').text()=='0')
				{
					$(".msg-list").find(".msg-bar-left").hide(); //隐藏未处理数量
				}
				else
				{
					$(".msg-list").find(".msg-bar-left").show(); //隐藏未处理数量
				}*/
				$(this).addClass("selected").siblings().removeClass("selected"); //选中选中的，去除其它选中
				$(".msg-list").show(); //显示选中的列表，隐藏其它未选中
				var pageno = 1;
				var isread ='';
				var keyword ='';
				var path;
				if(index == 0)
				{
					path = "information/get_task"; // 任务
				}else if(index == 1){
					path = "information/get_notice";// 消息
				}else if(index == 2){
					path = "information/get_message";// 通知
				}
				var obj = 
				{
					"type":index
				};
				$.post(path, obj,
					function(data) {
						if (index == 0) {
							/*$('.msg-list').hide();
							$('.msg-list:eq(' + index +')').show();*/
							
							$('.infor_page').append(data);
							if($('.infor_page ul.msg-li li').length==0)
							{
								$('.infor_page div.page').remove();
								text="还没有任何任务";
							}
							else if(<?php echo $task_sum;?>==0)
							{
								text="目前没有需要处理的任务";
							}
							$('.msg-list .msg-bar-left').html('');
							$('.msg-list .msg-bar-left').html(text);
						} else if (index == 1) {
							$('.infor_page').html('');
							$('.infor_page').append(data);
							if($('.infor_page ul.msg-li li').length==0)
							{
								$('.infor_page div.page').remove();
								text="还没有任何消息";
							}
							else if(<?php echo $notice_sum;?>==0)
							{
								text="目前没有需要查看的消息";
							}
							$('.msg-list .msg-bar-left').html('');
							$('.msg-list .msg-bar-left').html(text);
						} else if (index == 2) {
							/*$('.msg-list').hide();
							$('.msg-list:eq(' + index +')').show();*/
							$('.infor_page').html('');
							$('.infor_page').append(data);
							if($('.infor_page ul.msg-li li').length==0)
							{
								$('.infor_page div.page').remove();
								text="还没有任何通知";
							}
							else if(<?php echo $message_sum;?>==0)
							{
								text="目前没有需要查看的通知";
							}
							
							$('.msg-list .msg-bar-left').html(text);
						}
						$('.msg-list .msg-bar-left').show();
						/*$(this).parents(".msg-list").find(".msg-li").remove(); //移除列表
						$(this).parents(".msg-list").find(".page").remove(); //移除翻页
						$(this).parents(".msg-list").append(data); //重新追加列表及翻页*/
					   
						//$(".reloading").remove();//完成后隐藏刷新
					});
			})
		});
		//搜索功能
		$('.j-search').click(function()
		{
			var keyword = $(this).parent().find('input[name=keyword]').val();
			var reg=/\s/g;
			keyword=keyword.replace(reg,'');
				if(keyword=="")
					{
						alert("请输入需要查询的信息");
						return;
					}
				loadCont('information/searchInfo')	
		});
    //关闭任务
    /*$(".li-ml a.normal-link").click(function(){
			alert(111);
		    var close_task_id = $(this).parents("li").find("span.task_id").text();
			//alert(close_task_id);
			var path ='<?php //echo site_url('information/close_task'); ?>';
			var obj={
				"task_id":close_task_id
			}
			
			var li_obj = $(this).parents("li");
			var li_ml_obj = $(this).parent(".li-ml");
			$.post(path,obj,function(data){
	
				var data_json = eval('(' +data +')');
				if(data_json.code == 0){
					li_obj.removeClass("new");
					li_ml_obj.html("已关闭");
				}
			});
		})*/
		/*function ajax_page(obj, url) {
		
			var new_obj = eval($(obj));
			//alert(new_obj);
			var obj = {
				//"type":index,//类型
				//'pageno':pageno,//页数
				//'isread':isread,//状态
				//'keyword':keyword//关键词
			}
			$.post(path, obj,
			function(data) {
			   // alert(data) 
				index = 0;
				//$('.msg-list:eq('+index+')').append(data);
				new_obj.parents(".msg-list").find(".msg-li").remove(); //移除列表
				new_obj.parents(".msg-list").find(".page").remove(); //移除翻页
				new_obj.parents(".msg-list").append(data); //重新追加列表及翻页
				//$(".reloading").remove();//完成后隐藏刷新
			});
		
		}*/
		
		</script>
</body>
</html>
