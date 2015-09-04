                    <li>
                        <a class="treeNode" >
                            <span class="treeNodeName">未指定成本中心</span>
                        </a>
                    </li>
					<?php foreach($cost_center_arr as $k => $v):?>
                    <li>
                        <a class="treeNode">	
                            <span class="treeNodeName" style="cursor:default" target="<?php echo $v['id'];?>"><?php echo $v['cost_title'];?></span>
							<span class="centerTreeEditBtn" style="cursor:default" onclick="editThisCenterName(event,this)"></span>
                        </a>
                    </li>
					<?php endforeach;?> 
 
<script type="text/javascript">	
//重命名成本中心
var cost_list = [];
var i = 0;
$('#centerTree li').each(function() {
    cost_list[i] = {
        "name": $(this).find("span:first").text(),
        "id": $(this).find("span:first").attr("target")
    }
    i = i + 1;
}) function editThisCenterName(event, t) {
    $('#centerTree li input').each(function() {
        $(this).parent().after('<span class="centerTreeEditBtn" onclick="editThisCenterName(event,this)"></span>');
        if ($("#centerTree li.selected input").attr("name") == '') {

            $(this).parents("li").remove();
            $(this).parent().next(".centerTreeEditBtn").remove();
            $(this).remove();

        } else {
            $(this).parent(".treeNodeName").html($("#centerTree li.selected input").attr("name"));
            //$(this).parent().after('<span class="centerTreeEditBtn" onclick="editThisCenterName(event,this)"></span>');
        }
    }) 
	$("#centerTree li").removeClass("selected");
    //$(t).parents("li").removeClass("noedit");
    $(t).parents("li").addClass("selected");
    var text = $(t).prev(".treeNodeName").text();
    //alert(text)
    //$(t).prev(".treeNodeName").before('<input type="text" value="'+ text +'" size="20" onblur="sureEditCenter(this)"  />');
    //$(t).prev(".treeNodeName").hide();
    $(t).prev(".treeNodeName").html('<input  display="cursor:default" type="text" name="' + text + '" value="' + text + '" onclick="input_click(event,this)"/>');
    $(t).prev(".treeNodeName").find("input").focus();
    $(t).prev(".treeNodeName").find("input").addClass("cost_input");
    $(t).prev(".treeNodeName").find("input").select();
    $(t).remove();
    //event=event?event:window.event; 
    //event.stopPropagation(); 
    event.cancelBubble = true; //组织点击事件连接到父标签
}
function rename_focus(text, text1) //text1是原始的名称，text是名称
{
    $("#centerTree li.selected .treeNodeName").html('<input type="text"  name="' + text1 + '" value="' + text + '" size="20" onclick="input_click(event,this)" />');
    //var _t=
    //input_click(event,_t);
    $("#centerTree li.selected input").focus();
    //alert($("#centerTree li.selected input").attr('class'))
    //$("#centerTree li.selected input.two").addClass("costinput");
    $("#centerTree li.selected input").addClass("cost_input");

}
function input_click(event, t) {
    $(t).focus();

    //$(t).attr("id","select");
    $(t).empty();
    $(t).css("cursor", "default");
    /*var range = document.body.createTextRange(); //建立文本选区
	//alert(range.text)
    range.moveStart('character', document.getElementById("select").value.length); //选区的起点移到最后去
    range.collapse(true); 
    range.select();*/
    /*var textbox=document.all("input");
	var r=textbox.createTextRange();
	r.collapse(true);
	r.moveStart('character',pos);
	r.select();*/
    $(t).addClass("cost_input");
    event.cancelBubble = true; //组织点击事件连接到父标
}
//重命名完成后
function sureEditCenter(t) {
    //alert(12154)
    //$(t).focus();
    //var $(t)=$('.cost_input');
    var text = $(t).val();
    //alert(text)
    //alert(text)
    var text1 = $(t).parent(".treeNodeName").attr("name");
    if (text1 == undefined) {
        text1 = '新成本中心';
    }
    //alert(text1)
    if (text == "") {
        //$(t).focus();
        alert("请输入新的成本中心名称");
        rename_focus(text, text1);
        //editThisCenterName(t);
        return false;
    }
    var count = 0;
    var cancel = 0;
    $('#centerTree li').each(function() {

        if ($(this).find('span:first').text() != '') {
            if ($(this).find('span:first').text() == text) {
                //$(t).focus();
                alert("您输入的成本中心名称已存在，请重新输入!");
                rename_focus(text, text1);
                //editThisCenterName(t);
                count++;
                return false;
            }
        } else {
            if ($(t).attr("name") == text) {
                cancel = 1;
            }
        }
    })
    /*if(count==0&&$(t).parent(".treeNodeName").attr("target")==00)
	{
		$(t).parent(".treeNodeName").after('<span class="centerTreeEditBtn" onclick="editThisCenterName(this)"></span>')
		$(t).parent(".treeNodeName").html(text);
		sureAddCenter_O(t);
	}*/
    if (count == 0) {

        if (cancel == 0 || $(t).parent(".treeNodeName").attr("target") == 00) {
            var cost_id = $(t).parent(".treeNodeName").attr("target");
            if ($(t).parent(".treeNodeName").attr("target") == 00) {
                //alert(32423)
                cost_id = 0;
            }
            var path = 'costcenter/add_modify_cost';
            var obj = {
                "cost_title": text,
                "cost_id": cost_id
            }
            //alert(obj.cost_title)
            //alert(obj.cost_id)
            $.post(path, obj,
            function(data) {
                //alert(data);
                //alert(12)
                var json = $.parseJSON(data);
                if (json.code == 0) {
                    //alert(data)
                    if ($(t).parent(".treeNodeName").attr("target") == 00) {
                        //alert(11)
                        $(t).parent(".treeNodeName").attr("target", 'json.other_msg.new_cost_id');
                        var cost = {
                            "name": text,
                            "id": json.other_msg.new_cost_id
                        }
                        cost_list.push(cost);
                        sureAddCenter_O(t);
                    } else {
                        for (var i = 0; i < cost_list.length; i++) {
                            if (cost_list[i].id == cost_id) {
                                cost_list[i].name = text;
                            }
                        }
                    }
                    $(t).parent(".treeNodeName").after('<span class="centerTreeEditBtn" onclick="editThisCenterName(event,this)"></span>') $(t).parent(".treeNodeName").html(text);
                    //$(t).hide();
                    //$(t).next().show();
                    //alert($(t).parent(".treeNodeName").attr("target"))
                }
				else
				   {
				   		alert(json.prompt_text);
				   }
            })

        }
    }
    if (count == 0 && cancel == 1 && $(t).parent(".treeNodeName").attr("target") != 00) {
        $(t).parent(".treeNodeName").after('<span class="centerTreeEditBtn" onclick="editThisCenterName(event,this)"></span>') $(t).parent(".treeNodeName").html(text);
    }
    //event.cancelBubble=true;
}

//增加成本中心
function addCenter(event) {
    $("#centerTree li").removeClass("selected");
    $("#centerTree").append('<li class="selected"><a class="treeNode"	style="cursor:default">' + '<span class="treeNodeName"	target="00"><input	class="cost_input"  display="cursor:default" type="text" name="" value="新成本中心 " onclick="input_click(event,this)"/></span></a></li>');
    $('#centerTree input').select();

    //var _t=$('#centerTree li.selected span:last');<li class="selected"> 
    /* ' <span class="treeNodeName"	style="cursor:default" target="00">新成本中心'+
                   '</span> <span class="centerTreeEditBtn" style="cursor:default"	onclick="editThisCenterName(event,this)">'+
				   '</span></a> </li>
	//editThisCenterName(event,_t)
	//$("#centerTree li.selected input").focus();	*/
}
//确定添加成本中心的弹窗提醒
function sureAddCenter_O(t) {
    //alert(124)
    var val = $(t).val();
    //alert(val)
    if (val == "") {
        deleteCenter();
    } else {
        $(t).parent("li").html('<a class="treeNode" ><span class="treeNodeName">' + val + '</span></a>');
        $("#part02 tbody tr").hide();
        $("#part02 .page").hide();
        $(".deleteCenter").removeClass("disabled");
        showDialog('costcenter/addCostCenterStaff');

    }
}
$(function() {
    $(document).click(function(e)

    {
        //debugger
        //alert(11)
        var t = $(e.target)
        //alert($('input').attr('class'))
        //alert($('input').hasClass('two'))
        //alert($('input').hasClass('cost_input'))
        if (!t.hasClass('cost_input') && !t.hasClass('addGroup') && $('input').hasClass('cost_input')) {
            //alert(12)
            $('.cost_input').addClass('cost');
            $('input.cost_input').removeClass("cost_input") _t = $('.cost');
            sureEditCenter(_t);

        }
    })

    //成本中心的部门层级展示 加载该成本中心下的员工
    /* $('#centerTree li .treeNodeName input').blur(function()
	{
	  //alert(324234)
	})
	$('.treeNodeName input').focus(function()
	{
		$(this).parent().next().remove();
	})*/
    /*.blur(function()
	{
		//alert(34432);
	})*/
    $('#centerTree li').click(function() {
        //if(!$(this).hasClass("edit"))
        // {//$('#part02 .bread').find("span").remove();
        var value = $(this).find('span').eq(0).text();
        $('#part02').find("span").eq(1).text(value);
        $('#part02 .bread').css("visibility", "visible");
        var cost_id = $(this).find("span:first").attr("target");
        if (cost_id == null) {
            cost_id = 0;
        }
        var zTree = $.fn.zTree.getZTreeObj("ztree2");
        var nodes = zTree.getSelectedNodes();
        var treeNode = nodes[0];
        if (treeNode != null) {
            var org_id = treeNode.id;
        } else {
            var org_id = 0;
        }
        var obj = {
            "cost_id": cost_id,
            "org_id": org_id
        }

        var path = 'costcenter/get_cost_user_list';
        load_staff_center(obj, path, path_mag);
        /* $.post(path,obj,function(data)
				 {
					//alert(data);
					//var json=$.parseJSON(data);
					
					
				 })*/
        /* var obj={
		 	"org_id":
		 }*/
        // load_staff_center(obj,path_user_center,path_mag);
        //$('#test').text(value);
        //$("#test").load('<?php echo site_url('organize/staInfoPowPage')?>');
        //var select_spend='';
        // select_spend='<span>成本中心</span>&nbsp;&gt;&nbsp;<span>'+value+'</span>';
        // $('#part02').find('.bread').append(select_spend);
    })
})
</script>