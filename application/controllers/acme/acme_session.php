<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Acme_Session
* 
* Controller de gerenciamento de variáveis de sessão do sistema.
*
* @since		28/06/2013
* @location		acme.controllers.acme_session
*
*/
class Acme_Session extends Acme_Base_Module {
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
	* @override
	* index()
	* Entrada do módulo. Exibe listagem de variáveis de sessão em um box de visualização.
	* @return void
	*/
	public function index()
	{
		// Valida permissão de entrada do módulo
		$this->validate_permission('ENTER');
		
		// Coleta variáveis de sessão
		$args['session'] = $this->session->all_userdata();
		
		// Carrega view
		$this->template->load_page('_acme/acme_session/index', $args);
	}
	
	/**
	* ajax_change_language()
	* Altera a linguagem atual do sistema. O efeito é por usuário.
	* @param string language
	* @return void
	*/
	public function ajax_change_language($language = '')
	{
		switch($language)
		{
			case 'pt_BR':
			case 'en_US':
				$new_language = $language;
			break;
			
			default:
				$new_language = 'pt_BR';
			break;
		}
		$this->session->set_userdata('language', $new_language);
	}
}
