<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">添加生态管理员</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<table width="100%" class="table1">
          <tr>
            <th scope="col">选择员工</th>
            <th scope="col">&nbsp;</th>
            <th scope="col">已指定员工</th>
          </tr>
          <tr>
            <td><div class="combo searchBox" style="margin-bottom: 10px;">
                    <b class="bgR"></b>
                    <a class="icon" ></a>
                    <label class="label">通过关键字查找</label>
                    <input class="input" />
                </div>
                <div class="treeLeft">
                
                </div>
            </td>
            <td>
            	<a  onclick="addToRight()" class="btn"><span class="text">添加 ></span><b class="bgR"></b></a> <br /><br />
                <a  onclick="deleteToLeft()" class="btn"><span class="text">< 删除</span><b class="bgR"></b></a> 
            </td>
            <td><div class="treeRight">
                	
                </div></td>
          </tr>
        </table>

	</dd>
   
	<dd class="dialogBottom">
		<a class="btnBlue yes btn_confirm"  onclick="addStAdmin();"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
function importNewMembers(){
	$("#novalueTable").hide().prev("table").show();
	$("#tree .bbit-tree-selected").parent("li").removeClass("new-node");
	hideDialog();
}
function addToRight() {
	 var $this = $(".treeLeft .bbit-tree-selected");
	 if($this.length == 0) {
			return false; 
	 }
	//$(".bbit-tree-selected").each(function(index, element) {
		var _haveChildren = $this.next().is("ul");
		if(_haveChildren){
			$this.next("ul").find(".bbit-tree-node-leaf").each(function(i){
				var _title = $(this).attr("title"); 
				$(".treeRight").append('<a >'+ _title +'</a>')	
			})
		}
		else {
			var _title = $this.attr("title"); 
			$(".treeRight").append('<a >'+ _title +'</a>')
		}    
		$(".treeLeft .bbit-tree-selected").removeClass("bbit-tree-selected");
	// });	
}

function deleteToLeft(){
	$(".treeRight a.selected").remove();
}

function addHmMember(){
	hideDialog(); 
	$('.con-wrapper').eq(2).find('.table').show(); 
	$('.con-wrapper').eq(2).find('p:last').css("text-align","right");
	$('#hmMsg').hide();	
}

function addStAdmin(){
	
	$(".treeRight a").each(function(index, element) {
		var name = $(this).text();
        $(".tree").append('<li>'+
                        '<a class="treeNode selected"  style="padding-left: 6px;">'+
                        '    <span class="treeNodeName">'+name +'</span>'+
                        '</a>'+
                    '</li>');
		$(".delStAdmin").removeClass("disabled");
    });
	
	hideDialog(); 
	
}
var treeNodeText = [
		{'text':'海尔手机电子事业部', 
		 'children':[{'text':'研发部','users':[{'name':"李想"},{'name':"卢志新"},{'name':"全斌"}]},{'text':'市场部','users':[{'name':"王志良"},{'name':"黄凯"},{'name':"董向然"}]},{'text':'营销部','users':[{'name':"吴泽坤"}]}
		]},
		{'text':'海尔生活家电事业部'},
		{'text':'海尔电脑事业部'}
	];
	function createNode(){
		  var root = {
			"id" : "0",
			"text" : "海尔",
			"value" : "86",
			"showcheck" : false,
			"complete" : true,
			"isexpand" : true,
			"checkstate" : 0,
			"hasChildren" : true
		  };
		  var arr = [];
		  for(var i=0;i<treeNodeText.length; i++){
			var subarr = [];
			if(treeNodeText[i]['children']){
				for(var j=0;j<treeNodeText[i]['children'].length;j++){
					var userarr = [];
					if(treeNodeText[i]['children'][j]['users']) {
						for(var g=0;g<treeNodeText[i]['children'][j]['users'].length; g++){
							var value = "node-" + i + "-" + j+"-"+g; 
							userarr.push({
								"id" : value,
								 "text" : treeNodeText[i]['children'][j]['users'][g]['name'],
								 "value" : value,
								 "showcheck" : false,
								 "complete" : true,
								 "isexpand" : false,
								 "checkstate" : 0,
								 "hasChildren" : false
							})
						}
					}
				  var value = "node-" + i + "-" + j; 
				  subarr.push( {
					 "id" : value,
					 "text" : treeNodeText[i]['children'][j]['text'],
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
			  "id" : "node-" + i,
			  "text" : treeNodeText[i]['text'],
			  "value" : "node-" + i,
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
		}
	$(function(){
		var treedata = [createNode()];
				
		$(".treeLeft").treeview({
			showcheck:true,
			data:treedata
		});
		
		$(".treeRight a").live("click",function(){
			$(this).addClass("selected");	
		})
			
	})
</script>