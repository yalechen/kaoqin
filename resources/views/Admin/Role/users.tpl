{extends file='Admin/layout.tpl'} 

{block title}角色成员指派{/block} 
{block breadcrumb}
<li><a href="{route('RoleIndex')}">角色管理</a></li>
<li class="active"><a href="{route('RoleUsers')}">角色成员指派</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<form class="form-inline" role="form" action="{Route('RoleUsers')}" method="get">
					<div class="form-group">
						<label class="sr-only" for="key">名称</label>
                        <input type="text" class="form-control" id="key" name="key" value="{$smarty.get.key}" placeholder="手机号\姓名">
                    </div>
					<button type="submit" class="btn btn-info"><i class="fa fa-search"></i> 查询</button>
					<sapn class="help-inline" style="font-size:16px;">&nbsp;您将给<b>{$role.name}</b>指派用户</span>
				</form>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th style="width: 20px;"><input type="checkbox" id="checkAll" /></th>
						<th>#</th>
						<th>头像</th>
						<th>编号&nbsp;&nbsp;<i data-val="{$smarty.get.number}" data-sort="number" class="number_sort fa {if $smarty.get.number eq 'desc'}fa-sort-amount-desc{elseif $smarty.get.number eq 'asc'}fa-sort-amount-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>姓名</th>
						<th>手机号</th>
						<th>所属部门</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{foreach $users as $item}
					{if in_array($item.id,$assigned_user_ids)}{assign 'flag' 1}{else}{assign 'flag' 0}{/if}
					<tr>
						<td><input type="checkbox" class="checkboxes" value="{$item.id}" {if $flag eq 1}checked{/if} /></td>
						<td>{$item.id}</td>
						<td><img src="{$item.avatar_path}" style="width:35px; height:35px;"/></td>
						<td>{$item.number}</td>
						<td>{$item.realname}</td>
						<td>{$item.mobile}</td>
						<td>{if $item.dept_id gt 0}{$item->dept->name}{else}<span class="toggle-status label label-danger">未指派</span>{/if}</td>
						<td>
							<a class="btn btn-sm btn-success" href="javascript:;" onclick="userAssign({$role_id}, '{$role.name}',{$item.id},'{$item.realname}',{$flag})"><i class="icon-star"></i> 指派</a>
						</td>
					</tr>
					{foreachelse}
						<tr><td colspan="9" class="text-center">无相关数据！</td></tr>
					{/foreach}
				</tbody>
			</table>
			<div>
				<div class="col-lg-3" style="margin: 20px 0px; text-align:left;">
					<button type="button" class="btn btn-primary" id="multiAssignBtn" onclick="multiAssign({$role_id},'{$role.name}')">单页批量指派</button>
					<input type="hidden" name="ids" id="ids" value="{$assigned_user_ids_str}"/>
				</div>
				<div class="col-lg-9" style="text-align:right;">{$users->appends(['role_id'=>$role_id,'key' => $smarty.get.key])->render()}</div>
			</div>
		</section>
	</div>
</div>
{/block} 

{block script} 
<script type="text/javascript">
//确认 flag=1标识单个指派
function userAssign(role_id,role_name, user_id,real_name,flag){
	var desc='指派';
	if(flag==1){
		var desc='取消指派';
		//取消选中复选框
		//$(this).parent().parent().find(".checkboxes").prop('checked',false);
	}
	if(role_id>0 && user_id>0){
		iconfirm('确认要给<b>"'+role_name+'"</b>角色<b>'+desc+'"'+real_name+'"</b>用户吗？', function() {
			$.ajax({
				type: 'POST',
		        url: '{route("RoleUserAssign")}',
		        data: { role_id : role_id, user_id : user_id, flag:1 },
		        dataType: 'text',
		        success: function(data) {
		        	ialert(data);
		        	window.location.reload();//用reload会出现一种问题，就是当批量指派完，再进行单个取消指派，则会reload后还会选中文本框的问题
		        	//window.location.href='{route('RoleUsers')}'+'?role_id='+role_id;
		        },
		        error: function(xhq){
		        	ialert(xhq.responseText);
		        }
		    });
        });
	}else{
		ialert('参数错误，请选择角色和用户');
	}
}

//全部选择,仅单页全选
$("#checkAll").click(function(){
    if ($(this).attr("checked")) {
    	$(this).prop('checked',true);
        $(".checkboxes").prop('checked',true);
        //添加当页全部用户ID
        $("#ids").val('{$user_ids_str}');
    } else {
    	$(this).prop('checked',false);
        $(".checkboxes").prop('checked',false);
        //清空ids
        $("#ids").val('');
    }
});

//单个复选框选择时，移除和添加元素
$(".checkboxes").click(function(){
	nids=new Array();
	$(".checkboxes").each(function(){
		if($(this).attr("checked")){
			nids.push($(this).val());
			$("#ids").val(nids);
		}
	});
});

//批量指派用户 flag=2标识批量指派
function multiAssign(role_id,role_name){
	var user_ids=$("#ids").val();
	var all_user_ids='{$user_ids_str}';
	if(user_ids!='' && role_id>0 && all_user_ids!=''){
		iconfirm('确认要给<b>"'+role_name+'"</b>角色批量指派此页中勾选的这些用户吗？', function() {
			$.ajax({
				type: 'POST',
		        url: '{route("RoleUserAssign")}',
		        data: { role_id : role_id, user_id : user_ids, all_user_ids : all_user_ids, flag:2 },
		        dataType: 'text',
		        success: function(data) {
		        	ialert(data);
		        	window.location.reload();
		        },
		        error: function(xhq){
		        	ialert(xhq.responseText);
		        }
		    });
        });
	}else{
		ialert('参数错误，请选择角色和用户');
	}
};

//排序
$(".number_sort").click(function() {
    var url = "{route('RoleUsers', ['page' => $smarty.get.page, 'role_id' => $smarty.get.role_id, 'key' => $smarty.get.key])}";
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
