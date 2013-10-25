<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe cms_website_menu (Arquivo gerado com construtor de módulos)
* 
* Módule cms_website_menu: 
*
* @since		30/07/2013
* @location		controllers.cms_website_menu
*
*/
class cms_website_menu extends Acme_Base_Module {
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
	* Entrada do módulo. Exibe menus do website em formato de árvore vertical.
	* @return void
	*/
	public function index()
	{
		// Valida permissão de entrada do módulo
		$this->validate_permission('ENTER');
		
		// Filtros encaminhados - dá preferencia por POST, se não tenta localizar o array
		// de filtros do modulo que fica perambulando na sessao. Para este segundo caso,
		// tenta localizar conforme o nome do modulo, entao apaga todos os outros filtros.
		if($this->input->post() != '')
		{
			$filters = $this->input->post();
		} else if (get_value($this->session->userdata('module_array_filters'), $this->controller . '_filters') != '') {
			$filters = get_value($this->session->userdata('module_array_filters'), $this->controller . '_filters');
			$this->session->set_userdata('module_array_filters', null);
		} else {
			$filters = array();
		}
		
		// Seta o array de filtros em sessão para que quando alterar a pagina ainda dentro do modulo
		// e voltar para a pagina de listagem os filtros permaneçam como estavam anteriormente.
		$this->session->set_userdata('module_array_filters', array($this->controller . '_filters' => $filters));
		
		// Faz leitura dos menus do website
		$menus = $this->cms_website_menu_model->get_list_module();
		$menus = (count($menus) > 0) ? $this->template->menus_to_tree($menus) : array();
		
		// Carrega view
		$this->template->load_page('cms_website_menu/index', array('menus' => $menus));
	}
	
	/**
	* ajax_reorder_website_menu()
	* Atualiza o nodo de menu reordenado via interface, com base no id do nodo encaminhado.
	* @param int id_menu
	* @param int id_menu_parent_new
	* @param int order
	* @return void
	*/
	public function ajax_reorder_website_menu($id_menu = 0, $id_menu_parent_new = 0, $order = 0)
	{
		if($this->validate_permission('UPDATE', false))
		{
			$this->db->set('id_website_menu_parent', $id_menu_parent_new, false);
			$this->db->set('order', $order, false);
			$this->db->where(array('id_website_menu' => $id_menu));
			$this->db->update('cms_website_menu');
		} else {
			// Quando encaminhado um erro no cabeçalho http, o ajax dispara o callback error
			// exibindo a mensagem de que o usuário atual não possui esta permissão (UPDATE)
			header("HTTP/1.0 500 Internal Server Error");
		}
	}
	
	/**
	* ajax_modal_website_menu_new()
	* Modal de inserção de dados de um novo menu de website.
	* @return void
	*/
	public function ajax_modal_website_menu_new()
	{
		if($this->validate_permission('INSERT', false))
		{
			// Coleta os menus disponíveis
			$args['options_menus'] = $this->form->build_array_html_options($this->cms_website_menu_model->get_list_menus(), '', false);
			
			// Carrega view
			$this->template->load_page('cms_website_menu/ajax_modal_website_menu_new', $args, false, false);
		} else {
			$this->error->show_exception_message(lang('Você não possui permissões para executar esta ação (insert).'));
		}
	}
	
	/**
	* ajax_modal_website_menu_new_process()
	* Processa modal de inserção de menu de site.
	* @return void
	*/
	public function ajax_modal_website_menu_new_process()
	{
		if($this->validate_permission('INSERT', false))
		{
			foreach($this->input->post() as $column => $value)
			{
				if($value == '')
				{
					$value = 'NULL';
					$escape = false;
				} else {
					$escape = true;
				}
				$this->db->set($column, $value, $escape);
			}
			
			$this->db->insert('cms_website_menu');
			
			// Carrega view
			$this->template->load_page('cms_website_menu/ajax_close_modal_reload_page', array(), false, false);
		}
	}
	
	/**
	* ajax_modal_website_menu_update()
	* Modal de edição de dados de um determinado menu.
	* @param int id_menu
	* @return void
	*/
	public function ajax_modal_website_menu_update($id_menu = 0)
	{
		if($this->validate_permission('UPDATE', false))
		{
			$result = $this->db->get_where('cms_website_menu', array('id_website_menu' => $id_menu));
			$result = $result->result_array();
			if(isset($result[0]))
			{
				// Busca menu, Carrega view
				$args['data'] = $result[0];
				$this->template->load_page('cms_website_menu/ajax_modal_website_menu_update', $args, false, false);
			}
		} else {
			$this->error->show_exception_message(lang('Você não possui permissões para executar esta ação (update).'));
		}
	}
	
	/**
	* ajax_modal_website_menu_update_process()
	* Processa modal de atualização de menu do website.
	* @return void
	*/
	public function ajax_modal_website_menu_update_process()
	{
		if($this->validation->is_integer_($this->input->post('id_menu')) && $this->validate_permission('UPDATE', false))
		{
			foreach($this->input->post() as $column => $value)
			{
				if($value == '')
				{
					$value = 'NULL';
					$escape = false;
				} else {
					$escape = ($column == 'dtt_inative') ? false : true;
				}
				
				if($column != 'id_menu')
					$this->db->set($column, $value, $escape);
			}
			
			$this->db->where(array('id_website_menu' => $this->input->post('id_menu')));
			$this->db->update('cms_website_menu');
			
			// Carrega view
			$this->template->load_page('cms_website_menu/ajax_close_modal_reload_page', array(), false, false);
		}
	}
	
	/**
	* ajax_modal_website_menu_delete()
	* Modal de deleção de menu do website.
	* @param integer id_menu
	* @return void
	*/
	public function ajax_modal_website_menu_delete($id_menu = 0)
	{
		if($this->validation->is_integer_($id_menu) && $this->validate_permission('DELETE', false))
		{
			$result = $this->db->get_where('cms_website_menu', array('id_website_menu' => $id_menu));
			$result = $result->result_array();
			if(isset($result[0]))
			{
				// Variaveis para view
				// Faz leitura dos menus do website
				$data = $result[0];
				$menus = $this->cms_website_menu_model->get_list_module();
				$menus = (count($menus) > 0) ? $this->template->menus_to_tree($menus) : array();
		
				// Carrega view
				$this->template->load_page('cms_website_menu/ajax_modal_website_menu_delete', array('menus' => $menus, 'data' => $data), false, false);
			}
		} else {
			$this->error->show_exception_message(lang('Você não possui permissões para executar esta ação (delete).'));
		}
	}
	
	/**
	* ajax_modal_website_menu_delete_process()
	* Processa modal de deleção de menu do website.
	* @return void
	*/
	public function ajax_modal_website_menu_delete_process()
	{
		if($this->validation->is_integer_($this->input->post('id_menu')) && $this->validate_permission('DELETE', false))
		{
			$this->db->delete('cms_website_menu', array('id_website_menu' => $this->input->post('id_menu')));
			// Carrega view
			$this->template->load_page('cms_website_menu/ajax_close_modal_reload_page', array(), false, false);
		}
	}
}
