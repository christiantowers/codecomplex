<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe Acme_Access
* 
* Gerencia acesso a aplicação, em forma de controlador. Ações de login, logout e verificação de
* tempo de sessão e acesso (sessão expirou) estão aqui.
*
* @since		01/10/2012
* @location		acme.controllers.acme_access
*
*/
class Acme_Access extends Acme_Core {
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
		parent::__construct();
		$this->view_dir = '_acme/acme_access';
		$this->controller_name = 'acme_access';
		
	}
	
	/**
	* index()
	* Método 'padrão' do controlador. Carrega action da tela de login.
	* @return object
	*/
	public function index()
	{
		$this->login();
	}
	
	/**
	* login()
	* Formulario (Tela) de login.
	* @return void
	*/
	public function login()
	{
		// Coleta nome de usuário
		$args['login_user'] = $this->session->userdata('login_user');
		$this->session->unset_userdata('login_user');
	
		// Coleta possivel mensagem de erro de login
		$args['login_msg_error'] = $this->session->userdata('login_msg_error');
		$this->session->unset_userdata('login_msg_error');
		
		// Booleano se o erro de login está no campo usuário
		$args['bool_user_error'] = $this->session->userdata('bool_user_error');
		$this->session->unset_userdata('bool_user_error');
		
		// Booleano se o erro de login está no campo senha
		$args['bool_pass_error'] = $this->session->userdata('bool_pass_error');
		$this->session->unset_userdata('bool_pass_error');
		
		// Carrega pagina view do form de login
		$this->template->load_page('login', $args, false, false);
	}
	
	/**
	* login_process()
	* Processa o formulário de login/entrada do sistema. Após validado,
	* joga o usuário para a página inicial configurada em seu cadastro.
	* @return void
	*/
	public function login_process()
	{
		$login_user = $this->input->post('user');
		$login_pass = $this->input->post('pass');
		
		// Coleta dados do usuário 'validado'
		$user = $this->access->validate_login($login_user, $login_pass);

		// Caso usuario nao exista, redireciona para pagina de login
		if(!$user)
		{
			// Monta mensagem correta de erro
			if($login_user == '')
			{
				$this->session->set_userdata('login_user', $login_user);
				$this->session->set_userdata('bool_user_error', true);
				$this->session->set_userdata('login_msg_error', lang('Insira seu nome de usuário.'));
			}
			
			if($login_user != '')
			{
				$this->session->set_userdata('login_user', $login_user);
				$this->session->set_userdata('bool_user_error', true);
				$this->session->set_userdata('login_msg_error', lang('O usuário ou senha informados estão incorretos.'));
			}
			
			redirect($this->controller_name);
		} else {
			// Verifica se url_Default do usuario está preenchida
			// e o redireciona para lá, caso contrário joga para pagina
			// padrao de listagem de modulos e atalhos do codeigniter
			$url_default = (get_value($user, 'url_default') != '') ? $this->tag->eval_replace(get_value($user, 'url_default')) : URL_ROOT . '/acme_dashboard/';
			
			// Variaveis de informacao de usuario e sessao que vao para sessao
			$arr_session['id_user'] = get_value($user, 'id_user');
			$arr_session['user_group'] = get_value($user, 'user_group');
			$arr_session['user_name'] = get_value($user, 'user_name');
			$arr_session['login'] = get_value($user, 'login');
			$arr_session['user_img'] = $this->tag->eval_replace(get_value($user, 'url_img'));
			$arr_session['language'] = get_value($user, 'lang_default');
			$arr_session['url_default'] = $url_default;
			$arr_session['login_access'] = true;
			
			// Seta variáveis na sessão
			$this->session->set_userdata($arr_session);
			
			// Loga registro de login
			$this->log->db_log('login', 'login');
			
			// Redireciona para url do usuario
			redirect($url_default);
		}
	}
	
	/**
	* logout()
	* Saída do sistema.
	* @return void
	*/
	public function logout()
	{
		$this->load->library('session');
		$this->session->sess_destroy();
		redirect($this->controller_name);
	}
	
	/**
	* page_not_found()
	* 404, página não encontrada.
	* @return void
	*/
	public function page_not_found()
	{
		$this->error->show_404();
	}
	
	/**
	* forgot_password()
	* Tela de solicitação de alteração de senha. Esqueci senha.
	* @return void
	*/
	public function forgot_password()
	{
		// Carrega pagina view do form de login
		$this->template->load_page($this->view_dir . '/forgot_password', array(), false, false);
	}
	
	/**
	* forgot_password_process()
	* Processa tela de solicitação de alteração de senha.
	* @return void
	*/
	public function forgot_password_process()
	{
		if($this->input->post('login') != '' && $this->input->post('validate_human') != '')
		{
			// Carrega bibliotecas necessárias
			$this->load->model('acme/acme_user_model');
			
			// Coleta dados do usuário
			$user = $this->acme_user_model->get_user_data_by_login($this->input->post('login'));
			$args['user'] = $user;
			$args['key_access'] = md5(uniqid());
			// Verifica se usuário existe
			if(count($user) > 0)
			{
				// Usuario existe
				$args['user_exist'] = true;
				
				// Tenta enviar email para usuário
				// caso falhe, nao faz insert na tabela de log de resets
				$message_body = $this->template->load_page('_acme/acme_user/email_body_message_reset_password', $args, true, false);
				
				// Faz o envio, definitivamente
				if($this->app_email->send_email(lang('Solicitação de Alteração de Senha'), $message_body, get_value($user, 'email')))
				{
					// Seta controle OK
					$args['sent_email'] = true;
					
					// Gera chave de acesso para reset de senha
					$arr_ins['id_user'] = get_value($user, 'id_user');
					$arr_ins['email'] = get_value($user, 'email');
					$arr_ins['key_access'] = $args['key_access'];
					$this->db->insert('acm_user_reset_password', $arr_ins);
				} else {
					$args['sent_email'] = false;
				}
			} else {
				$args['sent_email'] = false;
				$args['user_exist'] = false;
			}

			// Carrega view
			$this->template->load_page($this->view_dir . '/forgot_password_process', $args, false, false);
		} else {
			redirect($this->controller_name . '/forgot_password');
		}
	}
	
	/**
	* reset_password()
	* Tela de acesso ao reset de senha do usuário com base em uma chave de acesso e id_user.
	* @param int id_user
	* @param string key_access
	* @return void
	*/
	public function reset_password($id_user = 0, $key_access = '')
	{
		if($id_user != '' && $key_access != '')
		{
			$data = $this->db->get_where('acm_user_reset_password', array('id_user' => $id_user, 'key_access' => $key_access));
			$reset_pass = $data->result_array();
			$args['reset_pass'] = (isset($reset_pass[0])) ? $reset_pass[0] : array();
			$args['allow_update'] = (count($args['reset_pass']) > 0 && get_value($args['reset_pass'], 'dtt_updated') == '') ? true : false;
			// Carrega pagina view da tela de reset de senha
			$this->template->load_page($this->view_dir . '/reset_password', $args, false, false);
		} else {
			redirect($this->controller_name . '/login');
		}
	}
	
	/**
	* reset_password_process()
	* Processa tela de acesso ao reset de senha do usuário.
	* @return void
	*/
	public function reset_password_process()
	{
		$id_user = $this->input->post('id_user');
		$key_access = $this->input->post('key_access');
		$password = $this->input->post('password');
		$password_repeat = $this->input->post('password_repeat');
		if($id_user != '' && $key_access != '' && $password != '' && $password_repeat != '' && ($password == $password_repeat))
		{
			$data = $this->db->get_where('acm_user_reset_password', array('id_user' => $id_user, 'key_access' => $key_access));
			$reset_pass = $data->result_array();
			$args['reset_pass'] = (isset($reset_pass[0])) ? $reset_pass[0] : array();
			
			// Somente faz o reset de senha caso exista o id do usuario (validacao anti ataque)
			if(count($args['reset_pass']) > 0)
			{
				// Marca o link como atualizado
				$this->db->set('dtt_updated', 'CURRENT_TIMESTAMP', false);
				$this->db->where(array('id_user' => $id_user, 'key_access' => $key_access));
				$this->db->update('acm_user_reset_password');
				
				// Altera a senha de usuário
				$this->db->set('password', md5($password));
				$this->db->where(array('id_user' => $id_user));
				$this->db->update('acm_user');
				
				// Carrega pagina view da tela de reset de senha
				$this->template->load_page($this->view_dir . '/reset_password_process', $args, false, false);
			} else {
				redirect($this->controller_name . '/login');
			}
		} else {
			redirect($this->controller_name . '/login');
		}
	}
}
