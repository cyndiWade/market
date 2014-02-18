<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
		<meta charset="utf-8"/>
	<title>后台管理系统</title>
	
	<link rel="stylesheet" href="<?php echo (APP_PATH); ?>Public/css/layout.css" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script src="<?php echo (APP_PATH); ?>Public/js/jquery-1.5.2.min.js" type="text/javascript"></script>
	<script src="<?php echo (APP_PATH); ?>Public/js/hideshow.js" type="text/javascript"></script>
	<script src="<?php echo (APP_PATH); ?>Public/js/jquery.tablesorter.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo (APP_PATH); ?>Public/js/jquery.equalHeight.js"></script>
	<script type="text/javascript">
	$(document).ready(function() 
    	{ 
      	  $(".tablesorter").tablesorter(); 
   	 } 
	);
	$(document).ready(function() {

	//When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

	});
	    </script>
	    <script type="text/javascript">
	    $(function(){
	        $('.column').equalHeight();
	    });
	</script>
	<style type="text/css">
		#page {
			float:right;
			width:500px;
			margin:0 30px 200px 0;
		}
	</style>
</head>


<body>

		<header id="header">
		<hgroup>
			<h1 class="site_title"><a href="index.html">后台管理系统</a></h1>
			<h2 class="section_title"> </h2><div class="btn_view_site"><a href="http://www.medialoot.com"> </a></div>
		</hgroup>
	</header> <!-- end of header bar -->
	
	<section id="secondary_bar">
		<div class="user">
			<p>管理员</p>
			<!-- <a class="logout_user" href="#" title="Logout">Logout</a> -->
		</div>
		<div class="breadcrumbs_container">
			<!-- 
			<article class="breadcrumbs">
				<a href="index.html">Website Admin</a> 
				<div class="breadcrumb_divider"></div> 
				<a class="current">Dashboard</a>
			</article>
			-->
		</div>
	</section><!-- end of secondary bar -->
	
	

	<aside id="sidebar" class="column">
		
		<h3>系统管理</h3>
		<ul class="toggle">
			<li class="icn_security"><a href="?s=/HtmlJoin/index">商铺列表</a></li>
			<li class="icn_settings"><a href="?s=/HtmlJoin/edit">添加商铺</a></li>
		</ul>
	</aside><!-- end of sidebar -->
	
	<!-- 内容主体 -->
	<section id="main" class="column">
		

		
		<article class="module width_3_quarter">
		<header><h3 class="tabs_involved">所有店铺列表</h3>
		
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
				<table class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr> 
						<th>数据编号</th> 
						<th>店铺类型</th>
	    				<th>店铺名</th> 
	    				<th>所属商业圈</th> 
	    				<th>人均消费</th> 
						<th>地址</th>
						<th>开业日期</th>
						<th>店铺类型</th>
						<th>负责人</th>
						<th>店铺图片</th>
						<th>操作</th>
					</tr> 
				</thead> 
				<tbody> 
					<?php if(is_array($purview)): $i = 0; $__LIST__ = $purview;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr> 
						<th><?php echo ($vo["id"]); ?></th> 		
	    				<td><?php echo ($vo["trade"]); ?></td> 
						<td><?php echo ($vo["store_name"]); ?></td> 
	    				<td><?php echo ($vo["estate"]); ?></td> 
						<td><?php echo ($vo["draw"]); ?></td> 
						<td><?php echo ($vo["address"]); ?></td> 
						<td><?php echo ($vo["start_business"]); ?></td> 
						<td>
							<?php if(($vo["classification"] == 1)): ?>总店
							<?php else: ?>
									分店<?php endif; ?>	
						</td> 
						<td><?php echo ($vo["official"]); ?></td> 
						
						<td><img src="<?php echo ($vo["store_pic"]); ?>" style="width:100px;height:100px;"/></td> 
	    				<td>
	    					<a href="?s=/HtmlJoin/edit/id/<?php echo ($vo["id"]); ?>" target="_blank"><img src="<?php echo (APP_PATH); ?>Public/images/icn_edit.png" title="编辑" /></a>　
							<a href="?s=/HtmlJoin/del/id/<?php echo ($vo["id"]); ?>"><img src="<?php echo (APP_PATH); ?>Public/images/icn_trash.png" title="删除"  /></a>
						</td> 
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				</tbody> 
				</table>
			</div><!-- end of #tab1 -->
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		
		<div class="clear"></div>
		
		<div class="spacer"></div>
		
		<div id="page"><?php echo ($page); ?></div>
		
	</section>


</body>

</html>