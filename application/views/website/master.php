<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php echo lang(WEBSITE_TITLE) . ' | ' . $this->title; ?></title>
	<?php echo load_css_file('style.css') ?>
	
	<?php echo load_css_file('simple-app.css') ?>
	<?php echo load_css_file('idangerous.swiper.css') ?>
	
	<?php echo load_js_file('jquery.min.1.7.2.js')?>
	<?php echo load_js_file('functions.website.js')?>
	<?php echo load_js_file('jquery.validationengine.js')?>
	<?php echo load_js_file('jquery.validationengine.pt_BR.js')?>	
	
	<?php echo load_js_file('idangerous/idangerous.swiper-2.0.min.js')?>
			
	<link type="text/css" rel="stylesheet" href="<?php echo URL_CSS_WEBSITE ?>/style.css" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo URL_IMG_WEBSITE ?>/favicon.ico">
	

	
</head>
<body>
<?php echo get_input_configurations(); ?>

<?php echo load_website_header(); ?>

<div id="content" style="border-bottom: 3px solid #29444b;"> 
	<div id="div-slide" style="background-image: url('<?php echo URL_IMG_WEBSITE ?>/bg.png');">
		
		<div class="center swiper-nav-r" id="left" style="position:absolute; top: 300px; right:0;  width:60px; height:auto; padding:10px 0 10px 0 !important; z-index:998;" >
			<div class="swiper-wrapper">
				<div class="swiper-slide">	
					<img src="<?php echo URL_IMG_WEBSITE ?>/arrow-next-right.png">
				</div>	
				
			</div>	
		</div>		
		<div class="swiper-nav-l" id="left" style="position:absolute; top: 300px; left:0;  width:60px; height:auto; padding:10px 0 10px 0 !important; z-index:998;" >
			<div class="swiper-wrapper">
					
				<div class="swiper-slide">	
					<img src="<?php echo URL_IMG_WEBSITE ?>/arrow-next-left.png">
				</div>
				
			</div>	
		</div>	

		<div style="height:486px !important;" class="swiper-pages swiper-container">
			<div style=" height: 100%;" class="swiper-wrapper">
				<div style=" height: 100%;" class="swiper-slide">
					<div style=" height: 100%;" class="swiper-container scroll-container">
						<div class="swiper-scrollbar"></div>
						<div class="swiper-wrapper">
							<div class="swiper-slide">

								<div id="site-body-text-product" style="height:242px; text-align:center !important;">
									<h2 style="margin-top: 45px;color:#fff;">ACME CMS<h2>
									<h4 style="color:#c5dbe8;">Este é o sistema CMS, pensado especialmente para você<h4>
									<h4 style="color:#c5dbe8;">que é desenvolvedor e gosta de liberdade.<h4>
									
									<div style="margin-top:30px;">
										
										<a style="text-decoration:none;" href="<?php echo URL_ROOT ?>/produtos/acme-cms"  > <input value="Saiba Mais" type="button" style="margin-right:10px; padding: 2px !important; width:192px; height:32px; background-color: transparent; border: 0; background-image:url('<?php echo URL_IMG_WEBSITE ?>/button-site-saibamais.png');background-repeat:no-repeat; box-shadow: 0 0 0; font-size: 20px; text-shadow: none;"> </a>
										<a style="text-decoration:none;" href="#"  > <input value="Experimente" type="button" style="padding: 2px !important; width:192px; height:32px; background-color: transparent; border: 0; background-image:url('<?php echo URL_IMG_WEBSITE ?>/button-site-experimente.png');background-repeat:no-repeat; box-shadow: 0 0 0; font-size: 20px; text-shadow: none;">  </a>
											
									</div>	
									
								
								</div>
								
								<div id="site-body-img-product" style="height:242; text-align:center !important;">
									<img src="<?php echo URL_IMG_WEBSITE ?>/product-acmecms.png" ></img>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				
				<div style=" height: 100%;" class="swiper-slide">
					<div style=" height: 100%;" class="swiper-container scroll-container">
						<div class="swiper-scrollbar"></div>
						<div class="swiper-wrapper">
							<div class="swiper-slide">


							<div id="site-body-text-product" style="height:242px; text-align:center !important;">
								<h2 style="margin-top: 45px;color:#fff;">ACME Engine<h2>
								<h4 style="color:#c5dbe8;">Construir aplicações web em php <h4>
								<h4 style="color:#c5dbe8;">nunca foi tão fácil..<h4>
								
								<div style="margin-top:30px;">
									<a style="text-decoration:none;" href="<?php echo URL_ROOT ?>/produtos/acme-engine"  >  <input value="Saiba Mais" type="button" style="margin-right:10px;; padding: 2px !important; width:192px; height:32px; background-color: transparent; border: 0; background-image:url('<?php echo URL_IMG_WEBSITE ?>/button-site-saibamais.png');background-repeat:no-repeat; box-shadow: 0 0 0; font-size: 20px; text-shadow: none;">  </a>
									<a style="text-decoration:none;" href="http://www.acmeengine.org" target="_blank" > <input value="Experimente" type="button" style="padding: 2px !important; width:192px; height:32px; background-color: transparent; border: 0; background-image:url('<?php echo URL_IMG_WEBSITE ?>/button-site-experimente.png');background-repeat:no-repeat; box-shadow: 0 0 0; font-size: 20px; text-shadow: none;"> </a>
										
								</div>					
							
							</div>
							<div id="site-body-img-product" style="height:242; text-align:center !important;">
								<img src="<?php echo URL_IMG_WEBSITE ?>/product-acmeengine.png" ></img>
							</div>
							</div>
						</div>
					</div>
				</div>		
				
				<div style=" height: 100%;" class="swiper-slide">
					<div style=" height: 100%;" class="swiper-container scroll-container">
						<div class="swiper-scrollbar"></div>
						<div class="swiper-wrapper">
							<div class="swiper-slide">


							<div id="site-body-text-product" style="height:242px; text-align:center !important;">
								<h2 style="margin-top: 45px;color:#fff;">Jetro Gestão Eclesiástica<h2>
								<h4 style="color:#c5dbe8;">Gerencie sua Igreja com agilidade<h4>
								<h4 style="color:#c5dbe8;">e toda praticidade web.<h4>
								
								<div style="margin-top:30px;">
									<a style="text-decoration:none;" href="<?php echo URL_ROOT ?>/produtos/jetro"  >  <input value="Saiba Mais" type="button" style="margin-right:10px;; padding: 2px !important; width:192px; height:32px; background-color: transparent; border: 0; background-image:url('<?php echo URL_IMG_WEBSITE ?>/button-site-saibamais.png');background-repeat:no-repeat; box-shadow: 0 0 0; font-size: 20px; text-shadow: none;"> </a>
									<a style="text-decoration:none;" href="#"  > <input value="Experimente" type="button" style="padding: 2px !important; width:192px; height:32px; background-color: transparent; border: 0; background-image:url('<?php echo URL_IMG_WEBSITE ?>/button-site-experimente.png');background-repeat:no-repeat; box-shadow: 0 0 0; font-size: 20px; text-shadow: none;"> </a>
										
								</div>					
							
							</div>
							<div id="site-body-img-product" style="height:242; text-align:center !important;">
								<img src="<?php echo URL_IMG_WEBSITE ?>/product-acmeengine.png" ></img>
							</div>
							</div>
						</div>
					</div>
				</div>				
			</div>
		</div>		
	</div>
	<script type="text/javascript">


	//Init Pages
	var pages = $('.swiper-pages').swiper({
		onImagesReady: function(){				
			<?php if(basename($this->url) != 'home'){ ?>
			show_area('div-slide');		
			<?php } ?>
		},
		resizeReInit: true
	});
		//Init Navigation
	var navr = $('.swiper-nav-r').swiper({
		slidesPerView: 'auto',
		freeMode:true,
		freeModeFluid:true,
		onSlideClick: function(navr){				
			pages.swipeNext();	
			//pages.swipeTo( nav.activeLoopIndex )				
		}
	});
	var navl = $('.swiper-nav-l').swiper({
		slidesPerView: 'auto',
		freeMode:true,
		freeModeFluid:true,
		onSlideClick: function(navl){		
			pages.swipePrev();
			//pages.swipeTo( nav.activeLoopIndex )	
		}
	});

	//pages.resizeFix()

	
</script>
	<div onclick="show_area_slide('div-slide', pages);" style="cursor:pointer; background-image: url('<?php echo URL_IMG_WEBSITE ?>/buttom-slide.png'); width: 36px; height: 19px; right: 49.5%; position: absolute; z-index:999" ></div>
</div>

<div id="site-body" style="min-height: <?php echo (basename($this->url) != 'home')?'450px':'335px' ?> !important;">

<!-- CONTEÚDO DA PÁGINA -->
<?php
	if($this->load_file_content)
	{
		$this->load->file($this->file_path_content);
	} else {
		echo $html;
	}
?>
	
	
</div>
<?php echo load_website_footer($this->url); ?>

</body>
</html>