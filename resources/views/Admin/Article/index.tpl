{extends file='Admin/layout.tpl'} 

{block title}文章管理{/block} 
{block breadcrumb}
<li class="active"><a href="{route('ArticleIndex')}">文章管理</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<form class="form-inline" role="form" action="{Route('ArticleIndex')}" method="get">
					<div class="form-group">
						<label class="sr-only" for="key">标题</label>
                        <input type="text" class="form-control" id="name" name="key" value="{$smarty.get.key}" placeholder="标题" />
                    </div>
                    <div class="form-group">
						<select class="form-control" name="type">
							<option value="" {if !$smarty.get.type}selected{/if}>--所有类型--</option>
							<option value="{constant('App\Models\Article::TYPE_ABOUT')}" {if constant('App\Models\Article::TYPE_ABOUT') eq $smarty.get.type}selected{/if}>{trans('article.type.'|cat:constant('App\Models\Article::TYPE_ABOUT'))}</option>
							<option value="{constant('App\Models\Article::TYPE_ACTION')}" {if constant('App\Models\Article::TYPE_ACTION') eq $smarty.get.type}selected{/if}>{trans('article.type.'|cat:constant('App\Models\Article::TYPE_ACTION'))}</option>
                    	</select>
                    </div>
					<button type="submit" class="btn btn-info"><i class="fa fa-search"></i> 查询</button>
					<a href="{route('ArticleEdit')}"><button type="button" class="btn btn-success"><i class="fa fa-plus"></i> 添加文章 </button></a>
				</form>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>标题</th>
						<th>类型</th>
						<th>排序值 &nbsp;&nbsp;<i data-val="{$smarty.get.sort}" data-sort="sort" class="sort fa {if $smarty.get.sort eq 'desc'}fa-sort-amount-desc{elseif $smarty.get.sort eq 'asc'}fa-sort-amount-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>创建日期</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{foreach $data as $item}
					<tr>
						<td>{$item.id}</td>
						<td>{$item.title|truncate:45:"...":true}</td>
						<td><span class="toggle-status label {if $item.type eq constant('App\Models\Article::TYPE_ABOUT')}label-danger{else}label-success{/if}" data-id="{$item.id}" data-status="{$item.status}">{trans('article.type.'|cat:$item.type)}</span></td>
						<td>{$item.sort}</td>
						<td>{$item.created_at|date_format:"%Y-%m-%d"}</td>
						<td>
							<a class="btn btn-sm btn-primary" href="{route('ArticleEdit', ['id'=>$item.id])}"><i class="icon-pencil"></i> 编辑</a>
							<a class="btn btn-sm btn-danger" data-toggle="modal" href="#DeleteConfirmModal" onclick="deleteConfirm({$item.id}, '{$item.title}')"><i class="icon-trash"></i> 删除</a>
						</td>
					</tr>
					{foreachelse}
						<tr><td colspan="12" class="text-center">无相关数据！</td></tr>
					{/foreach}
				</tbody>
			</table>
			<div class="text-center">
				{$data->appends(['key' => $smarty.get.key,'type' => $smarty.get.type])->render()}
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
                <p>您确定要删除“<strong id="article_title"></strong>”此文章吗？</p>
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('ArticleDelete')}">
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
function deleteConfirm(id, title){
	$("#id").val(id);
	$("#article_title").text(title);
}

//排序
$(".sort").click(function() {
    var url = "{route('ArticleIndex', ['page' => $smarty.get.page, 'key' => $smarty.get.key, 'type' => $smarty.get.type])}";
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
