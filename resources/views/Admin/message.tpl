{if Session::has('message_success') || Session::has('message_error') || Session::has('message_info') || Session::has('message_warning')}
<section class="panel">
    <div class="panel-body">
    	{if Session::has('message_success')}
        <div class="alert alert-success alert-block fade in" style="margin-bottom:0px;">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
            <!-- <h4><i class="fa fa-ok-sign"></i> 成功!</h4><p> {Session::get('message_success')}</p> -->
            <strong>恭喜!</strong> 
            {if is_string(Session::get('message_success'))}
            	{Session::get('message_success')}
            {elseif is_array(Session::get('message_success'))}
            	{if count(Session::get('message_success')) eq 1}
            		{$message_success=Session::get('message_success')}
            		{$message_success.0}
            	{else}
	            	<br>
	            	{foreach Session::get('message_success') as $message}
	            		{$message}<br>
	            	{/foreach}
            	{/if}
            {/if}
        </div>
        {/if}
		{if Session::has('message_error')}
        <div class="alert alert-block alert-danger fade in" style="margin-bottom:0px;">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
            <strong>错误!</strong> 
            {if is_string(Session::get('message_error'))}
            	{Session::get('message_error')}
            {elseif is_array(Session::get('message_error'))}
            	{if count(Session::get('message_error')) eq 1}
            		{$message_error=Session::get('message_error')}
            		{$message_error.0}
            	{else}
	            	<br>
	            	{foreach Session::get('message_error') as $message}
	            		{$message@iteration}、{$message}<br>
	            	{/foreach}
            	{/if}
            {/if}
        </div>
        {/if}
		{if Session::has('message_info')}
        <div class="alert alert-info fade in" style="margin-bottom:0px;">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
            <strong>注意!</strong> 
            {if is_string(Session::get('message_info'))}
            	{Session::get('message_info')}
            {elseif is_array(Session::get('message_info'))}
            	{if count(Session::get('message_info')) eq 1}
            		{$message_info=Session::get('message_info')}
            		{$message_info.0}
            	{else}
	            	<br>
	            	{foreach Session::get('message_info') as $message}
	            		{$message}<br>
	            	{/foreach}
            	{/if}
            {/if}
        </div>
        {/if}
		{if Session::has('message_warning')}
        <div class="alert alert-warning fade in" style="margin-bottom:0px;">
            <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
            </button>
            <strong>警告!</strong> 
            {if is_string(Session::get('message_warning'))}
            	{Session::get('message_warning')}
            {elseif is_array(Session::get('message_warning'))}
            	{if count(Session::get('message_warning')) eq 1}
            		{$message_warning=Session::get('message_warning')}
            		{$message_warning.0}
            	{else}
	            	<br>
	            	{foreach Session::get('message_warning') as $message}
	            		{$message}<br>
	            	{/foreach}
	            {/if}
            {/if}
        </div>
		{/if}
    </div>
</section>
{/if}