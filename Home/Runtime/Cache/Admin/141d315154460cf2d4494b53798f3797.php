<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html><html lang="en">  <head>    <meta charset="utf-8">    <title>Limpid 管理后台</title>    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <meta name="description" content="">    <meta name="author" content="">    <!-- Bootstrap core CSS -->    <link href="/Public/Admin/Endless/bootstrap/css/bootstrap.min.css" rel="stylesheet">	<!-- Font Awesome-->	<link href="/Public/Admin/Endless/css/font-awesome.min.css" rel="stylesheet">	<!-- Pace -->	<link href="/Public/Admin/Endless/css/pace.css" rel="stylesheet">	<!-- Datatable -->	<link href="/Public/Admin/Endless/css/jquery.dataTables_themeroller.css" rel="stylesheet">	<!-- Endless -->	<link href="/Public/Admin/Endless/css/endless.min.css" rel="stylesheet">	<link href="/Public/Admin/Endless/css/endless-skin.css" rel="stylesheet">	    </head>  <body class="overflow-hidden">	<!-- Overlay Div -->	<div id="overlay" class="transparent"></div>	<a href="" id="theme-setting-icon"><i class="fa fa-cog fa-lg"></i></a>	<div id="theme-setting">		<div class="title">			<strong class="no-margin">Skin Color</strong>		</div>		<div class="theme-box">			<a class="theme-color" style="background:#323447" id="default"></a>			<a class="theme-color" style="background:#efefef" id="skin-1"></a>			<a class="theme-color" style="background:#a93922" id="skin-2"></a>			<a class="theme-color" style="background:#3e6b96" id="skin-3"></a>			<a class="theme-color" style="background:#635247" id="skin-4"></a>			<a class="theme-color" style="background:#3a3a3a" id="skin-5"></a>			<a class="theme-color" style="background:#495B6C" id="skin-6"></a>		</div>		<div class="title">			<strong class="no-margin">Sidebar Menu</strong>		</div>		<div class="theme-box">			<label class="label-checkbox">				<input type="checkbox" checked id="fixedSidebar">				<span class="custom-checkbox"></span>				Fixed Sidebar			</label>		</div>	</div><!-- /theme-setting -->		<div id="wrapper" class="preload">		<div id="top-nav" class="skin-6 fixed">			<div class="brand">				<span>Limpid</span>				<span class="text-toggle"> Admin</span>			</div><!-- /brand -->			<button type="button" class="navbar-toggle pull-left" id="sidebarToggle">				<span class="icon-bar"></span>				<span class="icon-bar"></span>				<span class="icon-bar"></span>			</button>			<button type="button" class="navbar-toggle pull-left hide-menu" id="menuToggle">				<span class="icon-bar"></span>				<span class="icon-bar"></span>				<span class="icon-bar"></span>			</button>			<ul class="nav-notification clearfix">				<li class="profile dropdown">					<a class="dropdown-toggle" data-toggle="dropdown" href="#">						<strong><?php echo ($adname); ?></strong>						<span><i class="fa fa-chevron-down"></i></span>					</a>					<ul class="dropdown-menu">						<li>							<a class="clearfix" href="#">								<img src="<?php echo (WEB_ROOT); ?>Images/user.png" alt="User Avatar">								<div class="detail">									<strong><?php echo ($adname); ?></strong>								</div>							</a>						</li>						<li><a tabindex="-1" href="<?php echo U('Adminuser/chgpasswd');?>" class="theme-setting"><i class="fa fa-cog fa-lg"></i> 修改密码</a></li>						<li class="divider"></li>						<li><a tabindex="-1" class="main-link logoutConfirm_open" href="#logoutConfirm"><i class="fa fa-lock fa-lg"></i> 退出登陆</a></li>					</ul>				</li>			</ul>		</div><!-- /top-nav-->				<aside class="fixed skin-6">						<div class="sidebar-inner scrollable-sidebar">				<div class="size-toggle">					<a class="btn btn-sm" id="sizeToggle">						<span class="icon-bar"></span>						<span class="icon-bar"></span>						<span class="icon-bar"></span>					</a>					<a class="btn btn-sm pull-right logoutConfirm_open"  href="#logoutConfirm">						<i class="fa fa-power-off"></i>					</a>				</div><!-- /size-toggle -->					<div class="user-block clearfix">					<img src="<?php echo (WEB_ROOT); ?>Images/user.png" alt="User Avatar">					<div class="detail">						<strong><?php echo ($adname); ?></strong>						<ul class="list-inline">							<li><a href="<?php echo U('Adminuser/chgpasswd');?>">修改密码</a></li>						</ul>					</div>				</div><!-- /user-block -->				<div class="main-menu">					<ul>						<?php if ( in_array('1_0', $_SESSION['menupriv'])) { ?>						<li class="openable ">							<a href="#">								<span class="menu-icon">									<i class="fa fa-inbox fa-lg"></i> 								</span>								<span class="text">									物种管理								</span>								<span class="menu-hover"></span>							</a>							<ul class="submenu">								<?php if (in_array('1_0', $_SESSION['menupriv']) ) { ?>								<li class=""><a href="<?php echo U('Class/classmgr');?>"><span class="submenu-label"><i class="fa fa-gift fa-lg"></i>&nbsp;&nbsp;种类管理</span></a></li>								<?php } ?>							</ul>						</li>						<?php } ?>												<?php if (in_array('2_0', $_SESSION['menupriv']) || in_array('2_1', $_SESSION['menupriv']) || in_array('2_2', $_SESSION['menupriv'])) { ?>						<li class="openable ">							<a href="#">								<span class="menu-icon">									<i class="fa fa-truck fa-lg"></i> 								</span>								<span class="text">									疾病管理								</span>								<span class="menu-hover"></span>							</a>                                                         <ul class="submenu">								<?php if ( in_array('2_0', $_SESSION['menupriv'])) { ?>								<li class=""><a href="<?php echo U('Diseases/diseasesmgr');?>"><span class="submenu-label"><i class="fa fa-flag fa-lg"></i>&nbsp;&nbsp;疾病种类管理</span></a></li>                                                                                                                                <?php } ?>                                                                                                                                <?php if ( in_array('2_0', $_SESSION['menupriv'])) { ?>                                                                <li class=""><a href="<?php echo U('Diseases/categorymgr');?>"><span class="submenu-label"><i class="fa fa-tasks fa-lg"></i>&nbsp;&nbsp;疾病分类项目管理</span></a></li>								<?php } ?>                                                               							</ul>						</li>						<?php } ?>                                                						<?php if (in_array('3_0', $_SESSION['menupriv'])) { ?>						<li class="">							<a href="<?php echo U('Message/index');?>">								<span class="menu-icon">									<i class="fa fa-user-md fa-lg"></i> 								</span>								<span class="text">									留言管理								</span>								<span class="menu-hover"></span>							</a>						</li>						<?php } ?>                                                                                                <?php if (in_array('4_0', $_SESSION['menupriv'])) { ?>						<li class="">							<a href="<?php echo U('News/newsmgr');?>">								<span class="menu-icon">									<i class="fa fa-user-md fa-lg"></i> 								</span>								<span class="text">									文章管理								</span>								<span class="menu-hover"></span>							</a>						</li>						<?php } ?>						<?php if (in_array('5_0', $_SESSION['menupriv'])) { ?>						<li class="openable ">							<a href='#'>								<span class="menu-icon">									<i class="fa fa-users fa-lg"></i> 								</span>								<span class="text">									人员管理								</span>								<span class="menu-hover"></span>							</a>                                                  	<ul class="submenu">								<li class=""><a href="<?php echo U('User/usermgr');?>"><span class="submenu-label"><i class="fa fa-user fa-lg"></i>&nbsp;&nbsp;用户管理</span></a></li> 								<li class=""><a href="<?php echo U('User/professormgr');?>"><span class="submenu-label"><i class="fa fa-user fa-lg"></i>&nbsp;&nbsp;专家管理</span></a></li>                                                               												</ul>  						</li>						<?php } ?>						<?php if (in_array('9_1_0', $_SESSION['menupriv']) || in_array('9_2_0', $_SESSION['menupriv']) ) { ?>						<li class="openable  ">							<a href="#">								<span class="menu-icon">									<i class="fa fa-inbox fa-lg"></i> 								</span>								<span class="text">									系统管理								</span>								<span class="menu-hover"></span>							</a>							<ul class="submenu">								<?php if (in_array('9_1_0', $_SESSION['menupriv']) ) { ?>								<li class=""><a href="<?php echo U('System/rolemgr');?>"><span class="submenu-label"><i class="fa fa-unlock fa-lg"></i>&nbsp;&nbsp;角色权限管理</span></a></li>								<?php } ?>								<?php if (in_array('9_2_0', $_SESSION['menupriv']) ) { ?>								<li class=""><a href="<?php echo U('System/adminusermgr');?>"><span class="submenu-label"><i class="fa fa-user fa-lg"></i>&nbsp;&nbsp;用户管理</span></a></li>								<?php } ?>							</ul>						</li>						<?php } ?>					</ul>				</div><!-- /main-menu -->			</div><!-- /sidebar-inner scrollable-sidebar -->		</aside>		
<div id="master_body_nei">
  <div class="center"><img src="<?php echo (WEB_ROOT); ?>image/404.jpg" class="all_404" /></div>
</div>
	</div><!-- /wrapper -->	<a href="" id="scroll-to-top" class="hidden-print"><i class="fa fa-chevron-up"></i></a>		<!-- Logout confirmation -->	<div class="custom-popup width-100" id="logoutConfirm">		<div class="padding-md">			<h4 class="m-top-none"> 确定要退出后台?</h4>		</div>		<div class="text-center">			<a class="btn btn-success m-right-sm" href="<?php echo U('Adminuser/logout');?>">退出</a>			<a class="btn btn-danger logoutConfirm_close">取消</a>		</div>	</div>	    <!-- Le javascript    ================================================== -->    <!-- Placed at the end of the document so the pages load faster -->		<!-- Jquery -->	<script src="/Public/Admin/Endless/js/jquery-1.10.2.min.js"></script>		<!-- Bootstrap -->    <script src="/Public/Admin/Endless/bootstrap/js/bootstrap.min.js"></script> 	<!-- Datatable -->	<script src='/Public/Admin/Endless/js/jquery.dataTables.min.js'></script>			<!-- Modernizr -->	<script src='/Public/Admin/Endless/js/modernizr.min.js'></script>		<!-- Pace -->	<script src='/Public/Admin/Endless/js/pace.min.js'></script>		<!-- Popup Overlay -->	<script src='/Public/Admin/Endless/js/jquery.popupoverlay.min.js'></script>		<!-- Slimscroll -->	<script src='/Public/Admin/Endless/js/jquery.slimscroll.min.js'></script>		<!-- Cookie -->	<script src='/Public/Admin/Endless/js/jquery.cookie.min.js'></script>	<!-- Endless -->	<script src="/Public/Admin/Endless/js/endless/endless.js"></script>		<script>		$(function	()	{			$('#dataTable').dataTable( {				"bJQueryUI": true,				"sPaginationType": "full_numbers"			});						$('#chk-all').click(function()	{				if($(this).is(':checked'))	{					$('#responsiveTable').find('.chk-row').each(function()	{						$(this).prop('checked', true);						$(this).parent().parent().parent().addClass('selected');					});				}				else	{					$('#responsiveTable').find('.chk-row').each(function()	{						$(this).prop('checked' , false);						$(this).parent().parent().parent().removeClass('selected');					});				}			});		});	</script>	  </body></html>