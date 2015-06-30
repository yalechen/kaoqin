{extends file='Admin/layout.tpl'} 

{block title}编辑加班{/block} 
{block breadcrumb}
<li><a href="{route('OvertimeIndex')}">加班管理</a></li>
<li class="active"><a href="{route('OvertimeEdit',['id'=>$data.id])}">编辑加班</a></li>
{/block} 

{block main}
<link rel="stylesheet" type="text/css" href="{asset('js/bootstrap-fileinput-master/css/fileinput.css')}" />
<style>
.show_upload { display:none; }
</style>
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<div class="form">
					<form class="cmxform form-horizontal tasi-form" method="post" action="{route('OvertimeSave')}">
						<div class="form-group">
							<label for="realname" class="control-label col-lg-2">加班者</label>
							<div class="col-lg-10">
                               	<input class="form-control" id="realname" name="realname" value="{$data.user.realname}" type="text" disabled />
                            </div>
						</div>
                        <div class="form-group">
                            <label for="start_time" class="control-label col-lg-2">开始时间</label>
                            <div class="col-lg-10">
                                <input class="form-control form_datetime" size="16" id="start_time" type="text" name="start_time" value="{$data.start_time|date_format:"%Y-%m-%d %H:%M"}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="end_time" class="control-label col-lg-2">结束时间</label>
                            <div class="col-lg-10">
                                <input class="form-control form_datetime" size="16" id="end_time" type="text" name="end_time" value="{$data.end_time|date_format:"%Y-%m-%d %H:%M"}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hours" class="control-label col-lg-2">小时数</label>
                            <div class="col-lg-10">
                                <input class="form-control" name="hours" id="hours" type="text" value="{$data.hours}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reason" class="control-label col-lg-2">原因</label>
                            <div class="col-lg-10">
                                <textarea class="form-control " id="reason" name="reason" required>{$data.reason}</textarea>
                            </div>
                        </div>
                        {if $data.user.parent_user_id neq $data.audit_user_id && $data.user.parent_user_id gt 0}
                        <div class="form-group">
                            <label for="reason" class="control-label col-lg-2">审批者</label>
                            <div class="col-lg-10">
                                <p class="form-control-static">
                                	<span id="auditRealname">{if $data.audit_user_id eq 0}未设置{/if}</span>
                                	<a href="javascript:;" id="editAuditUser" data-id="{$data.id}">修改为加班者上级领导</a>
                                </p>
                                <input type="hidden" name="audit_user_id" value="{$data.audit_user_id}" />
                            </div>
                        </div>
                        {/if}
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
        						<input type="hidden" name="id" value="{$data.id}" />
                                <button class="btn btn-success" type="submit"> 保存</button>
                                <button class="btn btn-default" type="button"> 取消</button>
                            </div>
                        </div>
                    </form>
                </div>
			</div>
		</section>
	</div>
</div>
{/block} 

{block script} 
<script type="text/javascript" src="{asset('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js')}"></script>
<script type="text/javascript" src="{asset('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js')}"></script>
<script type="text/javascript">
//时间选择
$('.form_datetime').datetimepicker({
    format: 'yyyy-mm-dd hh:ii',
    autoclose:true,
    language: "zh-CN",
})

///点击修改
$(document).ready(function(){
    $("#editAuditUser").click(function() {
    	$("#auditRealname").text('');
    	var user_id="{$data.user_id}";
    	$.ajax({
	        url: '{route("UserParentUser")}',
	        data: { user_id : user_id },
	        dataType:'json',
	        success: function(data) {
	        	$("#auditRealname").text(data[0]['realname']);
	        }
	    });
    });
});
</script>
{/block}
