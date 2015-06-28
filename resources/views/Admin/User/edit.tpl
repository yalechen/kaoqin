{extends file='Admin/layout.tpl'} 

{block title}{if $data.id gt 0}{assign "title" "编辑"}{else}{assign "title" "新增"}{/if}{$title}用户{/block} 
{block breadcrumb}
<li><a href="{route('UserIndex')}">用户管理</a></li>
<li class="active"><a href="{route('UserEdit',['id'=>$data.id])}">{$title}用户</a></li>
{/block} 

{block main}
<link rel="stylesheet" type="text/css" href="{asset('js/bootstrap-fileinput-master/css/fileinput.css')}" />
<link rel="stylesheet" type="text/css" href="{asset('js/bootstrap-datepicker/css/datepicker.css')}"/>
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<div class="form">
					<form class="cmxform form-horizontal tasi-form" id="commentForm" method="post" action="{route('UserSave')}">
						<div class="form-group">
							<label for="name" class="control-label col-lg-2">用户名</label>
							<div class="col-lg-10">
                               	<input class="form-control" id="name" name="name" value="{old('name')|default:$data.name}" minlength="2" type="text" required />
                            </div>
						</div>
                        <div class="form-group">
                            <label for="number" class="control-label col-lg-2">编号</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="number" type="text" value="{old('number')|default:$data.number}" minlength="5" name="number" required />
                            </div>
                        </div>
                        {if !$data}
                        <div class="form-group">
                            <label for="password" class="control-label col-lg-2">密码</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="password" type="text" value="{old('password')|default:$data.password}" minlength="6" name="password" required />
                            </div>
                        </div>
                        {/if}
                        <div class="form-group">
                            <label for="mobile" class="control-label col-lg-2">手机号</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="mobile" type="text" name="mobile" value="{old('mobile')|default:$data.mobile}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="control-label col-lg-2">邮箱</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="email" type="email" name="email" value="{old('email')|default:$data.email}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="realname" class="control-label col-lg-2">真实姓名</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="area" type="text" name="realname" value="{old('realname')|default:$data.realname}" required />
                            </div>
                        </div>
                        {if $data.id gt 0}
                        <div class="form-group">
                            <label for="org_id" class="control-label col-lg-2">所属部门</label>
                            <div class="col-lg-10">
                                <p class="form-control-static">
                                	{if $data->dept->name}{$data->dept->name} [<a href="javascript:;" id="editBelongCategory" data-id="{$data.id}">修改</a>]
                                	{else}[<a href="javascript:;" id="editBelongCategory" data-id="{$data.id}">添加</a>]{/if}
                                </p>
                            </div>
                        </div>
                        {/if}
                        <div class="form-group"{if $data.id gt 0}style="display: none;"{/if} id="selCategory">
                            <label for="path" class="control-label col-lg-2">所属部门</label>
                            <div class="col-lg-10">
                                <select class="form-control sub_category" style="width:30%;display:inline;" name="path[]">
									{foreach $org as $item}
					                	<option value="{$item.id}">{$item.name}</option>
					                {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="show_depts">
                            <label for="dept_id" class="control-label col-lg-2"></label>
                            <div class="col-lg-10" id="sub_dept">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="province" class="control-label col-lg-2">省市区</label>
                            <div class="col-lg-10">
                                <select class="form-control" style="width:30%;display:inline;" id="province" tabindex="1" name="province_id">
									<option value="">--请选择省份--</option>
									{foreach $province as $item}
										<option value="{$item.id}" {if old('province_id')|default:$data.province_id eq $item.id}selected{/if}>{$item.name}</option>
									{/foreach}
			                    </select>
			                    <select class="form-control" style="width:30%;display:inline;" id="city" tabindex="1" name="city_id">
									<option value="">--请选择城市--</option>
			                    </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="birthday" class="control-label col-lg-2">出生日期</label>
                            <div class="col-lg-10">
                                <input class="form-control form-control-inline input-medium default-date-picker"  size="16" name="birthday" id="birthday" type="text" value="{if $data.birthday neq '0000-00-00'}{old('birthday')|default:$data.birthday}{else}{/if}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address" class="control-label col-lg-2">具体地址</label>
                            <div class="col-lg-10">
                                <textarea class="form-control " id="address" name="address" required>{old('address')|default:$data.address}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="control-label col-lg-2">性别</label>
                            <div class="col-lg-10">
                                <div class="radio-custom radio-success">
		                            <input type="radio" value="{constant('App\Models\User::GENDER_MALE')}" {if constant('App\Models\User::GENDER_MALE') eq old('gender')|default:$data.gender}checked{/if} name="gender" id="male">
		                            <label for="male">男</label>
		                            <input type="radio" value="{constant('App\Models\User::GENDER_FEMALE')}" {if constant('App\Models\User::GENDER_FEMALE') eq old('gender')|default:$data.gender}checked{/if} name="gender" id="female">
		                            <label for="female">女</label>
		                        </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="control-label col-lg-2">状态</label>
                            <div class="col-lg-10">
                                <div class="radio-custom radio-success">
		                            <input type="radio" value="{constant('App\Models\User::STATUS_ON')}" {if constant('App\Models\User::STATUS_ON') eq old('status')|default:$data.status}checked{/if} name="status" id="on">
		                            <label for="on">在职</label>
		                            <input type="radio" value="{constant('App\Models\User::STATUS_OFF')}" {if constant('App\Models\User::STATUS_OFF') eq old('status')|default:$data.status}checked{/if} name="status" id="off">
		                            <label for="off">离职</label>
		                        </div>
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

{block script} 
<script type="text/javascript" src="{asset('js/bootstrap-datepicker/js/bootstrap-datepicker.js')}"></script>
<script type="text/javascript" src="{asset('js/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js')}"></script>
<script type="text/javascript">
$('.default-date-picker').datepicker({
    format: 'yyyy-mm-dd',
    autoclose:true,
    language: "zh-CN",
})

//点击修改
$(document).ready(function(){
    $("#editBelongCategory").click(function() {
        $("#selCategory").toggle();
        //$("#show_depts").toggle();
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
    $("#sub_dept").children().remove();
    $.getJSON("{route("OrgSubDepts")}", { parent_id:parent_id }, function(data) {
        if (data.length > 0) {
            var select = '<select class="form-control" name="dept_id" tabindex="1"><option value="">--请选择--</option>';
            $(data).each(function(i, e) {
                select += "<option value='"+ e.id+"'>"+ e.name+"</option> ";
            });
            select += "</select>";
            $("#sub_dept").append(select);
        }
    });
});

//省市区下拉
$("#province").change(function() {
    getCity();
});

getCity();

function getCity() {
    var province_id = $("#province").val();
    var city_id = "{$data.city_id}";
	if( province_id > 0){
		$.ajax({
	        url: '{route("CityPull")}',
	        data: { province_id : province_id },
	        success: function(data) {
	            var html = "";
	            for (var i in data) {
	            	if(data[i]['id'] == city_id){
	            		html += "<option value='"+data[i]['id']+"' selected >"+data[i]['name']+"</option>"
	            	}else{
	            		html += "<option value='"+data[i]['id']+"'>"+data[i]['name']+"</option>"
	            	}
	                
	            }
	            $("#city option").not(":first").remove();
	            $("#city").append(html);
	            
	            getDistrict();
	        }
	    });
	}else{
		$("#city option").not(":first").remove();
	}
}
</script>
{/block}
