{extends file='Admin/layout.tpl'} 

{block title}请假列表{/block} 
{block breadcrumb}
<li class="active"><a href="{route('LeaveIndex')}">请假列表</a></li>
{/block} 

{block main}
<link rel="stylesheet" type="text/css" href="{asset('js/bootstrap-datetimepicker/css/datetimepicker.css')}"/>
<div class="row">
	<div class="col-sm-12" >
		<section class="panel">
			<div class="panel-body">
				<form class="form-inline" role="form" action="{Route('LeaveIndex')}" method="get">
                    <div class="form-group">
						<select class="form-control" name="leave_type_id">
							<option value="" {if !$smarty.get.leave_type_id}selected{/if}>--请假类型--</option>
							{foreach $types as $item}
								<option value="{$item.id}" {if $item.id eq $smarty.get.leave_type_id}selected{/if}>{$item.name}</option>
							{/foreach}
                    	</select>
                    </div>
                    <div class="form-group">
                    	<input class="form-control form_datetime" size="16" type="text" name="start_time" value="{$smarty.get.start_time}" placeholder="开始时间" />
						至
						<input class="form-control form_datetime" size="16" type="text" name="end_time" value="{$smarty.get.end_time}" placeholder="结束时间" />
                    </div>
					<button type="submit" class="btn btn-info"><i class="fa fa-search"></i> 查询</button>
				</form>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>姓名</th>
						<th>假别</th>
						<th>开始时间</th>
						<th>结束时间</th>
						<th>请假天数&nbsp;&nbsp;<i data-val="{$smarty.get.days}" data-sort="days" class="days_sort fa {if $smarty.get.days eq 'desc'}fa-sort-numeric-desc{elseif $smarty.get.days eq 'asc'}fa-sort-numeric-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>请假小时数&nbsp;&nbsp;<i data-val="{$smarty.get.hours}" data-sort="hours" class="hours_sort fa {if $smarty.get.hours eq 'desc'}fa-sort-numeric-desc{elseif $smarty.get.hours eq 'asc'}fa-sort-numeric-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>创建日期</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{foreach $data as $item}
					<tr>
						<td>{$item.id}</td>
						<td>{$item.realname}</td>
						<td>{$item.leave_type_name}</td>
						<td>{$item.start_time|date_format:"%Y-%m-%d %H:%M"}</td>
						<td>{$item.end_time|date_format:"%Y-%m-%d %H:%M"}</td>
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
				</tbody>
			</table>
			<div class="text-center">
				{$data->appends(['leave_type_id' => $smarty.get.leave_type_id,'start_time'=>$smarty.get.start_time,'end_time'=>$smarty.get.end_time])->render()}
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
<script type="text/javascript" src="{asset('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js')}"></script>
<script type="text/javascript" src="{asset('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js')}"></script>
<script type="text/javascript">
//删除确认
function deleteConfirm(id){
	$("#id").val(id);
}

$('.form_datetime').datetimepicker({
    format: 'yyyy-mm-dd hh:ii',
    autoclose:true,
    language: "zh-CN",
})

//排序
$(".sort,.days_sort,.hours_sort").click(function() {
    var url = "{route('LeaveIndex', ['page' => $smarty.get.page,'leave_type_id' => $smarty.get.leave_type_id,'start_time' => $smarty.get.start_time,'end_time' => $smarty.get.end_time])}";
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
