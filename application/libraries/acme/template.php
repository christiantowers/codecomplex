<?php
/**
*
* Classe Template
*
* Esta biblioteca gerencia funções relacionadas ao template selecionado para a aplicação.
* 
* @since		10/09/2012
* @location		acme.libraries.template
*
*/
class Template {
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
	* load_page()
	* Este método carrega uma página para o template setado atualmente. O segundo parametro sao as 
	* variaveis que estarão disponíveis no template. O terceiro parametro diz se o retorno tem de ser
	* somente html ou não (true/false). O quarto parametro diz se a página deve ser carregada com a 
	* master page ou não.
	* @param string page
	* @param array vars
	* @param boolean return_html
	* @param boolean load_master_page
	* @return mixed
	*/
	public function load_page($page = '', $arr_vars = array(), $return_html = false, $load_master_page = true)
	{
		if($load_master_page)
		{
			$html = $this->CI->load->view($this->CI->app_config->TEMPLATE . '/' . $page, $arr_vars, true);
			$html = $this->CI->load->view($this->CI->app_config->TEMPLATE . '/' . 'master', array('html' => $html), $return_html);
			if($return_html){ return $html; }
		} else {
			$html = $this->CI->load->view($this->CI->app_config->TEMPLATE . '/' . $page, $arr_vars, $return_html);
			if($return_html){ return $html; }
		}
	}
	
	/**
	* load_js_file()
	* Carrega um arquivo js, retornando tag script. O nome do arquivo encaminhado como parametro
	* não deve conter a extensão do arquivo.
	* @param string file
	* @return string html
	*/
	public function load_js_file($file = '')
	{
		return '<script type="text/javascript" language="javascript" src="' . $this->CI->app_config->URL_JS . '/' . $file . '"></script>' . "\n";
	}
	
	/**
	* load_css_file()
	* Carrega um arquivo css, retornando tag <link...>. O nome do arquivo encaminhado como parametro
	* pode não conter a extensão do arquivo.
	* @param string file
	* @return string html
	*/
	public function load_css_file($file = '')
	{
		return '<link type="text/css" rel="stylesheet" href="' . $this->CI->app_config->URL_CSS . '/' . $file . '" />' . "\n";
	}
	
	/**
	* load_array_config_js_files()
	* Carrega o html de scripts js setados no arquivo geral de configuração.
	* @return string html
	*/
	public function load_array_config_js_files()
	{
		$html = '';
		$this->CI->load->library('acme/app_config');
		$scripts = $this->CI->app_config->JS_FILES;
		foreach($scripts as $javascript_file)
		{
			$html .= '<script type="text/javascript" language="javascript" src="' . $this->CI->app_config->URL_JS . '/' . $javascript_file . '"></script>' . "\n";
		}
		return $html;
	}
	
	/**
	* load_array_config_css_files()
	* Carrega o html de arquivos css setados no arquivo geral de configuração.
	* @return string html
	*/
	public function load_array_config_css_files()
	{
		$html = '';
		$css_files = $this->CI->app_config->CSS_FILES;
		foreach($css_files as $file)
		{
			$html .= '<link type="text/css" rel="stylesheet" href="' . $this->CI->app_config->URL_CSS . '/' . $file . '" />' . "\n";
		}
		return $html;
	}
	
	/**
	* load_menu()
	* Carrega componente html chamado menu, localizado no diretorio html_components/menu/menu.php
	* do template atual. Encaminha para a função menu um array de menus, a ser construído da maneira
	* que for necessária.
	* @return string html_menu
	*/
	public function load_menu()
	{
		// Retorna o html do componente menu
		return $this->load_html_component('menu', array($this->get_array_menus()));
	}
	
	/**
	* load_logo_area()
	* Carrega componente html que é a area de logotipo do sistema. Este componente html recebe
	* como parametro a url que deve ser direcionada em caso de clique na imagem e a imagem que é
	* o logo, propriamente dito.
	* @return string html_logo
	*/
	public function load_logo_area()
	{
		// A URL que é encaminhada para a area do logo é o url padrão do usuario
		$url = $this->CI->session->userdata('url_default');
		
		// Coleta do banco de dados qual é a configuração de logotipo da aplicação
		$img = eval_replace($this->CI->app_config->get_app_config_db('app_logo'));
		
		// Retorna o html do componente logo
		return $this->load_html_component('logo_area', array($img, $url));
	}
	
	/**
	* load_user_info()
	* Carrega componente html de informacoes do usuario, como imagem, login, email, grupo e lista de
	* links favoritos.
	* @return string user_info
	*/
	public function load_user_info()
	{
		// Carrega bibliotecas necessárias para esta façanha
		$this->CI->load->library('session');
		$this->CI->load->model('acme/libraries/template_model');
		
		// Coleta informações do usuário, setadas na sessão
		$args['id_user'] = $this->CI->session->userdata('id_user');
		$args['login'] = $this->CI->session->userdata('login');
		$args['email'] = $this->CI->session->userdata('email');
		$args['user_name'] = $this->CI->session->userdata('user_name');
		$args['user_group'] = $this->CI->session->userdata('user_group');
		$args['user_img'] = $this->CI->session->userdata('user_img');
		$args['bookmarks'] = $this->CI->template_model->get_user_bookmarks($this->CI->session->userdata('id_user'));
		
		// Retorna o html do componente da area de info do usuario
		return $this->load_html_component('user_info', $args);
	}
	
	/**
	* load_form_filter()
	* Carrega componente html formulário de filtros da listagem do módulo. Recebe como parametro
	* um array de filtros (dados) vindos do banco de dados, mais especificamente da tabela acm_module_form_field
	* e o action do formulario.
	* @param array filters
	* @param array values
	* @param string action
	* @return string html_form_filters
	*/
	public function load_form_filter($filters = array(), $values = array(), $action = '')
	{
		// Transforma o array de dados de inputs do banco para string de inputs, textareas, etc.
		$arr_fields = $this->CI->form->build_array_html_form_fields($filters, $values, true);
		
		// Carrega formulário de filtros
		return $this->load_html_component('form_filter', array($arr_fields, $action));
	}
	
	/**
	* load_html_component()
	* Carrega um componente html de nome encaminhado como parametro. Espera-se que
	* exista um diretorio, arquivo e função de mesmo nome do que encaminhado. O segundo
	* parametro é um array de parametros que serão encaminhados à função.
	* @param string component
	* @param array config
	* @return string html_menu
	*/
	public function load_html_component($component = '', $params = array())
	{
		// Tenta incluir o arquivo de componente
		include_once('application/views/' . $this->CI->app_config->PATH_HTML_COMPONENTS . '/' . $component . '/' . $component . '.php');
		
		// Realiza chamada da função
		return call_user_func_array($component, $params);	
	}
	
	/**
	* menus_to_tree()
	* Recebe um conjunto de dados de array organizados por ordem de parent e os organiza
	* em formato de árvore (utilizado para construção de menus).
	* @param array menus
	* @return array menus_tree
	*/
	public function menus_to_tree(&$menus) 
	{
		$map = array(
			0 => array('childen' => array())
		);

		foreach ($menus as &$menu) {
			$menu['children'] = array();
			$map[$menu['id_menu']] = &$menu;
		}

		foreach ($menus as &$menu) {
			$map[$menu['id_menu_parent']]['children'][] = &$menu;
		}

		return $map[0]['children'];
	}
	
	/**
	* message()
	* Retorna o componente html mensagem, que é montado conforme parametros encaminhados.
	* @param string tipo
	* @param string titulo
	* @param string descricao
	* @param boolean close
	* @param string style
	* @return string html_message
	*/
	public function message($type = 'info', $title = '', $description = '', $close = false, $style = '')
	{
		// Retorna o html do componente mensagem
		return $this->load_html_component('_message', array($type, $title, $description, $close, $style));
	}
	
	/**
	* start_box()
	* Retorna o componente html do início de um box genérico, uma caixa com um estilo padrão.
	* @param string titulo
	* @param string url_img
	* @param string style
	* @return string html_box
	*/
	public function start_box($titulo = '', $url_img = '', $style = '')
	{
		// Retorna o html do componente mensagem
		return $this->load_html_component('_start_box', array($titulo, $url_img, $style));
	}
	
	/**
	* end_box()
	* Retorna o componente html de finalizacao da caixa genérica inicializada por start_box().
	* @return string html_box
	*/
	public function end_box()
	{
		// Retorna o html do componente mensagem
		return $this->load_html_component('_end_box');
	}
	
	/**
	* get_array_menus()
	* Retorna os menus cadastrados no sistema em formato de array/árvore.
	* @return array menus
	*/
	public function get_array_menus()
	{
		// Carrega model que fará leitura do menu no banco de dados
		$this->CI->load->model('acme/libraries/template_model');
		
		// Faz leitura do menu conforme o grupo de usuário atual
		// Esta leitura é recursiva, para cada menu o model busca
		// possíveis menus-filhos.
		$menus = $this->CI->template_model->get_menus($this->CI->session->userdata('user_group'));
		$menus = (count($menus) > 0) ? $this->menus_to_tree($menus) : array();
		
		// Retorna menus em formato de array
		return $menus;
	}
}