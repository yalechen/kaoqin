{extends file='Admin/layout.tpl'} 

{block title}机构管理{/block} 
{block breadcrumb}
<li class="active"><a href="{route('OrgIndex')}">机构管理</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<!-- <header class="panel-heading head-border"> 机构管理 </header> -->
			<div class="panel-body">
				<form class="form-inline" role="form" action="{Route('OrgIndex')}" method="get">
					<div class="form-group">
						<label class="sr-only" for="key">名称</label>
                        <input type="text" class="form-control" id="name" name="key" value="{$smarty.get.key}" placeholder="关键字">
                    </div>
                    <div class="form-group">
						<select class="form-control" name="org_id">
							<option value="0" {if !$smarty.get.org_id}selected{/if}>--所有机构--</option>
							{foreach $org as $item}
								<option value="{$item.id}" {if $item.id eq $smarty.get.org_id}selected{/if}>{$item.name}</option>
							{/foreach}
                    	</select>
                    </div>
					<button type="submit" class="btn btn-info"><i class="fa fa-search"></i> 查询</button>
					<a href="{route('OrgEdit')}"><button type="button" class="btn btn-success"><i class="fa fa-plus"></i> 添加机构 </button></a>
				</form>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>编号&nbsp;&nbsp;<i data-val="{$smarty.get.number}" data-sort="number" class="number_sort fa {if $smarty.get.number eq 'desc'}fa-sort-amount-desc{elseif $smarty.get.number eq 'asc'}fa-sort-amount-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>名称</th>
						<th>上级机构</th>
						<th>所属区域</th>
						<th>排序值 &nbsp;&nbsp;<i data-val="{$smarty.get.sort}" data-sort="sort" class="sort fa {if $smarty.get.sort eq 'desc'}fa-sort-amount-desc{elseif $smarty.get.sort eq 'asc'}fa-sort-amount-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>创建日期 &nbsp;&nbsp;<i data-val="{$smarty.get.id}" data-sort="id" class="id_sort fa {if $smarty.get.id eq 'desc'}fa-sort-amount-desc{elseif $smarty.get.id eq 'asc'}fa-sort-amount-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{if $data}
						{foreach $data as $item}
						<tr>
							<td>{$item.id}</td>
							<td>{$item.number}</td>
							<td>{$item.name}</td>
							<td>{$item->parentNode()->first()->name}</td>
							<td>{$item->area}</td>
							<td><span class="badge bg-success">{$item->sort}</span></td>
							<td>{$item.created_at|date_format:"%Y-%m-%d"}</td>
							<td>
								<a class="btn btn-sm btn-primary" href="{route('OrgEdit', ['id'=>$item.id])}"><i class="icon-pencil"></i> 编辑</a>
								<a class="btn btn-danger btn-sm" data-toggle="modal" href="#DeleteConfirmModal" onclick="deleteConfirm({$item.id}, '{$item.name}')"><i class="icon-trash"></i> 删除</a>
							</td>
						</tr>
						{foreachelse}
							<tr><td colspan="8" class="text-center">无相关数据！</td></tr>
						{/foreach}
					{/if}
				</tbody>
			</table>
			<div class="text-center">
				{$data->appends(['key' => $smarty.get.key,'org_id'=>$smarty.get.org_id])->render()}
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
                <p>您确定要删除“<strong id="org_name"></strong>”机构吗？</p>
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('OrgDelete')}">
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
function deleteConfirm(id, name){
	//$('#DeleteConfirmModal').find('.modal-body strong').text(name).end().find('form [name="id"]').val(id).end().modal();
	$("#id").val(id);
	$("#org_name").text(name);
}

//排序
$(".sort,.id_sort,.number_sort").click(function() {
    var url = "{route('OrgIndex', ['page' => $smarty.get.page, 'key' => $smarty.get.key, 'org_id' => $smarty.get.org_id])}";
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
