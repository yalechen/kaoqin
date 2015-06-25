{extends file='Admin/layout.tpl'} 

{block title}机构管理{/block} 
{block breadcrumb}
<li class="active"><a href="{route('OrgIndex')}">机构管理</a></li>
{/block} 

{block main}
<div class="row">
	<div class="col-sm-12">
		<section class="panel">
			<header class="panel-heading head-border"> Hover Table </header>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>编号</th>
						<th>分类名称</th>
						<th>上级分类名称</th>
						<th>所属区域</th>
						<th>排序值</th>
						<th>创建日期</th>
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
							<td>{$item->parentNode()->first()->name}</td>
							<td>{$item->area}</td>
							<td>{$item->sort}</td>
							<td>{$item.created_at|date_format:"%Y-%m-%d"}</td>
							<td>
								<a class="btn btn-sm btn-default" href="{route('OrgEdit', $item.id)}"><i class="icon-edit"></i>编辑</a>
								<!-- <button class="btn mini black" onclick="deleteConfirm({$item.id}, '{$item.name}')"><i class="icon-trash"></i> 删除</button> -->
							</td>
						</tr>
						{/foreach}
					{/if}
				</tbody>
			</table>
			<div class="text-center">
				{$data->render()}
			</div>
		</section>
	</div>
</div>
{/block} 

{block script} {/block}
