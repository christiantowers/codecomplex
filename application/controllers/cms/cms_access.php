<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe cms_access (Arquivo gerado com construtor de módulos)
* 
* Módule cms_access: Acesso ao Gerenciador, tela de login e afins.
*
* @since		31/07/2013
* @location		controllers.cms_access
*
*/
class cms_access extends Acme_Access {
	// Definição de atributos
	public $view_dir = '';
	public $controller_name = '';
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
		parent::__construct(__CLASS__);
		$this->view_dir = 'cms_access';
		$this->controller_name = 'cms_access';
	}
	
	/**
	* index()
	* Método 'padrão' do controlador. É invocado automaticamente quando o action deste 
	* controlador não é informado na URL. Por padrão, exibe tela de login.
	* @return void
	*/
	public function index()
	{
		parent::index();
	}
}
