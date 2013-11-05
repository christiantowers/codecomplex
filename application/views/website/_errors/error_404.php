<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php echo lang(WEBSITE_TITLE) . ' | ' . lang('Página não encontrada'); ?></title>
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
	<div id="site-body-content">
		<img src="<?php echo URL_IMG_WEBSITE?>/404_logo2b.png" style="float:left; margin:0 0px 40px 0px; width:370px;" />
		<div class="inline top" style="float:left; margin:0 0 0 50px; width:450px;">
			<h1 class="font_shadow_gray"><?php echo lang('Ops!')?></h1>
			<h2 class="font_shadow_gray"><?php echo lang('Página não encontrada.')?></h2>
			<h5><?php echo lang('A página que você está tentando acessar não existe ou não está disponível no momento. Certifique-se que você digitou corretamente o endereço na barra do seu navegador.')?></h5>
			<h4 style="margin-top:10px;"><a href="<?php echo URL_ROOT ?>"><?php echo lang('Leve-me para a Página Inicial')?></a></h4>
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