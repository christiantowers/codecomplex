<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe cms_dashboard (Arquivo gerado com construtor de módulos)
* 
* Módule cms_dashboard: Dashboard da área administrativa do Gerenciador de Conteúdo ACME CMS.
*
* @since		30/07/2013
* @location		controllers.cms_dashboard
*
*/
class cms_dashboard extends Acme_Base_Module {
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
	* Método 'exemplo' do controlador. Quando uma URL cms_dashboard/example for 
	* invocada, este método é disparado.
	* @return void
	*/
	public function example()
	{
	}
}
