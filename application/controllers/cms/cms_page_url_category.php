<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe cms_page_url_category (Arquivo gerado com construtor de módulos)
* 
* Módule cms_page_url_category: Este módulo gerencia categorias de páginas URL cadastradas no gerenciador CMS.
*
* @since		30/07/2013
* @location		controllers.cms_page_url_category
*
*/
class cms_page_url_category extends Acme_Base_Module {
	// Definição de atributos
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
		parent::__construct(__CLASS__);
	}
	
	/**
	* index()
	* Método 'padrão' do controlador. É invocado automaticamente quando 
	* o action deste controlador não é informado na URL. Por padrão seu efeito
	* é exibir a tela de listagem de entrada do módulo.
	* @param int actual_page
	* @return void
	*/
	public function index($actual_page = 0)
	{
		parent::index($actual_page);
	}
	
	/**
	* example()
	* Método 'exemplo' do controlador. Quando uma URL cms_page_url_category/example for 
	* invocada, este método é disparado.
	* @return void
	*/
	public function example()
	{
	}
}
