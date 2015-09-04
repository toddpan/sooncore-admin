<div class="cont-wrapper">
<ul class="infoNav">
	<li class="selected">企业信息</li>
	<li>企业权限</li>
	<li>企业员工</li>
	<li>生态员工</li>
</ul>
<dl class="infoCont">
</dl>
</div>
<script type="text/javascript">
var path_company = 'ecologycompany/info2_qiye';
var zTree = $.fn.zTree.getZTreeObj("stqyTree");
nodes = zTree.getSelectedNodes();
treeNode = nodes[0];
//alert(treeNode.pId)
var obj = {
    "org_id": treeNode.id
}

$.post(path_company, obj,
function(data) {

    $('.infoCont').append(data);
}) 
$('.infoNav li').click(function() {
    $('.infoNav li').removeClass("selected");
    $(this).addClass("selected");
    var zTree = $.fn.zTree.getZTreeObj("stqyTree");
    nodes = zTree.getSelectedNodes();
    treeNode = nodes[0];
    //alert(treeNode.pId)
    var obj = {
        "org_id": treeNode.id
    }
    if ($('.infoNav li:eq(0)').hasClass("selected")) {

        //alert(data)
        $('.infoCont dd').hide();
        $('.infoCont dd:eq(0)').show();

    } else if ($('.infoNav li:eq(1)').hasClass("selected")) {
        if ($('.infoCont dd').hasClass("right")) {
            $('.infoCont dd').hide();
            $('.infoCont dd.right').show();
        } else {
            var path = 'ecologycompany/info2_power';
            $.post(path, obj,
            function(data) {
                //alert(data)
                $('.infoCont dd').hide();
                $('.infoCont dd.qiye').after(data);
                $('.infoCont dd.right').show();
            })
        }
    } else if ($('.infoNav li:eq(2)').hasClass("selected")) {
        if ($('.infoCont dd').hasClass("staff")) {
            $('.infoCont dd').hide();
            $('.infoCont dd.staff').show();
        } else {
            var path = 'ecologycompany/info2_staff';
            $.post(path, obj,
            function(data) {
                //alert(data)
                $('.infoCont dd').hide();
                $('.infoCont dd.qiye').after(data);
                $('.infoCont dd.staff').show();
            })
        }
    } else if ($('.infoNav li:eq(3)').hasClass("selected")) {
        if ($('.infoCont dd').hasClass("ecol_staff")) {
            $('.infoCont dd').hide();
            $('.infoCont dd.ecol_staff').show();
        } else {
            var path = 'ecologycompany/info2_ecol_staff';
            $.post(path, obj,
            function(data) {
                //alert(data)
                $('.infoCont dd').hide();
                $('.infoCont dd.qiye').after(data);
                $('.infoCont dd.ecol_staff').show();
            })
        }
    }
    //alert(treeNode.id)
    /*var path_first='<?php echo site_url('ecologycompany/info'); ?>';
		$.post(path_first,obj,function(data)
		{
		//alert(data)
			$('.part01_1 .bread').after(data);
		})*/

})
</script>