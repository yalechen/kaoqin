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
    delay: { "show": 100, "hide" : 300 }
});

$('.node').click(function() {
    $('.node_action').attr('data-id', $(this).attr('data-id'));
});

$(document).on('click', '.addNode', function() {
    $('.node').popover('hide');
    var id = $(this).parent().attr('data-id');
    $("#purview_id").val('');
    $("#parent_id option").removeAttr('selected');
    $("#parent_id option").each(function() {
        if ($(this).val() == id) {
            $(this).attr('selected', 'selected');
            return false;
        }
    });
    $("#name,#purview_key,#controller,#action,#condition").val('');
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
            $("#purview_key").val(data.purview_key);
            $("#controller").val(data.controller);
            $("#action").val(data.action);
            $("#condition").val(data.condition);
            $("#sort_order").val(data.sort_order);
            var parent_id = data.parent_id;
            $("#parent_id option").each(function() {
                if ($(this).val() == parent_id) {
                    $(this).attr('selected', 'selected');
                    return false;
                }
            });
            $("[name='type']").filter("[value='"+data.type+"']").attr('checked', 'checked');
            $("[name='type']").filter("[value='"+data.type+"']").parent().addClass('checked');
            $("[name='useon']").filter("[value='"+data.useon+"']").attr('checked', 'checked');
            $("[name='useon']").filter("[value='"+data.useon+"']").parent().addClass('checked');
            $("[name='status']").filter("[value='"+data.status+"']").attr('checked', 'checked');
            $("[name='status']").filter("[value='"+data.status+"']").parent().addClass('checked');
            $("#remark").val(data.remark);
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
