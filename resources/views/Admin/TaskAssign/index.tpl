{extends file='Admin/layout.tpl'} 

{block title}临时任务{/block} 
{block breadcrumb}
<li class="active"><a href="{route('TaskAssignIndex')}">临时任务</a></li>
{/block} 

{block main}
<link rel="stylesheet" type="text/css" href="{asset('js/bootstrap-datepicker/css/datepicker.css')}"/>
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<form class="form-inline" role="form" action="{Route('TaskAssignIndex')}" method="get">
					<div class="form-group">
						<label class="sr-only" for="key">关键字</label>
                        <input type="text" class="form-control" id="name" name="key" value="{$smarty.get.key}" placeholder="标题">
                    </div>
                    <div class="form-group">
						<select class="form-control" name="status">
							<option value="" {if !$smarty.get.status}selected{/if}>--所有状态--</option>
							<option value="{constant('App\Models\TaskAssign::STATUS_STARTING')}" {if constant('App\Models\TaskAssign::STATUS_STARTING') eq $smarty.get.status}selected{/if}>{trans('task_assign.status.'|cat:constant('App\Models\TaskAssign::STATUS_STARTING'))}</option>
							<option value="{constant('App\Models\TaskAssign::STATUS_END')}" {if constant('App\Models\TaskAssign::STATUS_END') eq $smarty.get.status}selected{/if}>{trans('task_assign.status.'|cat:constant('App\Models\TaskAssign::STATUS_END'))}</option>
                    	</select>
                    </div>
                    <div class="form-group">
                    	<label class="sr-only" for="ymonth">年月份</label>
                    	<input class="form-control form-control-inline input-medium default-date-picker"  size="16" name="ymonth" id="ymonth" type="text" value="{$smarty.get.ymonth}" placeholder="年月份" />
                    </div>
					<button type="submit" class="btn btn-info"><i class="fa fa-search"></i> 查询</button>
					<a href="{route('TaskAssignEdit')}"><button type="button" class="btn btn-success"><i class="fa fa-plus"></i> 添加临时任务 </button></a>
				</form>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>接收者</th>
						<th>标题</th>
						<th>开始时间</th>
						<th>结束时间</th>
						<!-- <th>商户门店</th> -->
						<th>总拜访数/已拜访数</th>
						<th>完成度&nbsp;&nbsp;<i data-val="{$smarty.get.rate}" data-sort="rate" class="rate_sort fa {if $smarty.get.rate eq 'desc'}fa-sort-numeric-desc{elseif $smarty.get.rate eq 'asc'}fa-sort-numeric-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>状态</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{foreach $data as $item}
					<tr>
						<td>{$item.id}</td>
						<td>{$item.acceptUser.realname}</td>
						<td>{$item.title|truncate:30:"..":true}</td>
						<td>{$item.start_time|date_format:"%Y-%m-%d %H:%M"}</td>
						<td>{$item.end_time|date_format:"%Y-%m-%d %H:%M"}</td>
						{*<td>
							{foreach $item.custs as $cust}
								{if $cust@last}{$cust.cust.name}{else}
								{$cust.cust.name}|{/if}
							{/foreach}
						</td>*}
						<td>{$item.times}/{$item.visited_times}</td>
						<td>{intval($item.visited_times*100/$item.times)}%</td>
						<td><span class="toggle-status label {if constant('App\Models\TaskAssign::STATUS_STARTING') eq $item.status}label-danger{else}label-success{/if}">{trans('task_assign.status.'|cat:$item.status)}</span></td>
						<td>
							<a class="btn btn-sm btn-primary" href="{route('TaskAssignEdit', ['id'=>$item.id])}"><i class="icon-pencil"></i> 编辑</a>
							<a class="btn btn-sm btn-danger" data-toggle="modal" href="#DeleteConfirmModal" onclick="deleteConfirm({$item.id})"><i class="icon-trash"></i> 删除</a>
						</td>
					</tr>
					{foreachelse}
						<tr><td colspan="9" class="text-center">无相关数据！</td></tr>
					{/foreach}
				</tbody>
			</table>
			<div class="text-center">
				{$data->appends(['key'=>$smarty.get.key,'status'=>$smarty.get.status,'ymonth'=>$smarty.get.ymonth])->render()}
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
                <p>您确定要删除这个临时指定任务吗？</p>
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('TaskAssignDelete')}">
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
<script type="text/javascript" src="{asset('js/bootstrap-datepicker/js/bootstrap-datepicker.js')}"></script>
<script type="text/javascript" src="{asset('js/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js')}"></script>
<script type="text/javascript">
//删除确认
function deleteConfirm(id, name){
	$("#id").val(id);
	$("#leave_type_name").text(name);
}

//选择月份查询
$('.default-date-picker').datepicker({
    format: 'yyyy-mm',
    autoclose:true,
    language: "zh-CN",
});

//排序
$(".rate_sort").click(function() {
    var url = "{route('TaskAssignIndex', ['page' => $smarty.get.page,'key' => $smarty.get.key,'status' => $smarty.get.status,'ymonth' => $smarty.get.ymonth])}";
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
