{extends file='Admin/layout.tpl'} 

{block title}加班管理{/block} 
{block breadcrumb}
<li class="active"><a href="{route('OvertimeIndex')}">加班管理</a></li>
{/block} 

{block main}
<link rel="stylesheet" type="text/css" href="{asset('js/bootstrap-datetimepicker/css/datetimepicker.css')}"/>
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<form class="form-inline" role="form" action="{Route('OvertimeIndex')}" method="get">
					<div class="form-group">
						<label class="sr-only" for="key">理由</label>
                        <input type="text" class="form-control" id="key" name="key" value="{$smarty.get.key}" placeholder="加班事由">
                    </div>
                    <div class="form-group">
						<select class="form-control" name="status">
							<option value="" {if !$smarty.get.status}selected{/if}>--审批状态--</option>
							<option value="{constant('App\Models\Overtime::STATUS_PASS')}" {if constant('App\Models\Overtime::STATUS_PASS') eq $smarty.get.status}selected{/if}>{trans('overtime.status.'|cat:constant('App\Models\Overtime::STATUS_PASS'))}</option>
							<option value="{constant('App\Models\Overtime::STATUS_REJECT')}" {if constant('App\Models\Overtime::STATUS_REJECT') eq $smarty.get.status}selected{/if}>{trans('overtime.status.'|cat:constant('App\Models\Overtime::STATUS_REJECT'))}</option>
							<option value="{constant('App\Models\Overtime::STATUS_WAIT')}" {if constant('App\Models\Overtime::STATUS_WAIT') eq $smarty.get.status}selected{/if}>{trans('overtime.status.'|cat:constant('App\Models\Overtime::STATUS_WAIT'))}</option>
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
						<th>开始时间</th>
						<th>结束时间</th>
						<th>小时数&nbsp;&nbsp;<i data-val="{$smarty.get.hours}" data-sort="hours" class="hours_sort fa {if $smarty.get.hours eq 'desc'}fa-sort-numeric-desc{elseif $smarty.get.hours eq 'asc'}fa-sort-numeric-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>原因</th>
						<th>审核者</th>
						<th>审核状态</th>
						<th>创建日期</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{foreach $data as $item}
					<tr>
						<td>{$item.id}</td>
						<td>{$item.user.realname}</td>
						<td>{$item.start_time|date_format:"%Y-%m-%d %H:%M"}</td>
						<td>{$item.end_time|date_format:"%Y-%m-%d %H:%M"}</td>
						<td>{$item.hours}</td>
						<td>{$item.reason|truncate:20:"...":true}</td>
						<td>{$item.auditUser.name}</td>
						<td>{trans('overtime.status.'|cat:$item.status)}</td>
						<td>{$item.created_at}</td>
						<td>
							{if $item.status eq constant('App\Models\Overtime::STATUS_WAIT')}
							<a class="btn btn-sm btn-success" data-toggle="modal" href="#AuditConfirmModal" onclick="audit({$item.id},'{$item.user.realname}')"><i class="icon-pin"></i> 加班审核</a>
							{/if}
							<a class="btn btn-sm btn-primary" href="{route('OvertimeEdit', ['id'=>$item.id])}"><i class="icon-pencil"></i> 编辑</a>
							<a class="btn btn-sm btn-danger" data-toggle="modal" href="#DeleteConfirmModal" onclick="deleteConfirm({$item.id})"><i class="icon-trash"></i> 删除</a>
						</td>
					</tr>
					{foreachelse}
						<tr><td colspan="12" class="text-center">无相关数据！</td></tr>
					{/foreach}
				</tbody>
			</table>
			<div class="text-center">
				{$data->appends(['key' => $smarty.get.key,'status'=>$smarty.get.status,'start_time'=>$smarty.get.start_time,'end_time'=>$smarty.get.end_time])->render()}
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
                <p>您确定要删除这个加班记录吗？</p>
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('OvertimeDelete')}">
	            	<input type="hidden" id="id" name="id" value="" >
	                <button class="btn btn-danger" type="submit"> 确认</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- modal -->

<!-- Modal -->
<div class="modal fade" id="AuditConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">加班审核</h4>
            </div>
            <div class="modal-body">
            	接下来您将对“<STRONG id="user_name"></STRONG>”用户进行加班审核
            	<div class="row">
	                <div class="col-sm-12">
						<section class="panel">
							<div class="panel-body">
								<div class="form-group">
                                    <label for="status">审核结果</label>
                                    <div class="radio-custom radio-success">
			                            <input type="radio" value="{constant('App\Models\Overtime::STATUS_PASS')}" {if constant('App\Models\Overtime::STATUS_PASS') eq old('status')|default:$data.status}checked{/if} name="overtime_status" id="pass">
			                            <label for="pass">{trans('overtime.status.'|cat:constant('App\Models\Overtime::STATUS_PASS'))}</label>
			                            <input type="radio" value="{constant('App\Models\Overtime::STATUS_REJECT')}" {if constant('App\Models\Overtime::STATUS_REJECT') eq old('status')|default:$data.status}checked{/if} name="overtime_status" id="reject">
			                            <label for="reject">{trans('overtime.status.'|cat:constant('App\Models\Overtime::STATUS_REJECT'))}</label>
		                        	</div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">备注</label>
                                    <textarea class="form-control " id="remark" name="remark" required></textarea>
                                </div>
							</div>
						</section>
					</div>
				</div>
            </div>
            <div class="modal-footer">
            	<input type="hidden" id="overtime_id" name="overtime_id" value="" >
            	<button data-dismiss="modal" class="btn btn-default" type="button" id="closeModal">取消审核</button>
                <button class="btn btn-danger" type="button" id="confirmAuditBtn"> 确认审核</button>
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

//加班审核
function audit(id,user_name){
	$("#overtime_id").val(id);
	$("#user_name").text(user_name);
}
//审核处理
$("#confirmAuditBtn").click(function() {
	var overtime_id=$("#overtime_id").val();
	var status=$("input[name='overtime_status']:checked").val();
	var remark=$("#remark").val();
	if(overtime_id>0 && status){
		//iconfirm('确认要开始审核吗？', function() {
			$.ajax({
				type: 'POST',
		        url: '{route("OvertimeAudit")}',
		        data: { overtime_id : overtime_id, status : status, remark : remark },
		        dataType: 'text',
		        success: function(data) {
		        	//alert("审核成功");
		        	$("#closeModal").trigger('click');
		        	window.location.reload();
		        },
		        error: function(xhq){
		        	alert(xhq.responseText);
		        }
		    });
        //});
	}else{
		alert("批量指派失败，请选择用户");
	}
});

//时间控件
$('.form_datetime').datetimepicker({
    format: 'yyyy-mm-dd hh:ii',
    autoclose:true,
    language: "zh-CN",
})

//排序
$(".hours_sort").click(function() {
    var url = "{route('OvertimeIndex', ['page' => $smarty.get.page,'key' => $smarty.get.key,'status' => $smarty.get.status,'start_time' => $smarty.get.start_time,'end_time' => $smarty.get.end_time])}";
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
