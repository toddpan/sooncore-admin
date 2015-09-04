<div class="contMiddle" >
	<div class="conTabs">
		<b class="resizeBar" style="z-index:99"></b>
		<ul class="conTabsHead">
			<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER || $this->p_role_id == ACCOUNT_MANAGER){?>
<!-- 			<li class="selected">组织结构<span class="conline"></span></li> 原来的代码 -->
			<li class="selected" style="">组织结构</li>
			<?php }?>
			<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER){?>
<!--  			<li target="1">成本中心</li> -->
			<?php }?>
		</ul>
		<dl class="conTabsCont">
			<dd style="display:block;">
			<?php if($this->functions['orgManage']){ ?>
				<div class="toolBar">
					<a class="addGroup" id="addZuzhi"  style="cursor: pointer" title="添加组织结构"></a>
					<a class="delGroup" id="deleteZuzhi" style="cursor: pointer" title="删除组织结构"></a>
				</div>
			<?php }?>
				<div id="tree">
					<ul class="ztree treeorg" id="ztree"></ul>
				</div>
			</dd>
			<dd>
				<div class="toolBar">
					<a class="addGroup addCenter" id="add_cost" title="添加成本中心"></a>
					<a class="delGroup deleteCenter disabled" onclick="showDeleteCenter(this)" title="删除成本中心"></a>
				</div>
				<div class="tree" id="centerTree" style="display:block;cursor:default">
					<li>
						<a class="treeNode" >
							<span class="treeNodeName">未指定成本中心</span>
						</a>
					</li>
					<ul class="ztree" id="ztreecostcenter"></ul>
				</div>
		 	</dd>
		</dl>
		<span class="contabs-left"></span>
		<span class="contabs-right"></span>
	</div>
</div>