<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe cms_page_url (Arquivo gerado com construtor de módulos)
* 
* Módule cms_page_url: Este módulo gerencia conteúdos de páginas URL cadastradas no website atual.
*
* @since		30/07/2013
* @location		controllers.cms_page_url
*
*/
class cms_page_url extends Acme_Base_Module {
	// Definição de atributos
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
		parent::__construct(__CLASS__);
		
		// Carrega libraries comumente utilizadas
		$this->load->library('cms_tag');
	}
	
	/**
	* index()
	* Método 'padrão' do controlador. É invocado automaticamente quando 
	* o action deste controlador não é informado na URL. Por padrão seu efeito
	* é exibir a tela de listagem de entrada do módulo.
	* @param int actual_page
	* @return void
	*/
	public function index($actual_page = 0)
	{
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
		
		// Seta o array de filtros em sessão para que quando alterar a pagina ainda dentro do modulo
		// e voltar para a pagina de listagem os filtros permaneçam como estavam anteriormente.
		$this->session->set_userdata('module_array_filters', array($this->controller . '_filters' => $filters));
		
		// Tabela de dados
		$table = $this->array_table->get_instance();
		// Resultset do sql de listagem do módulo (encaminha post que são os filtros)
		$table->set_data($this->cms_page_url_model->get_resultset_module($filters));
		$table->set_items_per_page(50000);
		
		// Adiciona colunas a tabela
		$table->add_column('<a href="' . URL_ROOT . '/cms_page_url/form_update_custom/{0}"><img src="' . URL_IMG . '/icon_update.png" title="' . lang('Editar Página URL') . '"/></a>');
		$table->add_column('<a href="' . URL_ROOT . '{1}" target="_blank"><img src="' . URL_IMG . '/icon_show_page_url.png" title="' . lang('Visualizar (abrir) Página URL') . '"/></a>');
		$table->add_column('<a href="' . URL_ROOT . '/cms_page_url/form/delete/{0}"><img src="' . URL_IMG . '/icon_delete.png" title="' . lang('Deletar Página URL') . '"/></a>');
		
		// Coluna de método de execução
		$table->add_column('<img src="' . URL_IMG . '/icon_{METHOD_SHOW}.png" title="{METHOD_SHOW_DESCRIPTION}" />', false);
		$table->add_column('<img src="' . URL_IMG . '/icon_{DTT_INATIVE_BULLET}.png" title="{DTT_INATIVE_BULLET_DESCRIPTION}" />', false);
		$table->add_column('<img src="' . URL_IMG . '/icon_{show_master_page_bullet}.png" title="{show_master_page_bullet_description}" />', false);
		
		// Seta as colunas que quer
		$table->set_columns(array('#', 'url', 'title'));
		
		// Html da tabela
		$args['module_table'] = $table->get_html();
		
		// Monta form de filtros, caso exista
		$args['module_form_filter'] = $this->template->load_form_filter($this->filters, $filters, URL_ROOT . '/' . $this->controller);
		
		// Carrega camada de visualização
		$this->template->load_page('cms_page_url/index', $args);
	}
	
	/**
	* form_insert_custom()
	* Formulário de inserção de página URL.
	* @return void
	*/
	public function form_insert_custom()
	{
		// Valida permissão de inserção
		$this->validate_permission('INSERT');
		
		// Verifica erros e computa o form_data, apenas caso ocorra erro de upload
		// para os outros casos o form_data vai estar sempre vazio
		$args['form_data'] = $this->session->userdata('cms_page_url_form_insert_post');
		$args['error_uploading'] = $this->session->userdata('cms_page_url_form_insert_error_upload');
		
		// Apaga erro e dados do formulário
		$this->session->unset_userdata('cms_page_url_form_insert_post');
		$this->session->unset_userdata('cms_page_url_form_insert_error_upload');
		
		// Carrega models necessários
		$this->load->model('cms_page_url_category_model');
		
		// Carrega categorias para combo
		$args['options_categories'] = $this->form->build_array_html_options($this->cms_page_url_category_model->get_options_categories(), get_value($args['form_data'], 'id_page_url_category'));
		
		// Carrega outros conteudos (URLS)
		$args['options_urls'] = $this->form->build_array_html_options($this->cms_page_url_model->get_options_page_url(), get_value($args['form_data'], 'id_page_url_parent'));
		
		// Carrega view
		$this->template->load_page('cms_page_url/form_insert_custom', $args);
	}
	
	/**
	* form_insert_custom_process()
	* Processa formulário customizado de inserção de pagina url.
	* @return void
	*/
	public function form_insert_custom_process()
	{
		// Valida a permissão
		$this->validate_permission('INSERT');
		
		// Criação de arquivo de conteúdo
		switch(strtolower($this->input->post('method_show')))
		{
			case 'load_file':
				// Array de configurações do upload
				$config['overwrite'] = true;
				$config['upload_path'] = PATH_UPLOAD . '/website_pages/';
				$config['allowed_types'] = 'html|php|xhtml|htm';
				$config['remove_spaces'] = true;
				$config['file_name'] = preg_replace('/[\/]*?/i', '_', $this->input->post('url')) . '_' . uniqid() . '.php';
				
				// Carrega biblioteca de upload
				$this->load->library('upload', $config);
				
				// Faz o upload, efetivamente
				if(!$this->upload->do_upload('file_path_content'))
				{
					// caso ocorra algum erro ao carregar a imagem.
					$this->session->set_userdata('cms_page_url_form_insert_post', $this->input->post());
					$this->session->set_userdata('cms_page_url_form_insert_error_upload', $this->upload->display_errors('<h6>&bull;&nbsp;', '</h6>'));
					redirect('cms_page_url/form_insert_custom');
					exit;
				}
				
				// Até aqui imagem uploadeada com sucesso, seta o nome do arquivo para path inteiro
				$file_path_content = '<acme eval="PATH_UPLOAD"/>/website_pages/' . $config['file_name'];
			break;
			
			default:
			break;
		}
		
		// Inicializa transação
		$this->db->trans_start();
		
		// Seta o file_path_content caso esteja informado
		if(isset($file_path_content))
			$this->db->set('file_path_content', $file_path_content);
		
		// Ajusta array de inserção
		foreach($this->input->post() as $column => $value)
		{
			if($value == '')
			{
				$value = 'NULL';
				$escape = false;
			} else {
				$escape = true;
			}
			
			if($column != 'url_text')
				$this->db->set($column, $value, $escape);
		}
		
		// Insere pagina url
		$this->db->insert('cms_page_url');
		
		// Loga inserção de página URL
		$this->log->db_log(lang('Inserção de Página URL'), 'insert', 'cms_page_url', $this->input->post());
		
		// Completa transação
		$this->db->trans_complete();
		
		// Redirect para entrada do módulo
		redirect('cms_page_url');
	}
	
	/**
	* form_update_custom()
	* Formulário de edição de página URL.
	* @param int id_page_url
	* @return void
	*/
	public function form_update_custom($id_page_url = 0)
	{
		// Valida permissão de inserção
		$this->validate_permission('UPDATE');
		
		// Coleta dados da URL
		$page_url = $this->cms_page_url_model->select($id_page_url);
		$page_url = isset($page_url[0]) ? $page_url[0] : array();
		
		// Verifica erros e computa o form_data, apenas caso ocorra erro de upload
		// para os outros casos o form_data vai estar sempre vazio
		$args['form_data'] = ($this->session->userdata('cms_page_url_form_update_post') == '') ? $page_url : $this->session->userdata('cms_page_url_form_update_post');
		$args['error_uploading'] = $this->session->userdata('cms_page_url_form_update_error_upload');
		
		// Apaga erro e dados do formulário
		$this->session->unset_userdata('cms_page_url_form_update_post');
		$this->session->unset_userdata('cms_page_url_form_update_error_upload');
		
		// Carrega models necessários
		$this->load->model('cms_page_url_category_model');
		
		// Carrega categorias para combo
		$args['options_categories'] = $this->form->build_array_html_options($this->cms_page_url_category_model->get_options_categories(), get_value($args['form_data'], 'id_page_url_category'));
		
		// Carrega outros conteudos (URLS)
		$args['options_urls'] = $this->form->build_array_html_options($this->cms_page_url_model->get_options_page_url(), get_value($args['form_data'], 'id_page_url_parent'));
		
		// Carrega view
		$this->template->load_page('cms_page_url/form_update_custom', $args);
	}
	
	/**
	* form_update_custom_process()
	* Processa formulário customizado de edição de pagina url.
	* @return void
	*/
	public function form_update_custom_process()
	{
		// Valida a permissão
		$this->validate_permission('UPDATE');
		
		// Dados da pagina
		$page_url = $this->cms_page_url_model->select($this->input->post('id_page_url'));
		$page_url = isset($page_url[0]) ? $page_url[0] : array();
		
		// Criação de arquivo de conteúdo
		switch(strtolower($this->input->post('method_show')))
		{
			case 'load_file':
				// Só faz upload de imagem caso ela exista ;)
				if($_FILES['file_path_content']['name'] != '')
				{
					// Array de configurações do upload
					$config['overwrite'] = true;
					$config['upload_path'] = PATH_UPLOAD . '/website_pages/';
					$config['allowed_types'] = 'html|php|xhtml|htm';
					$config['remove_spaces'] = true;
					$config['file_name'] = trim(str_replace('/', '_', $this->input->post('url')) . '_' . uniqid() . '.php', '_');
					
					// Carrega biblioteca de upload
					$this->load->library('upload', $config);
					
					// Faz o upload, efetivamente
					if(!$this->upload->do_upload('file_path_content'))
					{
						// caso ocorra algum erro ao carregar a imagem.
						$this->session->set_userdata('cms_page_url_form_update_post', $this->input->post());
						$this->session->set_userdata('cms_page_url_form_update_error_upload', $this->upload->display_errors('<h6>&bull;&nbsp;', '</h6>'));
						redirect('cms_page_url/form_update_custom/' . $this->input->post('id_page_url'));
						exit;
					}
					
					// Até aqui imagem uploadeada com sucesso, seta o nome do arquivo para path inteiro
					$file_path_content = '<acme eval="PATH_UPLOAD"/>/website_pages/' . $config['file_name'];
				}
			break;
			
			default:
			break;
		}
		
		// Inicializa transação
		$this->db->trans_start();
		
		// Seta o file_path caso esteja informado e remove arquivo anterior
		if(isset($file_path_content))
		{
			$this->db->set('file_path_content', $file_path_content);
			@unlink(eval_replace(get_value($page_url, 'file_path_content')));
		}
		
		// Ajusta array de edição
		foreach($this->input->post() as $column => $value)
		{
			if($value == '')
			{
				$value = 'NULL';
				$escape = false;
			} else {
				$escape = ($column == 'dtt_inative') ? false : true;
			}
			
			if($column != 'id_page_url' && $column != 'url_text' && $column != 'file_path_content')
				$this->db->set($column, $value, $escape);
		}
		
		// Edita pagina url
		$this->db->where('id_page_url', $this->input->post('id_page_url'));
		$this->db->update('cms_page_url');
		
		// Loga edição de página URL
		$this->log->db_log(lang('Edição de Página URL'), 'update', 'cms_page_url', $this->input->post());
		
		// Completa transação
		$this->db->trans_complete();
		
		// Redirect para entrada do módulo
		redirect('cms_page_url');
	}
	
	/**
	* download_page_url_file()
	* Faz download do arquivo atual da pagina html.
	* @param int id_page_url
	* @return void
	*/
	public function download_page_url_file($id_page_url = 0)
	{
		// Testa permissão de entrada do modulo
		$this->validate_permission('ENTER');
		
		// Dados do relatório
		$page_url = $this->cms_page_url_model->select($id_page_url);
		$page_url = (isset($page_url[0])) ? $page_url[0] : array();
		
		// Exporta arquivo
		$this->load->helper('download_helper');
		force_download(basename(get_value($page_url, 'file_path_content')), file_get_contents(eval_replace(get_value($page_url, 'file_path_content'))));
	}
	
	/**
	* ajax_build_preview_page_url()
	* Monta uma página URL de preview via ajax, com base nos dados encaminhados por POST. Apenas
	* para métodos de inserção de conteúdo HTML Visual e HTML Customizado (código).
	* @return int id_page_url_preview
	*/
	public function ajax_build_preview_page_url()
	{
		// Testa permissão de entrada do modulo
		$this->validate_permission('ENTER');
		
		// Gera e seta hash
		$hash = md5(uniqid());
		$this->db->set('hash_access', $hash);
		
		// Ajusta array de inserção
		foreach($this->input->post() as $column => $value)
		{
			if($value == '')
			{
				$value = 'NULL';
				$escape = false;
			} else {
				$escape = ($column == 'dtt_inative') ? false : true;
			}
			
			$this->db->set($column, $value, $escape);
		}
		
		// Insere no banco de dados
		$this->db->insert('cms_page_url_preview');
		
		// Retorno em json
		$json['id_page_url_preview'] = $this->db->insert_id();
		$json['hash'] = $hash;
		echo json_encode($json);
	}
	
	/**
	* preview_page_url()
	* Exibe uma página preview, com base nos dados inseridos na tabela de preview, do banco de dados.
	* @param int id_page_url_preview
	* @return void
	*/
	public function preview_page_url($id_page_url_preview = 0)
	{
		// Testa permissão de entrada do modulo
		$this->validate_permission('ENTER');
		
		// Coleta dados da página de preview
		$page_url = $this->cms_page_url_model->get_page_url_preview($id_page_url_preview);
		
		// Carrega preview
		$this->_load_page_url_preview(get_value($page_url, 'id_page_url'));
	}
	
	/**
	* _load_page_url_preview()
	* Carrega o conteúdo de uma página URL no modo preview, com base no id encaminhado.
	* @param int id_page_url_preview
	* @return void
	*/
	private function _load_page_url_preview($id_page_url_preview = 0)
	{
		echo 'pararat';
		//die;
		$this->load->file('application/controllers/cms/cms_website.php');
		$wbs = new cms_website();
		$wbs->show_page_url_preview();
		/*
		// Busca dados da URL (caso não existam dados, página 404)
		$page_url = $this->cms_page_url_model->get_page_url_preview($id_page_url_preview);
		
		// Prossegue com exibição
		if(count($page_url) > 0)
		{
			// Carrega bibliotecas necessárias
			$this->load->library('cms_tag');
			$this->load->library('cms_template');
			
			
			// Carrega helpers custom para website
			$this->load->helper('cms_template_helper');
			
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
			$this->app_config->URL_CSS = URL_CSS_WEBSITE;
			$this->app_config->URL_IMG = URL_IMG_WEBSITE;
			$this->app_config->URL_HTML_COMPONENTS = URL_HTML_COMPONENTS_WEBSITE;
			$this->app_config->PATH_HTML_COMPONENTS = PATH_HTML_COMPONENTS_WEBSITE;
		
			// carregamento de arquivo é o unico que possui tratamento diferenciado
			$temp_file_path = '';
			$page_content = '';
			$page_load_file = false;
			$page_file_path = '';
			$page_title = get_value($page_url, 'title');
			
			// Calcula o conteúdo da página URL
			switch(strtolower(get_value($page_url, 'method_show')))
			{
				// Caso seja um arquivo, faz a inclusão deste ao invés de ler seu conteúdo
				case 'load_file':
				case 'html_visual':
				case 'html_custom':
					// O preview carrega o conteúdo cadastrado em um arquivo 
					// temporário, exibe-o e deleta-o posteriormente
					$page_load_file = true;
					
					// Coleta conteúdo do banco
					$html = (strtolower(get_value($page_url, 'method_show')) == 'html_visual') ? get_value($page_url, 'html_visual') : get_value($page_url, 'html_custom');
					
					// Seta nome do arquivo temporário
					$temp_file_path = 'application/temp/' . uniqid() . '.php';
					
					// Cria arquivo
					file_put_contents($temp_file_path, $html);
					
					// Simula arquivo como o leitor faz
					$page_file_path = $temp_file_path;
				break;
				
				case 'controller_exec':
					$args = explode('/', get_value($page_url, 'controller_action'));
					eval('$object = new ' . $args[0] . '($args);');
					$page_content = $object->{$args[1]}();
				break;
			}
			
			// echo 'pararat';
			// die;
			// print_r($page_url);
			// $page_content);
			// die;
			
			// Verifica se é para carregar página master
			if(get_value($page_url, 'show_master_page') == 'N')
			{
				if($page_load_file) 
				{
					echo $this->load->file($page_file_path, true);
				} else {
					echo $page_content;
				}
			} else {
				// Variáveis para view
				$args_view['page_title'] = $page_title;
				$args_view['page_content'] = $page_content;
				$args_view['page_load_file'] = $page_load_file;
				$args_view['page_file_path'] = $page_file_path;
				
				// Carrega pagina master com conteúdo dentro
				echo $this->template->load_page('master', $args_view, true, false);
			}
			
			// Deleta arquivo temporário
			@unlink($temp_file_path);
		} else {
			$this->error->show_404();
		}
		*/
	}
}
