{extends file='Admin/layout.tpl'} 

{block title}意见反馈{/block} 
{block breadcrumb}
<li class="active"><a href="{route('FeedbackIndex')}">意见反馈</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<form class="form-inline" role="form" action="{Route('FeedbackIndex')}" method="get">
					<div class="form-group">
						<label class="sr-only" for="key">关键字</label>
                        <input type="text" class="form-control" id="name" name="key" value="{$smarty.get.key}" placeholder="内容模糊查询">
                    </div>
					<button type="submit" class="btn btn-info"><i class="fa fa-search"></i> 查询</button>
				</form>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>发布者</th>
						<th>内容</th>
						<th>创建日期</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{if $data}
						{foreach $data as $item}
						<tr>
							<td>{$item.id}</td>
							<td>{$item.user.name}</td>
							<td>{$item.content|truncate:30:"...":true}</td>
							<td>{$item.created_at|date_format:"%Y-%m-%d"}</td>
							<td>
								<a class="btn btn-sm btn-danger" data-toggle="modal" href="#DeleteConfirmModal" onclick="deleteConfirm({$item.id})"><i class="icon-trash"></i> 删除</a>
							</td>
						</tr>
						{foreachelse}
							<tr><td colspan="12" class="text-center">无相关数据！</td></tr>
						{/foreach}
					{/if}
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
                <p>您确定要删除这条意见反馈吗？</p>
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('FeedbackDelete')}">
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
}
</script>
{/block}
