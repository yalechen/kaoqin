{extends file='Admin/layout.tpl'} 

{block title}{if $data.id gt 0}{assign "title" "编辑"}{else}{assign "title" "新增"}{/if}{$title}临时任务{/block} 
{block breadcrumb}
<li><a href="{route('TaskAssignIndex')}">临时任务</a></li>
<li class="active"><a href="{route('TaskAssignEdit',['id'=>$data.id])}">{$title}临时任务</a></li>
{/block} 

{block main}
<link rel="stylesheet" type="text/css" href="{asset('js/bootstrap-datetimepicker/css/datetimepicker.css')}"/>
<link rel="stylesheet" type="text/css" href="{asset('css/dropzone.css')}">
<style>
.auto_height { height: 350px; OVERFLOW-Y: auto; SCROLLBAR-FACE-COLOR: #ffffff; }
.dz-image img { height: 120px; width:120px; }
</style>
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
            <div class="panel-body">
            	<form id="my-awesome-dropzone" action="{route('FormUploadFile')}" class="dropzone"></form>
            </div>
        </section>
        
		<section class="panel">
			<div class="panel-body">
				<div class="form">
					<form class="cmxform form-horizontal tasi-form" method="post" action="{route('TaskAssignSave')}">
						<div class="form-group">
							<label for="title" class="control-label col-lg-2">标题</label>
							<div class="col-lg-10">
                               	<input class="form-control" id="title" name="title" value="{old('title')|default:$data.title}" type="text" required />
                            	<!-- <span class="help-block">标题一旦添加成就无法更改</span> -->
                            </div>
						</div>
                        <div class="form-group">
                            <label for="accept_user_id" class="control-label col-lg-2">接收者</label>
                            <div class="col-lg-10">
                            	<span id="span_accept_user_name">{old('accept_user_name')|default:$data.acceptUser.realname}</span>
                                <a class="btn btn-sm btn-warning" data-toggle="modal" href="#acceptUserModal" onclick="acceptUserAssign({$data.id|default:0})"><i class="icon-user"></i> 选择</a>
                                <input type="hidden" value="{$data.accept_user_id}" name="accept_user_id" id="accept_user_id" />
                                <input type="hidden" value="{$data.acceptUser.realname}" name="accept_user_name" id="accept_user_name" />
                            </div>
                        </div>
                        <div class="form-group">
                        	<label for="cust" class="control-label col-lg-2">指派客户：</label>
                        	<div class="col-lg-10">
	                            <div id="linkSelecter" style="margin-bottom: 10px;display: none;">
	                                <select class="form-control" style="width:30%;display:inline;" tabindex="1" name="cust[]">
	                                    <option value="0">--请选择客户--</option>
	                                    {foreach $cust as $item}
	                                        <option value="{$item->id}">{$item->name}</option>
	                                    {/foreach}
	                                </select>
	                                <input class="form-control" style="display:inline; width:20%" size="10" type="text" name="times[]" placeholder="拜访数量" value="" />
	                                <button type="button" class="btn btn-primary addSelecter">增加</button>
	                                <button type="button" class="btn btn-danger removeSelecter">删除</button>
	                            </div>
	                            <div id="link_list">
	                                {if $task_custs}
	                                    {foreach $task_custs as $item1}
	                                        <div id="linkSelecter" style="margin-bottom: 10px;">
	                                           <select class="form-control" tabindex="1" style="width:30%;display:inline;" name="cust[]">
                                                    {foreach $cust as $item2}
                                                        <option value="{$item2.id}" {if $item2.id eq $item1.cust_id}selected{/if}>{$item2.name}</option>
                                                    {/foreach}
	                                            </select>
	                                            <input class="form-control" style="display:inline; width:20%" size="10" type="text" name="times[]" placeholder="拜访数量" value="{$item1.times}" />
	                                            <button type="button" class="btn btn-primary addSelecter">增加</button>
	                                            <button type="button" class="btn btn-danger removeSelecter">删除</button>
	                                        </div>
	                                    {/foreach}
	                                {else}
	                                    <div style="margin-bottom: 10px;">
	                                        <select class="form-control" tabindex="1" style="width:30%;display:inline;" name="cust[]">
	                                            <option value="0">--请选择--</option>
	                                            {foreach $cust as $item}
	                                                <option value="{$item->id}">{$item->name}</option>
	                                            {/foreach}
	                                        </select>
	                                        <input class="form-control" style="display:inline; width:20%" size="10" type="text" name="times[]" placeholder="拜访数量" value="" />
	                                        <button type="button" class="btn btn-primary addSelecter">增加</button>
	                                        <button type="button" class="btn btn-danger removeSelecter">删除</button>
	                                    </div>
	                                {/if}
	                            </div>
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
                            <label for="remark" class="control-label col-lg-2">备注</label>
                            <div class="col-lg-10">
                                <textarea class="form-control " id="remark" name="remark">{old('remark')|default:$data.remark}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
        						<input type="hidden" name="id" value="{$data.id}" />
        						<input type="hidden" name="images" id="images" value="" />
        						<input type="hidden" name="old_images" id="old_images" value="{Session::get('old_images')}" />
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

<!-- Modal -->
<div class="modal fade" id="acceptUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">指派临时指定任务接收者</h4>
            </div>
            <div class="modal-body">
            	<div class="row">
	                <div class="col-sm-12">
						<section class="panel">
							<div class="panel-body">
								<form class="form-inline" role="form" id="accept_user_search_form">
									<div class="form-group">
										<label class="sr-only" for="key">关键字</label>
				                        <input type="text" class="form-control" id="modal_key" name="modal_key" value="{$smarty.get.modal_key}" placeholder="用户名\姓名\手机号">
				                    </div>
									<button type="button" class="btn btn-info" id="AcceptUserFind"><i class="fa fa-search"></i> 查询</button>
								</form>
							</div>
							<div id="UsersList">
							
							</div>
						</section>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button" id="close_modal">关闭</button>
                <!-- <button class="btn btn-success" type="button">关闭</button> -->
            </div>
        </div>
    </div>
</div>
<!-- modal -->
{/block} 

{block script} 
<script type="text/javascript" src="{asset('js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js')}"></script>
<script type="text/javascript" src="{asset('js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js')}"></script>
<script type="text/javascript" src="{asset('js/dropzone.js')}"></script>
<script type="text/javascript">
$('.form_datetime').datetimepicker({
    format: 'yyyy-mm-dd hh:ii',
    autoclose:true,
    language: "zh-CN",
})

//接收者模态框的搜索
$("#AcceptUserFind").click(function() {
    $.get('{route("SearchAcceptUsers")}', $("#accept_user_search_form").serialize(), function(data) {
        $("#UsersList").html(data);
    },'html');
    $("#UsersList").addClass("auto_height");
});

//多张图片上传
$(function() {
	var images = new Array();
	// 此控件请参考 http://www.dropzonejs.com文档
	//$("#my-awesome-dropzone").dropzone({
	var myDropzone = new Dropzone("#my-awesome-dropzone",{
		paramName: 'file',
		parallelUploads: 1,
		maxFiles: 5,
		maxFilesize: 1,
		//uploadMultiple: true,  //一次性提交多个文件，true的时候上传getRealPath会报错
		url: "{route('FormUploadFile')}",
		acceptedFiles: 'image/*',//".png,.gif,.jpg"     'image/*'  
		dictDefaultMessage: '添加参考图，将文件拖拽至此区域进行上传（或点击此区域），最多不超过5张',
		dictFallbackMessage: '您的浏览器不支持拖拽的上传',
		dictInvalidFileType: '仅能上传图片[jpg,png,gif]的格式',
		dictFileTooBig: '最大文件不能超过1M',
		dictCancelUpload: '取消',
		dictResponseError: '上传状态失败',
		dictCancelUploadConfirmation: '确认取消',
		dictRemoveFile: '移除',
		dictMaxFilesExceeded: '最多只能上传5张图片',
		addRemoveLinks: true,
		init: function() { 
			/* Maybe display some more file information on your page */
			//this.on("addedfile", function(file) { alert("Added file."); }); 
			this.on("success", function(file) { 
                var res = eval('(' + file.xhr.responseText + ')');
                images.push(res.hash);
                $("#images").val(images);
			}); 
			this.on("removedfile", function(file,data) {
				if(file.hash){
					for(i in images){
	                	if(images[i]==file.hash){
	                		images.splice(i,1);
	                	}
	                }
				}else{
					var res = eval('(' + file.xhr.responseText + ')');
	                for(i in images){
	                	if(images[i]==res.hash){
	                		images.splice(i,1);
	                	}
	                }
				}
                $("#images").val(images);
	        });
		  }
	});
	
	if($("#old_images").val()!=''){
		{foreach Session::get('old_images') as $picture}
		    file = {
		        url	: '{$picture.url}',
		        hash	: '{$picture.hash}',
		        path	: '{$picture.path}',
		        name : '{$picture.filename}',
		        size	: '{$picture.size}',
		        type	: '{$picture.storage.mime|replace:'/':'.'}'
		    };
		    images.push('{$picture.hash}');
		    $("#images").val(images);
		    
		    myDropzone.emit('addedfile', file);
		    myDropzone.emit('thumbnail', file, file.url);
	    {/foreach}
	}else{
    	{foreach $pictures as $picture}
		    file = {
		        url	: '{$picture.url}',
		        hash	: '{$picture.hash}',
		        path	: '{$picture.path}',
		        name : '{$picture.filename}',
		        size	: '{$picture.size}',
		        type	: '{$picture.storage.mime|replace:'/':'.'}'
		    };
		    images.push('{$picture.hash}');
		    $("#images").val(images);
		    
		    myDropzone.emit('addedfile', file);
		    myDropzone.emit('thumbnail', file, file.url);
	    {/foreach}
	}
});
	
//增加一行
$(document).on('click', '.addSelecter', function(){
    addLinkSelecter();
});

//删除一行
$(document).on('click', '.removeSelecter', function()
{
    var parent = $(this).parent();
    var del = false;
    if (parent.find('[name="cust[]"]').val() != '') {
        if (confirm('确定要删除吗？')) {
            del = true;
        }
    } else {
        del = true;
    }
    if (del) {
        $(this).parent().remove();
        $("#link_list .addSelecter:last").show();
        if ($("#link_list .removeSelecter").size() <= 1 ) {
            $("#link_list .removeSelecter").hide();
        }
    }
});

//克隆函数
function addLinkSelecter()
{
    $("#link_list .addSelecter").hide();
    var link = $("#linkSelecter").clone().show();
    $("#link_list").append(link);
    $("#link_list .removeSelecter").show();
}

$("#link_list .addSelecter:not(':last')").hide();
</script>
{/block}
