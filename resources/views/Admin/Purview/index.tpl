{extends file='Admin/layout.tpl'} 

{block title}权限管理{/block} 
{block breadcrumb}
<li class="active"><a href="{route('PurviewIndex')}">权限管理</a></li>
{/block} 

{block css}
<!--fuelux tree-->
<link rel="stylesheet" type="text/css" href="{asset('js/fuelux/css/tree-style.css')}" />
{/block}

{block main}
<div class="row">
	<div class="col-sm-12">
		{$html}
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="nodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><span id="operaname"></span>权限</h4>
            </div>
            <div class="modal-body">
            	<div class="row">
	                <div class="col-sm-12">
						<section class="panel" style="margin-bottom:0px;">
							<div class="panel-body">
								<form class="form-horizontal" role="form" id="form">
								<div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label" for="name">名称</label>
                                    <div class="col-lg-10"><input class="form-control " id="name" name="name" required /></div>
                                </div>
								<div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label" for="key">标识key</label>
                                    <div class="col-lg-10"><input class="form-control " id="key" name="key" required /></div>
                                </div>
								<div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label" for="parent_id">上级权限</label>
                                    <div class="col-lg-10">
                                    	<select class="form-control m-b-10" id="parent_id" name="parent_id">
				                            <option value="">第一级</option>
				                            {foreach $list as $item}
				                                {if $item.name}
				                                <option value="{$item.id}">{str_repeat('&nbsp;&nbsp;&nbsp;', count(explode(':', $item.path))-1)}{$item.name}</option>
				                                {/if}
				                            {/foreach}
				                        </select>
			                        </div>
                                </div>
								<div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label" for="controller">控制器</label>
                                    <div class="col-lg-10"><input class="form-control " id="controller" name="controller" required /></div>
                                </div>
								<div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label" for="action">所属方法</label>
                                    <div class="col-lg-10"><input class="form-control " id="action" name="action" required /></div>
                                </div>
								<div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label" for="condition">附加条件</label>
                                    <div class="col-lg-10"><input class="form-control " id="condition" name="condition" /></div>
                                </div>
								<div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label" for="sort">排序号</label>
                                    <div class="col-lg-10"><input class="form-control " id="sort" name="sort" value="100" required/></div>
                                </div>
								<div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label" for="type">所属类型</label>
                                    <div class="col-lg-10"><div class="radio-custom radio-success">
			                            <input type="radio" value="{constant('App\Models\Purview::TYPE_MENU')}" name="type" id="menu">
			                            <label for="menu">{trans('purview.type.'|cat:constant('App\Models\Purview::TYPE_MENU'))}</label>
			                            <input type="radio" value="{constant('App\Models\Purview::TYPE_ACTION')}" name="type" id="action2">
			                            <label for="action2">{trans('purview.type.'|cat:constant('App\Models\Purview::TYPE_ACTION'))}</label>
		                        	</div></div>
                                </div>
								<div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label" for="useon">使用平台</label>
                                    <div class="col-lg-10"><div class="radio-custom radio-success">
			                            <input type="radio" value="{constant('App\Models\Purview::USEON_PC')}" name="useon" id="pc">
			                            <label for="pc">{trans('purview.useon.'|cat:constant('App\Models\Purview::USEON_PC'))}</label>
			                            <input type="radio" value="{constant('App\Models\Purview::USEON_APP')}" name="useon" id="app">
			                            <label for="app">{trans('purview.useon.'|cat:constant('App\Models\Purview::USEON_APP'))}</label>
		                        	</div></div>
                                </div>
								<div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label" for="status">状态</label>
                                    <div class="col-lg-10"><div class="radio-custom radio-success">
			                            <input type="radio" value="{constant('App\Models\Purview::STATUS_ON')}" name="status" id="on">
			                            <label for="on">{trans('purview.status.'|cat:constant('App\Models\Purview::STATUS_ON'))}</label>
			                            <input type="radio" value="{constant('App\Models\Purview::STATUS_OFF')}" name="status" id="off">
			                            <label for="off">{trans('purview.status.'|cat:constant('App\Models\Purview::STATUS_OFF'))}</label>
		                        	</div></div>
                                </div>
                                </form>
							</div>
						</section>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">关闭</button>
                <button type="button" class="btn btn-primary" id="submitAction">确定</button>
                <!-- <button class="btn btn-success" type="button">关闭</button>data-dismiss="modal"  -->
            </div>
        </div>
    </div>
</div>
<!-- modal -->
{/block} 

{block script}
<script type="text/javascript" src="{asset('js/bootstrap-tooltip.js')}"></script>
<script type="text/javascript" src="{asset('js/bootstrap-popover.js')}"></script>
<script type="text/javascript">
$('.node').popover({
    html: true,
    title: '操作',
    container: 'body',
    content: '<div class="node_action"><a href="javascript:;" class="editNode">编辑</a> <a href="javascript:;" class="deleteNode">删除</a> <a href="javascript:;" class="addNode">添加</a></div>',
    trigger: 'click',
    placement: 'top',
    delay: { "show": 100, "hide" : 300 }
});

$('.node').click(function() {
    $('.node_action').attr('data-id', $(this).attr('data-id'));
});

$(document).on('click', '.addNode', function() {
    $('.node').popover('hide');
    var id = $(this).parent().attr('data-id');
    $("#purview_id").val('');
    $("#operaname").text('添加');
    $("#parent_id option").removeAttr('selected');
    $("#parent_id option").each(function() {
        if ($(this).val() == id) {
            $(this).attr('selected', 'selected');
            return false;
        }
    });
    $("#name,#key,#controller,#action,#condition").val('');
    $("#nodeModal").modal('show');
});

$(document).on('click', '.deleteNode', function() {
    $('.node').popover('hide');
    var id = $(this).parent().attr('data-id');
    iconfirm('确认要删除吗？', function() {
        $.post('{route("PurviewDelete")}', { id : id }, function(data) {
            window.location.reload();
        }, 'text');
    });
});

$(document).on('click', '.editNode', function() {
    $('.node').popover('hide');
    var id = $(this).closest('.node_action').attr('data-id');
    if (id) {
        $.get('{route("PurviewInfo")}', { id : id } ,function(data) {
            $("#purview_id").val(id);
            $("#name").val(data.name);
            $("#key").val(data.key);
            $("#controller").val(data.controller);
            $("#action").val(data.action);
            $("#condition").val(data.condition);
            $("#sort").val(data.sort);
            var parent_id = data.parent_id;
            $("#parent_id option").each(function() {
                if ($(this).val() == parent_id) {
                    $(this).attr('selected', 'selected');
                    return false;
                }
            });
            $("[name='type']").filter("[value='"+data.type+"']").attr('checked', 'checked');
            $("[name='useon']").filter("[value='"+data.useon+"']").attr('checked', 'checked');
            $("[name='status']").filter("[value='"+data.status+"']").attr('checked', 'checked');
            $("#nodeModal").modal('show');
        });
    }
});

$("#submitAction").click(function() {
    var data = $("#form").serialize();
    $.ajax({
        type: 'POST',
        url: '{route("PurviewSave")}',
        data: $("#form").serialize(),
        success: function(data) {
            window.location.reload();
        }
    });
});
</script>
{/block}
