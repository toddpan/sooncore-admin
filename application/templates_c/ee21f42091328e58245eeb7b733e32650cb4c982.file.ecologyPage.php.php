<?php /* Smarty version Smarty-3.1.18, created on 2015-08-22 21:32:12
         compiled from "application\views\ecologycompany\ecologyPage.php" */ ?>
<?php /*%%SmartyHeaderCode:925755d879dccc1135-01702886%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ee21f42091328e58245eeb7b733e32650cb4c982' => 
    array (
      0 => 'application\\views\\ecologycompany\\ecologyPage.php',
      1 => 1434809345,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '925755d879dccc1135-01702886',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'org_list_json' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d879dd6b7f53_71552728',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d879dd6b7f53_71552728')) {function content_55d879dd6b7f53_71552728($_smarty_tpl) {?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>云企管理中心</title>
	</head>
	<body>
		<div class="init_stqy_page">
			<div class="contHead"><span class="title01">企业生态</span>
  				<div class="contHead-right">
   					<div class="fr rightLine">
      					<a class="btnLabel" onclick="loadPage('ecologycompany/appointPage','company')"></a>
					</div>
    				<div class="headSearch rightLine">
      					<div class="combo searchBox">
							<b class="bgR"></b> 
							<a class="icon js-search"  ></a>
        					<label class="label">请输入查询条件</label>
        					<input class="input" name="keyword" />
      				</div>
   			 	</div>
  			</div>
		</div>
		<div class="conTabs contMiddle">
			<b class="resizeBar" style="z-index:99"></b>
  			<ul class="conTabsHead">
  				
    			<li class="selected">生态企业<span class="conline"></span></li>
    			<li>管理员</li>
  			</ul>
  			<dl class="conTabsCont">
    			<dd style="display: block;">
      				<div class="toolBar">
						 <a class="addGroup addGroupcompany" title="创建生态企业"></a> 
						 <a class="delGroup disabled" id="delet_group" title="删除生态企业"></a>
					</div>
     				<div id="tree_company">
       			 		<ul class="ztree" id="stqyTree">
        				</ul>
      				</div>
    			</dd>
    			<dd>
      				<div class="toolBar" style="display: block"> 
						<a class="addGroup addGroupstaff" title="添加生态管理员"></a> 
						<a class="delGroup delStAdmin disabled" onclick="deleteStAdmin(this);" title="删除生态管理员"></a> 
					</div>
      				<div id="tree_admin">
       	 				<ul class="ztree" id="adminTree" style="display:block;">
        				</ul>
      				</div>
    			</dd>
  			</dl>
		</div>
		<div class="contRight">
  			<div id="part01">
    			<div class="part01_1">
      				<div class="bread"><span>创想空间商务通信服务有限公司</span></div>
    			</div>
    			<div class="part01_2" style="display: none">
      				<div class="bread"><span>创想空间商务通信服务有限公司</span>&nbsp;&gt;&nbsp;<span>北京分公司</span></div>
   				</div>
  			</div>
		<div id="part02" style="display: none;">
    		<div class="tabToolBar">
				<a class="btnBlue btnAddUser" id="admin_create_stqy">
					<span class="text">创建生态企业</span>
					<b class="bgR"></b>
				</a>
      			<div class="tabToolBox fl" style="display: none;">
					<a class="btnGray btn btnMoveManage" >
						<span class="text">删除生态企业</span>
						<b class="bgR"></b>
					</a>
				</div>
  			</div>
			<div class="pop-box" id="allGroup2" style="display: none">
				<span class="pop-arrow"></span>
  				<div class="pop-box-content"></div>
			</div>
		</div>
		<script type="text/javascript" src="public/js/jquery.ztree.all-3.5.min.js"></script>
		<script type="text/javascript" src="public/js/common.js"></script>
		<script type="text/javascript" src="public/js/self_common.js"></script>
		<script type="text/javascript" src="public/js/self_tree.js"></script>
		<script type="text/javascript" src="public/js/part_js/ecol_page.js"></script>
		<script type="text/javascript" src="public/js/part_js/admin_page.js"></script>
		<script type="text/javascript" src="public/js/part_js/createEco_tree.js"></script>
		<script type="text/javascript" src="public/js/jquery.ztree.exhide-3.5.js"></script>
		<script type="text/javascript">
		var operate_type=0;
		$('.company').removeClass("false");
		var adminpath= "ecology/curNextLevelManagers";//初始化的管理员的组织结构
		var stqyNodes = <?php echo $_smarty_tpl->tpl_vars['org_list_json']->value;?>
;//初始化的企业组织结构
		var path="ecologycompany/get_next_OrgList";//要加载的每个组织结构
		var ecology_path="ecologycompany/get_next_manager_json";//要加载的每级生态企业
		var adminNodes;
		function Initstqy()//初始化企业结构树
		{ 
			var leng=stqyNodes.length;
			//create_node(stqyNodes);
			
			$.fn.zTree.init($("#stqyTree"), stqySetting, stqyNodes);
			var zTree = $.fn.zTree.getZTreeObj("stqyTree");
			var nodes =zTree.getNodes();
			zTree.selectNode(nodes[0]);
			var treeNode = zTree.getSelectedNodes();
			if(treeNode[0].pId==null)
			{
				var obj={
					"org_id":treeNode[0].id
				};
				//alert(treeNode.id)
				var path_first="ecologycompany/info";
				$.post(path_first,obj,function(data)
				{
					//alert(data)
					$('.part01_1').find(".cont-wrapper").remove();
					$('.part01_1 .bread').after(data);
				})
			}
			else 
			{
				var obj={
					"org_id":treeNode[0].id
				};
				//alert(treeNode.id)
				var path_other ='ecologycompany/info2';
				$.post(path_other,obj,function(data)
				{
				//alert(data)
					$('.part01_1').find(".cont-wrapper").remove();
					$('.part01_1 .bread').after(data);
				})
			}
			//$('.ztree li a').trigger('click');
		
			var org_ID=nodes[0].id;//获得当前组织id
			var parent_orgid=nodes[0].pId;
			//alert(org_ID);
			//alert(parent_orgid)
			var nodes = zTree.getSelectedNodes();
			if(nodes[0]!=null)
			{
				var value=[];
				value.push(nodes[0].name);
				/*while(nodes[0].pId!=null)
				{
					nodes = zTree.getNodesByParam("id", nodes[0].pId, null);
					if(nodes[0]!=null)
					{
					  value.push(nodes[0].name);
					}
				}*/
				//alert(32243)
				var  staff_depart="";
				//staff_depart=' <div class="bread part0">';
				for(var i=value.length-1;i>0;i--)
				{
					staff_depart=staff_depart+'<span>'+value[i]+'</span>&nbsp;&gt;&nbsp';
				}
				staff_depart=staff_depart+'<span>'+value[i]+'</span>';
				//staff_depart=staff_depart+"</div>";
				// $('.link_limitSet').after(staff_depart);
				//alert(2324);
				$('#part01 .part01_1 .bread').find('span').text('');
				$('#part01 .part01_1 .bread').find('span').append(staff_depart);
			}
		   /* var obj={
				"parent_orgid":parent_orgid,
				"org_id":org_ID
			};
			load_staff(obj,path_user,path_mag);*/
		}
		if($('.conTabs li:eq(0)').hasClass("selected"))
		{
			//alert(23324)
			 Initstqy();//初始化生态企业
		}
       
		function toggleAccount(t){
				if($(t).find("span.text").text()=="关闭帐号") {
				showDialog('ecologycompany/closeAccount');
					var _this = $(t);
					$("#dialog .dialogBottom .btn_confirm").live("click",function(){
						_this.find("span.text").text("开启帐号");
						hideDialog();
					})
				}
				else {
					$(t).find("span.text").text("关闭帐号");
				}
			}
		$(function()
		{
			$('.conTabsHead > li').click(function(){
				var ind = $(this).index();
				$(this).addClass('selected').siblings().removeClass('selected');
				$('.conTabsCont > dd').eq(ind).show().siblings().hide();
				$('.contRight > div').eq(ind).show().siblings().hide();
			});
			
			//组织结构树
			$('.treeNode').each(function(){
				var _this = $(this);
				var pNum = _this.parents('.tree').length;
				_this.css('padding-left', 6+(pNum-1)*16+'px');
			});
			$('.treeNodeArrow').click(function(){
				var _this = $(this);
				if(_this.hasClass('close')){
					_this.removeClass('close').addClass('open').parent().siblings('.subTree').show();
				}else if(_this.hasClass('open')){
					_this.removeClass('open').addClass('close').parent().siblings('.subTree').hide();
				}
				return false;
			});
			$('.treeNode').click(function(){
				$('.treeNode').removeClass('selected');
				$(this).addClass('selected');
			});
			
			$(".bbit-tree-node > div").click(function(){
				$(".bbit-tree-node div").removeClass("bbit-tree-selected");
				$(this).addClass("bbit-tree-selected");
			})
			
			$('.infoNav li').click(function(){
				
				var ind = $(this).index();
				//var len = $(this).parent("ul").children().length;
				
				//if(ind<len-1) {
				$(this).addClass('selected').siblings().removeClass('selected');
				$(this).parent(".infoNav").next('.infoCont').find("dd").eq(ind).show().siblings().hide();
				//}
			});
			
			$('.btn_infoEdit').click(function(){
				$(this).addClass('hide').siblings('.btn_infoSave, .btn_infoCancel').removeClass('hide');
				$('.infoTable .infoText').not('.dotEdit').each(function(){
					$(this).hide().next().removeClass('hide');
				});
			});
			$('.btn_infoEdit2').click(function(){
				$(this).addClass('hide').siblings('.btn_save2, .btn_cancel2').removeClass('hide');
				$('.setStqy label').each(function(){
					$(this).removeClass('disabled').find("input").removeAttr("disabled");
				});
			});
			$('.btn_save2').click(function(){
				$(this).addClass('hide').siblings('.btn_cancel2').addClass('hide').siblings('.btn_infoEdit2').removeClass('hide');
				$('.setStqy label').each(function(){
					$(this).addClass('disabled').find("input").attr("disabled","disabled");
				});
			});
			$('.btn_cancel2').click(function(){
				$(this).addClass('hide').siblings('.btn_save2').addClass('hide').siblings('.btn_infoEdit2').removeClass('hide');
				$('.setStqy label').each(function(){
					$(this).addClass('disabled').find("input").attr("disabled","disabled");
				});
			});
			
			$('.btn_infoCancel').click(function(){
				$(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoSave').addClass('hide');
				$('.infoTable .infoText').not('.dotEdit').each(function(){
					$(this).show().next().addClass('hide');
				});
			});
			$('.btn_infoSave').click(function(){
				$(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoCancel').addClass('hide');
				$('.infoTable .infoText').not('.dotEdit').each(function(){
					$(this).show().next().addClass('hide');
					var text = $(this).next().hasClass('inputBox') ? $(this).next().find('.input').val() : $(this).next().hasClass('selectBox') ? $(this).next().find('.text').text() : '';
					$(this).text(text);
				});
			});
			
			$(".selectGroup").click(function(event){
				
				$("#allGroup2").toggle();
				event.stopPropagation();
			})
			
			$(".bbit-tree-node-ct li").live("click",function(){
				$(".part01_2").show().siblings().hide();
				$("#tree_0").removeClass("bbit-tree-selected")	
			})
			$("#tree_0").live("click",function(){
				$(".part01_1").show().siblings().hide();
			})
			
			$(document).click(function(){
				$("#allGroup2").hide();
		
				//$(".datepickers").empty();	
			})
			 //员工搜索js事件
			$('.js-search').click(function(){
				//alert(111);
				var keyword = $(this).parent().find('input[name=keyword]').val();
				var reg=/\s/g;
				keyword=keyword.replace(reg,'');
				if(keyword=="")
					{
						alert("请输入需要查询的信息");
						return;
					}				
				loadCont('search/searchComEcology'+'?keyword='+keyword);
			});

		});
		</script>
	</body>
</html>
<?php }} ?>
