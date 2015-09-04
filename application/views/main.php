<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>蜜蜂管理中心</title>
	</head>
	<body>
		<div class="contHead" id="main_head">
			<span class="title01 rightLine">首页</span>
			<span class="title02">欢迎使用蜜蜂管理后台<?php if($this->p_role_id != ACCOUNT_MANAGER){ ?> ，管理组织与员工请先为员工设置统一的标签<?php } ?>。</span>
		</div>
		<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER){?>
		<a class="setLabelBtn" onclick="set_tag();">设置员工标签</a>
		<?php }?>
		<div class="block" <?php if($this->p_role_id == ACCOUNT_MANAGER){ ?> style="border-top: 0;"<?php } ?>>
  			<h2>帐号情况</h2>
  			<div class="block-content">
  				<div class="block-content-left" id="chart1"></div>
    			<div class="block-content-right" id="chart2"></div>
  			</div>
		</div>
<!-- 		<div class="block"> -->
			<?php //if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER){?>
<!--   			<div class="block-left"> -->
<!--     			<h2> -->
<!--     				<span class="rl">最新通知</span> -->
<!--     				<a  class="more more_list1">更多</a> -->
<!--     			</h2> -->
<!--     			<div class="block-content"> -->
<!--       				<ul class="news-list"> -->
      				<?php //if(is_string($message)){?>
<!--          				<li><?php //echo $message; ?></li> -->
        			<?php
//       					}else{
//       						foreach($message as $msg){
//       				?>
<!--        			<li><a ><?php //echo $msg['content']; ?></a></li> -->
        			<?php
//       						}
//       					}
//  					?>
<!--       				</ul> -->
<!--     			</div> -->
<!--   			</div> -->
  			<?php //}?>
  			<?php //if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER || $this->p_role_id == ACCOUNT_MANAGER || $this->p_role_id == ECOLOGY_MANAGER){?>
<!--   			<div class="block-right"> -->
<!--     			<h2> -->
<!--     				<span class="rl">最新消息</span> -->
<!--     				<a  class="more more_list2">更多</a> -->
<!--     			</h2> -->
<!--     			<div class="block-content"> -->
<!--       				<ul class="news-list"> -->
      				<?php 
//       					if(is_string($notice)){
//       				?>
<!--          				<li><?php //echo $notice;?></li> -->
        			<?php
//      	 				}else{
//         					foreach($notice as $ntc){
//         			?>
<!--          				<li><?php //echo $ntc['content'];?></li> -->
        			<?php 
//         					}
//         				}
//         			?>
<!--       				</ul> -->
<!--     			</div> -->
<!--   			</div> -->
  			<?php // }?>
<!-- 		</div> -->
		
		<script language="javascript" type="text/javascript" src="public/js/excanvas.js"></script>
		<script type="text/javascript" src="public/js/jquery.jqplot.js"></script>
		<script type="text/javascript" src="public/js/jqplot.pieRenderer.min.js"></script>
		<script type="text/javascript" src="public/js/jqplot.donutRenderer.min.js"></script>		
		<script type="text/javascript">
			$('.main').removeClass("false");
			function set_tag()
			{
				$(".group").parent("li").removeAttr('id');
				$('#ri_group').empty();
				loadPage('tag/manageTag/0','group');
			}
			$(function(){
     			$('.more_list1').click(function(){
					loadCont("information/infoManPage" + '/' + 1);
				});	
			$('.more_list2').click(function(){
				loadCont("information/infoManPage" + '/' + 2);
				});	
			})
		</script>
		<script type="text/javascript">
			$(document).ready(function(){  
				var is_open_users;
				var not_open_users;
				var is_used_users;
				var not_used_user;
				$.post('main/countUser',[],function(data){
					is_open_users 	= data.data.is_open_users;
					not_open_users 	= data.data.not_open_users;
					is_used_users 	= data.data.is_used_users;
					not_used_user 	= data.data.not_used_user;					
					var data = [['已开通',is_open_users],['未开通',not_open_users]];
					var data1 = [['已启用',is_used_users], ['未启用',not_used_user]];
					if(is_open_users==0 || not_open_users==0)
					{
						$.jqplot('chart1', [data], {
							title: { text:'帐号开通', show:true, fontSize:'16px' ,textColor:'#000'},
							seriesDefaults:{			
							renderer:$.jqplot.PieRenderer,			
							rendererOptions: {			
								showDataLabels: true,
								dataLabels: 'value'
										}
				
							},
							legend: { show:true, location: 'e'}
						});
						
						
						$.jqplot('chart2', [data1], { 
							title: { text:'帐号启用', show:true, fontSize:'16px' ,textColor:'#000'},
							seriesDefaults:{			
							renderer:$.jqplot.PieRenderer,			
							rendererOptions: {			
								showDataLabels: true,
								dataLabels: 'value'
										}				
							},
							legend: { show:true, location: 'e'}
						});

						
					}
					else
					{
							$.jqplot('chart1', [data], {
							title: { text:'帐号开通', show:true, fontSize:'16px' ,textColor:'#000'},
							seriesDefaults: {
								shadow:false,
								renderer: jQuery.jqplot.PieRenderer, 
								rendererOptions: {
									dataLabels: 'value',
									startAngle: -45,
									showDataLabels: true,
									sliceMargin: 0, // 饼的每个部分之间的距离
									fill:true, // 设置饼的每部分被填充的状态
									shadow:true, //为饼的每个部分的边框设置阴影，以突出其立体效果
									shadowOffset: 2, //设置阴影区域偏移出饼的每部分边框的距离    
									shadowDepth: 5, // 设置阴影区域的深度    
									shadowAlpha: 0.07, // 设置阴影区域的透明度
									fillColor: '#000',       // 设置填充区域的颜色  
									fillAlpha: 0.4,       // 梃置填充区域的透明度 
									diameter: 100 //直径
								}
							},
							legend: { show:true, location: 'e'}
						});
						
						
						
						$.jqplot('chart2', [data1], { 
							title: { text:'帐号启用', show:true, fontSize:'16px' ,textColor:'#000'},
							seriesDefaults: {
								shadow:false,
								renderer: jQuery.jqplot.PieRenderer, 
								rendererOptions: {
									dataLabels: 'value',
									startAngle: -45,
									showDataLabels: true,
									sliceMargin: 0, // 饼的每个部分之间的距离
									fill:true, // 设置饼的每部分被填充的状态
									shadow:true, //为饼的每个部分的边框设置阴影，以突出其立体效果
									shadowOffset: 2, //设置阴影区域偏移出饼的每部分边框的距离    
									shadowDepth: 5, // 设置阴影区域的深度    
									shadowAlpha: 0.07, // 设置阴影区域的透明度
									fillColor: '#000',       // 设置填充区域的颜色  
									fillAlpha: 0.4,       // 梃置填充区域的透明度 
									diameter: 100 //直径
								}
							},
							legend: { show:true, location: 'e'}
						});
					}
					
					
					},'json');
					
				

			});

			// 如果是账号管理员，则去掉id为main_head的div的下边框的显示
			<?php if($this->p_role_id == ACCOUNT_MANAGER){?>
//				$('#main_head').addClass('border-bottom:0;');
			<?php } ?>
		</script>
	</body>
</html>
