<table class="table table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>编号</th>
			<th>用户名</th>
			<th>姓名</th>
			<th>手机号</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody id="UsersList">
		{if $data}
			{foreach $data as $item}
			<tr>
				<td>{$item.id}</td>
				<td>{$item.number}</td>
				<td>{$item.name}</td>
				<td>{$item.realname}</td>
				<td>{$item.mobile}</td>
				<td>
					<a class="btn btn-sm btn-warning" href="javascript:void(0)" onclick="assignParentUser({$item.id}, {$user_id})"><i class="icon-login"></i> 指派</a>
				</td>
			</tr>
			{foreachelse}
				<tr><td colspan="6" class="text-center">无相关数据！</td></tr>
			{/foreach}
		{/if}
	</tbody>
</table>
{if $data}
<div class="text-center">
	{$data->render()}
</div>
{/if}
<script type="text/javascript">
//删除确认
function assignParentUser(parent_user_id, user_id){
	alert($(this).text());
	var obj=$(this);
	if( parent_user_id > 0 && user_id>0){
		$.ajax({
			type: 'POST',
	        url: '{route("AssignParentUsers")}',
	        data: { parent_user_id : parent_user_id, user_id : user_id },
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
		alert("参数错误！上级用户ID和被指派用户ID不能为空");
	}
}
</script>