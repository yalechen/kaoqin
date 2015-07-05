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
		{foreach $data as $item}
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
<script type="text/javascript">
//批量选中
$("#checkAll").click(function()
{
    if ($(this).parent().hasClass('checked')) {
        $(".checkboxes").parent().removeClass('checked');
    } else {
        $(".checkboxes").parent().addClass('checked');
    }
});

//指派确认
function assignUser(user_id, dept_id){
	var obj=$(this);
	if( user_id > 0 && dept_id>0){
		$.ajax({
			type: 'POST',
	        url: '{route("AssignUsers")}',
	        data: { user_id : user_id, dept_id : dept_id },
	        dataType: 'json',
	        success: function(data) {
	        	//更改本行事件为取消指派
	        	//obj.attr("onclick","javascript:void(0);");
	        	//obj.html('<i class="icon-login"></i> 已指派');
	        	//alert(obj.parent().html());
	        	alert("指派成功");
	        }
	    });
	}else{
		alert("参数错误！用户ID和被指派部门ID不能为空");
	}
}
</script>