{extends file='Admin/layout.tpl'} 

{block title}Excel批量导入{/block} 
{block breadcrumb}
<li class="active"><a href="{route('LeaveExcelIndex')}">Excel批量导入</a></li>
{/block} 

{block main}
<link rel="stylesheet" type="text/css" href="{asset('js/bootstrap-fileinput-master/css/fileinput.css')}" />
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<div class="form">
				<form class="cmxform form-horizontal tasi-form" method="post" action="{route('LeaveExcelImport')}" enctype="multipart/form-data" >
					<div class="form-group">
						<div class="col-lg-12">
							<input id="excelImport" class="file" type="file" name="file" multiple=true>
							<span class="help-block">
								<a href="{asset('excel/import_leave.csv')}" target="_blank">模板文件下载</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								目前仅支持csv此格式，并且文档大小不能超过2M
							</span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-12">
							<button class="btn btn-success btn-block" type="submit"> 保存</button>
						</div>
					</div>
				</form>
				</div>
			</div>
		</section>
	</div>
</div>

<div class="row">
	<div class="col-sm-12" style="height:407px;OVERFLOW-Y:auto;SCROLLBAR-FACE-COLOR:#ffffff;">
		<section class="panel">
			{if Session::has('logs')}
			{assign "logs" Session::get('logs')}
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>姓名</th>
						<th>手机号</th>
						<th>错误原因</th>
					</tr>
				</thead>
				<tbody>
					{foreach $logs as $error}
					<tr>
						<td>{$error@iteration}</td>
						<td>{$error.realname}</td>
						<td>{$error.mobile}</td>
						<td>{$error.msg}</td>
					</tr>
					{/foreach}
				</tbody>
			</table>
			{/if}
		</section>
	</div>
</div>
{/block} 

{block script} 
<script type="text/javascript" src="{asset('js/bootstrap-fileinput-master/js/fileinput.js')}"></script>
<script type="text/javascript">
//约束上传条件
$("#excelImport").fileinput({
    allowedFileExtensions : ['csv'],
    showPreview : true,
    maxFileSize: 2000,
    showUpload:false,
    showRemove:false
});
</script>
{/block}
