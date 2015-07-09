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
	<tbody>
		{foreach $data as $item}
		<tr>
			<td>{$item.id}</td>
			<td>{$item.number}</td>
			<td>{$item.name}</td>
			<td>{$item.realname}</td>
			<td>{$item.mobile}</td>
			<td>
				<a class="btn btn-sm btn-warning" href="javascript:void(0)" onclick="assignUser({$item.id}, {$cust_id})"><i class="icon-login"></i> 指派</a>
			</td>
		</tr>
		{foreachelse}
			<tr><td colspan="6" class="text-center">无相关数据！</td></tr>
		{/foreach}
	</tbody>
</table>
<script type="text/javascript">
//指派巡店者
function assignUser(user_id, cust_id){
	var obj=$(this);
	if( user_id > 0 && cust_id>0){
		$.ajax({
			type: 'POST',
	        url: '{route("AssignCustUser")}',
	        data: { cust_id : cust_id, user_id : user_id },
	        dataType: 'json',
	        success: function(data) {
	        	alert("指派成功");
	        }
	    });
	}else{
		alert("参数错误！上级用户ID和被指派用户ID不能为空");
	}
}
</script>