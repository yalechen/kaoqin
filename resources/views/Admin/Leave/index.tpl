{extends file='Admin/layout.tpl'} 

{block title}请假列表{/block} 
{block breadcrumb}
<li class="active"><a href="{route('LeaveIndex')}">请假列表</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12" style="height:407px;OVERFLOW-Y:auto;SCROLLBAR-FACE-COLOR:#ffffff;">
		<section class="panel">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>姓名</th>
						<th>假别</th>
						<th>开始时间</th>
						<th>结束时间</th>
						<th>请假天数</th>
						<th>请假小时数</th>
						<th>创建日期</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{if $data}
						{foreach $data as $item}
						<tr>
							<td>{$item.id}</td>
							<td>{$item.realname}</td>
							<td>{$item.leave_type_name}</td>
							<td>{$item.start_time}</td>
							<td>{$item.end_time}</td>
							<td>{$item.days}</td>
							<td>{$item.hours}</td>
							<td>{$item.created_at}</td>
							<td>
								<a class="btn btn-sm btn-primary" href="{route('LeaveEdit', ['id'=>$item.id])}"><i class="icon-pencil"></i> 编辑</a>
								<a class="btn btn-sm btn-danger" data-toggle="modal" href="#DeleteConfirmModal" onclick="deleteConfirm({$item.id})"><i class="icon-trash"></i> 删除</a>
							</td>
						</tr>
						{foreachelse}
							<tr><td colspan="12" class="text-center">无相关数据！</td></tr>
						{/foreach}
					{/if}
				</tbody>
			</table>
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
                <p>您确定要删除这个请假记录吗？</p>
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('LeaveDelete')}">
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
function deleteConfirm(id){
	$("#id").val(id);
}
</script>
{/block}
