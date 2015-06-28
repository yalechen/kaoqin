{extends file='Admin/layout.tpl'} 

{block title}指派门店{/block} 
{block breadcrumb}
<li><a href="{route('UserIndex')}">用户管理</a></li>
<li class="active"><a href="{route('UserAssignCust',['id'=>$user.id])}">指派门店</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<form class="form-inline" role="form" action="{Route('UserAssignCust')}" method="get">
					<div class="form-group">
						<label class="sr-only" for="key">关键字</label>
                        <input type="text" class="form-control" id="name" name="key" value="{$smarty.get.key}" placeholder="编号\名称\手机号">
                    </div>
                    <div class="form-group">
						<select class="form-control" name="type">
							<option value="0" {if !$smarty.get.org_id}selected{/if}>--所有类型--</option>
							<option value="{constant('App\Models\Cust::TYPE_STORE')}" {if constant('App\Models\Cust::TYPE_STORE') eq $smarty.get.type}selected{/if}>{trans('cust.type.'|cat:constant('App\Models\Cust::TYPE_STORE'))}</option>
							<option value="{constant('App\Models\Cust::TYPE_GROUP')}" {if constant('App\Models\Cust::TYPE_GROUP') eq $smarty.get.type}selected{/if}>{trans('cust.type.'|cat:constant('App\Models\Cust::TYPE_GROUP'))}</option>
                    	</select>
                    </div>
                    <div class="form-group">
						<select class="form-control" name="province_id" id="province">
							<option value="0" {if !$smarty.get.org_id}selected{/if}>--所有省份--</option>
							{foreach $province as $item}
								<option value="{$item.id}" {if $item.id eq $smarty.get.province_id}selected{/if}>{$item.name}</option>
							{/foreach}
                    	</select>
                    </div>
                    <input type="hidden" name="user_id" value="{$smarty.get.user_id}" />
					<button type="submit" class="btn btn-info"><i class="fa fa-search"></i> 查询</button>
				</form>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>编号</th>
						<th>名称</th>
						<th>类型</th>
						<th>巡店者</th>
						<th>等级</th>
						<th>联系人</th>
						<th>联系方式</th>
						<th>地址</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{if $data}
						{foreach $data as $item}
						<tr>
							<td>{$item.id}</td>
							<td>{$item.number}</td>
							<td>{$item.name}</td>
							<td><span class="toggle-status label {if $item.type eq constant('App\Models\Cust::TYPE_GROUP')}label-danger{else}label-warning{/if}">{trans('cust.type.'|cat:$item.type)}</span></td>
							<td>{if $item.user_id gt 0}{$item.user.name}{else}<span class="toggle-status label label-danger">未指派{/if}</td>
							<td>{$item.custLevel.name}</td>
							<td>{$item.contacts}</td>
							<td>{$item.mobile}</td>
							<td>{if $item.province_id gt 0}{$item.province.name}{/if}{if $item.city_id gt 0}{$item.city.name}{/if}{if $item.district_id gt 0}{$item.district.name}{/if}</td>
							<td>
								<a class="btn btn-sm btn-success" onclick="assignConfirm({$user.id}, '{$user.name}',{$item.id}, '{$item.name}')" data-toggle="modal" href="#AssignCustModal"><i class="icon-star"></i> 马上指派</a>
							</td>
						</tr>
						{foreachelse}
							<tr><td colspan="10" class="center">无相关数据！</td></tr>
						{/foreach}
					{/if}
				</tbody>
			</table>
			<div class="text-center">
				{$data->appends(['user_id'=>$smarty.get.user_id,'key' => $smarty.get.key,'type' => $smarty.get.type,'province_id' => $smarty.get.province_id,'city_id' => $smarty.get.city_id])->render()}
			</div>
		</section>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="AssignCustModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">指派确认</h4>
            </div>
            <div class="modal-body">
            	{if $cust.id gt 0}
            		<p>您确定要将“<strong id="user_name"></strong>”的旧巡查门店“<strong id="old_cust_name"></strong>”指派更新为新门店“<strong id="new_cust_name"></strong>”吗？</p>
            		<P>注意：如果点击确认，则旧门店需要重新指派</P>
            	{else}
                	<p>您确定要指派“<strong id="new_cust_name"></strong>”门店为“<strong id="user_name"></strong>”的巡查门店吗？</p>
                {/if}
            </div>
            <div class="modal-footer">
            	<form method="post" action="{route('UserAssignCustSave')}">
	            	<input type="hidden" id="user_id" name="user_id" value="" >
	            	<input type="hidden" id="cust_id" name="cust_id" value="" >
	                <button class="btn btn-danger" type="submit"> 确认</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- modal -->
{/block} 

{block script} 
<script type="text/javascript">
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

//删除确认
function assignConfirm(user_id, user_name, cust_id,cust_name){
	$("#user_id").val(user_id);
	$("#user_name").text(user_name);
	$("#cust_id").val(cust_id);
	$("#new_cust_name").text(cust_name);
	if("{$cust.id}">0){
		$("#old_cust_name").text("{$cust.name}");
	}
}
</script>
{/block}
