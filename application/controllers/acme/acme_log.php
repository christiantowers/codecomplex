<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Acme_Log
* 
* Classe abstra��o para o m�dulo de logs do sistema.
*
* @since		13/08/2012
* @location		acme.controllers.acme_log
*
*/
class Acme_Log  extends Acme_Base_Module {
	// Defini��o de atributos
	
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
	* M�todo 'padr�o' do controlador. � invocado automaticamente quando 
	* o action deste controlador n�o � informado na URL. Por padr�o seu efeito
	* � exibir a tela de listagem de entrada do m�dulo.
	* @param int actual_page
	* @return void
	*/
	public function index($actual_page = 0)
	{
		parent::index($actual_page);
	}
}
