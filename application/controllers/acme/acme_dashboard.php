<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Acme_Engine
* 
* Classe abstracao para motor de geração de códigos e instalação de sistema.
*
* @since		15/10/2012
* @location		acme.controllers.acme_dashboard
*
*/
class Acme_Dashboard extends Acme_Base_Module {
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
	* Método 'padrão' do controlador. Dashboard do sistema.
	* @return void
	*/
	public function index()
	{
		$this->validate_permission('VIEW_DASHBOARD');
		
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
		
		// Seta o array de filtros em sessão para que quando alterar a pagina ainda dentro do modulo
		// e voltar para a pagina de listagem os filtros permaneçam como estavam anteriormente.
		$this->session->set_userdata('module_array_filters', array($this->controller . '_filters' => $filters));
		
		// Inicializa a média de tempo de acesso à página
		$this->benchmark->mark('code_start');
		
		// Carrega o rank de acesso de browsers no sistema.
		$args['browsers'] = $this->access->browser_rank();
		
		// Usuários por grupo
		$this->load->model('acme/acme_user_group_model');
		$args['groups'] = $this->acme_user_group_model->get_users_by_group();
		
		// Calcula filtros para modulos (exibir somente modulos do acme ou app)
		$args['show_acme_modules'] = (get_value($filters, 'show_acme_modules') == 'Y') ? true : false;
		
		// Para box de error tracker
		$args['count_errors'] = $this->error->count_distinct_errors();
		$args['error_types'] = $this->error->get_distinct_types();
		$args['error_chart'] = $this->error->get_count_errors_by_type();
		
		// Para listagem de modulos
		$args['modules'] = $this->acme_dashboard_model->get_list_modules($args['show_acme_modules']);
		
		// Tempo médio de acesso
		$this->benchmark->mark('code_end');
		$args['average_time_access'] = $this->benchmark->elapsed_time('code_start', 'code_end');
		
		// Carrega camada de visualização
		$this->template->load_page('_acme/acme_dashboard/dashboard', $args);
	}
}
