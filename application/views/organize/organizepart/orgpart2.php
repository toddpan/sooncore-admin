<div class="contMiddle" >
    <div class="conTabs">
        <div id="addMoreBox">
            <ul class="addMoreUl">
                <?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER){?>
                <li>
                    <a class="btn addOrg" id="addZuzhi" onclick="addOrg(event)">
                        <em class="ico"></em>
                        <span class="text">添加部门</span>
                    </a>
                </li>
                <li>
                    <a class="btn addUser" onclick="addNewMember_one()">
                        <em class="ico"></em>
                        <span class="text">添加员工</span>
                    </a>
                </li>
                <li>
                    <a class="btn addMore" onclick="javascript:alert('正在开发中');">
                        <em class="ico"></em>
                        <span class="text">批量添加</span>
                    </a>
                </li>
                <?php }?>
                <li>
                    <a class="btn ttYaoqing" onclick="javascript:alert('正在开发中');">
                        <em class="ico"></em>
                        <span class="text">团队邀请</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <dl class="conTabsCont">
                <dd style="display:block;">
                        <div id="tree">
                            <ul class="ztree treeorg" id="ztree">
                                <li class="level0" tabindex="0" hidefocus="true" level="0">
                                <?php
                                    $base_org = $org_json[0];
                                    //print_r($baseJson);
                                    echo '<a class="nodeBtn curSelectedNode" org_id="'.$base_org['id'].'" parent_id="'.($base_org['parentId'] ? $base_org['parentId'] : 0).'" title="'.$base_org['name'].'" node_code="'.$base_org['nodeCode'].'">
                                                <span class="button level0 switch noline_open"></span>
                                                <span>'.$base_org['name'].'</span>
                                            </a>';
                                    
                                    $child_org = array_slice($org_json,1); //排除首级数组
                                    //print_r($child_org);
                                    if(count($child_org)>0){
                                        echo '<ul class="level0" level="0">';
                                        $k_start = 2;
                                        foreach($child_org as $k=>$c_org){
                                            $k += $k_start;
                                            if($c_org['childNodeCount']>0){
                                                $icoClassName = 'noline_close';
                                            }  else {
                                                $icoClassName = 'noline_docu';
                                            }
                                            echo '<li class="level1" tabindex="0" level="1">
                                                    <a class="nodeBtn" org_id="'.$c_org['id'].'" parent_id="'.$c_org['parentId'].'" title="'.$base_org['name'].' > '.$c_org['name'].'" node_code="'.$c_org['nodeCode'].'">
                                                        <span style="margin-left:'.(15*($k0+1)).'px" class="button level1 switch '.$icoClassName.'"></span>
                                                        <span>'.$c_org['count'].$c_org['name'].'</span>
                                                    </a>
                                                </li>';
                                        }
                                        echo '</ul>';
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
