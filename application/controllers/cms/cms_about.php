<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe cms_about (Arquivo gerado com construtor de módulos)
* 
* Módule cms_about: 
*
* @since		30/07/2013
* @location		controllers.cms_about
*
*/
class cms_about extends Acme_Base_Module {
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
	* about_acme_cms()
	* Página modal de 'sobre' o ACME CMS.
	* @return void
	*/
	public function about_acme_cms()
	{
		$this->template->load_page('cms_about/about_acme_cms', array(), false, false);
	}
}
