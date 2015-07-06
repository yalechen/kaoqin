<table class="table table-hover">
	<thead>
		<tr>
			<th><input type="checkbox" data-set=".checkboxes" id="checkAll" /></th>
			<th>#</th>
			<th>编号</th>
			<th>用户名</th>
			<th>姓名</th>
			<th>手机号</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<script type="text/javascript">
		var ids = new Array();
		</script>
		{foreach $data as $key=>$item}
		<script type="text/javascript">
		ids.push({$ids.$key});
		</script>
		<tr>
			<td><input type="checkbox" class="checkboxes" value="{$item.id}" /></td>
			<td>{$item.id}</td>
			<td>{$item.number}</td>
			<td>{$item.name}</td>
			<td>{$item.realname}</td>
			<td>{$item.mobile}</td>
			<td>
				<a class="btn btn-sm btn-warning" href="javascript:void(0)" onclick="assignUser({$item.id}, {$dept_id})"><i class="icon-login"></i> 指派</a>
			</td>
		</tr>
		{foreachelse}
			<tr><td colspan="6" class="text-center">无相关数据！</td></tr>
		{/foreach}
	</tbody>
</table>
<input type="hidden" name="ids" id="ids" value=""/>
<script type="text/javascript">
//批量选中
var ids_clone=ids;
$("#checkAll").click(function()
{
    if ($(this).attr("checked")) {
    	$(this).prop('checked',true);
        $(".checkboxes").prop('checked',true);
        
        $("#ids").val(ids_clone);
    } else {
    	$(this).prop('checked',false);
        $(".checkboxes").prop('checked',false);
        //清空ids
        var ids=new Array();
        $("#ids").val(ids);
    }
});

//单个复选框选择时，移除和添加元素
$(".checkboxes").click(function(){
	nids=new Array();
	$(".checkboxes").each(function(){
		if($(this).attr("checked")){
			nids.push($(this).val());
			$("#ids").val(nids);
		}
	});
});

//指派确认
function assignUser(user_id, dept_id){
	var obj=$(this);
	if( user_id > 0 && dept_id>0){
		$.ajax({
			type: 'POST',
	        url: '{route("AssignUsers")}',
	        data: { user_id : user_id, dept_id : dept_id },
	        dataType: 'text',
	        success: function(data) {
	        	alert("指派成功");
	        },
	        error:function(xhq){
	        	alert("指派失败");
	        }
	    });
	}else{
		alert("参数错误！用户ID和被指派部门ID不能为空");
	}
}

if(ids){
	
}
</script>