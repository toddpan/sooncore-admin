<div class="contMiddle" >
    <div class="conTabs">
        <dl class="conTabsCont">
                <dd style="display:block;">
                        <div id="tree">
                            <ul class="ztree treeorg" id="ztree">
                                <li id="ztree_1" class="level0" tabindex="0" hidefocus="true" level="0">
                                <?php
                                    $baseJson = $org_json;
                                    //print_r($baseJson);
                                    foreach ($baseJson as $k0=>$org){
                                        echo '<a class="nodeBtn curSelectedNode" org_id="'.$org['org_id'].'" parent_id="'.$org['parent_id'].'" title="'.$org['org_name'].'" node_code="'.$org['node_code'].'">
                                                    <span class="button level0 switch noline_open"></span>
                                                    <span>'.$org['org_name'].'</span>
                                                </a>';
                                        $child_org = $org['dept_list'];
                                        if(count($child_org)>0){
                                            echo '<ul id="ztree_1_ul" class="level0" level="0">';
                                            $k_start = 2;
                                            foreach($child_org as $k=>$c_org){
                                                $k += $k_start;
                                                if($c_org['count']>0){
                                                    $icoClassName = 'noline_close';
                                                }  else {
                                                    $icoClassName = 'noline_docu';
                                                }
                                                echo '<li id="ztree_1_'.$k.'" class="level1" tabindex="0" level="1">
                                                        <a class="nodeBtn" org_id="'.$c_org['org_id'].'" parent_id="'.$c_org['parend_id'].'" title="'.$org['org_name'].' > '.$c_org['org_name'].'" node_code="'.$c_org['node_code'].'">
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
                        </div>
                </dd>
        </dl>
        <span class="contabs-left"></span>
        <span class="contabs-right"></span>
    </div>
</div>
