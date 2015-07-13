{extends file='Admin/layout.tpl'} 

{block title}角色管理{/block} 
{block breadcrumb}
<li class="active"><a href="{route('RoleIndex')}">角色管理</a></li>
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
				<form class="form-inline" role="form" action="{Route('RoleIndex')}" method="get">
					<div class="form-group">
						<label class="sr-only" for="key">名称</label>
                        <input type="text" class="form-control" id="name" name="key" value="{$smarty.get.key}" placeholder="key\名称">
                    </div>
					<button type="submit" class="btn btn-info"><i class="fa fa-search"></i> 查询</button>
					<a href="{route('RoleEdit')}"><button type="button" class="btn btn-success"><i class="fa fa-plus"></i> 添加角色 </button></a>
				</form>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>标识&nbsp;&nbsp;<i data-val="{$smarty.get.key_sort}" data-sort="key_sort" class="key_sort fa {if $smarty.get.key_sort eq 'desc'}fa-sort-amount-desc{elseif $smarty.get.key_sort eq 'asc'}fa-sort-amount-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>名称</th>
						<th>备注</th>
						<th>状态</th>
						<th>创建日期</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{foreach $data as $item}
					<tr>
						<td>{$item.id}</td>
						<td>{$item.key}</td>
						<td>{$item.name}</td>
						<td>{$item.remark|truncate:35:"...":true}</td>
						<td><span class="toggle-status label {if $item.status eq constant('App\Models\Role::STATUS_OFF')}label-danger{else}label-success{/if}" data-id="{$item.id}" data-status="{$item.status}">{trans('role.status.'|cat:$item.status)}</span></td>
						<td>{$item.created_at|date_format:"%Y-%m-%d"}</td>
						<td>
							<a class="btn btn-sm btn-success" href="{route('RoleUsers', ['role_id'=>$item.id])}"><i class="icon-users"></i> 指派成员</a>
							<a class="btn btn-sm btn-info" data-toggle="modal" href="#AssignPurviewModal" onclick="assignPurview({$item.id}, '{$item.name}')"><i class="icon-badge"></i> 指派权限</a>
							<a class="btn btn-sm btn-primary" href="{route('RoleEdit', ['id'=>$item.id])}"><i class="icon-pencil"></i> 编辑</a>
							<a class="btn btn-sm btn-danger" data-toggle="modal" href="#DeleteConfirmModal" onclick="deleteConfirm({$item.id}, '{$item.name}')"><i class="icon-trash"></i> 删除</a>
						</td>
					</tr>
					{foreachelse}
						<tr><td colspan="7" class="text-center">无相关数据！</td></tr>
					{/foreach}
				</tbody>
			</table>
			<div class="text-center">
				{$data->appends(['key' => $smarty.get.key])->render()}
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
                <p>您确定要删除“<strong id="role_name"></strong>”角色吗？</p>
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('RoleDelete')}">
	            	<input type="hidden" id="id" name="id" value="" >
	                <button class="btn btn-danger" type="submit"> 确认</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- modal -->

<!-- Modal -->
<div class="modal fade" id="AssignPurviewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">指派<b id="assign_role_name"></b>角色的权限</h4>
            </div>
            <div class="modal-body">
            	<div class="row">
	                <div class="col-sm-12">
						<section class="panel">
							<div class="panel-body auto_height">
								<ul id="treePermission" class="ztree"></ul>
							</div>
						</section>
					</div>
				</div>
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('RolePurviewAssign')}">
	            	<input type="hidden" id="role_id" name="role_id" value="" >
	                <button class="btn btn-danger" type="button" id="assignConfirmPurviewBtn"> 确认</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- modal -->
{/block} 

{block script} 
<link rel="stylesheet" href="{asset('js/zTree_v3-master/css/zTreeStyle/zTreeStyle.css')}" type="text/css">
<script type="text/javascript" src="{asset('js/zTree_v3-master/js/jquery.ztree.core-3.5.min.js')}"></script>
<script type="text/javascript" src="{asset('js/zTree_v3-master/js/jquery.ztree.excheck-3.5.min.js')}"></script>
<script type="text/javascript">
//删除确认
function deleteConfirm(id, name){
	$("#id").val(id);
	$("#role_name").text(name);
}

//排序
$(".key_sort").click(function() {
    var url = "{route('RoleIndex', ['page' => $smarty.get.page, 'key' => $smarty.get.key])}";
    var val = $(this).attr('data-val');
    var sort = $(this).attr('data-sort');
    if (val != 'desc') {
        url += '&'+sort+'=desc';
    } else {
        url += '&'+sort+'=asc';
    }
    window.location.href = url;
});

//指派权限弹出框
function assignPurview(role_id, name){
	$("#role_id").val(role_id);
	$("#assign_role_name").text(name);
	//加载树
    $.ajax({
        "type":"GET",
        "dataType":'json',
        "url": "{route('GetPurviewByRoleId')}",
        "data": { role_id : role_id },
        success:function(data){
            /*var zNodes =[
             id:2, pId:0, name:"随意勾选 2", checked:true, open:true},
             ];*/
            var zNodes = [];
            var size = data.length;
            for ( var i = 0; i < size; i++) {
                var node = {
                    id : data[i].id,
                    pId : data[i].pid,
                    key : data[i].key,
                    name : data[i].name,
                    checked : data[i].checked,
                    open : true
                };
                zNodes.push(node);
            }

            var setting = {
                check: {
                    enable: true
                },
                data: {
                    simpleData: {
                        enable: true
                    }
                }
            };
            $.fn.zTree.init($("#treePermission"), setting, zNodes);
        },
        error:function(e){

        }
    });
}

//选择完成，确认指派权限
$("#assignConfirmPurviewBtn").click(function(){
    var purview_ids = [];
    var treeObj = $.fn.zTree.getZTreeObj("treePermission");//获得对象
    var nodes = treeObj.getCheckedNodes(true);//获取所选节点的数据
    var role_id = $("#role_id").val();

    if ( nodes.length < 1 ){
        ialert("请选择角色权限！");
        return false;
    } else {
        for ( var i = 0; i < nodes.length; i++) {
            purview_ids[i] = nodes[i].id; // 获取选中节点的值
        }
        $.ajax({
            "type" : 'POST',
            "dataType" : 'json',
            "url" : "{route("RolePurviewAssign")}",
            "data" : { role_id : role_id , purview_ids : purview_ids },
            success : function(data){
            	//ialert("指派权限成功");
                window.location.href = "{route("RoleIndex")}";
            },
            error : function(e){
                ialert(e.responseText);
            }
        });

    }
});
</script>
{/block}
