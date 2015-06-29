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
				<form class="cmxform form-horizontal tasi-form" method="post" action="{route('CustSave')}">
					<div class="form-group">
						<div class="col-lg-10">
							<input id="excelImport" class="file" type="file" multiple=true>
							<span class="help-block">仅支持xls, xlsx, csv此三种格式，并且文档大小不能超过2M</span>
						</div>
						<label for="name" class="col-lg-2">
							<button class="btn btn-success" type="submit"> 保存</button>
						</label>
					</div>
				</form>
				</div>
			</div>
		</section>
	</div>
</div>
{/block} 

{block script} 
<script type="text/javascript" src="{asset('js/bootstrap-fileinput-master/js/fileinput.js')}"></script>
<script type="text/javascript">
//约束上传条件
$("#excelImport").fileinput({
    allowedFileExtensions : ['xls', 'xlsx', 'xlsm', 'csv'],
    showPreview : true,
    maxFileSize: 2000,
    showUpload:false,
    showRemove:false
});
</script>
{/block}
