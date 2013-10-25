<?php
/**
*
* Classe Access
*
* Esta biblioteca gerencia funções relacionadas ao acesso ao sistema, como validação de sessão ou
* permissões de módulo.
* 
* @since		01/10/2012
* @location		acme.libraries.access
*
*/
class Access {
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
	* validate_login()
	* Valida um login (usuário e senha) para acesso ao sistema. Verifica se usuário e senha
	* encaminhados são válidos. Caso válido, retorna array de dados do usuario, senão false.
	* @param string user
	* @param string pass
	* @return mixed data/false
	*/
	public function validate_login($user = '', $pass = '')
	{
		// Carrega model
		$this->CI->load->model('acme/libraries/access_model');
		
		// Valida o usuário encaminhado no banco de dados
		$arr_validate = $this->CI->access_model->validate_login($user, $pass);
		
		// Se count array > 0, usuario valido
		return (count($arr_validate) > 0) ? $arr_validate : false;
	}
	
	/**
	* validate_session()
	* Valida a sessão. Retorna true caso logado, redireciona para pagina inicial caso nao logado.
	* @return mixed boolean
	*/
	public function validate_session()
	{
		// Verifica se variáveis da sessão estão preenchidas
		if($this->CI->session->userdata('login_access') == '' || $this->CI->session->userdata('id_user') == '')
		{
			redirect('/');
			exit;
		}
		return true;
	}
	
	/**
	* browser_rank()
	* Retorna lista de browsers que acessaram o sistema e a porcentagem de acesso de cada um.
	* Utilizado no dashboard do sistema.
	* @return array browsers
	*/
	public function browser_rank()
	{
		// Carrega model
		$this->CI->load->model('acme/libraries/access_model');
		
		// Verifica se variáveis da sessão estão preenchidas
		return $this->CI->access_model->browser_rank();
	}
	
	/**
	* validate_permission()
	* Valida uma permissão com base na permissao e modulo encaminhados (permissao do usuario logado
	* para o modulo encaminhado). Exibe página de erro de permissão ou booleano quando parametro
	* de teste de permissao é encaminhado.
	* @param string module
	* @param string permission
	* @param boolean exib_page
	* @param integer id_user
	* @return mixed has_permission
	*/
	public function validate_permission($module = '', $permission = '', $exib_page = true, $id_user = 0)
	{
		// Carrega model
		$this->CI->load->model('acme/libraries/access_model');
		
		// Resolve iduser
		$id_user = ($id_user != 0) ? $id_user : $this->CI->session->userdata('id_user');
		
		// Verifica se variáveis da sessão estão preenchidas
		$count_permission = $this->CI->access_model->get_user_permission($module, $permission, $id_user);
		
		// Carrega ou nao pagina de erro
		if($exib_page) 
		{
			if($count_permission <= 0)
			{
				$this->CI->error->show_error(lang('Usuário sem Permissão'), lang('Usuário sem permissão para esta ação') . ' (' . $permission . ')', 'error_permission', 500, false);
			}
		} else {
			return ($count_permission > 0) ? true : false;
		}
		
		return;
	}
}