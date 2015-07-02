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
		{foreach $data as $item}
		<tr>
			<td>{$item.id}</td>
			<td>{$item.number}</td>
			<td>{$item.name}</td>
			<td>{$item.realname}</td>
			<td>{$item.mobile}</td>
			<td>
				<a class="btn btn-sm btn-warning" href="javascript:void(0)" onclick="assignAcceptUser({$item.id},'{$item.realname}')"><i class="icon-login"></i> 指派</a>
			</td>
		</tr>
		{foreachelse}
			<tr><td colspan="6" class="text-center">无相关数据！</td></tr>
		{/foreach}
	</tbody>
</table>
{if $data}
<div class="text-center">
	{$data->render()}
</div>
{/if}
<script type="text/javascript">
//指派临时任务接收者确认
function assignAcceptUser(user_id,user_name){
	var obj=$(this);
	if( user_id){
		$("#accept_user_id").val(user_id);
		$("#accept_user_name").text(user_name);
		$("#close_modal").trigger('click');
	}else{
		alert("参数错误！");
	}
}
</script>