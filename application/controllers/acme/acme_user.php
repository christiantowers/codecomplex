<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Acme_User
* 
* Classe abstração de módulo de usuários da aplicação.
*
* @since		13/08/2012
* @location		acme.controllers.acme_user
*
*/
class Acme_User extends Acme_Base_Module {
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
	* permission_manager()
	* Tela de gerenciamento de permissões de usuário.
	* @param integer id_user
	* @return void
	*/
	public function permission_manager($id_user = 0)
	{
		$this->validate_permission('PERMISSION_MANAGER');
		
		// Coleta filtros
		$filters = $this->input->post();
		
		// Calcula filtros para modulos (exibir somente modulos do acme ou app)
		$args['show_acme_modules'] = (get_value($filters, 'show_acme_modules') == 'Y') ? true : false;
		
		// Permission do usuario
		$args['lista'] =  $this->acme_user_model->get_list_permissions($id_user, $args['show_acme_modules']);
		$args['user_data'] = $this->acme_user_model->get_user_data($id_user);
		
		// Carrega view
		$this->template->load_page('_acme/acme_user/permission_manager', $args);
	}
	
	/**
	* ajax_set_user_permission()
	* Habilita ou desabilita uma permissão de um módulo para determinado usuário
	* incluindo um novo registro ou deletando da tabela de permissões.
	* @param integer id_user
	* @param integer id_module_permission
	* @param string action
	* @return void
	*/
	public function ajax_set_user_permission($id_user = 0, $id_module_permission = 0, $action = '')
	{
		if($this->validate_permission('PERMISSION_MANAGER', false))
		{
			$permission_ins['id_user'] = $id_user;
			$permission_ins['id_module_permission'] = $id_module_permission;	
			
			// Dados para inserção
			if( strtolower($action) == 'enable')
			{				
				// Insere um novo registro de acao para este formulario
				$this->db->insert('acm_user_permission', $permission_ins);
			}else{					
				$this->db->delete('acm_user_permission', $permission_ins);
			}			
		}	
	}
	
	/**
	* ajax_modal_bookmark_insert()
	* Modal de inserção de novo favorito.
	* @param integer id_user
	* @return void
	*/
	public function ajax_modal_bookmark_insert($id_user = 0)
	{
		if($this->session->userdata('id_user') == $id_user || $this->validate_permission('BOOKMARK_MANAGER', false))
		{
			// Dados do usuário
			$user = $this->acme_user_model->get_user_data($id_user);
			
			// Carrega view
			$this->template->load_page('_acme/acme_user/ajax_modal_bookmark_insert', array('id_user' => $id_user, 'user' => $user), false, false);
		}
	}
	
	/**
	* ajax_modal_bookmark_insert_process()
	* Processa modal de inserção de novo favorito.
	* @return void
	*/
	public function ajax_modal_bookmark_insert_process()
	{
		if($this->session->userdata('id_user') == $this->input->post('id_user') || $this->validate_permission('BOOKMARK_MANAGER', false))
		{
			// Dados do FAVORITO
			$arr_ins['link'] = $this->input->post('link');
			$arr_ins['name'] = $this->input->post('name');
			$arr_ins['id_user'] = $this->input->post('id_user');
			$this->db->insert('acm_user_bookmark', $arr_ins);
			
			// Carrega view
			$this->template->load_page('_acme/acme_user/ajax_modal_bookmark_process', array(), false, false);
		}
	}
	
	/**
	* ajax_modal_bookmark_update()
	* Modal de edição de favorito.
	* @param integer id_bookmark
	* @return void
	*/
	public function ajax_modal_bookmark_update($id_bookmark = 0)
	{
		// Dados do bookmark
		$bookmark = $this->acme_user_model->get_user_bookmark_data($id_bookmark);
		if($this->session->userdata('id_user') == get_value($bookmark, 'id_user') || $this->validate_permission('BOOKMARK_MANAGER', false))
		{
			// Carrega view
			$this->template->load_page('_acme/acme_user/ajax_modal_bookmark_update', array('bookmark' => $bookmark), false, false);
		}
	}
	
	/**
	* ajax_modal_bookmark_update_process()
	* Processa modal de edição de favorito.
	* @return void
	*/
	public function ajax_modal_bookmark_update_process()
	{
		// Dados do bookmark
		$bookmark = $this->acme_user_model->get_user_bookmark_data($this->input->post('id_user_bookmark'));
		if($this->session->userdata('id_user') == get_value($bookmark, 'id_user') || $this->validate_permission('BOOKMARK_MANAGER', false))
		{
			// Update
			$arr_upd['link'] = $this->input->post('link');
			$arr_upd['name'] = $this->input->post('name');
			$this->db->update('acm_user_bookmark', $arr_upd, 'id_user_bookmark = ' . $this->input->post('id_user_bookmark'));
			
			// Carrega view
			$this->template->load_page('_acme/acme_user/ajax_modal_bookmark_process', array(), false, false);
		}
	}
	
	/**
	* ajax_modal_bookmark_delete()
	* Modal de deleção de favorito.
	* @param integer id_bookmark
	* @return void
	*/
	public function ajax_modal_bookmark_delete($id_bookmark = 0)
	{
		// Dados do bookmark
		$bookmark = $this->acme_user_model->get_user_bookmark_data($id_bookmark);
		if($this->session->userdata('id_user') == get_value($bookmark, 'id_user') || $this->validate_permission('BOOKMARK_MANAGER', false))
		{
			// Carrega view
			$this->template->load_page('_acme/acme_user/ajax_modal_bookmark_delete', array('bookmark' => $bookmark), false, false);
		}
	}
	
	/**
	* ajax_modal_bookmark_delete_process()
	* Processa modal de deleção de favorito.
	* @return void
	*/
	public function ajax_modal_bookmark_delete_process()
	{
		// Dados do bookmark
		$bookmark = $this->acme_user_model->get_user_bookmark_data($this->input->post('id_user_bookmark'));
		if($this->session->userdata('id_user') == get_value($bookmark, 'id_user') || $this->validate_permission('BOOKMARK_MANAGER', false))
		{
			// Delete
			$this->db->delete('acm_user_bookmark', 'id_user_bookmark = ' . $this->input->post('id_user_bookmark'));
			
			// Carrega view
			$this->template->load_page('_acme/acme_user/ajax_modal_bookmark_process', array(), false, false);
		}
	}
	
	/**
	* user_profile()
	* Habilita ou desabilita uma permissão de um módulo para determinado usuário
	* incluindo um novo registro ou deletando da tabela de permissões.
	* @param integer id_user
	* @return void
	*/
	public function user_profile($id_user = 0)
	{		
		// Dados do usuário
		$arr_userdata = $this->acme_user_model->get_user_data($id_user);
		$args['id_user']     = get_value($arr_userdata, 'id_user');
		$args['login']       = get_value($arr_userdata, 'login');
		$args['password']    = get_value($arr_userdata, 'password');
		$args['name']        = get_value($arr_userdata, 'name');
		$args['email']       = get_value($arr_userdata, 'email');
		$args['user_img']    = (get_value($arr_userdata, 'url_img') == '' || !file_exists(PATH_UPLOAD . "/user_photos/" . basename(get_value($arr_userdata, 'url_img')))) ? URL_IMG . '/avatar_user_unknown.png' : get_value($arr_userdata, 'url_img');
		$args['observation'] = get_value($arr_userdata, 'observation');
		$args['group']       = get_value($arr_userdata, 'grup');
		$args['lang_default']= get_value($arr_userdata, 'lang_default');
		$args['url_default'] = get_value($arr_userdata, 'url_default');			
		
		// Ranking de acessos por browsers
		$browser_rank = $this->acme_user_model->browser_rank_user($id_user);
		$args['browser_rank'] = isset($browser_rank[0]) ? $browser_rank : array(0 => array());
		
		// Se tem permissão para edição
		$args['editable'] = ($this->validate_permission('EDIT_PROFILE', false) || $id_user == $this->session->userdata('id_user')) ? true : false;
		$this->template->load_page('_acme/acme_user/user_profile', $args);
	}
	
	/**
	* user_profile_photo_upload()
	* Carrega a tela de upload da foto do usuário.
	* @param integer id_user
	* @param string file_name
	* @param string ext
	* @return void
	*/
	public function user_profile_photo_upload($id_user = 0, $file_name = '', $ext = '')
	{
		if(($this->validate_permission('EDIT_PROFILE', false)) || ($id_user == $this->session->userdata('id_user')) ){
			// Variável PARA CONTROle de edição
			$edit = false;
		
			// carrega lib de edição de imagem.
			$this->load->library('acme/file_crop');			
			
			// Se não existe nome da imagem
			if( $file_name != '' ){
				
				// guarda em variável o nome da imagem e miniatura anteriores.
				$file_name_old  = PATH_UPLOAD."/user_photos/".$file_name .".".$ext; 
				$thumb_name_old = PATH_UPLOAD."/user_photos/thumbnail_".$file_name .".".$ext; 
				
				// Se não existir a miniatura com esse nome.
				if( !file_exists($thumb_name_old) )
				{				
					// carrega do banco o nome verdadeiro da miniatura.
					$arr_userdata = $this->acme_user_model->get_user_data($id_user);
					$thumb_name_old = PATH_UPLOAD."/user_photos/".basename(get_value($arr_userdata, 'url_img'));	
				}				
				$edit = true;
			}
			
			$error = false;
			$field = array();
			$module = array();
			
			// carrega dados do usuário.
			$arr_userdata = $this->acme_user_model->get_user_data($id_user);	
			
			$args['id_user']         = get_value($arr_userdata, 'id_user');
			$args['login']           = get_value($arr_userdata, 'login');
			$args['password']        = get_value($arr_userdata, 'password');
			$args['name']            = get_value($arr_userdata, 'name');
			$args['email']           = get_value($arr_userdata, 'email');
			$args['user_img_large']  = basename(get_value($arr_userdata, 'url_img_large')); 
			$args['user_img']        = get_value($arr_userdata, 'url_img');
			$args['observation']     = get_value($arr_userdata, 'observation');
			$args['group']           = get_value($arr_userdata, 'grup');
			$args['file_name_old']   = !(isset($file_name_old))?'':$file_name_old;
			$args['thumb_name_old']  = !(isset($thumb_name_old))?'':$thumb_name_old;
			
			// Caso usuário tenha imagem de perfil (fisico e banco)
			if($args['user_img_large'] != "" and file_exists(PATH_UPLOAD."/user_photos/".$args['user_img_large'] ) and $edit == false  )
			{			
				// variáveis necessárias para funcionar o editor de miniatura.
				$args['error'] = false;				
				$args['file_name'] = $args['user_img_large'];
				$args['thumb_name'] = $args['user_img'];
				$args['thumb_width'] = $this->file_crop->THUMB_WIDTH;
				$args['thumb_height'] = $this->file_crop->THUMB_HEIGHT;
				$args['file_width'] = $this->file_crop->get_width(PATH_UPLOAD."/user_photos/".$args['user_img_large'] );
				$args['file_height'] = $this->file_crop->get_height(PATH_UPLOAD."/user_photos/".$args['user_img_large'] );
				
				// Redireciona para tela de edição de miniatura
				$this->session->set_userdata('user_profile_photo_data', $args);
				
				// Carrega view editar 
				redirect('acme_user/user_profile_photo_edit_thumbnail');
			}else{
				// Carrega view upload de imagem
				$this->template->load_page('_acme/acme_user/user_profile_photo_upload', $args);
			}
		}
	}
	
	/**
	* user_profile_photo_upload_process()
	* Processa upload da foto de usuário.
	* @return void
	*/
	public function user_profile_photo_upload_process()
	{	
		// carrega lib de edição de imagem.
		$this->load->library('acme/file_crop');
		
		// Se ocorreu erro no upload do arquivo, variável é preenchido com erro.
		$args['error'] = (!$this->file_crop->upload_file()) ? lang($this->file_crop->error) : false;
	
		// Variáveis obtidas do upload do arquivo.
		$args['file_name'] = $this->file_crop->file_name;
		$args['thumb_name'] = $this->file_crop->thumb_name;
		$args['thumb_width'] = $this->file_crop->THUMB_WIDTH;
		$args['thumb_height'] = $this->file_crop->THUMB_HEIGHT;
		$args['file_width'] = $this->file_crop->file_width;
		$args['file_height'] = $this->file_crop->file_height;
		$args['id_user']  = $this->input->post('id_user');
		$args['file_name_old']  = ($this->input->post('file_name_old')=='')  ? '' : $this->input->post('file_name_old');		
		$args['thumb_name_old'] = ($this->input->post('thumb_name_old')=='') ? '' : $this->input->post('thumb_name_old');		
	
		// Se não ocorreu erro no upload.
		if($args['error'] == false)
		{
			// Apaga Imagem antiga
			$this->file_crop->delete_file( $this->input->post('file_name_old'));
			
			// Salva no banco o nome da nova imagem
			$this->db->set('url_img_large' , '<acme eval="URL_UPLOAD" />/user_photos/'.$this->file_crop->file_name );
			$this->db->where(array('id_user' => $this->input->post('id_user')));
			$this->db->update('acm_user');		
		}
		
		// Seta variáveis da imagem na sessão
		$this->session->set_userdata('user_profile_photo_data', $args);
		
		// Redireciona para tela de edição de miniatura
		redirect('acme_user/user_profile_photo_edit_thumbnail');
	}
	
	/**
	* user_profile_photo_edit_thumbnail()
	* Tela de edição de miniatura de imagem do usuário..
	* @return void
	*/
	public function user_profile_photo_edit_thumbnail()
	{
		// Pega da sessão os dados da imagem
		$args = $this->session->userdata('user_profile_photo_data');
		
		// Só permite edição caso imagem exista
		if(isset($args) && count($args) > 0) 
		{
			$this->template->load_page('_acme/acme_user/user_profile_photo_edit_thumbnail', $args);
		} else {
			redirect($this->session->userdata('url_default'));
		}// Carrega view
	}
	
	/**
	* user_profile_photo_save_thumb()
	* Processa edição da foto do usuário.
	* @return void
	*/
	public function user_profile_photo_save_thumb()
	{
		// carrega lib de edição de imagem.
		$this->load->library('acme/file_crop');
	
		// Salva miniatura com novas coordenadas.
		if( $this->file_crop->save_thumb($this->input->post())){
			//apaga a miniatura antiga			
			$this->file_crop->delete_file($this->input->post('thumb_name_old'));
		}		
		
		// salva imagens no banco.
		$this->db->set('url_img', '<acme eval="URL_UPLOAD" />/user_photos/'.$this->input->post('thumb_name'));
		$this->db->set('url_img_large', '<acme eval="URL_UPLOAD" />/user_photos/'.$this->input->post('file_name') );
		$this->db->where(array('id_user' => $this->input->post('id_user')));
		$this->db->update('acm_user');
		
		// Muda a imagem da sessão atual (somente caso o usuário seja o mesmo sendo alterado)
		if($this->input->post('id_user') == $this->session->userdata('id_user'))
			$this->session->set_userdata('user_img', URL_UPLOAD."/user_photos/".$this->input->post('thumb_name'));
		
		redirect('acme_user/user_profile/' . $this->input->post('id_user'));		
		//$this->template->load_page('_acme/acme_user/user_profile_photo_save_thumb', array(), false, false);
	}
	
	/**
	* ajax_modal_data_edit()
	* Carrega dados de edição do perfil do usuário.
	* @param integer id_user
	* @return void
	*/
	public function ajax_modal_data_edit($id_user = 0)
	{	
		// Se tem permissão de edição.
		if(($this->validate_permission('EDIT_PROFILE', false)) || ($id_user == $this->session->userdata('id_user')) )
		{				
			// carrega model user_group para preencher combo, com todos grupos.
			$this->load->model('acme/acme_user_group_model');
			$arr_groups = $this->acme_user_group_model->get_all_group();			
			$args['arr_groups'] = $arr_groups;			
			
			// Carrega dados do usuário.
			$arr_userdata = $this->acme_user_model->get_user_data($id_user);				
			
			$args['id_user']        = get_value($arr_userdata, 'id_user');
			$args['login']          = get_value($arr_userdata, 'login');
			$args['password']       = get_value($arr_userdata, 'password');
			$args['name']           = get_value($arr_userdata, 'name');
			$args['email']          = get_value($arr_userdata, 'email');						
			$args['id_user_group']  = get_value($arr_userdata, 'id_user_group');
			$args['group']          = get_value($arr_userdata, 'grup');
			$args['id_user_lang']   = get_value($arr_userdata, 'id_user_lang');
			$args['lang_default']   = get_value($arr_userdata, 'lang_default');
			$args['url_default']    = get_value($arr_userdata, 'url_default');
			
			$args['options_groups'] = $this->form->build_array_html_options($arr_groups, get_value($arr_userdata, 'id_user_group'));
			
			// Carrega view
			$this->template->load_page('_acme/acme_user/ajax_modal_data_edit', $args, false, false);
		}
	}
	
	/**
	* ajax_modal_data_edit_process()
	* Salva dados pessoais do perfil.
	* @return void
	*/
	public function ajax_modal_data_edit_process()
	{
		// Se tem permissão de edição.
		if(($this->validate_permission('EDIT_PROFILE', false)) || ($this->input->post('id_user') == $this->session->userdata('id_user')) )
		{					
			// Coloca os dados do formulário de edição do perfil em array
			foreach($this->input->post() as $column => $value)
			{
				if($value == '')
				{
					$value = 'NULL';
					$escape = false;
				} else {
					$escape = true;
				}			
				// escapa colunas				
				if($column != 'id_user' and $column != 'lang_default')
					$this->db->set($column, $value, $escape);
			}
			
			$this->db->where(array('id_user' => $this->input->post('id_user')));
			$this->db->update('acm_user');
			
			// Salva informações de idioma.
			if($this->input->post('lang_default')!='' && $this->input->post('url_default')!='') 
			{
				$this->db->set('lang_default', $this->input->post('lang_default'));
				$this->db->set('url_default',  $this->input->post('url_default'));
				$this->db->where(array('id_user' => $this->input->post('id_user')));
				$this->db->update('acm_user_config');
			}
			
			$this->template->load_page('_acme/acme_user/ajax_modal_data_edit_process', array(), false, false);
		}
	}
	
	/**
	* ajax_change_password()
	* Tela modal de alteração de password. Só permite alteração de senha do usuário corrente da sessão.
	* @param integer id_user
	* @return void
	*/
	public function ajax_change_password($id_user = 0)
	{
		// Dados do bookmark
		if($this->session->userdata('id_user') == $id_user)
		{
			// Coleta dados do usuário
			$args['user'] = $this->acme_user_model->get_user_data($id_user);
			// Carrega view
			$this->template->load_page('_acme/acme_user/ajax_change_password', $args, false, false);
		}
	}
	
	/**
	* ajax_change_password_process()
	* Processa tela modal de alteração de password.
	* @return void
	*/
	public function ajax_change_password_process()
	{
		// Dados do bookmark
		if($this->session->userdata('id_user') == $this->input->post('id_user'))
		{
			// Coleta dados do usuário
			$args['user'] = $this->access->validate_login($this->input->post('login'), $this->input->post('actual_password'));
			$args['id_user'] = $this->input->post('id_user');
			
			// Usuário realmente ok!
			if($args['user'])
			{
				$this->db->set('password', md5($this->input->post('new_password')));
				$this->db->where(array('id_user' => $this->input->post('id_user')));
				$this->db->update('acm_user');
			}
			
			// Carrega view
			$this->template->load_page('_acme/acme_user/ajax_change_password_process', $args, false, false);
		}
	}
	
	/**
	* form_insert_custom()
	* Formulário customizado de inserção de usuário. Regras básicas:
	* -> Caso o usuário que esteja tentando inserir outro usuário pertença a outro grupo diferente
	*    de ROOT, então não é possível inserir o usuário neste grupo ROOT. Apenas outros usuários
	*    do grupo ROOT podem inserir.
	* @return void
	*/
	public function form_insert_custom()
	{
		// Valida a permissão
		$this->validate_permission('INSERT');
		
		// Coleta grupos para fazer validação posterior
		$this->load->model('acme/acme_user_group_model');
		$args['groups'] = $this->acme_user_group_model->get_user_groups();
		
		// Variável de teste se usuário atual é ROOT
		$args['is_root'] = ($this->session->userdata('user_group') == 'ROOT') ? true : false;
		
		// Carrega view
		$this->template->load_page('_acme/acme_user/form_insert_custom', $args);
	}
	
	/**
	* form_insert_custom_process()
	* Processa formulário customizado de inserção de usuário.
	* @return void
	*/
	public function form_insert_custom_process()
	{
		// Valida a permissão
		$this->validate_permission('INSERT');
		
		// Inicializa transação
		$this->db->trans_start();
		
		// Insere usuário (acm_user)
		$arr_ins['id_user_group'] = $this->input->post('id_user_group');
		$arr_ins['name'] = $this->input->post('name');
		$arr_ins['email'] = $this->input->post('email');
		$arr_ins['login'] = $this->input->post('login');
		$arr_ins['password'] = md5($this->input->post('password'));
		$arr_ins['observation'] = $this->input->post('observation');
		$this->db->insert('acm_user', $arr_ins);
		$id_user = $this->db->insert_id();
		
		// Insere na tabela de configurações
		$arr_con['id_user'] = $id_user;
		$arr_con['lang_default'] = $this->input->post('lang_default');
		$arr_con['url_default'] = $this->input->post('url_default');
		$this->db->insert('acm_user_config', $arr_con);
		
		// Loga inserção de usuário
		$this->log->db_log(lang('Inserção de usuário'), 'insert', 'acm_user', array_merge($arr_ins, $arr_con));
		
		// Completa transação
		$this->db->trans_complete();
		
		// Redirect para entrada do módulo
		redirect('acme_user');
	}
	
	/**
	* form_update_custom()
	* Formulário customizado de inserção de usuário. Regras básicas:
	* -> Caso o usuário atual não seja do grupo ROOT, então não poderá editar os dados de usuários
	*    do grupo ROOT.
	* @param int id_user
	* @return void
	*/
	public function form_update_custom($id_user = 0)
	{
		// Valida a permissão
		$this->validate_permission('UPDATE');
		
		// Coleta dados do usuário de id encaminhado
		$args['user'] = $this->acme_user_model->get_user_data($id_user);
		
		// Coleta grupos para fazer validação posterior
		$this->load->model('acme/acme_user_group_model');
		$args['groups'] = $this->acme_user_group_model->get_user_groups();
		
		// Variável de teste se usuário atual é ROOT
		$args['is_root'] = ($this->session->userdata('user_group') == 'ROOT') ? true : false;
		
		// Variável para teste de edição
		$args['editable'] = ($this->session->userdata('user_group') != 'ROOT' && get_value($args['user'], 'group_name') == 'ROOT') ? false : true;
		
		// Carrega view
		$this->template->load_page('_acme/acme_user/form_update_custom', $args);
	}
	
	/**
	* form_update_custom_process()
	* Processa formulário customizado de edição de usuário.
	* @return void
	*/
	public function form_update_custom_process()
	{
		// Valida a permissão
		$this->validate_permission('UPDATE');
		
		// Inicializa transação
		$this->db->trans_start();
		
		// Coleta os dados do usuário para log
		$id_user = $this->input->post('id_user');
		$user = $this->acme_user_model->get_user_data($id_user);
		
		// Insere usuário (acm_user)
		if($this->input->post('login') != '')
		{
			$arr_ins['login'] = $this->input->post('login');
		}
		if($this->input->post('dtt_inative') != '')
		{
			$this->db->set('dtt_inative', $this->input->post('dtt_inative'), false);
		} else {
			$this->db->set('dtt_inative', 'NULL', false);
		}
		$arr_ins['id_user_group'] = $this->input->post('id_user_group');
		$arr_ins['name'] = $this->input->post('name');
		$arr_ins['email'] = $this->input->post('email');
		$arr_ins['observation'] = $this->input->post('observation');
		$this->db->update('acm_user', $arr_ins, array('id_user' => $id_user));
		
		// Coleta os dados de configs do usuário para log
		$configs = $this->acme_user_model->get_user_data($id_user);
		
		// Insere na tabela de configurações
		$arr_con['id_user'] = $id_user;
		$arr_con['lang_default'] = $this->input->post('lang_default');
		$arr_con['url_default'] = $this->input->post('url_default');
		$this->db->update('acm_user_config', $arr_con, array('id_user' => $id_user));
		
		// Loga edição de usuário
		$this->log->db_log(lang('Edição de usuário'), 'update', 'acm_user', array(array_merge($user, $configs), array_merge($arr_ins, $arr_con)));
		
		// Completa transação
		$this->db->trans_complete();
		
		// Redirect para entrada do módulo
		redirect('acme_user');
	}
	
	/**
	* verify_login()
	* Verifica se um login de id encaminhado existe ou não e retorna um booleano em JSON.
	* @param string login
	* @return void
	*/
	public function verify_login($login = '')
	{
		$data = $this->db->get_where('acm_user', array('login' => $login));
		$user = $data->result_array();
		$user = isset($user[0]) ? $user[0] : array();
		$ret['user_exists'] = (get_value($user, 'id_user') != '') ? true : false;
		echo json_encode($ret);
	}
	
	/**
	* reset_password()
	* Tela de confirmação de solicitação de reset de senha.
	* @param int id_user
	* @return void
	*/
	public function reset_password($id_user = 0)
	{
		$this->validate_permission('RESET_PASSWORD');
		
		// Coleta dados do usuário
		$args['user'] = $this->acme_user_model->get_user_data($id_user);
		
		// Carrega view
		$this->template->load_page('_acme/acme_user/reset_password', $args);
	}
	
	/**
	* reset_password_process()
	* Processa tela de confirmação de solicitação de reset de senha. Faz o envio realmente de email
	* para o usuário.
	* @return void
	*/
	public function reset_password_process()
	{
		$this->validate_permission('RESET_PASSWORD');
		
		// Coleta dados do usuário
		$user = $this->acme_user_model->get_user_data($this->input->post('id_user'));
		
		// Tenta enviar email para usuário
		// caso falhe, nao faz insert na tabela de log de resets
		$args['user'] = $user;
		$args['key_access'] = md5(uniqid());
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
			
			// Loga reenvio de senha
			$this->log->db_log(lang('Solicitação de Alteração de Senha'), 'reset_password', 'acm_user_reset_password', $user);
		} else {
			$args['sent_email'] = false;
		}
		
		// Carrega view
		$this->template->load_page('_acme/acme_user/reset_password_process', $args);
	}
	
	/**
	* ajax_copy_permissions()
	* Modal de cópia de permissões de um determinado usuário para o usuário de id encaminhado.
	* @param integer id_user
	* @return void
	*/
	public function ajax_copy_permissions($id_user = 0)
	{
		// Valida permissão
		$args['permission'] = $this->validate_permission('COPY_PERMISSIONS', false);
		
		// Dados do usuário
		$args['user'] = $this->acme_user_model->get_user_data($id_user);
		
		// Dados de opções de usuário
		$args['user_options'] = $this->form->build_array_html_options($this->acme_user_model->get_users_to_html_options());
		
		// Variável para teste de caso usuario nao seja root e esteja tentando acessar uma copia para um
		$args['editable'] = ($this->session->userdata('user_group') != 'ROOT' && get_value($args['user'], 'group_name') == 'ROOT') ? false : true;
		
		// Carrega view
		$this->template->load_page('_acme/acme_user/ajax_copy_permissions', $args, false, false);
	}
	
	/**
	* ajax_copy_permissions_process()
	* Processa modal de cópia de permissões de um determinado usuário para outro, ambos de id
	* encaminhado por post.
	* @return void
	*/
	public function ajax_copy_permissions_process()
	{
		$id_user_to = $this->input->post('id_user_to');
		$id_user_from = $this->input->post('id_user_from');
		if($this->validate_permission('COPY_PERMISSIONS', false) && $id_user_from != '' && $id_user_to != '')
		{
			// Deleta permissões anteriores do usuário PARA
			$this->db->where(array('id_user' => $id_user_to));
			$this->db->delete('acm_user_permission');
			
			// Copia permissões
			$this->acme_user_model->copy_permissions($id_user_from, $id_user_to);
			
			// Carrega view
			$this->template->load_page('_acme/acme_user/ajax_copy_permissions_process', array(), false, false);
		}		
	}
}
