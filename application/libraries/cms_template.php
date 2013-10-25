<?php
/**
*
* Classe CMS_Template
*
* Esta biblioteca gerencia funções relacionadas ao template do website.
* 
* @since		01/08/2013
* @location		libraries.cms_template
*
*/
class CMS_Template {
	// Definição de Atributos
	var $CI = null;
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	/**
	* load_website_header()
	* Carrega componente html do cabeçalho do site, localizado no diretorio html_components/website_header/website_header.php
	* do template atual do website.
	* @return string
	*/
	public function load_website_header()
	{
		// Retorna o html do componente menu
		return $this->CI->template->load_html_component('website_header');
	}
	
	/**
	* load_website_footer()
	* Carrega componente html do footer do site, localizado no diretorio html_components/website_footer/website_footer.php
	* do template atual do website.
	* @param string url
	* @return string
	*/
	public function load_website_footer($url='')
	{
		// Retorna o html do componente menu
		return $this->CI->template->load_html_component('website_footer', array($url) );
	}
	
	/**
	* load_website_menu()
	* Carrega componente html chamado website_menu, localizado no diretorio html_components/website_menu/website_menu.php
	* do template atual do website. Encaminha para a função menu um array de menus, a ser construído da maneira
	* que for necessária.
	* @return string html_menu
	*/
	public function load_website_menu()
	{
		// Retorna o html do componente menu
		return $this->CI->template->load_html_component('website_menu', array($this->get_array_website_menus()));
	}
	
	/**
	* get_array_website_menus()
	* Retorna os menus cadastrados para o website em formato de array/árvore.
	* @return array menus
	*/
	public function get_array_website_menus()
	{
		// Carrega model que fará leitura do menu no banco de dados
		$this->CI->load->model('libraries/cms_template_model');
		
		// Faz leitura do menu conforme o grupo de usuário atual
		// Esta leitura é recursiva, para cada menu o model busca
		// possíveis menus-filhos.
		$menus = $this->CI->cms_template_model->get_website_menus();
		$menus = (count($menus) > 0) ? $this->CI->template->menus_to_tree($menus) : array();
		
		// Retorna menus em formato de array
		return $menus;
	}
}