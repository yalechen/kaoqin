{extends file='Admin/layout.tpl'} 

{block title}系统设置{/block} 
{block breadcrumb}
<li><a href="{route('ConfigsIndex')}">系统设置</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<div class="panel-body">
				<div class="form">
					<form class="cmxform form-horizontal tasi-form" method="post" action="{route('ConfigsSave')}">
						<div class="form-group">
							<label for="logo" class="control-label col-lg-2">系统Logo路径</label>
							<div class="col-lg-10">
                               	<input class="form-control" id="logo" name="logo" value="{old('logo')|default:$data.logo}" type="text" required />
                            </div>
						</div>
                        <div class="form-group">
                            <label for="favicon" class="control-label col-lg-2">系统图标路径</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="favicon" type="text" value="{old('favicon')|default:$data.favicon}" name="favicon" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sms_sn" class="control-label col-lg-2">短信帐号</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="sms_sn" type="text" name="sms_sn" value="{old('sms_sn')|default:$data.sms_sn}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sms_pwd" class="control-label col-lg-2">短信密码</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="sms_pwd" type="text" name="sms_pwd" value="{old('sms_pwd')|default:$data.sms_pwd}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sms_signature" class="control-label col-lg-2">短信签名</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="sms_signature" type="text" name="sms_signature" value="{old('sms_signature')|default:$data.sms_signature}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sms_pretend" class="control-label col-lg-2">短信前缀</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="sms_pretend" type="text" name="sms_pretend" value="{old('sms_pretend')|default:$data.sms_pretend}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sms_pretend" class="control-label col-lg-2">门店签到范围限制(米)</label>
                            <div class="col-lg-10">
                                <input class="form-control " id="sms_pretend" type="number" name="sign_cust_range" value="{old('sign_cust_range')|default:$data.sign_cust_range}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
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