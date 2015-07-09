{extends file='Admin/layout.tpl'} 

{block title}{if $data.id gt 0}{assign "title" "编辑"}{else}{assign "title" "新增"}{/if}{$title}文章{/block} 
{block breadcrumb}
<li><a href="{route('ArticleIndex')}">文章管理</a></li>
<li class="active"><a href="{route('ArticleEdit',['id'=>$data.id])}">{$title}文章</a></li>
{/block} 
{block css}
	<link href="{asset('js/summernote/dist/summernote.css')}" rel="stylesheet">
{/block} 
{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<div class="form">
					<form class="cmxform form-horizontal tasi-form" method="post" action="{route('ArticleSave')}" id="article_form">
						<div class="form-group">
							<label for="title" class="control-label col-lg-2">标题</label>
							<div class="col-lg-10">
                               	<input class="form-control" id="title" name="title" value="{old('title')|default:$data.title}" type="text" required />
                            </div>
						</div>
                        <div class="form-group">
                            <label for="number" class="control-label col-lg-2">内容</label>
                            <div class="col-lg-10">
                           		<div class="summernote">{old('content')|default:$data.content}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sort" class="control-label col-lg-2">排序值</label>
                            <div class="col-lg-10">
                                <input class="form-control" type="text" id="sort" name="sort" value="{old('sort')|default:$data.sort|default:100}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type" class="control-label col-lg-2">类型</label>
                            <div class="col-lg-10">
                                <div class="radio-custom radio-success">
		                            <input type="radio" value="{constant('App\Models\Article::TYPE_ABOUT')}" {if constant('App\Models\Article::TYPE_ABOUT') eq old('type')|default:$data.type}checked{/if} name="type" id="about">
		                            <label for="about">关于我们</label>
		                            <input type="radio" value="{constant('App\Models\Article::TYPE_ACTION')}" {if constant('App\Models\Article::TYPE_ACTION') eq old('type')|default:$data.type}checked{/if} name="type" id="action">
		                            <label for="action">功能介绍</label>
		                        </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
        						{if $data.id gt 0}<input type="hidden" name="id" value="{$data.id}" />{/if}
        						<input type="hidden" name="content" id="content" value="" />
                                <button class="btn btn-success" type="submit" id="savebtn"> 保存</button>
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
<script src="{asset('js/summernote/dist/summernote.min.js')}"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	$('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,                 // set focus to editable area after initializing summernote
        onImageUpload: function(files, editor, $editable) {   //上传图片
			var file=files[0];
			var filename = false;
			try{
				filename = file['name'];
			} catch(e){
				filename = false;
			}
			if(!filename){
				alert("文件获取失败");
			}
			data = new FormData();
			data.append("file", file);
			$.ajax({
				data: data,
				dataType : 'json',
				type: "POST",
				url: "{route('FormUploadFile')}",
				cache: false,
				contentType: false,
				processData: false,
				success: function(data) {
					editor.insertImage($editable, data.url);
				},
				error:function(){
					alert("上传失败");
				}
			});
		}
    });
	
	$("#savebtn").click(function(){
		$("#content").val($('.summernote').eq(0).code());
		$("#article_form").submit();
	});
});
</script>
{/block}