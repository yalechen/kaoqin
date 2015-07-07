{extends file='Admin/layout.tpl'} 

{block title}角色管理{/block} 
{block breadcrumb}
<li class="active"><a href="{route('RoleIndex')}">角色管理</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<form class="form-inline" role="form" action="{Route('RoleIndex')}" method="get">
					<div class="form-group">
						<label class="sr-only" for="key">名称</label>
                        <input type="text" class="form-control" id="name" name="key" value="{$smarty.get.key}" placeholder="key\名称">
                    </div>
					<button type="submit" class="btn btn-info"><i class="fa fa-search"></i> 查询</button>
					<a href="{route('RoleEdit')}"><button type="button" class="btn btn-success"><i class="fa fa-plus"></i> 添加角色 </button></a>
				</form>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>标识&nbsp;&nbsp;<i data-val="{$smarty.get.key_sort}" data-sort="key_sort" class="key_sort fa {if $smarty.get.key_sort eq 'desc'}fa-sort-amount-desc{elseif $smarty.get.key_sort eq 'asc'}fa-sort-amount-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>名称</th>
						<th>备注</th>
						<th>状态</th>
						<th>创建日期</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{foreach $data as $item}
					<tr>
						<td>{$item.id}</td>
						<td>{$item.key}</td>
						<td>{$item.name}</td>
						<td>{$item.remark|truncate:35:"...":true}</td>
						<td><span class="toggle-status label {if $item.status eq constant('App\Models\Role::STATUS_OFF')}label-danger{else}label-success{/if}" data-id="{$item.id}" data-status="{$item.status}">{trans('role.status.'|cat:$item.status)}</span></td>
						<td>{$item.created_at|date_format:"%Y-%m-%d"}</td>
						<td>
							<a class="btn btn-sm btn-primary" href="{route('RoleEdit', ['id'=>$item.id])}"><i class="icon-pencil"></i> 编辑</a>
							<a class="btn btn-sm btn-danger" data-toggle="modal" href="#DeleteConfirmModal" onclick="deleteConfirm({$item.id}, '{$item.name}')"><i class="icon-trash"></i> 删除</a>
						</td>
					</tr>
					{foreachelse}
						<tr><td colspan="12" class="text-center">无相关数据！</td></tr>
					{/foreach}
				</tbody>
			</table>
			<div class="text-center">
				{$data->appends(['key' => $smarty.get.key])->render()}
			</div>
		</section>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="DeleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">删除确认</h4>
            </div>
            <div class="modal-body">
                <p>您确定要删除“<strong id="role_name"></strong>”角色吗？</p>
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('RoleDelete')}">
	            	<input type="hidden" id="id" name="id" value="" >
	                <button class="btn btn-danger" type="submit"> 确认</button>
                </form>
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
	$("#role_name").text(name);
}

//排序
$(".key_sort").click(function() {
    var url = "{route('RoleIndex', ['page' => $smarty.get.page, 'key' => $smarty.get.key])}";
    var val = $(this).attr('data-val');
    var sort = $(this).attr('data-sort');
    if (val != 'desc') {
        url += '&'+sort+'=desc';
    } else {
        url += '&'+sort+'=asc';
    }
    window.location.href = url;
});
</script>
{/block}
