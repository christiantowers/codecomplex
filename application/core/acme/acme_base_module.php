<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Acme_Base_Module
* 
* Módulo base utilizado na aplicação. Todo e qualquer módulo deve extender desta classe, que
* contém métodos referentes à abstração de um módulo básico rodado dentro da aplicação ACME Engine.
*
* Fluxo de carregamento do módulo:
* 1) Construção do objeto da classe;
* 2) Verifica se a sessão é válida, isto é, usuário está logado;
* 3) Localiza dados do módulo no banco de dados, com base no nome da classe;
* 4) Carrega o módulo, isto é, transpassa os dados do banco de dados para o objeto,
*    para que este fique num estado utilizável;
* 5) Carrega camada modelo do módulo
* 6) Redireciona para listagem do módulo;
*
* @since		25/10/2012
* @location		acme.controllers.acme_module_base
*
*/
class Acme_Base_Module extends Acme_Core {
	// Definição de Atributos
	var $id_module = '';
	var $controller = '';
	var $table = '';
	var $lang_key_rotule = '';
	var $url_img = '';
	var $description = '';
	var $sql_list = '';
	var $items_per_page = '';
	
	// Atributos que são resultsets
	var $menus = array();
	var $actions = array();
	var $configs = array();
	var $filters = array();
	
	// Atributos de layout, visualização
	var $entry_link = '';
	var $grid_html = '';
	var $header_html = '';
	var $filter_html = '';
	
	/**
	* __construct()
	* Construtor de classe. Recebe o nome da classe do módulo a ser carregado.
	* @param string controller
	* @return object
	*/
	public function __construct($controller = '')
	{
		parent::__construct();
		
		// Antes de tudo, verifica se sessão é válida
		$this->access->validate_session();
		
		// Seta a classe do módulo atual para o que for encaminhado
		// este valor é utilizado para carregar o restante dos dados
		$this->controller = strtolower($controller);
		
		// Carrega model do módulo
		$this->load->model('acme/core/base_module_model');
		
		// Carrega o módulo
		$this->load_module();
	}
	
	/**
	* load_module()
	* Carrega os dados do módulo, do banco de dados para o objeto atual. Prepara o módulo para que
	* este fique em um estado onde possa ser utilizável.
	* @return void
	*/
	public function load_module()
	{
		// Localiza dados no banco de dados
		$module = $this->base_module_model->get_module_data($this->controller);
		if(count($module) > 0)
		{
			// Carrega conteúdo para atributos do objeto/controller
			$this->id_module = get_value($module, 'id_module');
			$this->lang_key_rotule = lang(get_value($module, 'lang_key_rotule'));
			$this->sql_list = get_value($module, 'sql_list');
			$this->url_img = eval_replace(get_value($module, 'url_img'));
			$this->description = lang(get_value($module, 'description'));
			$this->table = get_value($module, 'table');
			$this->items_per_page = get_value($module, 'items_per_page');
			
			// Carrega camada model do modulo (classes com prefixo acme_ sao carregadas de models/acme/classe
			$model = (stripos($this->controller, 'acme_') === 0) ? 'acme/' . $this->controller . '_model' : $this->controller . '_model';
			$this->load->model($model);
			
			// Localiza e seta filtros do modulo
			$this->filters = $this->{$this->controller . '_model'}->get_filters($this->id_module);
			
			// Localiza e seta menus do modulo
			$this->menus = $this->{$this->controller . '_model'}->get_menus($this->id_module);
			
			// Localiza e seta Actions
			$this->actions = $this->{$this->controller . '_model'}->get_actions($this->id_module);
			
			// Seta variaveis pertinentes ao model
			$this->{$this->controller . '_model'}->set_sql_list($this->sql_list);
			$this->{$this->controller . '_model'}->set_table($this->table);
			$this->{$this->controller . '_model'}->set_primary_key();
		} else {
			// Não localizou um módulo cadastrado com o nome da classe
			$this->error->show_error(lang('Módulo não localizado.'), lang('Não foi possível carregar o módulo especificado. Certifique-se que o nome da classe definida para este módulo está de acordo com o cadastrado no banco de dados.') . ' Classe: ' . $this->controller);
		}
	}
	
	/**
	* validate_permission()
	* Valida uma permissão do módulo corrente.
	* @param string permission
	* @param boolean exib_page
	* @param integer id_user
	* @return mixed has_permission
	*/
	public function validate_permission($permission = '', $exib_page = true, $id_user = 0)
	{
		// Valida permissao pela biblioteca access
		return $this->access->validate_permission($this->controller, $permission, $exib_page, $id_user);
	}
	
	/**
	* index()
	* Listagem do módulo. Tela de entrada, retrieve de dados.
	* @return void
	*/
	public function index($actual_page = 0)
	{
		// Permissao de leitura do modulo
		$this->validate_permission('ENTER');
		
		// Filtros encaminhados - dá preferencia por POST, se não tenta localizar o array
		// de filtros do modulo que fica perambulando na sessao. Para este segundo caso,
		// tenta localizar conforme o nome do modulo, entao apaga todos os outros filtros.
		if($this->input->post() != '')
		{
			$filters = $this->input->post();
		} else if (get_value($this->session->userdata('module_array_filters'), $this->controller . '_filters') != '') {
			$filters = get_value($this->session->userdata('module_array_filters'), $this->controller . '_filters');
			$this->session->unset_userdata('module_array_filters');
		} else {
			$filters = array();
		}
		
		// DEBUG:
		// print_r($filters);
		
		// Seta o array de filtros em sessão para que quando alterar a pagina ainda dentro do modulo
		// e voltar para a pagina de listagem os filtros permaneçam como estavam anteriormente.
		$this->session->set_userdata('module_array_filters', array($this->controller . '_filters' => $filters));
		
		// Mensagem de sem consulta, caso modulo sem sql
		if($this->sql_list != '')
		{
			// Formulario de filtros (Transforma array de fields do banco em string <input ... )
			$args['module_form_filter'] = $this->template->load_form_filter($this->filters, $filters, URL_ROOT . '/' . $this->controller);
		
			// Resultset do sql de listagem do módulo (encaminha post que são os filtros)
			$resultset_module = $this->{$this->controller . '_model'}->get_resultset_module($filters);
		
			// Monta tabela de dados
			$obj_table = $this->array_table->get_instance();
			$obj_table->set_id($this->controller . '_module_table');
			$obj_table->set_items_per_page($this->items_per_page);
			$obj_table->set_actual_page($actual_page);
			$obj_table->set_page_link($this->controller . '/');
			$obj_table->set_data($resultset_module);
			
			// Adiciona a tabela de dados, as possíveis ações do módulo
			if(count($this->actions) > 0)
			{
				foreach($this->actions as $action)
				{
					$column  = '<a href="' . eval_replace(get_value($action, 'link')) . '"';
					$column .= (get_value($action, 'target') != '') ? ' target="' . get_value($action, 'target') . '"' : '';
					$column .= (get_value($action, 'lang_key_rotule') != '') ? ' title="' . get_value($action, 'lang_key_rotule') . '"' : '';
					$column .= (get_value($action, 'javascript') != '') ? ' ' . get_value($action, 'javascript') . ' ' : '';
					$column .= ">";
					$column .= (get_value($action, 'url_img') != '') ? '<img src="' . eval_replace(get_value($action, 'url_img')) . '" />' : '';
					$column .= (get_value($action, 'url_img') == '') ? get_value($action, 'lang_key_rotule') : '';
					$column .= '</a>';
					$obj_table->add_column($column);
				}
			}
		
			// Html da tabela
			$args['module_table'] = $obj_table->get_html();
		} else {
			$args['module_table'] = message('note', lang('Sem consulta'), lang('Este módulo não possui uma consulta de exibição de dados cadastrada.'));
			$args['module_form_filter'] = '';
		}
		
		// Carrega página de exibição de dados
		$this->template->load_page('_acme/acme_base_module/index', $args);
	}
	
	/**
	* form()
	* Página de montagem de formulario. Recebe como parametro o tipo de formulario (operation)
	* que devera ser montado.
	* @param string operation
	* @param integer pk_value
	* @return void
	*/
	public function form($operation = '', $pk_value = 0)
	{
		// Permissao da operacao do formulario
		$this->validate_permission($operation);
		
		// Ajusta operacao
		$operation = strtolower($operation);
		
		// Caso a operacao nao seja insert, deve validar a chave primaria
		if(($operation != 'insert' && $this->validation->is_integer_($pk_value)) || $operation == 'insert')
		{
			// Coleta dados do form
			$form = $this->{$this->controller . '_model'}->get_form($this->id_module, $operation);
			if(count($form) > 0)
			{
				// Coleta fields e values destes fields
				$fields = $this->{$this->controller . '_model'}->get_form_fields(get_value($form, 'id_module_form'));
				$values = (is_integer_($pk_value)) ? $this->{$this->controller . '_model'}->select($pk_value) : array();
				$values = (count($values) > 0) ? $values[0] : $values;
				
				// print_r($values[0]);
				
				// Transforma campos e valores em campos HTML
				$html_fields = $this->form->build_array_html_form_fields($fields, $values);
			
				// Variaveis do view
				$args['form'] = $form;
				$args['html_fields'] = $html_fields;
				$args['fields'] = $fields;
				$args['values'] = $values;
				$args['operation'] = $operation;
				$args['pk_value'] = $pk_value;
			
				// Load do view
				$this->template->load_page('_acme/acme_base_module/form_' . $operation, $args);
			} else {
				// Carrega página de formulário inexistente
				$this->template->load_page('_acme/acme_base_module/form_warning_exist');
			}
		} else {
			// Redireciona para entrada do modulo
			redirect(URL_ROOT . '/' . $this->controller);
		}
	}
	
	/**
	* form_process()
	* Processa formulario do modulo. Contempla as 4 operacoes basicas (insert, update, delete, view).
	* @return void
	*/
	public function form_process()
	{
		// Ajusta operacao
		$operation = strtolower($this->input->post('operation'));
		
		// Permissao da operacao do formulario
		$this->validate_permission($operation);
		
		// VALIDAÇÕES EM PHP, FAZER AQUI!
		
		// Chama funcao interna correspondente a operacao (_insert, _update, _view, _delete)
		$this->{'_' . $operation}($this->input->post());
	}
	
	/**
	* _insert()
	* Insere um registro encaminhado através de um processamento de formulario.
	* @param array post
	* @return void
	*/
	private function _insert($post = array())
	{
		// Só faz insert caso post encaminhado
		if(count($post) > 0)
		{			
			// Array de dados do insert
			$form_data = get_value($post, $this->table);
			
			// insere registro na tabela vinculada ao modulo
			$this->{$this->controller . '_model'}->insert($form_data);
			
			// Insere um registro de log
			$this->log->db_log('Inserção de registro', 'insert', $this->table, $post);
		}
		
		// Redireciona para entrada do modulo
		redirect(URL_ROOT . '/' . $this->controller);
	}
	
	/**
	* _update()
	* Atualiza um registro encaminhado atraves de um processamento de formulario.
	* @param array post
	* @return void
	*/
	private function _update($post = array())
	{
		// Só faz ação caso post encaminhado
		if(count($post) > 0)
		{			
			// Array de dados
			$form_data = get_value($post, $this->table);
			
			// Insere um registro de log
			$old = $this->{$this->controller . '_model'}->select(get_value($post, 'primary_key_value'));
			$arr_log['old'] = $old[0];
			$arr_log['new'] = $post;
			$this->log->db_log('Edição de registro', 'update', $this->table, $arr_log);
			
			// Atualiza registro na tabela vinculada ao modulo
			$this->{$this->controller . '_model'}->update($form_data, array($this->{$this->controller . '_model'}->primary_key => get_value($post, 'primary_key_value')));
		}
		
		// Redireciona para entrada do modulo
		redirect(URL_ROOT . '/' . $this->controller);
	}
	
	/**
	* _delete()
	* Remove um registro encaminhado atraves de um processamento de formulario.
	* @param array post
	* @return void
	*/
	private function _delete($post = array())
	{
		// Só faz ação caso post encaminhado
		if(count($post) > 0)
		{			
			// Insere um registro de log
			$old = $this->{$this->controller . '_model'}->select(get_value($post, 'primary_key_value'));
			$arr_log = $old[0];
			$this->log->db_log('Deleção de registro', 'delete', $this->table, $arr_log);
			
			// Remove registro
			$this->{$this->controller . '_model'}->delete(array($this->{$this->controller . '_model'}->primary_key => get_value($post, 'primary_key_value')));
		}
		
		// Redireciona para entrada do modulo
		redirect(URL_ROOT . '/' . $this->controller);
	}
	
	/**
	* _view()
	* View de um registro encaminhado atraves de um processamento de formulario (não faz nada).
	* @param array post
	* @return void
	*/
	private function _view($post = array())
	{
		// Redireciona para entrada do modulo
		redirect(URL_ROOT . '/' . $this->controller);
	}
}
