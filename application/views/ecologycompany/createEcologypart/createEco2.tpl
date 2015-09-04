<div class="ldapSetBox" id="creater_two" style="display:none" target="2">
		<dl class="ldapSetCont">
			<dd style="padding: 10px;">
				<div class="setStqy" id="setS2">
					{$j=1}
					{$length=$permissions_label_name|@count}
					{foreach from=$permissions_label_name key=key item=item}
						{$name=$item}
						{if $length==$j} 
							<label class="checkbox checked" target="{$key}">
								<input type="checkbox" checked="checked" />{$name}
							</label>
						{else}
							<label class="checkbox checked" target="{$key}">
								<input type="checkbox" checked="checked" />{$name}
							</label>
							<br />
						{/if}
					{/foreach}
				</div>
			</dd>
		</dl>
</div>
<script type="text/javascript" src="public/js/part_js/createEco2.js"></script>
<script type="text/javascript">
//var company_ecol_id = '<?php echo $org_id ; ?>';
var cost_get_staff ='organize/get_next_orguser_list'; //组织结构和成本中心部分的调入员工
var obj2_json = '';
var obj2 = {};
</script>
	