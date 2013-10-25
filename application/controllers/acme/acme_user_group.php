<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Acme_User_Group
* 
* Classe abstraзгo para mуdulo de grupos de usuбrios da aplicaзгo.
*
* @since		13/08/2012
* @location		acme.controllers.acme_user_group
*
*/
class Acme_User_Group  extends Acme_Base_Module {
	// Definiзгo de atributos
	
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
	* Mйtodo 'padrгo' do controlador. Й invocado automaticamente quando 
	* o action deste controlador nгo й informado na URL. Por padrгo seu efeito
	* й exibir a tela de listagem de entrada do mуdulo.
	* @param int actual_page
	* @return void
	*/
	public function index($actual_page = 0)
	{
		parent::index($actual_page);
	}
}
