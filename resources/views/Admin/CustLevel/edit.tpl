{extends file='Admin/layout.tpl'} 

{block title}{if $data.id gt 0}{assign "title" "编辑"}{else}{assign "title" "新增"}{/if}{$title}商户等级{/block} 
{block breadcrumb}
<li><a href="{route('CustLevelIndex')}">商户等级</a></li>
<li class="active"><a href="{route('CustLevelEdit',['id'=>$data.id])}">{$title}商户等级</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<div class="form">
					<form class="cmxform form-horizontal tasi-form" method="post" action="{route('CustLevelSave')}">
						<div class="form-group">
							<label for="name" class="control-label col-lg-2">名称</label>
							<div class="col-lg-10">
                               	<input class="form-control" id="name" name="name" value="{old('name')|default:$data.name}" type="text" required />
                            </div>
						</div>
                        <div class="form-group">
                            <label for="times" class="control-label col-lg-2">拜访次数</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="times" type="text" value="{old('times')|default:$data.times}" name="times" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sort" class="control-label col-lg-2">排序值</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="sort" type="text" name="sort" value="{old('sort')|default:$data.sort}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
        						{if $data.id gt 0}<input type="hidden" name="id" value="{$data.id}" />{/if}
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