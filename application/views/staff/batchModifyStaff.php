<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>sooncore平台管理中心</title>
	<!-- Bootstrap styles 引导风格-->
	<!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">-->
	<!--<link rel="stylesheet" href="<?php echo base_url('public/jQueryFileUpload/bootstrap.min.css');?>">-->
	
	<!-- Generic page styles通用页面样式 -->
	<!--<link rel="stylesheet" href="<?php echo base_url('public/jQueryFileUpload/css/style.css');?>">-->
	<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars CSS样式文件输入字段作为按钮并调整引导进度条-->
	<!--<link rel="stylesheet" href="<?php echo base_url('public/jQueryFileUpload/css/jquery.fileupload.css');?>">-->
</head>

<body>
<!--组织与员工_批量添加员工.html-->
<div class="contHead">
	<span class="title01 rightLine">组织管理</span><span class="title03">批量修改</span>
	<div class="contHead-right">
	<div class="fr rightLine"><a class="btnSet" onclick="toggleMenu('menu1',event)"  ></a></div>
	
	<div class="headSearch rightLine">
		<div class="combo searchBox">
			<b class="bgR"></b>
			<a class="icon"  onclick="loadCont('搜索_组织与帐号.html')"></a>
			<label class="label">请输入查询条件</label>
			<input class="input" />
		</div>
	</div>
     <ul class="menu" id="menu1">
            <li><a  onclick="loadCont('tag/addTagPage/0','group')">员工标签管理</a></li>
      <!--        <li><a  onclick="loadCont('Ldap/showLdapPage')">LDAP设置</a></li>  -->
        </ul>
    </div>
</div>
<!-- end contHead -->

<div class="contTitle"><span class="tips">请上传修改后的文档。</span></div>
<?php if(1 > 2):?>
	<a class="btn_icon btn_toLoad" >上传文档<input class="inputFile" type="file" title=""></a>
<?php endif;?>
<a class=" upTEMP btn_icon btn_toLoad inputFile "  >上传文档</a>
<form style="display: none">
	<input class="inputFile1" id="fileupload" type="file"  title=""   name="files[]" />
</form>
<div class="normal-msg">系统将自动为您更新已有账号的信息，新增账号请使用<a  onclick="loadCont('staff/batchAddStaffPage')">批量添加新员工</a>。</div>
<!--[if IE 6]>
<script type="text/javascript">
	DD_belatedPNG.fix('.btn_icon, .btn_icon .iconL, .btn_icon .iconR');
</script>
<![endif]-->

<script type="text/javascript">
//	$(function(){
//		$('.btn_toLoad .inputFile').change(function(){
//			//url_index = '主页.html';
//			//url_group = '组织与员工.html';
//			//loadCont('组织与员工_批量导入_导入成功.html');
//			showDialog('弹窗_上传文档3.html');
//		});
//	});
</script>
	<script type="text/javascript">
		$(function(){
			$('.upTEMP ').click(function(){
				$('input[class=inputFile1]').trigger('click');
				//url_index = '主页.html';
				//url_group = '组织与员工.html';
				//loadCont('组织与员工_批量导入_导入成功.html');   
							//alert("show didlog");
						  // showDialog('弹窗_上传文档.html');
						  
				// var filepath = this.value; 
				// var gotourl = "<?php // echo site_url('bulkimport/upCSVFileaa').'/1/'?>" + Math.random();// + "?upfilepath=" + encodeURIComponent(filepath);  
				// showDialog('<?php // echo site_url('bulkimport/upSCVFile');?>');
				 //alert(gotourl);
				/* showDialog(gotourl);
				$('.load_file').show();
				var _dialog=$('.load_file');
				var _mask = $('.mask').height($('body').height()).width($(window).width()).show();
				var w = _dialog.width();
				var h = _dialog.height();
				_dialog.css({
					'margin-top': -h/2+'px',
					'margin-left': -w/2+'px'
				});*/
	
			});
			$('.inputFile1').change(function()
			{
				var dialog='<dl class="dialogBox D_uploadTable load_file">'+
				'<dt class="dialogHeader">'+
				'<span class="title">上传文档</span>'+
				'<a  class="close" onClick="hideDialog();"></a>'+
				'</dt>'+
				'<dd class="dialogBody">'+
				'<div class="tcText">正在上传文档...</div>'+
				 '<div class="statusBar">'+
				 '<div class="loading"></div>'+
				'</div>'+
				'</dd>'+
				'<dd class="dialogBottom">'+
				'<a class="btnGray btn_cancel" ><span class="text" onClick="hideDialog();">取消</span><b class="bgR"></b></a>'+
				'</dd>'+
			   '</dl>';
			   // var _mask = $('.mask').height($('body').height()).width($(window).width()).show();
				var _dialog = $('#dialog').show();
				var _mask = $('.mask').height($('body').height()).show();
				 _dialog.find('.dialogBorder').append(dialog);
				 var w = _dialog.width();
				 var h = _dialog.height();
				 _dialog.css({
							'margin-top': -h/2+'px',
							'margin-left': -w/2+'px'
						});
			   /* //$('.load_file').show();
				var _dialog=$('.load_file');
				var w = _dialog.width();
				var h = _dialog.height();
				_dialog.css({
					'margin-top': -h/2+'px',
					'margin-left': -w/2+'px'
				});*/
			});
			//$('.downLoadTemp .inputFile').change(function(){
				//url_index = '主页.html';
				//url_group = '组织与员工.html';
				//loadCont('组织与员工_批量导入_导入成功.html');   
							//alert("show didlog");
						  // showDialog('弹窗_上传文档.html');
						  
				// var filepath = this.value; 
				// var gotourl = "<?php // echo site_url('bulkimport/upCSVFileaa').'/1/'?>" + Math.random() + "?upfilepath=" + encodeURIComponent(filepath);  
				// showDialog('<?php // echo site_url('bulkimport/upSCVFile');?>');
				// showDialog(gotourl);
	
			//});
		});
			
	 function submitform(){
	   //判断文件后缀是否合法
	   
	   //文件大小判断
				  
	   //submit
	   //document.form.submit();
	  }       
	</script>
<!--<script src="<?php echo base_url('public/jQueryFileUpload/jquery.min.js');?>"></script>-->
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo base_url('public/jQueryFileUpload/js/vendor/jquery.ui.widget.js');?>"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo base_url('public/jQueryFileUpload/js/jquery.iframe-transport.js');?>"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo base_url('public/jQueryFileUpload/js/jquery.fileupload.js');?>"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>-->
<script src="<?php echo base_url('public/jQueryFileUpload/bootstrap.min.js');?>"></script>
<script>
/*jslint unparam: true */
/*global window, $ */
$(function () {
//		var i = 0;
//		var t;
//		var t2 = setTimeout(function(){
//		/*var t2 = setTimeout(function(){
//			$(".tcText").text("正在导入组织与帐号...");
//		},1500);*/
//		function statusBar(){
//			if(i<100){
//				$(".statusBar .loading").css("width",i+"%");
//				i++;
//				t = setTimeout(function(){statusBar();},30);
//			}else{
//				$(".dialogHeader .title").html("导入组织与帐号");
//				$(".tcText").html("正在导入组织与帐号...<br><span  class='green'>已经导入10个组织50个帐号</span>");
//				$(".statusBar").remove();
//				setTimeout(
//					function()
//					{
//						//location.href= '<?php // echo site_url('bulkimport/importlayout');?>?#group';
//						//loadPage('<?php // echo site_url('ecologycompany/staffInfoPage');?>','group');
//						//hideDialog();
//					},2000
//				);
//				
//				clearTimeout(t);
//				//clearTimeout(t2);
//			}
//		}
	//	statusBar();
	$('.D_uploadTable .close, .D_uploadTable .btn_cancel').click(function(){
		//clearTimeout(t);
		hideDialog();
	});

	//function submitform(){
	   // alert(values);
	//}
    //'use strict';
    // Change this to the location of your server-side upload handler:更改为你的服务器端上传处理程序的位置
    // var url = '<?php // echo ('/ucadmin/public/jQueryFileUpload/server/php/');?>';
	 var url = '<?php echo ('/ucadmin/public/jQueryFileUpload/server/php/');?>';
	//alert(url);
    $('#fileupload').fileupload({
        url: url,
        dataType:'json',
        done: function (e, data) {
          /*  $('.statusBar .loading').css(
                'width',
                 '100%'
            );*/
			var filename = '';
            $.each(data.result.files, function (index, file) {
			     filename = file.name;
                $('<p/>').text(file.name).appendTo('#files');
            });
			//alert(filename);
			//上传完成
			//解析文件
			
			var gotourl = "<?php echo site_url('bulkimport/upCSVFile').'/1/'?>" + Math.random() + "?filename=" + encodeURIComponent(filename); 	
			/*$("#dialog .dialogHeader .title").html("导入组织与帐号");
			$("#dialog .tcText").html("正在导入组织与帐号...<br><span  class='green'>已经导入10个组织50个帐号</span>");
			$("#dialog .statusBar").remove();
			$("#dialog .btn_cancel").hide();
			
			setTimeout(function(){
				$("#dialog .dialogHeader .title").html("更新组织与帐号");
				$("#dialog .tcText").html("<span  class='green'>更新完毕</span>");
			},3000
			);
			setTimeout(function(){
				//location.href= 'import-layout.html?#group';
				//loadPage('组织与员工.html','group');
				$('.mask').hide(); 
				loadCont(gotourl);
				hideDialog();},4000
			);	*/
				$('.mask').hide(); 
				loadCont(gotourl);
				hideDialog();				 
             //showDialog(gotourl);  
			//loadCont(gotourl,'group');
			// hideDialog();
			////location.href= '<?php // echo site_url('bulkimport/importlayout');?>?#group';
			//loadPage('<?php // echo site_url('ecologycompany/organizeStaff');?>','group');
			//hideDialog();  
			//跳转到解析页面[另一个一样的页面,只是是在记录解析的数量]
			//隐藏当前对话框
			//hideDialog();
        },
		fail:function(e,data)
				{
					//alert("文件错误请重新上传");
					if((typeof data.result) == "undefined"){
						alert("可能您传输的文件格式不正确!!!"); 
					}else{
						$.each(data.result.files, function (index, file) {
						alert(file.error)
							var error = $('<span/>').text(file.error);
							$(data.context.children()[index])
								.append('<br>')
								.append(error);
						});
					}
					//alert(data.result)
					 hideDialog();
				},
        progressall: function (e, data) {//进度条
            var progress = parseInt(data.loaded / data.total * 100, 10);
            //$('#progress .progress-bar').css(
            //    'width',
            //    progress + '%'
           // );
           // alert(progress);
            $('.statusBar .loading').css(
                'width',
                progress + '%'
            );
			
			
        }
    }).prop('disabled', !$.support.fileInput)
    .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>
</body>
</html>