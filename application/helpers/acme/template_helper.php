<?php
/**
* -------------------------------------------------------------------------------------------------
* Template Helper
*
* Centraliza funções da biblioteca de templates da aplicação derivado de Acme_Base_Module.
*
* A chamada das funções contidas neste arquivo ajudante são alias para os métodos de mesmo nome
* localizados na respectiva biblioteca (Template). Sendo assim, as instruções abaixo retornam o mesmo
* resultado esperado:
*	example_function(); // função localizada neste arquivo
* 	$this->template->example_function();
* 
* @since		21/03/2013
* @location		acme.helpers.template
* -------------------------------------------------------------------------------------------------
*/

/**
* message()
* Retorna o componente html mensagem, que é montado conforme parametros encaminhados.
* @param string tipo
* @param string titulo
* @param string descricao
* @param boolean close
* @param string style
* @return string html_message
*/
function message($type = 'info', $title = '', $description = '', $close = false, $style = '')
{
	$CI =& get_instance();
	return $CI->template->message($type, $title, $description, $close, $style);
}

/**
* start_box()
* Retorna o componente html do início de um box genérico, uma caixa com um estilo padrão.
* @param string titulo
* @param string url_img
* @param string style
* @return string html_box
*/
function start_box($titulo = '', $url_img = '', $style = '')
{
	$CI =& get_instance();
	return $CI->template->start_box($titulo, $url_img, $style);
}

/**
* end_box()
* Retorna o componente html de finalizacao da caixa genérica inicializada por start_box().
* @return string html_box
*/
function end_box()
{
	$CI =& get_instance();
	return $CI->template->end_box();
}

/**
* load_html_component()
* Carrega um componente html de nome encaminhado como parametro. Espera-se que
* exista um diretorio, arquivo e função de mesmo nome do que encaminhado. O segundo
* parametro é um array de parametros que serão encaminhados à função.
* @param string component
* @param array config
* @return string html_menu
*/
function load_html_component($component = '', $params = array())
{
	$CI =& get_instance();
	return $CI->template->load_html_component($component, $params);
}

/**
* load_js_file()
* Carrega um arquivo js, retornando tag script. O nome do arquivo encaminhado como parametro
* não deve conter a extensão do arquivo.
* @param string file
* @return string html
*/
function load_js_file($file = '')
{
	$CI =& get_instance();
	return $CI->template->load_js_file($file);
}

/**
* load_css_file()
* Carrega um arquivo css, retornando tag <link...>. O nome do arquivo encaminhado como parametro
* pode não conter a extensão do arquivo.
* @param string file
* @return string html
*/
function load_css_file($file = '')
{
	$CI =& get_instance();
	return $CI->template->load_css_file($file);
}

/**
* load_array_config_js_files()
* Carrega o html de scripts js setados no arquivo geral de configuração.
* @return string html
*/
function load_array_config_js_files()
{
	$CI =& get_instance();
	return $CI->template->load_array_config_js_files();
}

/**
* load_array_config_css_files()
* Carrega o html de arquivos css setados no arquivo geral de configuração.
* @return string html
*/
function load_array_config_css_files()
{
	$CI =& get_instance();
	return $CI->template->load_array_config_css_files();
}