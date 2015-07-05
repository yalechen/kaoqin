{extends file='Admin/layout.tpl'} 

{block title}部门管理{/block} 
{block breadcrumb}
<li class="active"><a href="{route('DeptIndex')}">部门管理</a></li>
{/block} 

{block main}
<style>
.toggle-status { cursor:pointer; }
.auto_height { height: 350px; OVERFLOW-Y: auto; SCROLLBAR-FACE-COLOR: #ffffff; }
</style>
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<form class="form-inline" role="form" action="{Route('DeptIndex')}" method="get">
					<div class="form-group">
						<label class="sr-only" for="key">名称</label>
                        <input type="text" class="form-control" id="name" name="key" value="{$smarty.get.key}" placeholder="关键字">
                    </div>
                    <div class="form-group">
						<select class="form-control" name="org_id">
							<option value="0" {if !$smarty.get.org_id}selected{/if}>--全部机构--</option>
							{foreach $org as $item}
								<option value="{$item.id}" {if $item.id eq $smarty.get.org_id}selected{/if}>{$item.name}</option>
							{/foreach}
                    	</select>
                    </div>
					<button type="submit" class="btn btn-info"><i class="fa fa-search"></i> 查询</button>
					<a href="{route('DeptEdit')}"><button type="button" class="btn btn-success"><i class="fa fa-plus"></i> 添加部门 </button></a>
				</form>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>名称</th>
						<th>所属机构</th>
						<th>状态</th>
						<th>排序值 &nbsp;&nbsp;<i data-val="{$smarty.get.sort}" data-sort="sort" class="sort fa {if $smarty.get.sort eq 'desc'}fa-sort-amount-desc{elseif $smarty.get.sort eq 'asc'}fa-sort-amount-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>创建日期 &nbsp;&nbsp;<i data-val="{$smarty.get.id}" data-sort="id" class="id_sort fa {if $smarty.get.sort eq 'desc'}fa-sort-amount-desc{elseif $smarty.get.sort eq 'asc'}fa-sort-amount-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{if $data}
						{foreach $data as $item}
						<tr>
							<td>{$item.id}</td>
							<td>{$item.name}</td>
							<td>{$item->org->name}</td>
							<td><span class="toggle-status label {if $item.status eq constant('App\Models\Dept::STATUS_OFF')}label-danger{else}label-success{/if}" data-id="{$item.id}" data-status="{$item.status}">{trans('dept.status.'|cat:$item.status)}</span></td>
							<td>{$item.sort}</td>
							<td>{$item.created_at|date_format:"%Y-%m-%d"}</td>
							<td>
								<a class="btn btn-sm btn-info" data-toggle="modal" href="#UserModal" onclick="setUser({$item.id}, '{$item.name}')"><i class="icon-emoticon-user"></i> 指派员工</a>
								<a class="btn btn-sm btn-primary" href="{route('DeptEdit', ['id'=>$item.id])}"><i class="icon-pencil"></i> 编辑</a>
								<a class="btn btn-danger btn-sm" data-toggle="modal" href="#DeleteConfirmModal" onclick="deleteConfirm({$item.id}, '{$item.name}')"><i class="icon-trash"></i> 删除</a>
							</td>
						</tr>
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
                <p>您确定要删除“<strong id="dept_name"></strong>”部门吗？</p>
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('DeptDelete')}">
	            	<input type="hidden" id="id" name="id" value="" >
	                <button class="btn btn-danger" type="submit"> 确认</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- modal -->

<!-- Modal -->
<div class="modal fade" id="UserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">给“<STRONG id="assign_dept_name"></STRONG>”部门指派员工</h4>
            </div>
            <div class="modal-body">
            	接下来您将给“<STRONG id="parent_user_objname"></STRONG>”指派上级领导，请使用关键字搜索
            	<div class="row">
	                <div class="col-sm-12">
						<section class="panel">
							<div class="panel-body">
								<form class="form-inline" role="form" id="user_search_form">
									<div class="form-group">
										<label class="sr-only" for="key">关键字</label>
				                        <input type="text" class="form-control" id="modal_key" name="modal_key" value="{$smarty.get.modal_key}" placeholder="用户名\姓名\手机号">
				                    </div>
				                    <INPUT type="hidden" value="" name="dept_id" id="dept_id" />
									<button type="button" class="btn btn-info" id="UserFind"><i class="fa fa-search"></i> 查询</button>
								</form>
							</div>
							<div id="UsersList">
							
							</div>
						</section>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-success" type="button" id="closeAvatarModal">确认指派</button>
                <input type="hidden" value="" id="dept_id" />
                <input type="hidden" value="" id="target_user_id" />
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
	$("#id").val(id);
	$("#dept_name").text(name);
}

//更换状态
$(".toggle-status").click(function() {
    var id = $(this).attr('data-id');
    var status = $(this).attr('data-status');
    if (id>0) {
        var title = '开启';
        if (status == '{constant('App\Models\Dept::STATUS_ON')}') {
            title = '关闭';
        }
        iconfirm('确认要'+title+'此部门吗？', function() {
            $.post('{route('DeptStatusChange')}', { id : id, status: status }, function(data) {
                window.location.reload();
            }, 'text');
        });
    }
});

//排序
$(".sort,.id_sort").click(function() {
    var url = "{route('DeptIndex', ['page' => $smarty.get.page, 'key' => $smarty.get.key, 'org_id' => $smarty.get.org_id])}";
    var val = $(this).attr('data-val');
    var sort = $(this).attr('data-sort');
    if (val != 'desc') {
        url += '&'+sort+'=desc';
    } else {
        url += '&'+sort+'=asc';
    }
    window.location.href = url;
});

//指派用户
function setUser(id, name){
	$("#dept_id").val(id);
	$("#assign_dept_name").text(name);
} 

//指派用户模态框的搜索
$("#UserFind").click(function() {
    $.get('{route("SearchUsers")}', $("#user_search_form").serialize(), function(data) {
        $("#UsersList").html(data);
    },'html');
    $("#UsersList").addClass("auto_height");
});
</script>
{/block}
