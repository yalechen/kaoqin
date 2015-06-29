{extends file='Admin/layout.tpl'} 

{block title}{if $data.id gt 0}{assign "title" "编辑"}{else}{assign "title" "新增"}{/if}{$title}商户{/block} 
{block breadcrumb}
<li><a href="{route('CustIndex')}">商户管理</a></li>
<li class="active"><a href="{route('CustEdit',['id'=>$data.id])}">{$title}商户</a></li>
{/block} 

{block main}
<link rel="stylesheet" type="text/css" href="{asset('js/bootstrap-fileinput-master/css/fileinput.css')}" />
<style>
.show_upload { display:none; }
</style>
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<div class="form">
					<form class="cmxform form-horizontal tasi-form" method="post" action="{route('CustSave')}">
						<div class="form-group">
							<label for="name" class="control-label col-lg-2">名称</label>
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
                        <div class="form-group">
                            <label for="file" class="control-label col-lg-2">Logo</label>
                            <div class="col-lg-10">
                            	{if $data.logo_path}
                            	<span class="file-input">
                            		<div class="file-preview">
                            			<div class="close fileinput-remove">×</div>
                            			<div class="">
                            				<div class="file-preview-thumbnails">
												<div data-fileindex="0" class="file-preview-frame">
   													<img style="width:auto;height:160px;" alt="{$data.name}" title="{$data.name}" class="file-preview-image" src="{$data.logo_path}">
   													<div class="file-thumbnail-footer">
    													<div style="width: 170px;" class="file-caption-name" title="{$data.name}">{$data.name}</div>
													</div>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								</span>
								<div class="show_upload">
									<input id="file_logo" class="file" type="file" name="file" multiple=true>
		                        	<span class="help-block">请上传[jpg、png、gif]格式的图片，建议规格为500*500,不大于1M </span>
								</div>
                            	{else}
                            		<input id="file_logo" class="file" type="file" name="file" multiple=true>
		                        	<span class="help-block">请上传[jpg、png、gif]格式的图片，建议规格为500*500,不大于1M </span>
		                        {/if}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contacts" class="control-label col-lg-2">联系人</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="contacts" type="text" name="contacts" value="{old('contacts')|default:$data.contacts}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mobile" class="control-label col-lg-2">手机号</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="mobile" type="text" name="mobile" value="{old('mobile')|default:$data.mobile}" required />
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
			                    <select class="form-control" style="width:30%;display:inline;" id="district" tabindex="1" name="district_id">
									<option value="">--请选择县区--</option>
			                    </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address" class="control-label col-lg-2">具体地址</label>
                            <div class="col-lg-10">
                                <textarea class="form-control " id="address" name="address" required>{old('address')|default:$data.address}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address" class="control-label col-lg-2">地图定位</label>
                            <div class="col-lg-10">
                                <div class="col-lg-8" style="padding-left:0px"><input type="text" class="form-control" placeholder="" id="map_search"> </div>
                                <div class="col-lg-4"><button type="button" onclick="searchMap();" class="btn btn-default">搜索</button></div>
								<p class="help-block Red">注：这里只是模糊定位，精准定位请地图上移动红点。</p>
								<div id="map" style="width: 700px; height: 300px;">
								
				             	</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remark" class="control-label col-lg-2">备注</label>
                            <div class="col-lg-10">
                                <textarea class="form-control " id="remark" name="remark" required>{old('remark')|default:$data.remark}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type" class="control-label col-lg-2">类型</label>
                            <div class="col-lg-10">
                                <div class="radio-custom radio-success">
		                            <input type="radio" value="{constant('App\Models\Cust::TYPE_STORE')}" {if constant('App\Models\Cust::TYPE_STORE') eq old('type')|default:$data.type}checked{/if} name="type" id="store">
		                            <label for="store">门店</label>
		                            <input type="radio" value="{constant('App\Models\Cust::TYPE_GROUP')}" {if constant('App\Models\Cust::TYPE_GROUP') eq old('type')|default:$data.type}checked{/if} name="type" id="group">
		                            <label for="group">集团</label>
		                        </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
        						{if $data.id gt 0}<input type="hidden" name="id" value="{$data.id}" />{/if}
        						<input type="hidden" name="longitude" id="longitude"  value="{$data.lng|default:old('longitude')|default:'118.145208'}"/>
        						<input type="hidden" name="latitude"  id="latitude"  value="{$data.lat|default:old('latitude')|default:'24.478839'}"/>
        						<input type="hidden" name="logo_path"  id="logo_path"  value="{$data.logo_path}"/>
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
<script type="text/javascript" src="{asset('js/bootstrap-fileinput-master/js/fileinput.js')}"></script>
<script type="text/javascript">
//约束logo上传条件
$("#file_logo").fileinput({
    allowedFileExtensions : ['jpg', 'png','gif'],
    showPreview : true,
    maxFileSize: 2000,
    minImageWidth: 500,
    minImageHeight: 500,
    showUpload:false,
    showRemove:false
});
$(function() {
	//开始上传
    $(document).on('change', "#file_logo", function() {
        $.ajaxFileUpload({
            url : '{route('FormUploadFile')}',
            secureuri : false,
            fileElementId : 'file_logo',
            dataType : 'json',
            data : {  },
            success : function (data, status) {
                $("#logo_path").val(data.path);
            },
            error : function (data, status, e) {
                alert(e);
            }
        });
    });
});

//修改时，关闭图片显示改为上传
$(".fileinput-remove").click(function(){
	$(".file-input").toggle();
	$("#logo_path").val("");
	$(".show_upload").show();
	$(".file-input-new").show();
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

$("#city").change(function() {
	getDistrict();
});

function getDistrict() {
	var city_id = $("#city").val();
	var district_id = "{$data.district_id}";
	if( city_id > 0){
		$.ajax({
	        url: '{route("DistrictPull")}',
	        data: { city_id : city_id },
	        success: function(data) {
	            var html = "";
	            for (var i in data) {
	            	if(data[i]['id'] == district_id){
	            		html += "<option value='"+data[i]['id']+"' selected >"+data[i]['name']+"</option>"
	            	}else{
	            		html += "<option value='"+data[i]['id']+"'>"+data[i]['name']+"</option>"
	            	}
	                
	            }
	            $("#district option").not(":first").remove();
	            $("#district").append(html);
	        }
	    });
	}else{
		$("#district option").not(":first").remove();
	}
}
</script>

<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=fNRGtmRhQ9t2Byno569QoYUG"></script>
<script type="text/javascript">
//创建和初始化地图函数：
function initMap() {
	var lng = $("#longitude").val();
    var lat = $("#latitude").val();
    if (lng != '' && lat != '') {
    	createMap(lng,lat);//初始化并创建地图
    }else{
    	createMap(118.145208,24.478839);//初始化并创建地图
    }
}

//地图搜索
function searchMap() {
    var area = $("#map_search").val(); //得到地区
    var ls = new BMap.LocalSearch(map);
    ls.setSearchCompleteCallback(function(rs) {
        if (ls.getStatus() == BMAP_STATUS_SUCCESS) {
            var poi = rs.getPoi(0);
            if (poi) {
                createMap(poi.point.lng, poi.point.lat);//创建地图(经度poi.point.lng,纬度poi.point.lat)
            }
        }
    });
    ls.search(area);
}

//创建地图函数：
function createMap(x, y) {
    var map = new BMap.Map("map");//在百度地图容器中创建一个地图
    var point = new BMap.Point(x, y);//定义一个中心点坐标
    map.centerAndZoom(point, 15);//设定地图的中心点和坐标并将地图显示在地图容器中
    window.map = map;//将map变量存储在全局
	
        map.addControl(new BMap.NavigationControl);
        map.addControl(new BMap.OverviewMapControl);
        map.enableScrollWheelZoom();
        map.enableContinuousZoom();
	    var marker = new BMap.Marker(point);  // 创建标注
        map.addOverlay(marker);              // 将标注添加到地图中
        marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
        marker.enableMassClear();
        marker.disableDragging();
		
		map.addEventListener("click",function(e){
			map.clearOverlays();
			var marker = new BMap.Marker(e.point);  // 创建标注
			map.addOverlay(marker);              // 将标注添加到地图中
			marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
			marker.enableMassClear();
			marker.disableDragging();
			document.getElementById("longitude").value=e.point.lng;
			document.getElementById("latitude").value=e.point.lat;
		});
		
}

initMap();//创建和初始化地图
</script>
{/block}
