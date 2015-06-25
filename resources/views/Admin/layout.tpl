<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<meta name="author" content="Mosaddek" />
<meta name="keyword" content="slick, flat, dashboard, bootstrap, admin, template, theme, responsive, fluid, retina" />
<meta name="description" content="" />
<link rel="shortcut icon" href="javascript:;" type="image/png">
<title>{block title}{{$smarty.block.child}|strip_tags|default:'管理中心'} - {/block}考勤管理系统</title>
<link rel="stylesheet" type="text/css" href="{asset('js/jquery-easy-pie-chart/jquery.easy-pie-chart.css')}" media="screen" />
<link rel="stylesheet" type="text/css" href="{asset('js/vector-map/jquery-jvectormap-1.1.1.css')}">
<link rel="stylesheet" type="text/css" href="{asset('css/slidebars.css')}">
<link rel="stylesheet" type="text/css" href="{asset('js/switchery/switchery.min.css')}" media="screen" />
<link rel="stylesheet" type="text/css" href="{asset('js/jquery-ui/jquery-ui-1.10.1.custom.min.css')}" />
<link rel="stylesheet" type="text/css" href="{asset('js/icheck/skins/all.css')}">
<link rel="stylesheet" type="text/css" href="{asset('css/owl.carousel.css')}">
<link rel="stylesheet" type="text/css" href="{asset('css/style.css')}">
<link rel="stylesheet" type="text/css" href="{asset('css/style-responsive.css')}">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="{asset('js/html5shiv.js')}"></script>
<script src="{asset('js/respond.min.js')}"></script>
<![endif]-->
{block head}{/block}
</head>

<body class="sticky-header">
<section>
	<div class="sidebar-left">
    	{include 'Admin/sidebar-menu.tpl'}
	</div>
    <div class="body-content" >
        {include 'Admin/header.tpl'}

        <div class="page-head">
        	<h3 class="m-b-less">
                {block title}{/block}{block smallTitle}{if {$smarty.block.child}} <small>{$smarty.block.child}</small>{/if}{/block}
            </h3>
            <!--<span class="sub-title">Welcome to Static Table</span>-->
            <div class="state-information">
                <ol class="breadcrumb m-b-less bg-less">
                    <li><a href="{route('AdminIndex')}">主页</a></li>
                    {block breadcrumb}
                    <li><a href="#">Form</a></li>
                    <li class="active">Form Layout</li>
                    {/block}
                </ol>
            </div>
        </div>

        <div class="wrapper">
        	{block main}{/block}
        </div>

        <footer>
            2015 &copy; SlickLab by VectorLab.
        </footer>

        <div class="sb-slidebar sb-right sb-style-overlay">
        {include 'Admin/sidebar-right.tpl'}
        </div>
    </div>
</section>

<script type="text/javascript" src="{asset('js/jquery-1.10.2.min.js')}"></script>
<script type="text/javascript" src="{asset('js/jquery-ui/jquery-ui-1.10.1.custom.min.js')}"></script>
<script type="text/javascript" src="{asset('js/jquery-migrate.js')}"></script>
<script type="text/javascript" src="{asset('js/bootstrap.min.js')}"></script>
<script type="text/javascript" src="{asset('js/modernizr.min.js')}"></script>
<script type="text/javascript" src="{asset('js/jquery.nicescroll.js')}"></script>
<script type="text/javascript" src="{asset('js/slidebars.min.js')}"></script>
<script type="text/javascript" src="{asset('js/switchery/switchery.min.js')}"></script>
<script type="text/javascript" src="{asset('js/switchery/switchery-init.js')}"></script>
<!--flot chart -->
<script type="text/javascript" src="{asset('js/flot-chart/jquery.flot.js')}"></script>
<script type="text/javascript" src="{asset('js/flot-chart/flot-spline.js')}"></script>
<script type="text/javascript" src="{asset('js/flot-chart/jquery.flot.resize.js')}"></script>
<script type="text/javascript" src="{asset('js/flot-chart/jquery.flot.tooltip.min.js')}"></script>
<script type="text/javascript" src="{asset('js/flot-chart/jquery.flot.pie.js')}"></script>
<script type="text/javascript" src="{asset('js/flot-chart/jquery.flot.selection.js')}"></script>
<script type="text/javascript" src="{asset('js/flot-chart/jquery.flot.stack.js')}"></script>
<script type="text/javascript" src="{asset('js/flot-chart/jquery.flot.crosshair.js')}"></script>
<!--earning chart init-->
<script type="text/javascript" src="{asset('js/earning-chart-init.js')}"></script>
<!--Sparkline Chart-->
<script type="text/javascript" src="{asset('js/sparkline/jquery.sparkline.js')}"></script>
<script type="text/javascript" src="{asset('js/sparkline/sparkline-init.js')}"></script>
<!--easy pie chart-->
<script type="text/javascript" src="{asset('js/jquery-easy-pie-chart/jquery.easy-pie-chart.js')}"></script>
<script type="text/javascript" src="{asset('js/easy-pie-chart.js')}"></script>
<!--vectormap-->
<script type="text/javascript" src="{asset('js/vector-map/jquery-jvectormap-1.2.2.min.js')}"></script>
<script type="text/javascript" src="{asset('js/vector-map/jquery-jvectormap-world-mill-en.js')}"></script>
<script type="text/javascript" src="{asset('js/dashboard-vmap-init.js')}"></script>
<!--Icheck-->
<script type="text/javascript" src="{asset('js/icheck/skins/icheck.min.js')}"></script>
<script type="text/javascript" src="{asset('js/todo-init.js')}"></script>
<!--jquery countTo-->
<script  type="text/javascript" src="{asset('js/jquery-countTo/jquery.countTo.js')}"></script>
<!--owl carousel-->
<script   type="text/javascript"src="{asset('js/owl.carousel.js')}"></script>
<!--common scripts for all pages-->
<script type="text/javascript" src="{asset('js/scripts.js')}"></script>
{block script}{/block}
<script type="text/javascript">
$(document).ready(function() {
        //countTo
        $('.timer').countTo();
        
        //owl carousel
        $("#news-feed").owlCarousel({
            navigation : true,
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem : true,
            autoPlay:true
        });
});

$(window).on("resize",function(){
    var owl = $("#news-feed").data("owlCarousel");
    owl.reinit();
});
</script>
</body>
</html>
