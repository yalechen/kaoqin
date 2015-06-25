{extends file='Admin/layout.tpl'} 

{block title}{if $data.id gt 0}{assign "title" "编辑"}{else}{assign "title" "新增"}{/if}机构{/block} 
{block breadcrumb}
<li><a href="{route('OrgIndex')}">机构管理</a></li>
<li class="active"><a href="{route('OrgEdit',['id'=>$data.id])}">{$title}机构</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<header class="panel-heading head-border"> {$title}机构 </header>
			<div class="panel-body">
				<div class="form">
					<form class="cmxform form-horizontal tasi-form" id="commentForm" method="get" action="{route('OrgEdit')}">
						<div class="form-group">
							<label for="name" class="control-label col-lg-2">名称</label>
							<div class="col-lg-10">
                               	<input class="form-control" id="name" name="name" minlength="2" type="text" required />
                            </div>
						</div>
                        <div class="form-group">
                            <label for="number" class="control-label col-lg-2">编号</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="number" type="text" minlength="5" name="number" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="area" class="control-label col-lg-2">所属区域</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="area" type="text" name="area" required />
                            </div>
                        </div>
                        {if $data.id gt 0}
                        <div class="form-group">
                            <label for="" class="control-label col-lg-2">上级机构</label>
                            <div class="col-lg-10">
                                <p class="form-control-static">{if $parent_node}{$parent_node->name}{else}一级机构{/if} [<a href="javascript:;" id="editBelongCategory" data-id="{$data.id}">修改</a>]</p>
                            </div>
                        </div>
                        {/if}
                        <div class="form-group"{if $data.id gt 0}style="display: none;"{/if} id="selCategory">
                            <label for="path" class="control-label col-lg-2">上级机构</label>
                            <div class="col-lg-10">
                                <select class="form-control sub_category" style="width:30%;display:inline;" name="path[]">
                                	<option value="0" selected>--做为第一级机构--</option>
									{foreach $org as $item}
					                	<option value="{$item.id}">{$item.name}</option>
					                {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="form-group"{if $data.id gt 0}style="display: none;"{/if}>
                            <label for="province" class="control-label col-lg-2">省市区</label>
                            <div class="col-lg-10">
                                <select class="form-control" style="width:30%;display:inline;" id="province" tabindex="1" name="province_id">
									<option value="">--请选择省份--</option>
									{foreach $province as $item}
										<option value="{$item.id}" {if $data.province_id eq $item.id}selected{/if}>{$item.name}</option>
									{/foreach}
			                    </select>
			                    <select class="form-control" style="width:30%;display:inline;" id="city" tabindex="1" name="city_id">
									<option value="">--请选择城市--</option>
			                    </select>
			                    <select class="form-control" style="width:30%;display:inline;" id="district" tabindex="1" name="district_id">
									<option value="">--请选择区县--</option>
			                    </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address" class="control-label col-lg-2">具体地址</label>
                            <div class="col-lg-10">
                                <textarea class="form-control " id="address" name="address" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sort" class="control-label col-lg-2">排序值</label>
                            <div class="col-lg-10">
                                <input class="form-control" type="text" id="sort" name="sort" required></textarea>
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
                            <div class="col-lg-offset-2 col-lg-10">
                            	<input type="hidden" name="longitude" id="longitude"  value="{$data.longitude}"/>
        						<input type="hidden" name="latitude"  id="latitude"  value="{$data.latitude}"/>
                                <button class="btn btn-success" type="submit"><i class="fa-save"></i> 保存</button>
                                <button class="btn btn-default" type="button"><i class="fa-undo"></i> 取消</button>
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
