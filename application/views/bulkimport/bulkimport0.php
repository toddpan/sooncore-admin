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
	<!-- 组织与员工_批量导入_下载模板上传文档.html -->
	<div class="contHead">
		<span class="title01 rightLine">组织管理</span><span class="title03">批量导入</span>
	</div>
	<!-- end contHead -->
	<div class="contTitle"><span class="text">批量导入组织与员工</span><span class="tips">您已定义的员工标签如下：</span></div>
	<div class="userTagBox">
		<?php foreach ($all_tag_names_arr as $key => $value):?>
			  <span class="tag"><?php echo $value ?></span>
		<?php endforeach;?>
		<a class="link" onclick="loadCont('tag/addTagPage');">修改</a>
	</div>
	<a class="downLoadTemp" href="<?php echo site_url('batchimport/downloadTemplate');?>" target="_self" >
		<span class="btn_downLoad">下载模板</span>
		<span class="text01">请先下载模板，根据已定义的员工标签填写内容， 模板中的员工标签不可编辑，否则无法导入。</span>
	</a>
	 <!--<form name="form" action="<?php // echo site_url('bulkimport/upCSVFile');?>/0/" id="uploadForm" method="post" enctype="multipart/form-data" >-->
	<a class="downLoadTemp upTEMP" >
		<span class="btn_upLoad" >上传文档</span>
		<span class="text01" >填写完成模板内容后，上传文档导入组织与员工。</span>
		<?php if(1 > 2):?>
			<input class="inputFile" type="file" title="" name="userfile"  onchange="submitform()"/>
		<?php endif;?>
	</a>
	<form style="display: none">
		<input class="inputFile1" id="fileupload" type="file"  title=""   name="files[]"/>
		<input type="button" value="提交" onclick="ajaxFileUpload()"/>
	</form>
	<div class="mask"></div>
	<div id="dialog" class="dialog">
		<div class="dialogBorder">
		</div>
	 </div>
	<!-- </form>  -->
<script src="public/js/jquery-1.10.2.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="public/js/ajaxfileupload.js"></script>
<script src="public/jQueryFileUpload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="public/jQueryFileUpload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="public/jQueryFileUpload/js/jquery.fileupload.js"></script>
<script src="public/jQueryFileUpload/js/jquery.fileupload-process.js"></script>
<script src="public/jQueryFileUpload/js/jquery.fileupload-validate.js"></script>
<script src="public/jQueryFileUpload/js/cors/jquery.xdr-transport.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>-->
<script src="public/jQueryFileUpload/bootstrap.min.js"></script>
<script type="text/javascript">
var iframe = false;
if($.support.msie  && $.support.version < 10){
	iframe = true;
}
iframe=true;
function ajaxFileUpload(filename){
//alert(filename)
  var url = '<?php echo ('/ucadmin/public/jQueryFileUpload/server/php/');?>';
   $.ajaxFileUpload(
	   {
			url:url,            //需要链接到服务器地址
			secureuri:false,
			fileElementId:'fileupload',                        //文件选择框的id属性
			dataType: 'json',                                     //服务器返回的格式，可以是json
			success: function (data, status)            //相当于java中try语句块的用法
			{
				//$('#result').html('添加成功');
				//alert(1)
				var gotourl = "<?php echo site_url('bulkimport/upCSVFile').'/0/'?>" + Math.random() + "?filename=" + encodeURIComponent(filename);

		             //showDialog(gotourl);

		             $('.mask').hide();
		             loadCont(gotourl);
					 hideDialog();
			},
			error: function (data, status, e)            //相当于java中catch语句块的用法
			{
				//$('#result').html('添加失败');
				//alert(data.name)
				//alert(status)
				var gotourl = "<?php echo site_url('bulkimport/upCSVFile').'/0/'?>" + Math.random() + "?filename=" + encodeURIComponent(data.name);

		             //showDialog(gotourl);

		             $('.mask').hide();
		             loadCont(gotourl);
					 hideDialog();
			}
	});

 }
		/*var t2 = setTimeout(function(){
			$(".tcText").text("正在导入组织与帐号...");
		},1500);*/
$(function(){
		//$('#fileupload').click(function()
			//{
			//	ajaxFileUpload();
			//})
			$('.upTEMP ').click(function(){
				$('input[class=inputFile1]').trigger('click');
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
				'<div class="loading" style="width:0%"></div>'+
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
				//ajaxFileUpload($(this).val());
			});
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
			 var url = '<?php echo '/ucadmin/public/jQueryFileUpload/server/php/';?>';
			//alert(url);
			 /* $('.statusBar .loading').css(
		                'width',
		                 '100%'
		            );*/
		    $('#fileupload').fileupload({
		        url: url,
				type:"POST",
				iframe:true,
		        dataType:'json',
				acceptFileTypes:/(\.|\/)(xlsx|xls|csv)$/i,
				autoUpload:true,
           		//maxFileSize: 10000000, // 10 MB
				minFileSize:0,
            	previewMaxWidth: 100,
           		previewMaxHeight: 100,
            	previewCrop: true,
				add: function(e, data) {
					var uploadErrors = [];
					if(data.originalFiles[0]['type'].length && data.originalFiles[0]['type']!="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" && data.originalFiles[0]['type']!="application/vnd.ms-excel") {
					 uploadErrors.push('文件类型不符合要求');
					 hideDialog();
					}
					if(data.originalFiles[0]['size'].length && data.originalFiles[0]['size'] > 10000000) {
					 uploadErrors.push('文件大小不能超过10M');
					 hideDialog();
					}
					//alert(data.originalFiles[0]['size'])
					if(data.originalFiles[0]['size']==0) {
					 uploadErrors.push('文件大小不能为空');
					 hideDialog();
					}
					if(uploadErrors.length > 0) {
					 alert(uploadErrors.join("\n"));
					 		} else {
				 	data.submit();
					}
					/*var gotourl = "<?php echo site_url('bulkimport/upCSVFile').'/0/'?>";
					var obj={
					"filename":data.originalFiles[0]['name']
					}
					$.post(gotourl,obj,function(data)
					{
						alert(data)
					})*/
			  	},
		        done: function (e, data) {
		          	//alert(2)
					//alert(1111)
					var filename = '';
		            $.each(data.result.files, function (index, file) {
					     filename = file.name;
						 filesize = file.size;
		                $('<p/>').text(file.name).appendTo('#files');
		            });
					//alert(filename)
					var gotourl = "<?php echo site_url('bulkimport/upCSVFile').'/0/'?>" + Math.random() + "?filename=" + encodeURIComponent(filename);
					//从这把文件名给我的
		             //showDialog(gotourl);
		             //var gotourl='bulkimport/upCSVFile'
		             $('.mask').hide();
		             loadCont(gotourl);
					//location.href= '<?php //echo site_url('bulkimport/importlayout');?>?#group';
						//loadPage('<?php //echo site_url('ecologycompany/organizeStaff');?>','group');
					//hideDialog();
					//跳转到解析页面[另一个一样的页面,只是是在记录解析的数量]
					//隐藏当前对话框
					//hideDialog();
		        },
				fail:function(e,data)
				{
					//alert(1)
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
			       // alert(data)
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
		    .parent().addClass($.support.fileInput ? undefined : 'disabled')
		})

	 function submitform(){
	   //判断文件后缀是否合法

	   //文件大小判断

	   //submit
	   //document.form.submit();
	  }
</script>

	<!--[if IE 6]>
	<script type="text/javascript">
		DD_belatedPNG.fix('.btn_icon, .btn_icon .iconL, .btn_icon .iconR');
	</script>
	<![endif]-->

</body>
</html>







