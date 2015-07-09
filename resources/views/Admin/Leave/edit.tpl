{extends file='Admin/layout.tpl'} 

{block title}编辑请假{/block} 
{block breadcrumb}
<li><a href="{route('LeaveIndex')}">请假列表</a></li>
<li class="active"><a href="{route('LeaveEdit',['id'=>$data.id])}">编辑请假</a></li>
{/block} 

{block main}
<link rel="stylesheet" type="text/css" href="{asset('js/bootstrap-datetimepicker/css/datetimepicker.css')}"/>
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<div class="form">
					<form class="cmxform form-horizontal tasi-form" method="post" action="{route('LeaveSave')}">
						<div class="form-group">
							<label for="realname" class="control-label col-lg-2">姓名</label>
							<div class="col-lg-10">
                               	<input class="form-control" id="realname" name="realname" value="{old('realname')|default:$data.realname}" type="text" disabled />
                            </div>
						</div>
                        <div class="form-group">
                            <label for="leave_type_name" class="control-label col-lg-2">假别</label>
                            <div class="col-lg-10">
                                <input class="form-control" id="leave_type_name" type="text" name="leave_type_name" value="{old('leave_type_name')|default:$data.leave_type_name}" disabled />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start_time" class="control-label col-lg-2">开始时间</label>
                            <div class="col-lg-10">
                                <input class="form-control form_datetime" size="16" id="start_time" type="text" name="start_time" value="{old('start_time')|default:$data.start_time|date_format:"%Y-%m-%d %H:%M"}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="end_time" class="control-label col-lg-2">结束时间</label>
                            <div class="col-lg-10">
                                <input class="form-control form_datetime" size="16" id="end_time" type="text" name="end_time" value="{old('end_time')|default:$data.end_time|date_format:"%Y-%m-%d %H:%M"}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="days" class="control-label col-lg-2">请假天数</label>
                            <div class="col-lg-10">
                                <input class="form-control" name="days" id="days" type="text" value="{old('days')|default:$data.days}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hours" class="control-label col-lg-2">小时数</label>
                            <div class="col-lg-10">
                                <input class="form-control" name="hours" id="hours" type="text" value="{old('hours')|default:$data.hours}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
        						{if $data.id gt 0}<input type="hidden" name="id" value="{$data.id}" />{/if}
                                <button class="btn btn-success" type="submit"> 保存</button>
                                <button class="btn btn-default" type="button" onclick="javascript:history.go(-1)"> 取消</button>
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
$('.form_datetime').datetimepicker({
    format: 'yyyy-mm-dd hh:ii',
    autoclose:true,
    language: "zh-CN",
})
</script>
{/block}
