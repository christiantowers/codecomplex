<?php
/**
* -------------------------------------------------------------------------------------------------
* cms_template Helper
*
* Centraliza funções da biblioteca de templates do website.
*
* A chamada das funções contidas neste arquivo ajudante são alias para os métodos de mesmo nome
* localizados na respectiva biblioteca (cms_template). Sendo assim, as instruções abaixo retornam 
* o mesmo resultado esperado:
*	example_function(); // função localizada neste arquivo
* 	$this->cms_template->example_function();
* 
* @since		21/03/2013
* @location		helpers.cms_template
* -------------------------------------------------------------------------------------------------
*/

/**
* load_website_menu()
* Retorna o componente html de menus.
* @return string html
*/
function load_website_menu()
{
	$CI =& get_instance();
	return $CI->cms_template->load_website_menu();
}

/**
* load_website_header()
* Retorna o componente html de cabeçalho. Por padrão, este componente utiliza em seu interior a
* chamada dos menus do website (menus pertencem ao cabeçalho).
* @return string html
*/
function load_website_header()
{
	$CI =& get_instance();
	return $CI->cms_template->load_website_header();
}

/**
* load_website_footer()
* Carrega componente html do footer do site, localizado no diretorio html_components/website_footer/website_footer.php
* do template atual do website.
* @param string url
* @return string
*/
function load_website_footer($url='')
{
	$CI =& get_instance();
	return $CI->cms_template->load_website_footer($url);
}
