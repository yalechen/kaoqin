{extends file='Admin/layout.tpl'} 

{block title}权限填充{/block} 
{block breadcrumb}
<li class="active"><a href="{route('PurviewIndex')}">权限填充</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="isolate-tabs">
			<ul class="nav nav-tabs nav-justified">
            	<li class="active"><a data-toggle="tab" href="#menu">手动菜单填充</a></li>
                <li class=""><a data-toggle="tab" href="#action">自动路由填充</a></li>
			</ul>
			<div class="panel-body">
                <div class="tab-content">
                    <div id="menu" class="tab-pane active">
                        <div class="form">
							<form class="cmxform form-horizontal tasi-form" method="post" action="{route('PurviewSeedByMenus')}">
								<div class="form-group">
									<label for="key" class="control-label col-lg-2">标识key</label>
									<div class="col-lg-10">
		                               	<input class="form-control" id="key" name="key" value="{old('key')|default:$data.key}" type="text" required />
		                            </div>
								</div>
								<div class="form-group">
									<label for="name" class="control-label col-lg-2">名称</label>
									<div class="col-lg-10">
		                               	<input class="form-control" id="name" name="name" value="{old('name')|default:$data.name}" type="text" required />
		                            </div>
								</div>
								<div class="form-group">
									<label for="name" class="control-label col-lg-2">上级菜单</label>
									<div class="col-lg-10">
										<select class="form-control sub_menu" style="width:30%;display:inline;" name="path[]">
		                                	<option value="0" selected>--做为第一级菜单--</option>
											{foreach $menus as $item}
							                	<option value="{$item.id}">{$item.name}</option>
							                {/foreach}
		                                </select>
									</div>
								</div>
		                        <div class="form-group">
		                            <label for="remark" class="control-label col-lg-2">应用后台</label>
		                            <div class="col-lg-10">
		                                <select class="form-control" style="width:30%;display:inline;" name="useon">
		                                	<option value="{constant('App\Models\Purview::USEON_PC')}" selected>PC</option>
											<option value="{constant('App\Models\Purview::USEON_APP')}">APP</option>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group">
									<label for="sort" class="control-label col-lg-2">排序值</label>
									<div class="col-lg-10">
		                               	<input class="form-control" id="sort" name="sort" value="{old('sort')|default:$data.sort}" type="text" required />
		                            </div>
								</div>
		                        <div class="form-group">
		                            <label for="type" class="control-label col-lg-2">状态</label>
		                            <div class="col-lg-10">
		                                <div class="radio-custom radio-success">
				                            <input type="radio" value="{constant('App\Models\Purview::STATUS_ON')}" {if constant('App\Models\Purview::STATUS_ON') eq old('status')|default:$data.status}checked{/if} name="status" id="on">
				                            <label for="on">开启</label>
				                            <input type="radio" value="{constant('App\Models\Purview::STATUS_OFF')}" {if constant('App\Models\Purview::STATUS_OFF') eq old('status')|default:$data.status}checked{/if} name="status" id="off">
				                            <label for="off">关闭</label>
				                        </div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <div class="col-lg-offset-2 col-lg-10">
		                                <button class="btn btn-success" type="submit"> 保存</button>
		                                <button class="btn btn-default" type="button"> 取消</button>
		                            </div>
		                        </div>
		                    </form>
		                </div>
                    </div>
                    <div id="action" class="tab-pane">
                    	<form class="form-inline" role="form" method="post" action="{Route('PurviewSeedByRoutes')}">
                                <button type="submit" class="btn btn-primary">开始路由填充</button>
                        </form>
                    </div>
                </div>
			</div>
		</section>
	</div>
</div>
{/block} 

{block script}
<script type="text/javascript">
//获取下级菜单权限
$(document).on('change', ".sub_menu", function() {
    var parent_id = $(this).val();
    var obj = $(this);
    obj.nextAll().remove();
    $.getJSON("{route("PurviewMenuSub")}", { parent_id:parent_id }, function(data) {
        if (data.length > 0) {
            var select = '<select class="form-control sub_menu" name="path[]" tabindex="1" style="width:30%;display:inline;"><option value="">--请选择--</option>';
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