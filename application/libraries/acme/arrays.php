<?php
/**
*
* Classe Template
*
* Esta biblioteca gerencia funções relacionadas ao template selecionado para a aplicação.
* 
* @author		Leandro Mangini Antunes
* @subpackage	acmeengine.core.libraries.template
* @since		10/09/2012
*
*/
class Arrays {
	// Definição de Atributos
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
	}
	
	/**
	* load_page()
	* Este método carrega uma página para o template setado atualmente. O segundo parametro sao as 
	* variaveis que estarão disponíveis no template. O terceiro parametro diz se o retorno tem de ser
	* somente html ou não (true/false). O quarto parametro diz se a página deve ser carregada com a 
	* master page ou não.
	* @param string page
	* @param array vars
	* @param boolean return_html
	* @param boolean load_master_page
	* @return mixed
	*/
	public function load_page($page = '', $arr_vars = array(), $return_html = false, $load_master_page = true)
	{
		$CI = get_instance();
		if($load_master_page)
		{
			$html = $CI->load->view($page, $arr_vars, true);
			$html = $CI->load->view('master.php', array('html' => $html), $return_html);
			if($return_html){ return $html; }
		} else {
			$html = $CI->load->view($page, $arr_vars, $return_html);
			if($return_html){ return $html; }
		}
	}
}