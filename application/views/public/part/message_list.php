	
		<ul class="msg-li">
			<?php 
			foreach($message_arr as $k => $v):
				 $msg_id = arr_unbound_value($v,'id',2,'');
				 $title = arr_unbound_value($v,'title',2,'');
				 $send_name = arr_unbound_value($v,'send_name',2,'');
				 $content = arr_unbound_value($v,'content',2,'');
				 $addtime = arr_unbound_value($v,'addtime',2,'');
				 $isread = arr_unbound_value($v,'isread',2,'');
				?>
		  		<li <?php  if( $isread == 0 ): ?>  class="new" <?php  endif ;?>>
				  <a  onClick="detail_page(<?php echo $msg_id;?>,this);"> <?php echo $title;?></a> 
				  <span class="time"><?php echo $addtime;?></span> 
				</li>
		   <?php endforeach;?>
		  
		  <!--<li><a  onClick="showDetailPage('msg1.html',this)">欢迎使用全时蜜蜂</a> <span class="time">17:35</span> </li>-->
		</ul>
		<div class="page">
		 <?php echo $page_text;?>
		</div>
		<script type="text/javascript">
		 if($('ul.msg-li li').length==0)
			{
				$('.infor_page div.page').remove();
			}
		function detail_page(id,t)
		{
			if($(t).parent().hasClass("new"))
			{
				$(t).parent().parent().parent().hide();
				$(t).parent().parent().parent().prev().hide();
				showDetailPage("message"+'/'+id,t)
			}
		}
		</script>