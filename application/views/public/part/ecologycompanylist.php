    <!-- end tabToolBar -->
    <table class="table">
      <thead>
        <tr>
          <th width="30"><label class="checkbox">
            <input type="checkbox" />
            </label></th>
          <th>生态企业名称</th>
          <th>所在区域</th>
          <th>企业负责人</th>
          <th>上级生态企业</th>
          <th>生态管理员</th>
        </tr>
      </thead>
      <tbody>	
	<?php foreach($eco_list as $v):?>
        <tr>
          <td><label class="checkbox" name="<?php echo element('id',$v,0);?>"><input type="checkbox" /></label></td>
          <td><a user=""><?php echo element('name',$v,'');?></a></td>
          <td>北京(暂定)</td>
          <td><a ><?php echo element('qudao_manager_name',$v,'');?></a></td>
          <td><a  user="" class="ellipsis"><?php echo element('parent_ecology_name',$v,'');?></a></td>
          <td><a ><?php echo element('ecology_manager_name',$v,'');?></a></td>
        </tr>
   <?php endforeach;?>
      </tbody>
    </table>
    <!-- end table -->
    <div class="page"><a class="disabled" >首页</a> <a
	class="disabled" >上一页</a> <a class="num selected"
	>1</a> <a class="num " >2</a> <a
	class="num " >3</a> <a class="" >下一页</a> <a class="" >尾页</a> <span class="text ml10">第</span>
      <div class="inputBox"><b class="bgR"></b>
        <label class="label"></label>
        <input class="input" value="" />
      </div>
      <span class="text">页/<?php echo $total;?></span>
<script type="text/javascript">
$(function() {
    $('#part02 table tbody td:eq(3) a').click(function() {
        var path_staff_information ='staff/modify_staff_page';
        var obj = {
            "user_id": $(this).attr("name")
        }
        //alert($(this).name);
        $.post(path_staff_information, obj,
        function(data) {

            $('#part02 .page').after(data);
            $('#part02 .tabToolBar').hide();
            $('#part02 .table').remove();
            $('#part02 .page').hide();
        })
    })
    //生态管理员
    $('#part02 table tbody td:eq(5) a').click(function() {
        var path_staff_information ='staff/modify_staff_page';
        var obj = {
            "user_id": $(this).attr("name")
        }
        //alert($(this).name);
        $.post(path_staff_information, obj,
        function(data) {
            //alert(data)
            //$('#part01 div.bread').after(data);
            $('#part02 .page').after(data);
            $('#part02 .tabToolBar').hide();
            $('#part02 .table').remove();
            $('#part02 .page').hide();

        })
    }) 
	$('#part02 .table label.checkbox').die('mouseup');
    $('#part02 .table label.checkbox').live('mouseup',
    function() {
        //alert(2222)
        $('table').removeClass("sel");
        $(this).parents("table").addClass("sel");
        // alert(334);
        var count1 = 0;
        //alert($(this).attr("class"))
        //$(this).attr("target","1");
        if ($(this).parent().next().text() == "生态企业名称") {
            if (!$(this).hasClass("checked")) {
                $(this).addClass("checked");
                $(this).parents("thead").next().find("label.checkbox").addClass("checked");
                $("#part02 .tabToolBar .tabToolBox").show();
            } else {
                $(this).removeClass("checked");
                $(this).parents("thead").next().find("label.checkbox").removeClass("checked");
                $('thead label:first').removeClass("checked");
                $("#part02 .tabToolBar .tabToolBox").hide();
            }
            //alert($(this).parents("thead").next().find("label").attr("class"));
        } else {
            if (!$(this).hasClass("checked")) { //
                $(this).addClass("checked");
                // alert($(this).parentsUntil('tr').parent().siblings().find("label").filter(".checked").length+1)
                // alert(Math.floor(count.length/2-1))
                if ($('#part02 .table tbody tr label.checked').length == $('#part02 .table tbody tr').length) { //alert(2323)
                    $('#part02 .table thead label:first').addClass("checked");
                    ////  j++
                }
                //$(this).parents("thead").next().find("label").addClass("checked");
                $("#part02 .tabToolBar .tabToolBox").show();
            } else {
                // $('#part01 .tabToolBox').hide();

                $(this).removeClass("checked");
                $('#part02 .table thead label:first').removeClass("checked");
                //$(".tabToolBar .tabToolBox").hide();
                if ($('#part02 .table tbody tr label.checked').length == 0) {
                    $("#part02 .tabToolBar .tabToolBox").hide();

                } else {
                    $("#part02 .tabToolBar .tabToolBox").show();
                }
                //$(this).parents("thead").next().find("label").removeClass("checked");
            }
        }
    })
})
</script>>