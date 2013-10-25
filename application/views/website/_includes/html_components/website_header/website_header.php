<?php
/**
* website_header()
* Monta o header do site. Por padrão, o header é composto de logo e menus.
* @return string html
*/
function website_header()
{
	$html = '<div id="site-header">';
		$html .= '<a href="' . URL_ROOT . '"><img src="' . URL_IMG_WEBSITE . '/logo.png" style="margin:12px 0 0 0;" class="inline top" /></a>';
		$html .= '<div id="site-menu" class="inline top right font_shadow_gray">' . load_website_menu() . '</div>';
	$html .= '</div>';
	
	return $html;
}