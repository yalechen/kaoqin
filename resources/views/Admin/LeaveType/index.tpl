{extends file='Admin/layout.tpl'} 

{block title}请假类型{/block} 
{block breadcrumb}
<li class="active"><a href="{route('LeaveTypeIndex')}">请假类型</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<a href="{route('LeaveTypeEdit')}"><button type="button" class="btn btn-success"><i class="fa fa-plus"></i> 添加请假类型 </button></a>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>名称</th>
						<th>排序值 &nbsp;&nbsp;<i data-val="{$smarty.get.sort}" data-sort="sort" class="sort fa {if $smarty.get.sort eq 'desc'}fa-sort-amount-desc{elseif $smarty.get.sort eq 'asc'}fa-sort-amount-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>创建日期</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{foreach $data as $item}
					<tr>
						<td>{$item.id}</td>
						<td>{$item.name}</td>
						<td><span class="badge bg-success">{$item.sort}</span></td>
						<td>{$item.created_at|date_format:"%Y-%m-%d"}</td>
						<td>
							<a class="btn btn-sm btn-primary" href="{route('LeaveTypeEdit', ['id'=>$item.id])}"><i class="icon-pencil"></i> 编辑</a>
							<a class="btn btn-sm btn-danger" data-toggle="modal" href="#DeleteConfirmModal" onclick="deleteConfirm({$item.id}, '{$item.name}')"><i class="icon-trash"></i> 删除</a>
						</td>
					</tr>
					{foreachelse}
						<tr><td colspan="5" class="text-center">无相关数据！</td></tr>
					{/foreach}
				</tbody>
			</table>
			<div class="text-center">
				{$data->render()}
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
                <p>您确定要删除“<strong id="leave_type_name"></strong>”这个请假类型吗？</p>
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('LeaveTypeDelete')}">
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
	$("#leave_type_name").text(name);
}

//排序
$(".sort").click(function() {
    var url = "{route('LeaveTypeIndex', ['page' => $smarty.get.page])}";
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
