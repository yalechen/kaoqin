{extends file='Admin/layout.tpl'} 

{block title}用户管理{/block} 
{block breadcrumb}
<li class="active"><a href="{route('UserIndex')}">用户管理</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<form class="form-inline" role="form" action="{Route('UserIndex')}" method="get">
					<div class="form-group">
						<label class="sr-only" for="key">名称</label>
                        <input type="text" class="form-control" id="name" name="key" value="{$smarty.get.key}" placeholder="用户名\姓名\手机号\邮箱">
                    </div>
					<button type="submit" class="btn btn-info"><i class="fa fa-search"></i> 查询</button>
					<a href="{route('UserEdit')}"><button type="button" class="btn btn-success"><i class="fa fa-plus"></i> 添加用户 </button></a>
				</form>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>编号</th>
						<th>用户名</th>
						<th>姓名</th>
						<th>手机号</th>
						<th>email</th>
						<th>所属部门</th>
						<th>状态</th>
						<th>创建日期</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{if $data}
						{foreach $data as $item}
						<tr>
							<td>{$item.id}</td>
							<td>{$item.number}</td>
							<td>{$item.name}</td>
							<td>{$item.realname}</td>
							<td>{$item.mobile}</td>
							<td>{$item.email}</td>
							<td>{if $item.dept_id gt 0}{$item->dept->name}{else}<span class="toggle-status label label-danger">未指派</span>{/if}</td>
							<td><span class="toggle-status label {if $item.status eq constant('App\Models\User::STATUS_OFF')}label-danger{else}label-success{/if}" data-id="{$item.id}" data-status="{$item.status}">{trans('user.status.'|cat:$item.status)}</span></td>
							<td>{$item.created_at|date_format:"%Y-%m-%d"}</td>
							<td>
								<a class="btn btn-sm btn-success" href="{route('UserAssignCust', ['user_id'=>$item.id])}"><i class="icon-star"></i> 巡店</a>
								<a class="btn btn-sm btn-primary" href="{route('UserEdit', ['id'=>$item.id])}"><i class="icon-pencil"></i> 编辑</a>
								<a class="btn btn-sm btn-danger" data-toggle="modal" href="#DeleteConfirmModal" onclick="deleteConfirm({$item.id}, '{$item.name}')"><i class="icon-trash"></i> 删除</a>
							</td>
						</tr>
						{/foreach}
					{/if}
				</tbody>
			</table>
			<div class="text-center">
				{$data->appends(['key' => $smarty.get.key])->render()}
			</div>
		</section>
	</div>
</div>

<!-- Modal data-toggle="modal" href="#AssignCustModal"  -->
<div class="modal fade" id="DeleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">删除确认</h4>
            </div>
            <div class="modal-body">
                <p>您确定要删除“<strong id="user_name"></strong>”用户吗？</p>
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('UserDelete')}">
	            	<input type="hidden" id="id" name="id" value="" >
	                <button class="btn btn-danger" type="submit"> 确认</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- modal -->

<!-- Modal -->
<div class="modal fade" id="AssignCustModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">指派用户的巡查商户门店</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group ">
                        <label class="control-label col-md-4">关键字</label>
                        <div class="col-md-8">
                            <input size="16" type="text" value="2012-06-15 14:45" readonly class="form_datetime form-control">
                            <span class="help-block">Select datetimepicker </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- modal -->
{/block} 

{block script} 
<script type="text/javascript">
//删除确认
function deleteConfirm(id, name){
	$("#id").val(id);
	$("#user_name").text(name);
}

//更换状态
$(".toggle-status").click(function() {
    var id = $(this).attr('data-id');
    var status = $(this).attr('data-status');
    if (id>0) {
        var title = '在职';
        if (status == '{constant('App\Models\Dept::STATUS_ON')}') {
            title = '离职';
        }
        iconfirm('确认要将此用户更新为'+title+'吗？', function() {
            $.post('{route('UserStatusChange')}', { id : id, status: status }, function(data) {
                window.location.reload();
            }, 'text');
        });
    }
});
</script>
{/block}
