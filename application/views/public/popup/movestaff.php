<dl class="dialogBox D_addAccounts">
	<dt class="dialogHeader"> <span class="title">调岗员工</span> <a  class="close" onclick="hideDialog();"></a> </dt>
	<dd class="dialogBody"> <span class="text01">请选择想要调岗的部门</span>
		<div style="height: 200px; margin: 10px 0 0; border: 1px solid #ddd; background: #fff; overflow: auto">
		<!--<div class="pop-box-content">-->
		<ul class="ztree" id="dgmoveorg">
                    <li id="ztree_1" class="level0" tabindex="0" hidefocus="true" treenode="">
                        <?php
                            $baseJson = $org_json;
                            //print_r($baseJson);
                            foreach ($baseJson as $k0=>$org){
                                echo '<a class="noteBtn level0" org_id="'.$org['org_id'].'" parent_id="'.$org['parent_id'].'" title="'.$org['org_name'].'" node_code="'.$org['node_code'].'">
                                            <span class="button level0 switch noline_open"></span>
                                            <span>'.$org['org_name'].'</span>
                                        </a>';
                                $child_org = $org['dept_list'];
                                if(count($child_org)>0){
                                    echo '<ul id="ztree_1_ul" class="level0 ">';
                                    $k_start = 2;
                                    foreach($child_org as $k=>$c_org){
                                        $k += $k_start;
                                        if($c_org['count']>0){
                                            $icoClassName = 'noline_close';
                                        }  else {
                                            $icoClassName = 'noline_docu';
                                        }
                                        echo '<li id="ztree_'.$k.'" class="level1" tabindex="0">
                                                <a class="noteBtn" org_id="'.$c_org['org_id'].'" parent_id="'.$c_org['parend_id'].'" title="'.$org['org_name'].'->'.$c_org['org_name'].'" node_code="'.$c_org['node_code'].'">
                                                    <span style="display: inline-block;width:'.(15*($k0+1)).'px"></span>
                                                    <span class="button level1 switch '.$icoClassName.'"></span>
                                                    <span>'.$c_org['org_name'].'</span>
                                                </a>
                                            </li>';
                                    }
                                    echo '</ul>';
                                }

                            }

                        ?>

                        </li>
		</ul>
		<!--</div>-->
		</div>
	</dd>
	<dd class="dialogBottom"> <a class="btn yes"  id="move_staff_part"><span class="text">调岗到该部门</span><b class="bgR"></b></a> <a class="btnGray btn btn_cancel" onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a> </dd>
</dl>
<script type="text/javascript">
var key=0;
 $(".btnChangeUser_O").removeClass("false");
function disable_select(t)
{
    //alert(1);
 	var oid = t.attr("org_id");
	if(oid != null)
	{   
            $('#dgmoveorg a').removeClass("curSelectedNode");
            t.addClass("curSelectedNode");
            var oldObj = getSelectNode();
            
	   if(oid==oldObj.oid)
	    {
		  //alert(oid);
                  $('#dgmoveorg a').removeClass("curSelectedNode");
                  alert("请选择与当前不同的部门");
	    }
	}
}

$(function(){
	$('#dgmoveorg a').die("click");
	$('#dgmoveorg a').live("click",function()
          {   
              var _this = $(this);
              disable_select(_this);
          });
		
		
//            $(window).keydown(function(e){
//                   if(e.ctrlKey){
//                           key=1;
//                   }else if(e.shiftKey){
//                           key=2;
//                   }
//           }).keyup(function(){
//                           key=0;
//           });
			
});

	
</script>
