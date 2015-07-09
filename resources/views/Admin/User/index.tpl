{extends file='Admin/layout.tpl'} 

{block title}用户管理{/block} 
{block breadcrumb}
<li class="active"><a href="{route('UserIndex')}">用户管理</a></li>
{/block} 

{block main}
<link rel="stylesheet" type="text/css" href="{asset('js/bootstrap-fileinput-master/css/fileinput.css')}" />
<style>
.auto_height { height: 350px; OVERFLOW-Y: auto; SCROLLBAR-FACE-COLOR: #ffffff; }
</style>
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<form class="form-inline" role="form" action="{Route('UserIndex')}" method="get">
					<div class="form-group">
						<label class="sr-only" for="key">名称</label>
                        <input type="text" class="form-control" id="name" name="key" value="{$smarty.get.key}" placeholder="用户名\姓名\手机号">
                    </div>
					<button type="submit" class="btn btn-info"><i class="fa fa-search"></i> 查询</button>
					<a href="{route('UserEdit')}"><button type="button" class="btn btn-success"><i class="fa fa-plus"></i> 添加用户 </button></a>
				</form>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<!-- <th style="width: 20px;"><input type="checkbox" class="group-checkable" data-set="#goods_item_list .checkboxes" id="checkAll" /></th> -->
						<th>#</th>
						<th>头像</th>
						<th>编号&nbsp;&nbsp;<i data-val="{$smarty.get.number}" data-sort="number" class="number_sort fa {if $smarty.get.number eq 'desc'}fa-sort-amount-desc{elseif $smarty.get.number eq 'asc'}fa-sort-amount-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>用户名&nbsp;&nbsp;<i data-val="{$smarty.get.name}" data-sort="name" class="name_sort fa {if $smarty.get.name eq 'desc'}fa-sort-alpha-desc{elseif $smarty.get.name eq 'asc'}fa-sort-alpha-asc{else}fa-arrows-v{/if}" style="cursor: pointer;"></i></th>
						<th>姓名</th>
						<th>手机号</th>
						<th>上级</th>
						<th>所属部门</th>
						<th>状态</th>
						<th>创建日期</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{foreach $data as $item}
					<tr>
						<td>{$item.id}</td>
						<td><img src="{$item.avatar_path}" style="width:35px; height:35px;"/></td>
						<td>{$item.number}</td>
						<td>{$item.name}</td>
						<td>{$item.realname}</td>
						<td>{$item.mobile}</td>
						<td>{$item.leader.realname}</td>
						<td>{if $item.dept_id gt 0}{$item->dept->name}{else}<span class="toggle-status label label-danger">未指派</span>{/if}</td>
						<td><span class="toggle-status label {if $item.status eq constant('App\Models\User::STATUS_OFF')}label-danger{else}label-success{/if}" data-id="{$item.id}" data-status="{$item.status}">{trans('user.status.'|cat:$item.status)}</span></td>
						<td>{$item.created_at|date_format:"%Y-%m-%d"}</td>
						<td>
							<a class="btn btn-sm btn-warning" data-toggle="modal" href="#parentUserModal" onclick="parentUserAssign({$item.id}, '{$item.name}')"><i class="icon-user"></i> 上级</a>
							<a class="btn btn-sm btn-success" data-toggle="modal" href="#custModal" onclick="custAssign({$item.id}, '{$item.name}')"><i class="icon-star"></i> 巡店</a>
							<a class="btn btn-sm btn-primary" href="{route('UserEdit', ['id'=>$item.id])}"><i class="icon-pencil"></i> 编辑</a>
							<a class="btn btn-sm btn-danger" data-toggle="modal" href="#DeleteConfirmModal" onclick="deleteConfirm({$item.id}, '{$item.name}')"><i class="icon-trash"></i> 删除</a>
						</td>
					</tr>
					{foreachelse}
						<tr><td colspan="9" class="text-center">无相关数据！</td></tr>
					{/foreach}
				</tbody>
			</table>
			<div class="text-center">
				{$data->appends(['key' => $smarty.get.key])->render()}
			</div>
		</section>
	</div>
</div>

<!-- Modal data-toggle="modal" href="#AssignCustModal"  -->
<div class="modal fade" id="DeleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">删除确认</h4>
            </div>
            <div class="modal-body">
                <p>您确定要删除“<strong id="user_name"></strong>”用户吗？</p>
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('UserDelete')}">
	            	<input type="hidden" id="id" name="id" value="" >
	                <button class="btn btn-danger" type="submit"> 确认</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- modal -->

<!-- Modal -->
<div class="modal fade" id="parentUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">指派上级领导</h4>
            </div>
            <div class="modal-body">
            	接下来您将给“<STRONG id="parent_user_objname"></STRONG>”指派上级领导，请使用关键字搜索
            	<div class="row">
	                <div class="col-sm-12">
						<section class="panel">
							<div class="panel-body">
								<form class="form-inline" role="form" id="parent_user_search_form">
									<div class="form-group">
										<label class="sr-only" for="key">关键字</label>
				                        <input type="text" class="form-control" id="modal_key" name="modal_key" value="{$smarty.get.modal_key}" placeholder="用户名\姓名\手机号">
				                    </div>
				                    <INPUT type="hidden" value="" name="user_id" id="user_id" />
									<button type="button" class="btn btn-info" id="ParentUserFind"><i class="fa fa-search"></i> 查询</button>
								</form>
							</div>
							<div id="UsersList">
							
							</div>
						</section>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">关闭</button>
                <!-- <button class="btn btn-success" type="button">关闭</button> -->
            </div>
        </div>
    </div>
</div>
<!-- modal -->

<!-- Modal -->
<div class="modal fade" id="custModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">指派用户所巡商户</h4>
            </div>
            <div class="modal-body">
            	接下来您将给“<STRONG id="user_objname"></STRONG>”指派ta所巡的商户门店，可使用搜索查找
            	<div class="row">
	                <div class="col-sm-12">
						<section class="panel">
							<div class="panel-body">
								<form class="form-inline" role="form" id="user_search_form">
									<div class="form-group">
										<label class="sr-only" for="key">关键字</label>
				                        <input type="text" class="form-control" id="modal_key" name="modal_key" value="{$smarty.get.modal_key}" placeholder="用户名\姓名\手机号">
				                    </div>
				                    <div class="form-group">
				                    	<select class="form-control" name="type">
											<option value="" {if !$smarty.get.org_id}selected{/if}>--所有类型--</option>
											<option value="{constant('App\Models\Cust::TYPE_STORE')}" {if constant('App\Models\Cust::TYPE_STORE') eq $smarty.get.type}selected{/if}>{trans('cust.type.'|cat:constant('App\Models\Cust::TYPE_STORE'))}</option>
											<option value="{constant('App\Models\Cust::TYPE_GROUP')}" {if constant('App\Models\Cust::TYPE_GROUP') eq $smarty.get.type}selected{/if}>{trans('cust.type.'|cat:constant('App\Models\Cust::TYPE_GROUP'))}</option>
				                    	</select>
				                    </div>
				                    <INPUT type="hidden" value="" name="user_objid" id="user_objid" />
									<button type="button" class="btn btn-info" id="UserFind"><i class="fa fa-search"></i> 查询</button>
								</form>
							</div>
							<div id="UsersList2">
							
							</div>
						</section>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button" id="closeModal">取消指派</button>
                <button data-dismiss="modal" class="btn btn-success" type="button" id="confirmAssign">批量指派</button>
            </div>
        </div>
    </div>
</div>
<!-- modal -->
{/block} 

{block script} 
<script type="text/javascript" src="{asset('js/bootstrap-fileinput-master/js/fileinput.js')}"></script>
<script type="text/javascript">
//删除确认
function deleteConfirm(id, name){
	$("#id").val(id);
	$("#user_name").text(name);
}

//上级领导指派
function parentUserAssign(id, name){
	$("#user_id").val(id);
	$("#parent_user_objname").text(name);
}

//上级模态框的搜索
$("#ParentUserFind").click(function() {
    $.get('{route("SearchParentUsers")}', $("#parent_user_search_form").serialize(), function(data) {
        $("#UsersList").html(data);
    },'html');
    $("#UsersList").addClass("auto_height");
});

//巡店指派
function custAssign(id, name){
	$("#user_objid").val(id);
	$("#user_objname").text(name);
}

//巡店模态框的搜索
$("#UserFind").click(function() {
    $.get('{route("SearchCust")}', $("#user_search_form").serialize(), function(data) {
        $("#UsersList2").html(data);
    },'html');
    $("#UsersList2").addClass("auto_height"); 
});

//批量指派所巡店的商户
$("#confirmAssign").click(function() {
	var ids=$("#ids").val();
	var user_id=$("#user_objid").val();
	var user_name=$("#user_objname").text();
	if(user_id>0 && ids){
		iconfirm('确认要给'+user_name+'用户指派以上选择的商户门店吗？', function() {
			$.ajax({
				type: 'POST',
		        url: '{route("UserAssignCustSave")}',
		        data: { user_id : user_id, cust_id : ids },
		        dataType: 'text',
		        success: function(data) {
		        	alert("指派成功");
		        },
		        error: function(){
		        	alert("指派失败");
		        }
		    });
        });
	}else{
		alert("批量指派失败，请选择商户门店");
	}
});

//省市区下拉
$("#province").change(function() {
    getCity($(this));
});

function getCity(obj) {
    var province_id = $("#province").val();
    var city_id = "{$data.city_id}";
    obj.nextAll().remove();
	if( province_id > 0){
		$.ajax({
	        url: '{route("CityPull")}',
	        data: { province_id : province_id },
	        success: function(data) {
	            var html = '<select class="form-control" name="city_id" id="city"><option value="0">--所有城市--</option>';
	            for (var i in data) {
	            	if(data[i]['id'] == city_id){
	            		html += "<option value='"+data[i]['id']+"' selected >"+data[i]['name']+"</option>"
	            	}else{
	            		html += "<option value='"+data[i]['id']+"'>"+data[i]['name']+"</option>"
	            	}
	                
	            }
	            html +='</select>';
	            obj.parent().append(html);
	        }
	    });
	}
}

//更换状态
$(".toggle-status").click(function() {
    var id = $(this).attr('data-id');
    var status = $(this).attr('data-status');
    if (id>0) {
        var title = '在职';
        if (status == '{constant('App\Models\Dept::STATUS_ON')}') {
            title = '离职';
        }
        iconfirm('确认要将此用户更新为'+title+'吗？', function() {
            $.post('{route('UserStatusChange')}', { id : id, status: status }, function(data) {
                window.location.reload();
            }, 'text');
        });
    }
});

//排序
$(".number_sort,.name_sort").click(function() {
    var url = "{route('UserIndex', ['page' => $smarty.get.page, 'key' => $smarty.get.key])}";
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
