<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sooncore平台管理中心</title>
</head>
<body>
<!--创建生态企业4.html-->
<div class="contHead">
	<span class="title01 rightLine">组织管理</span><span class="title03">创建生态企业</span>
</div>
<div class="ldapSetBox">
	<div class="ldapSetStep qystStep">
    	<a  class="selected">1. 填写生态企业信息<b class="arrow"></b></a>
    	<a  class="selected">2. 设置生态企业权限<b class="arrow"></b></a>
    	<a  class="selected">3. 设置该企业管理员<b class="arrow"></b></a>
    	<a  class="selected current">4. 设置本方参与的用户<b class="arrow"></b></a>
        <div class="bar">
        	<div class="innerBar" style="width:100%;">
                <b class="ibgL"></b><b class="ibgR"></b>
            </div>
            <b class="bgL"></b><b class="bgR"></b>
        </div>
    </div>
    <dl class="ldapSetCont">
        <dd style="padding: 10px;">
        	<table width="100%" class="table1" style="width: 680px;">
          <tr>
            <td scope="col">选择员工</td>
            <td scope="col">&nbsp;</td>
            <td scope="col">已指定员工</td>
          </tr>
          <tr>
            <td><div class="combo searchBox" style="margin-bottom: 10px;">
                    <b class="bgR"></b>
                    <a class="icon"></a>
                    <label class="label">通过关键字查找</label>
                    <input class="input" style="width: 274px;" />
                </div>
                <div class="treeLeft" style="width:312px">
                    <ul class="ztree" id="org_treeLeft"></ul>
                </div>
            </td>
            <td>
            	<a  onclick="addToRight()" class="btn"><span class="text">添加 ></span><b class="bgR"></b></a> <br /><br />
                <a  onclick="deleteToLeft()" class="btn"><span class="text">< 删除</span><b class="bgR"></b></a> 
            </td>
            <td><div class="treeRight" style="width: 240px">
                	
                </div></td>
          </tr>
        </table>
        </dd>
    </dl>
    
	<div class="toolBar2">
    	<a class="btnGray btn fl" href="javascript:loadPage('<?php echo site_url('ecologycompany/quitSetEcologyCompany')?>','main');"><span class="text" style="cursor: pointer">放弃</span><b class="bgR"></b></a>
		<a class="btnGray btn" href="javascript:loadCont('<?php echo site_url('ecologycompany/setCompanyAdmin')?>');"><span class="text"style="cursor: pointer">上一步</span><b class="bgR"></b></a>
		<a class="btnBlue" href="javascript: setStqySuccess();"><span class="text" style="cursor: pointer">完成设置</span><b class="bgR"></b></a>
	</div>
</div>
<div id="checking">
	<span>验证设置中，请稍候...</span>
</div>
<script type="text/javascript" src="<?php echo base_url('public/js/jquery.ztree.all-3.5.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/js/self_tree.js');?>"></script>
<script type="text/javascript">
var path="<?php echo site_url('organize/get_next_OrgList');?>";//要加载的每个组织结构
function addToRight() {
    var zTree = $.fn.zTree.getZTreeObj("org_treeLeft");
    var Node=zTree.getSelectedNodes();
    if(Node==null)
    {
        alert("请选中要指定的员工，再添加");
        return false;
    }
    //var path_getOrg_staff;
    var fold_staff='';
    $('.treeRight a').removeClass("selected");
    for(var i=0;i<Node.length;i++)
    {
        //alert(Node[i].userCount)
        if(Node[i].userCount>0)//有员工
        {
            var obj={
                "org_id":Node[i].id,
                "type":1
            }
            $.post(cost_get_staff,obj,function(data)
            {
                //alert(data)
                var json=$.parseJSON(data);
                if(json.code==0)
                {
                    //var json=$.parseJSON(data);
                    var node=json.other_msg.users;
                    var childNodes=eval('(' +node+ ')');
                    //alert(node)
                    //alert(childNodes.length)
                    var leng=childNodes.length;
                    for(var j=0;j<leng;j++)
                    {
                        if(childNodes[j].identity==1)
                        {
                            var common_staff=0;
                            var a_leng=$('.treeRight a');
                            if(a_leng.length>0)
                            {
                                $('.treeRight a').each(function(){
                                    if($(this).attr("id")==childNodes[j].id && $(this).attr("class")==childNodes[j].pId)
                                    {
                                        $(this).addClass("selected");
                                        common_staff=1;

                                    }
                                })
                            }
                            if(common_staff!=1)
                            {
                                fold_staff='<a id='+childNodes[j].id+' class='+childNodes[j].pId+' style="cursor: pointer">'+childNodes[j].name+'</a>';							//alert(fold_staff)
                                $('div.treeRight').append(fold_staff);
                            }
                        }

                    }

                }
            })
        }
        else//没有有员工，选中的是组织或者是员工
        {
            if(Node[i].identity==1)//选中的为员工
            {
                var common_staff=0;
                var a_leng=$('.treeRight a');
                if(a_leng.length>0)
                {
                    $('.treeRight a').each(function()
                    {
                        if($(this).attr("id")==Node[i].id && $(this).attr("class")==Node[i].pId)
                        {
                            $(this).addClass("selected");
                            common_staff=1;

                        }

                    })
                }
                if(common_staff!=1)
                {
                    fold_staff='<a id='+Node[i].id+' class='+Node[i].pId+' style="cursor: pointer">'+Node[i].name+'</a>';
                    $('div.treeRight').append(fold_staff);
                }
            }
        }
        zTree.cancelSelectedNode(Node[i]);
    }
}

function deleteToLeft(){
	$(".treeRight a.selected").remove();
}
function setStqySuccess() {
	/*$("#checking").show();
	var clr = setTimeout(function(){
		loadCont('组织与帐号_LDAP同步5.html');	
		clearTimeout(clr);
	},2000)*/
	var staff='';
	$('.treeRight a').each(function()
	{
	   staff=staff+'{id:'+$(this).attr("id")+',name:'+$(this).text()+'},'
	})
	staff=DelLastComma(staff);
	/*if(staff!='')
	{
	  var path;
	  var obj={
	      "staff_message":staff
	  }
	  $.post(path,obj,function(data)
	  {
	     //alert(1);
	  })
	}*/
	var hash = location.hash;
	if(hash == "#company"){
		loadPage('<?php echo site_url('ecologycompany/ecologyInfoPage')?>','company');
	}
	else {
		loadPage('init-stqy3.html','companyAdmin');
	}
}
function create_node(Nodes)
{
    var leng=Nodes.length;
    //alert(Len)
    // alert(leng)

    for(var i=0; i<leng;i++)
    {
        if(Nodes[i].childNodeCount>0 )
        {   var count=0;
            Nodes[i].nocheck=false;
            for(var j=0;j<leng;j++)
            {
                if(Nodes[i].id==Nodes[j].pId)
                {
                    // alert(Nodes[i].name)
                    if(Nodes[j].name=='')
                    {
                        Nodes[j].nocheck=true;
                    }
                    count++;
                }
            }
            if(count==0)
            {
                //alert(count)
                var node={id:1,pId:Nodes[i].id,name:'',nocheck:true};
                Nodes.push(node);
            }
            //Nodes[i].=false;
        }
        else
        {
            if(Nodes[i].name=='')
            {
                Nodes[i].nocheck=true;
            }else
            {
                Nodes[i].nocheck=false;
            }

        }
    }
}
/*var treeNodeText3 = [
		{'text':'海尔手机电子事业部', 
		 'children':[{'text':'研发部','users':[{'name':"李想",'id':"1"},{'name':"卢志新",'id':"2"},{'name':"全斌"}]},{'text':'市场部','users':[{'name':"王志良"},{'name':"黄凯"},{'name':"董向然"}]}
		]},
		{'text':'海尔生活家电事业部'},
		{'text':'海尔电脑事业部'}
	];
	function createNode3(){
		  var root = {
			"id" : "c30",
			"text" : "海尔",
			"value" : "86",
			"showcheck" : false,
			"complete" : true,
			"isexpand" : true,
			"checkstate" : 0,
			"hasChildren" : true
		  };
		  var arr = [];
		  for(var i=0;i<treeNodeText3.length; i++){
			var subarr = [];
			if(treeNodeText3[i]['children']){
				for(var j=0;j<treeNodeText3[i]['children'].length;j++){
					var userarr = [];
					if(treeNodeText3[i]['children'][j]['users']) {
						for(var g=0;g<treeNodeText3[i]['children'][j]['users'].length; g++){
							var value = "c3-node-" + i + "-" + j+"-"+g; 
							userarr.push({
								"id" : value,
								 "text" : treeNodeText3[i]['children'][j]['users'][g]['name'],
								 "value" : value,
								 "showcheck" : false,
								 "complete" : true,
								 "isexpand" : false,
								 "checkstate" : 0,
								 "hasChildren" : false
							})
						}
					}
				  var value = "c3-node-" + i + "-" + j; 
				  subarr.push( {
					 "id" : value,
					 "text" : treeNodeText3[i]['children'][j]['text'],
					 "value" : value,
					 "showcheck" : false,
					 "complete" : true,
					 "isexpand" : true,
					 "checkstate" : 0,
					 "hasChildren" : userarr.length?true:false,
					 "ChildNodes" : userarr
				  });
				}
			}
			arr.push( {
			  "id" : "c3-node-" + i,
			  "text" : treeNodeText3[i]['text'],
			  "value" : "c3-node-" + i,
			  "showcheck" : false,
			  "complete" : true,
			  "isexpand" : true,
			  "checkstate" : 0,
			  "hasChildren" : subarr.length?true:false,
			  "ChildNodes" : subarr
			});
		  }
		  root["ChildNodes"] = arr;
		  return root; 
		}*/
	$(function(){
		//var treedata3 = [createNode3()];
        var org_zNodes=[];
        var org_first_path ='<?php echo site_url('organize/get_first_org_user')?>';
        $.post(org_first_path,[],function(data)
        {
            //alert(data)
            org_zNodes=eval('(' +data + ')');
            create_node(org_zNodes);
            //$.fn.zTree.init($("#costtreeLeft"),costSetting, org_zNodes);
            $.fn.zTree.init($("#org_treeLeft"),companySetting,org_zNodes);
            //alert(cost_zNodes)
        })		;
		/*$(".ldapSetCont .treeLeft").treeview({
			showcheck:true,
			data:treedata3
		});*/
		$(".treeRight a").die("click");
		$(".treeRight a").live("click",function(){
			$(this).addClass("selected");	
		})
			
		$('.searchBox .icon').click(function()
		{ 
		   $('.treeLeft div').each(function()
		   {
		     
		     if($(this).attr("title")==$('.searchBox input').val())
			    {
				   $(this).addClass("bbit-tree-selected");
				}
		   })
		})
	})	
	
</script>
</body>
</html>