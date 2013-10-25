<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php echo lang(WEBSITE_TITLE) . ' | ' . $this->title; ?></title>
	<?php echo load_css_file('style.css') ?>
	<?php echo load_js_file('jquery.min.1.7.2.js')?>
	<?php echo load_js_file('functions.website.js')?>
	<link type="text/css" rel="stylesheet" href="<?php echo URL_CSS_WEBSITE ?>/style.css" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo URL_IMG_WEBSITE ?>/favicon.ico">
</head>
<body>
<table style="height:100%;width:100%;">
<tr>
<td id="site-body-background">
<?php echo load_website_header(); ?>
<div id="site-body">
	<div id="site-shadow-left"></div>
	<div id="site-shadow-right"></div>
	<div id="site-body-content">
		<img src="<?php echo URL_IMG_WEBSITE?>/logo_broken.png" style="float:left;margin:50px 20px 0 170px;width:215px;" />
		<div class="inline top" style="margin:-227px 0 0 400px;width:450px;">
			<h1 class="font_shadow_gray"><?php echo lang('Ops!')?></h1>
			<h3 class="font_shadow_gray"><?php echo lang('Não conseguimos carregar esta página :(')?></h3>
			<br />
			<h5><?php echo lang('Esta página não está disponível no momento. Por favor, tente novamente.')?></h5>
			<h4 style="margin-top:10px;" class="inline top"><a href="javascript:void(0)" onclick="window.location.reload();"><?php echo lang('Recarregar página')?></a></h4><h6 style="margin:18px 0 0 15px;" class="inline top"> ou&nbsp;&nbsp;&nbsp;<a href="<?php echo URL_ROOT ?>"><?php echo lang('Leve-me para a Página Inicial')?></a></h6>
		</div>
	</div>
	<br />
</div>
</td>
</tr>
<tr><td id="site-footer-background"><?php echo load_website_footer(); ?></td></tr>
</table>
</body>
</html>