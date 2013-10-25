<?php
/**
*
* Classe Log
*
* Esta biblioteca gerencia funções relacionadas ao registro de logs no sistema.
* 
* @since		01/10/2012
* @location		acme.libraries.log
*
*/
class Log {
	// Definição de Atributos
	var $CI = null;
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
	}
	
	/**
	* db_log()
	* Salva um registro de log no banco de dados (tabela acm_log). 
	* @param integer id_user
	* @param string text_log
	* @param string action
	* @param string table
	* @param string ip_address
	* @param string user_agent
	* @return void
	*/
	public function db_log($text_log = '', $action = '', $table = '', $array_data = array(), $ip_address = '', $user_agent = '', $browser_name = '', $browser_version = '')
	{
		// Carrega helpers e models necessários
		$this->CI =& get_instance();
		$this->CI->load->library('user_agent');
		$this->CI->load->model('acme/libraries/log_model');
		
		// Resolve endereço de ip e user_agent
		$user_agent = ($user_agent == '') ? $this->CI->input->user_agent() : $user_agent;
		$ip_address = ($ip_address == '') ? $this->CI->input->ip_address() : $ip_address;
		$browser_name = ($browser_name == '') ? $this->CI->agent->browser() : $browser_name;
		$browser_version = ($browser_version == '') ? $this->CI->agent->version() : $browser_version;
		$id_user = ($this->CI->session->userdata('id_user') == '') ? 0 : $this->CI->session->userdata('id_user');
		$login = ($this->CI->session->userdata('login') == '') ? '' : $this->CI->session->userdata('login');
		
		// Insere log no banco de dados
		$this->CI->log_model->insert_log($id_user, $text_log, $action, $table, var_export($array_data, true), $ip_address, $user_agent, $browser_name, $browser_version, $login);
	}
	
	/**
	* log_error()
	* Salva um registro de log de erro no banco de dados (tabela acm_log_error). 
	* @param string template
	* @param string header
	* @param string message
	* @param string status_code
	* @return void
	*/
	public function log_error($template = '', $header = '', $message = '', $status_code = '')
	{
		// Carrega helpers e models necessários
		$this->CI =& get_instance();
		$this->CI->load->model('acme/libraries/log_model');
		
		// Insere log no banco de dados
		$this->CI->log_model->insert_log_error($template, $header, $message, $status_code);
	}
}