<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe cms_website (Arquivo gerado com construtor de módulos)
* 
* Módule cms_website: Leitor de conteúdos, responsável por ser bootstrap do website atual.
*
* @since		30/07/2013
* @location		controllers.cms_website
*
*/
class cms_website extends Acme_Core {
	// Definição de atributos
	public $id_page_url; // ID da url no banco de dados, usado em log de acesso e afins
	public $url; // URL chave de acesso a esta página
	public $title; // Título da página URL, para ser utilizado em <title></title> no view
	public $method_show; // Define o método de exibição da página URL
	public $show_master_page; // Booleano se é para exibir página master ou não
	public $file_path_content; // Arquivo de conteúdo da página
	public $html_visual; // Conteúdo HTML criado no editor visual
	public $html_custom; // Conteúdo HTML criado no editor de código
	public $_404; // Booleano informando se a página é um 404 ou não (página não existe no banco de dados)
	public $error = false; // atributo auxiliar para tratamento de erros
	
	// Varivável de teste para carregar arquivo de conteúdo ou não (default false), para que a 
	// página master funcione com Rotas de url reescritas
	public $load_file_content = false;
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
		// Carrega construtor superior (Acme_Core)
		parent::__construct(__CLASS__);
		
		// Carrega bibliotecas necessárias
		$this->load->library('cms_tag');
		$this->load->library('cms_template');
		
		// Carrega helpers custom para website
		$this->load->helper('cms_template_helper');
		
		// Carrega model do website
		$this->load->model('cms_website_model');
		
		// Seta o template atual, com base nas configurações do website
		$website_template = $this->app_config->get_app_config_db('website_template');
		
		// Defines de configurações da APP
		define('WEBSITE_TITLE', $this->app_config->get_app_config_db('website_title'));
		
		// Monta os defines necessários para os paths do template do website
		define('URL_IMG_WEBSITE', URL_ROOT . '/application/views/' . $website_template . '/_includes/img');
		define('URL_JS_WEBSITE', URL_ROOT . '/application/views/' . $website_template . '/_includes/js');
		define('URL_CSS_WEBSITE', URL_ROOT . '/application/views/' . $website_template . '/_includes/css');
		define('URL_FONTS_WEBSITE', URL_ROOT . '/application/views/' . $website_template . '/_includes/fonts');
		define('URL_HTML_COMPONENTS_WEBSITE', URL_ROOT . '/application/views/' . $website_template . '/_includes/html_components');
		define('PATH_HTML_COMPONENTS_WEBSITE', $website_template . '/_includes/html_components');
		
		// Altera as propriedades do objeto config (para carregar componentes e páginas HTML do template corretamente)
		$this->app_config->TEMPLATE = $website_template;
		$this->app_config->URL_JS = URL_JS_WEBSITE;
		$this->app_config->URL_JS_WEBSITE = URL_JS_WEBSITE;
		$this->app_config->URL_CSS = URL_CSS_WEBSITE;
		$this->app_config->URL_CSS_WEBSITE = URL_CSS_WEBSITE;
		$this->app_config->URL_IMG = URL_IMG_WEBSITE;
		$this->app_config->URL_IMG_WEBSITE = URL_IMG_WEBSITE;
		$this->app_config->URL_HTML_COMPONENTS = URL_HTML_COMPONENTS_WEBSITE;
		$this->app_config->URL_HTML_COMPONENTS_WEBSITE = URL_HTML_COMPONENTS_WEBSITE;
		$this->app_config->PATH_HTML_COMPONENTS = PATH_HTML_COMPONENTS_WEBSITE;
		$this->app_config->PATH_HTML_COMPONENTS_WEBSITE = PATH_HTML_COMPONENTS_WEBSITE;
		
		// Carrega dados da pagina url do banco de dados com base na url atual (ajustada)
		$url = '/'. trim($this->uri->uri_string(), '/');
		$page_url = $this->_get_db_array_page_url($url);
		
		// DEBUG: Achou a página no banco de dados ?
		// print_r($page_url);
		
		// Caso ainda assim o registro não seja encontrado, então, lamento-vos mas isso é um 404
		$this->_load_page_url($page_url);
		
		// Registra um acesso a esta página (cms_page_url_access)
		$this->_try_log_access();
	}
	
	/**
	* _remap()
	* Faz mapeamento de método com base na url invocada. Substitui caracteres '-' da url por '_'.
	* Sendo assim, um action invocado na url com o nome 'get-something' deve ser declarado como 
	* método 'get_something()'. Este método é utilizado quando o metodo de exibição controller_exec
	* redireciona para um controlador/ação que possua traços em sua definição
	* @param string method
	* @return object
	*/
	public function _remap($method = '', $params = array()) 
	{
		$method = str_replace('-', '_', $method);
		if(method_exists($this, $method))
			return call_user_func_array(array($this, $method), $params);
		else
			$this->template->load_page('_errors/error_page_url_load', array(), false, false);
	}
	
	/**
	* _get_db_array_page_url()
	* Retorna um array de registro de dados do banco de dados da página de url encaminhada, ou
	* array vazio caso não localize. Tenta duas vezes: caso não encontre pela url encaminhada, 
	* diretamente, tenta localizar por URL original (modificada com rota, como acontece nos casos
	* de redirecionamento da url no método 'controller_exec').
	* @param string url
	* @return array page_url
	*/
	private function _get_db_array_page_url($url = '')
	{
		// Tenta achar a página url diretamente
		$page_url = $this->cms_website_model->get_page_url($url);
		
		// Caso a registro não seja encontrado, tenta mais uma vez utilizando a url de rota original
		$page_url = (count($page_url) <= 0) ? $this->cms_website_model->get_page_url($this->_try_get_original_route($url)) : $page_url;
		
		return $page_url;
	}
	
	/**
	* _load_page_url()
	* Carrega dados do objeto atual (atributos) com base em um array de dados da página URL
	* previamente carregado do banco de dados.
	* @param array  page_url
	* @return object
	*/
	private function _load_page_url($page_url = array())
	{
		// Caso a página seja encontrada no banco de dados, seta atributos do objeto página atual
		$this->id_page_url = get_value($page_url, 'id_page_url');
		$this->url = get_value($page_url, 'url');
		$this->title = get_value($page_url, 'title');
		$this->method_show = get_value($page_url, 'method_show');
		$this->show_master_page = (strtolower(get_value($page_url, 'show_master_page')) == 'y') ? true : false;
		$this->file_path_content = eval_replace(get_value($page_url, 'file_path_content'));
		$this->html_visual = get_value($page_url, 'html_visual');
		$this->html_custom = get_value($page_url, 'html_custom');
		$this->_404 = (count($page_url) > 0) ? false : true;
	}
	
	/**
	* _try_get_original_route()
	*  Muito bem, muito bem... Agora a url encaminhada é analisada para ver se bate com alguma
	* rota original estabelecida. Caso sim, verifica se existe uma página url com esta rota original
	* (geralmente isso ocorre quando há o redirecionamento de uma URL para um controlador/ação
	* onde a rota original possui um regex como, por exemplo, /downloads/get-acme-engine/(:any)
	* PORÇÃO DE CÓDIGO RETIRADA DO system/core/Router.php
	* @param string url
	* @return string route_url_original
	*/
	private function _try_get_original_route($url = '')
	{
		$original_route = '';
		$routes = $this->router->routes;
		
		// Loop through the route array looking for wild-cards
		foreach($routes as $key => $val)
		{
			// Get actual $key, to return after if necessary
			$aux_key = $key;
			
			// Convert wild-cards to RegEx
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));

			// Adjust key and val
			$key = trim($key, '/');
			$url = trim($url, '/');
			
			// Does the RegEx match?
			if (preg_match('#^'.$key.'$#', $url))
			{
				$original_route = '/' . trim($aux_key, '/');
				break;
			}
		}
		
		return $original_route;
	}
	
	/**
	* _try_log_access()
	* Loga acesso à página. Espera-se que até este ponto o objeto desta classe esteja 
	* devidamente preenchido.
	* @return void
	*/
	private function _try_log_access()
	{
		if($this->id_page_url != '' &&  $this->id_page_url != 0)
		{
			// Carrega helpers necessários
			$this->load->library('user_agent');
			
			// Monta array de inserção
			$arr_ins['id_page_url'] = $this->id_page_url;
			$arr_ins['user_agent'] = $this->input->user_agent();
			$arr_ins['ip_address'] = $this->input->ip_address();
			$arr_ins['is_ajax_request'] = ($this->input->is_ajax_request()) ? 'Y' : 'N';
			$arr_ins['is_referral'] = ($this->agent->is_referral()) ? 'Y' : 'N';
			$arr_ins['is_robot'] = ($this->agent->is_robot()) ? 'Y' : 'N';
			$arr_ins['is_browser'] = ($this->agent->is_browser()) ? 'Y' : 'N';
			$arr_ins['is_mobile'] = ($this->agent->is_mobile()) ? 'Y' : 'N';
			$arr_ins['browser_name'] = $this->agent->browser();
			$arr_ins['browser_version'] = $this->agent->version();
			$arr_ins['mobile_name'] = $this->agent->mobile();
			$arr_ins['robot_name'] = $this->agent->robot();
			$arr_ins['platform'] = $this->agent->platform();
			$arr_ins['referrer'] = ($this->agent->is_referral()) ? $this->agent->referrer() : '';
			$arr_ins['agent_string'] = $this->agent->agent_string();
			
			// Tenta ver se existe um registro exatamente igual, para não logá-lo
			$log = $this->db->get_where('cms_page_url_access', $arr_ins);
			$log = $log->result_array();
			
			// Loga acesso, definitivamente
			if(count($log) <= 0)
				$this->db->insert('cms_page_url_access', $arr_ins);
		}
	}
	
	/**
	* index()
	* Exibe a página inicial do website. A url desta página está definida nas configurações
	* do website cadastrada no banco de dados, tabela 'acm_app_config', chave 'website_url_homepage'.
	* @return void
	*/
	public function index()
	{
		$this->homepage();
	}
	
	/**
	* homepage()
	* Página inicial do website (index), quando nenhuma URL é informada.
	* @return void
	*/
	public function homepage()
	{
		// Força carregamento do objeto atual com base na URL index
		$url = '/' . trim($this->app_config->get_app_config_db('website_url_homepage'), '/');
		$page_url = $this->_get_db_array_page_url($url);
		
		// Carrega objeto página
		$this->_load_page_url($page_url);
		
		// Tenta logar acesso a esta página
		$this->_try_log_access();
		
		// Carrega conteúdo
		$this->show_page_url();
	}
	
	/**
	* show_page_url()
	* Toda URL, com exceção daquelas marcadas com método de exibição 'controller_exec' é mapeada
	* e passa por este método, que verifica a consistência do objeto atual. Caso esteja devidamente
	* preenchido, exibe o conteúdo da página.
	*
	* PÁGINAS MARCADAS COM O MÉTODO DE EXIBIÇÃO controller_exec não passam por aqui, são redirecionadas
	* automaticamente para a URL invocada.
	* @return void
	*/
	public function show_page_url()
	{
		// Página existe! :P
		if(!$this->_404)
		{
			// Calcula o conteúdo da página URL
			switch(strtolower($this->method_show))
			{
				// Verifica, caso o método seja de carregar arquivo, se o arquivo existe
				case 'load_file':
					// Diz que é para carregar página de conteúdo (usado na página master)
					$this->load_file_content = true;
					
					// Verifica se arquivo existe
					if(!file_exists($this->file_path_content))
						$this->error = true;
				break;
				
				// Para métodos de html visual e código html, cria um arquivo em tempo real, exibe-o e depois apaga
				case 'html_visual':
				case 'html_custom':
					// Coleta conteúdo do banco
					$html = (strtolower($this->method_show) == 'html_visual') ? $this->html_visual : $this->html_custom;
					
					// Seta nome do arquivo temporário
					$temp_file_path = 'application/temp/' . uniqid() . '.php';
					
					// Cria arquivo (gera erro caso não consiga)
					if(file_put_contents($temp_file_path, $html) === false)
						$this->error = true;
					
					// Simula arquivo como o leitor faz
					$this->file_path_content = $temp_file_path;
					
					// Diz que é para carregar página de conteúdo (usado na página master)
					$this->load_file_content = true;
				break;
				
				// Método de exibição 'avariado'
				default:
					$this->error = true;
				break;
			}
			
			// Verifica existência de erro
			if($this->error === true)
			{
				$this->template->load_page('_errors/error_page_url_load', array(), false, false);
			} else {
				// Verifica se é para carregar página master
				if($this->show_master_page)
					echo $this->template->load_page('master', array('html' => ''), true, false);
				else
					echo $this->load->file($this->file_path_content, true);
			}
			
			// Deleta arquivo temporário
			if(isset($temp_file_path) && file_exists($temp_file_path))
				@unlink($temp_file_path);
			
			// Interrompe execução da página (já mostrou o que tinha que mostrar :P )
			// exit;
		} else {
			$this->error->show_404();
		}
	}
	
	/**
	* show_page_url_preview()
	* Exibe uma página URL no modo preview.
	* @param int id_page_url_preview
	* @param string hash
	* @return void
	*/
	public function show_page_url_preview($id_page_url_preview = 0, $hash = '')
	{
		$pup = $this->db->get_where('cms_page_url_preview', array('id_page_url' => $id_page_url_preview, 'hash_access' => $hash));
		$pup = $pup->result_array();
		$pup = (isset($pup[0])) ? $pup[0] : array();
		if(count($pup) > 0)
		{
			$this->_load_page_url($pup);
			$this->show_page_url();
			
			// Remove conteúdo preview da tabela do banco de dados
			$this->db->delete('cms_page_url_preview', array('id_page_url' => $id_page_url_preview, 'hash_access' => $hash));
		}
	}
}
