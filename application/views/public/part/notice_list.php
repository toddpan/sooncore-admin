		<ul class="msg-li">
			<?php 
			foreach($notice_arr as $k => $v):
				 $notice_id = arr_unbound_value($v,'id',2,'');
				 $notice_content = arr_unbound_value($v,'content',2,'');
				 $notice_addtime = arr_unbound_value($v,'addtime',2,'');
				 $notice_isread = arr_unbound_value($v,'isread',2,'');
				?>
		 		 <li target="<?php echo $notice_id;?>"
				 	<?php  if( $notice_isread == 0 ): ?> 
					 class="new"  
					 onclick="detail_notice(this,'<?php echo $notice_content;?>')" 
					<?php  endif ;?> >
					<?php echo $notice_content;?> 
					<span class="time"><?php echo $notice_addtime;?></span> 
				</li>
		   <?php endforeach;?>
		</ul>
		<div class="page">
		 <?php echo $page_text;?>
		 </div>
		 <div id="dialog" class="dialog">
			<div class="dialogBorder">
			</div>
	 	</div>
		
		
		 <script type="text/javascript">
		 if($('ul.msg-li li').length==0)
			{
				$('.infor_page div.page').remove();
			}
		function detail_notice(t,context)
		{
			if($(t).hasClass("new"))
			{
					$('.dialog .dialogBorder').html('');
				$('ul.msg-li li.isread').removeClass("isread");
				var time=$(t).find('span').text();
				$(t).addClass("isread");
				var text=''+time+context+'';
				var dialog='<dl class="dialogBox D_confirm">'+
						'<dt class="dialogHeader">'+
							'<span class="title">查看消息</span>'+
							'<a class="close" onclick="hide_detial_notice();"></a>'+
						'</dt>'+
						'<dd class="dialogBody">'+
							'<span class="text01"></span>'+
						'</dd>'+
						'<dd class="dialogBottom">'+
		'<a class="btnGray btn btn_cancel" onclick="hide_detial_notice()"><span class="text">关闭</span><b class="bgR"></b></a>'+
						'</dd>'+
					'</dl>';
				var _mask = $('.mask').show();
				var _dialog=$('.dialog').show();
				_dialog.find('.dialogBorder').append(dialog);
				$('.dialog .dialogBox .dialogBody').find('span.text01').text(text);
				var w = _dialog.width();
				var h = _dialog.height();
				 _dialog.css({
							'margin-top': -h/2+'px',
							'margin-left': -w/2+'px'
						});
			}
			
		}
		function hide_detial_notice()
		{
			$('.dialog').hide();
			$('.mask').hide();
			var obj=$('ul.msg-li li.isread').attr("target");
			//var path;
			/*$.post(path,obj,function(data)
			{
				if(data.code==0)
				{
					$('ul.msg-li li.isread').removeClass("new");
				}
			},"json");*/
			$('ul.msg-li li.isread').removeClass("new");
		}
		</script>