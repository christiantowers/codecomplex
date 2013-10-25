<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Acme_Core
* 
* Classe núcleo do sistema. Todas as outras classes derivam desta classe, que serve como um container
* filtro para a aplicação, em geral. Aglomera todos os recursos disponíveis no interior da aplicação.
*
* @since		13/08/2012
* @location		acme.core.acme_core
*
*/
class Acme_Core extends CI_Controller {
	// Definição de atributos
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
		parent::__construct();
		
		// Carregamento de Helpers
		$this->load->helper('url_helper');
		$this->load->helper('acme/access_helper');
		$this->load->helper('acme/app_config_helper');
		$this->load->helper('acme/app_date_helper');
		$this->load->helper('acme/app_email_helper');
		$this->load->helper('acme/arrays_helper');
		$this->load->helper('acme/database_helper');
		$this->load->helper('acme/dir_helper');
		$this->load->helper('acme/error_helper');
		$this->load->helper('acme/form_helper');
		$this->load->helper('acme/log_helper');
		$this->load->helper('acme/tag_helper');
		$this->load->helper('acme/template_helper');
		$this->load->helper('acme/validation_helper');
		
		// Biblioteca para sessão
		$this->load->library('session');
		
		// Carrega configurações da aplicação (arquivo acme.settings.php)
		$this->load->library('acme/app_config');
		
		// Define a linguagem padrão da aplicacão (primeiro tenta verificar se variavel de controle
		// de linguagem está disponivel na sessao, caso nao esteja entao coleta variavel padrao
		// setada no indice DEFAULT_LANGUAGE no arquivo de configuracao
		$language = ($this->session->userdata('language') != '') ? $this->session->userdata('language') : $this->app_config->DEFAULT_LANGUAGE;
		
		// Carrega arquivo de linguagem padrao
		$this->lang->load('acme_global', $language);
		
		// Carrega helper de linguagem para que o uso da funcao lang() esteja disponivel em toda aplicacao
		// para mais informacoes, acesse http://codeigniter.com/user_guide/libraries/language.html
		$this->load->helper('acme/language');
		
		// Carrega o restante das bibliotecas necessarias para aplicacao
		$this->load->library('acme/template');
		$this->load->library('acme/error');
		$this->load->library('acme/log');
		$this->load->library('acme/access');
		$this->load->library('acme/arrays');
		$this->load->library('acme/array_table');
		$this->load->library('acme/form');
		$this->load->library('acme/tag');
		$this->load->library('acme/validation');
		$this->load->library('acme/app_date');
		$this->load->library('acme/app_email');
		
		// Carrega classe estática que contém a versão atual
		$this->load->file('application/core/acme/acme_version.php');
		
		// Carrega o define da versão atual (Estará disponível através da constante ACME_VERSION)
		Acme_Version::build_define_version();		
		
		// Carrega uma instancia com banco de dados (para uso em controllers
		$this->load->database();
	}
}
