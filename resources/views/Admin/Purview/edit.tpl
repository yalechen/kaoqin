{extends file='Admin/layout.tpl'} 

{block title}{if $data.id gt 0}{assign "title" "编辑"}{else}{assign "title" "新增"}{/if}{$title}角色{/block} 
{block breadcrumb}
<li><a href="{route('RoleIndex')}">角色管理</a></li>
<li class="active"><a href="{route('RoleEdit',['id'=>$data.id])}">{$title}角色</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<div class="form">
					<form class="cmxform form-horizontal tasi-form" method="post" action="{route('RoleSave')}">
						<div class="form-group">
							<label for="key" class="control-label col-lg-2">标识key</label>
							<div class="col-lg-10">
                               	<input class="form-control" id="key" name="key" value="{old('key')|default:$data.key}" type="text" {if $data.id gt 0}disabled{else}required{/if} />
                               	{if $data.id gt 0}<input type="hidden" name="key" value="{$data.key}" />{/if}
                               	{if !$data}<span class="help-block">添加完成后则无法修改</span>{/if}
                            </div>
						</div>
						<div class="form-group">
							<label for="name" class="control-label col-lg-2">名称</label>
							<div class="col-lg-10">
                               	<input class="form-control" id="name" name="name" value="{old('name')|default:$data.name}" type="text" required />
                            </div>
						</div>
                        <div class="form-group">
                            <label for="remark" class="control-label col-lg-2">备注</label>
                            <div class="col-lg-10">
                                <textarea class="form-control " id="remark" name="remark" required>{old('remark')|default:$data.remark}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type" class="control-label col-lg-2">状态</label>
                            <div class="col-lg-10">
                                <div class="radio-custom radio-success">
		                            <input type="radio" value="{constant('App\Models\Role::STATUS_ON')}" {if constant('App\Models\Role::STATUS_ON') eq old('status')|default:$data.status}checked{/if} name="status" id="on">
		                            <label for="on">开启</label>
		                            <input type="radio" value="{constant('App\Models\Role::STATUS_OFF')}" {if constant('App\Models\Role::STATUS_OFF') eq old('status')|default:$data.status}checked{/if} name="status" id="off">
		                            <label for="off">关闭</label>
		                        </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
        						{if $data.id gt 0}<input type="hidden" name="id" value="{$data.id}" />{/if}
                                <button class="btn btn-success" type="submit"> 保存</button>
                                <button class="btn btn-default" type="button" onclick="javascript:history.go(-1)"> 取消</button>
                            </div>
                        </div>
                    </form>
                </div>
			</div>
		</section>
	</div>
</div>
{/block} 