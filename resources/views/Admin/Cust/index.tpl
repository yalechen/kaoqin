{extends file='Admin/layout.tpl'} 

{block title}商户管理{/block} 
{block breadcrumb}
<li class="active"><a href="{route('CustIndex')}">商户管理</a></li>
{/block} 

{block main}
<!-- <link rel="stylesheet" type="text/css" href="{asset('js/bootstrap-fileinput-master/css/fileinput.css')}" /> -->
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<form class="form-inline" role="form" action="{Route('CustIndex')}" method="get">
					<div class="form-group">
						<label class="sr-only" for="key">名称</label>
                        <input type="text" class="form-control" id="name" name="key" value="{$smarty.get.key}" placeholder="名称\联系人\手机号">
                    </div>
                    <div class="form-group">
						<select class="form-control" name="type">
							<option value="" {if !$smarty.get.type}selected{/if}>--所有类型--</option>
							<option value="{constant('App\Models\Cust::TYPE_STORE')}" {if constant('App\Models\Cust::TYPE_STORE') eq $smarty.get.type}selected{/if}>{trans('cust.type.'|cat:constant('App\Models\Cust::TYPE_STORE'))}</option>
							<option value="{constant('App\Models\Cust::TYPE_GROUP')}" {if constant('App\Models\Cust::TYPE_GROUP') eq $smarty.get.type}selected{/if}>{trans('cust.type.'|cat:constant('App\Models\Cust::TYPE_GROUP'))}</option>
                    	</select>
                    </div>
                    <div class="form-group">
						<select class="form-control" name="level_id">
							<option value="" {if !$smarty.get.level_id}selected{/if}>--所有等级--</option>
							{foreach $level as $item}
								<option value="{$item.id}" {if $item.id eq $smarty.get.level_id}selected{/if}>{$item.name}</option>
							{/foreach}
                    	</select>
                    </div>
					<button type="submit" class="btn btn-info"><i class="fa fa-search"></i> 查询</button>
					<a href="{route('CustEdit')}"><button type="button" class="btn btn-success"><i class="fa fa-plus"></i> 添加商户 </button></a>
				</form>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>logo</th>
						<th>编号&nbsp;&nbsp;<i data-val="{$smarty.get.number}" data-sort="number" class="number_sort fa {if $smarty.get.number eq 'desc'}fa-sort-amount-desc{elseif $smarty.get.number eq 'asc'}fa-sort-amount-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>名称</th>
						<th>类型</th>
						<th>等级&nbsp;&nbsp;<i data-val="{$smarty.get.level}" data-sort="level" class="level_sort fa {if $smarty.get.level eq 'desc'}fa-sort-amount-desc{elseif $smarty.get.level eq 'asc'}fa-sort-amount-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>巡店者&nbsp;&nbsp;<i data-val="{$smarty.get.user}" data-sort="user" class="user_sort fa {if $smarty.get.user eq 'desc'}fa-sort-amount-desc{elseif $smarty.get.user eq 'asc'}fa-sort-amount-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>联系人</th>
						<th>联系方式</th>
						<th>创建日期</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{foreach $data as $item}
					<tr>
						<td>{$item.id}</td>
						<td><img src="{$item.logo_path}" style="width:35px; height:35px;"/></td>
						<td>{$item.number}</td>
						<td>{$item.name}</td>
						<td><span class="toggle-status label {if $item.type eq constant('App\Models\Cust::TYPE_STORE')}label-danger{else}label-success{/if}" data-id="{$item.id}" data-status="{$item.status}">{trans('cust.type.'|cat:$item.type)}</span></td>
						<td>{$item.custLevel.name}</td>
						<td>{$item.user.name}</td>
						<td>{$item.contacts}</td>
						<td>{$item.mobile}</td>
						<td>{$item.created_at|date_format:"%Y-%m-%d"}</td>
						<td>
							<a class="btn btn-sm btn-primary" href="{route('CustEdit', ['id'=>$item.id])}"><i class="icon-pencil"></i> 编辑</a>
							<a class="btn btn-sm btn-danger" data-toggle="modal" href="#DeleteConfirmModal" onclick="deleteConfirm({$item.id}, '{$item.name}')"><i class="icon-trash"></i> 删除</a>
						</td>
					</tr>
					{foreachelse}
						<tr><td colspan="12" class="text-center">无相关数据！</td></tr>
					{/foreach}
				</tbody>
			</table>
			<div class="text-center">
				{$data->appends(['key' => $smarty.get.key,'type' => $smarty.get.type,'level_id' => $smarty.get.level_id])->render()}
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
                <p>您确定要删除“<strong id="cust_name"></strong>”商户吗？</p>
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('CustDelete')}">
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
<!-- <script type="text/javascript" src="{asset('js/bootstrap-fileinput-master/js/fileinput.js')}"></script>
<script type="text/javascript" src="{asset('js/file-input-init.js')}"></script> -->
<script type="text/javascript">
//删除确认
function deleteConfirm(id, name){
	$("#id").val(id);
	$("#cust_name").text(name);
}

//排序
$(".number_sort,.user_sort,.level_sort").click(function() {
    var url = "{route('CustIndex', ['page' => $smarty.get.page, 'key' => $smarty.get.key, 'type' => $smarty.get.type, 'level_id' => $smarty.get.level_id])}";
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
