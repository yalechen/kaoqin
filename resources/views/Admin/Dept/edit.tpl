{extends file='Admin/layout.tpl'} 

{block title}{if $data.id gt 0}{assign "title" "编辑"}{else}{assign "title" "新增"}{/if}{$title}部门{/block} 
{block breadcrumb}
<li><a href="{route('DeptIndex')}">部门管理</a></li>
<li class="active"><a href="{route('DeptEdit',['id'=>$data.id])}">{$title}部门</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<div class="form">
					<form class="cmxform form-horizontal tasi-form" method="post" action="{route('DeptSave')}">
						<div class="form-group">
							<label for="name" class="control-label col-lg-2">名称</label>
							<div class="col-lg-10">
                               	<input class="form-control" id="name" name="name" value="{old('name')|default:$data.name}" minlength="2" type="text" required />
                            </div>
						</div>
                        {if $data.id gt 0}
                        <div class="form-group">
                            <label for="" class="control-label col-lg-2">所属机构</label>
                            <div class="col-lg-10">
                                <p class="form-control-static">{$data->org->name} [<a href="javascript:;" id="editBelongCategory" data-id="{$data.id}">修改</a>]</p>
                            </div>
                        </div>
                        {/if}
                        <div class="form-group"{if $data.id gt 0}style="display: none;"{/if} id="selCategory">
                            <label for="path" class="control-label col-lg-2">所属机构</label>
                            <div class="col-lg-10">
                                <select class="form-control sub_category" style="width:30%;display:inline;" name="path[]">
									{foreach $org as $item}
					                	<option value="{$item.id}">{$item.name}</option>
					                {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remark" class="control-label col-lg-2">备注</label>
                            <div class="col-lg-10">
                                <textarea class="form-control " id="remark" name="remark" required>{old('remark')|default:$data.remark}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sort" class="control-label col-lg-2">排序值</label>
                            <div class="col-lg-10">
                                <input class="form-control" type="text" id="sort" name="sort" value="{old('sort')|default:$data.sort|default:100}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="control-label col-lg-2">状态</label>
                            <div class="col-lg-10">
		                        <input type="checkbox" name="status" class="js-switch-blue" checked/>
		                    </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
        						<input type="hidden" name="id" value="{$data.id}" />
                                <button class="btn btn-success" type="submit"> 保存</button>
                                <button class="btn btn-default" type="button"> 取消</button>
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
<script type="text/javascript">
//点击修改
$(document).ready(function(){
    $("#editBelongCategory").click(function() {
        $("#selCategory").toggle();
        if ($("#selCategory").is(":hidden")) {
            $(this).text('修改');
        } else {
            $(this).text('取消修改');
        }
    });
});

//展开下级机构
$(document).on('change', ".sub_category", function() {
    var parent_id = $(this).val();
    var obj = $(this);
    obj.nextAll().remove();
    $.getJSON("{route("OrgSub")}", { parent_id:parent_id }, function(data) {
        if (data.length > 0) {
            var select = '<select class="form-control sub_category" style="width:30%;display:inline;" name="path[]" tabindex="1"><option value="">--请选择--</option>';
            $(data).each(function(i, e) {
                select += "<option value='"+ e.id+"'>"+ e.name+"</option> ";
            });
            select += "</select>";
            obj.parent().append(select);
        }
    });
});
</script>
{/block}
