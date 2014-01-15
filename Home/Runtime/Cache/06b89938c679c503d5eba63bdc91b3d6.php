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
		.input_place {
			width:40%; 
			
		}
		.input_place p {
			margin:0 0 30px 0;
		}
	</style>
	
	<script type="text/javascript"src="http://maps.google.com/maps/api/js?sensor=false"></script> 
	<script type="text/javascript">
		$(function () {
			
			
			//地图模块
			(function () { 
				$('.address_a').blur(function () {
					codeAddress(this.value);
				});	
				 function codeAddress(address)  {      
					var  geocoder =new google.maps.Geocoder();     
					 geocoder.geocode({ 'address': address }, function(results, status) {            
					 	if(status == google.maps.GeocoderStatus.OK)  {
							//赋值
							$("input[name='lat']").val(results[0].geometry.location.lat()) ;
							$("input[name='lng']").val(results[0].geometry.location.lng()) ;
						 } else {              
							alert("对不起没有找到此地址:"+ status);       
							$("input[name='lat']").val('') ;   
							$("input[name='lng']").val('') ;
						 }           
					 });    
				 } 
			})();
		});
	</script>
	
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
		
<!-- 数据表-->
		<article class="module width_full">
			<header><h3>编辑店铺信息</h3></header>
			
			<form action="" method="post" accept-charset="utf-8" enctype ="multipart/form-data" >
				<div class="module_content">
					
					<fieldset class="input_place"> 
						<p><label>店铺名:</label><input type="text" name="store_name" value="<?php echo ($store_data["store_name"]); ?>" /></p>
						<p><label>店主手机:</label><input type="text" name="store_phone" value="<?php echo ($store_data["store_phone"]); ?>" placeholder="请确认填写的号码是真实的" /></p>
						<p><label>所属行业:</label>
							<select name="trade">
								<?php if(is_array($trade)): $i = 0; $__LIST__ = $trade;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($key == $store_data['trade'])): ?><option value="<?php echo ($key); ?>" selected="selected"><?php echo ($vo); ?></option>
									<?php else: ?>
										<option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
							</select>
						</p>
						<p><label>商业圈:</label><input type="text" name="estate" value="<?php echo ($store_data["estate"]); ?>" /></p>
						<p><label>城市:</label><input type="text" name="city" value="<?php echo ($store_data["city"]); ?>" placeholder="如：广州市"/></p>
						<p><label>详细地址:</label><input type="text" name="address" value="<?php echo ($store_data["address"]); ?>" class="address_a" placeholder="如：上海市，普陀区，中山北路2020号" /></p>
						<p><label>经度:</label><input type="text" name="lng" value="<?php echo ($store_data["lng"]); ?>" placeholder="输入详细地址，自动获取（经度）"/></p>
						<p><label>纬度:</label><input type="text" name="lat" value="<?php echo ($store_data["lat"]); ?>" placeholder="输入详细地址，自动获取（纬度）"/></p>
						<p><label>开业日期:</label><input type="text" name="start_business" value="<?php echo ($store_data["start_business"]); ?>" /></p>
						<p><label>店铺属性:</label>
							<?php if(($store_data['classification'] == 1)): $checked1 = 'checked=checked'; ?>
							<?php elseif(($store_data['classification'] == 2)): ?>
								<?php $checked2 = 'checked=checked'; endif; ?>
							<input type="radio" name="classification" value="1" <?php echo ($checked1); ?>>总店　
							<input type="radio" name="classification" value="2" <?php echo ($checked2); ?>>分店
						</p>
						<p><label>负责人:</label><input type="text" name="official" value="<?php echo ($store_data["official"]); ?>" /></p>
						<p><label>人均消费:</label><input type="text" name="draw" value="<?php echo ($store_data["draw"]); ?>" /></p>		
						<p><label>店铺图片:</label><input type="file" name="store_pic"  /></p>
						<p><label>营业执照图片:</label><input type="file" name="licence_pic"  /></p>	

						<p><label>简介-140个字</label>
						<textarea rows="12" name="introduce"><?php echo ($store_data["introduce"]); ?></textarea>
													
					</fieldset>
					<input type="submit" value="提交" class="alt_btn">
					<div class="clear"></div>
				
				</div>
				</div>
				
			</form>
			
		</article><!-- end of post new article -->
		
	
		
		<div class="clear"></div>
		
		
	
		<div class="spacer"></div>
	</section>


</body>

</html>