<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Acme_Engine
* 
* Classe abstracao para motor de geração de códigos, instalação e atualização de sistema. Esta 
* classe contempla os métodos de implementação dos seguintes módulos:
*
* - Acme_Installer		(instalador do sistema)
* - Acme_Maker			(criador de módulos internos do sistema)
* - Acme_Updater		(atualizador do sistema, através de pacotes de atualização)
*
* Para maiores detalhes, analise o conjunto de funções que cada módulo possui através do bloco
* de comentários disponível no decorrer da classe.
*
* @since		15/10/2012
* @location		acme.core.acme_engine
*
*/
abstract class Acme_Engine extends Acme_Core {
	// Definição de atributos
	public $file_method_process = 'xml';
	public $file_module_extension = 'xml';
	public $path_module_packages = 'application/uploads/acme/packages_update';
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
		parent::__construct();
	}

	// ------------------------------------------------------------------------------------------
	// CONJUNTO DE FUNÇÕES DO MOTOR DE ACME ENGINE UTILIZADAS NO MÓDULO DE INSTALAÇÃO.
	// ------------------------------------------------------------------------------------------
	// application/controllers/acme/acme_installer => Módulo responsável pela instalação do
	// sistema ACME de forma a torná-lo um novo sistema.
	// O conjunto de funções abaixo contemplam validações dos requisitos do ambiente atual,
	// bibliotecas ativas e conjunto de configurações de banco de dados. São estas funções:
	// 
	// $this->_check_installer_permissions()
	// Verifica as permissões necessárias para o instalador funcionar.
	// 
	// $this->_analyze_system_requirements()
	// Verifica os requisitos de sistema necessários para que o ACME Engine possa ser instalado.
	// 
	// $this->_analyze_install()
	// Analisa os dados do post do formulário de instalação (verifica a integridade, etc).
	// 
	// $this->_install_acme_engine()
	// Realiza a instalação do ACME Engine com base nas informações de post encaminhadas e já
	// validadas anteriormete.
	// ------------------------------------------------------------------------------------------
	/**
	* _check_installer_permissions()
	* Checa as permissões necessárias para o installer funcionar. Retorna true caso permissions ok 
	* ou false, caso falta de permissao.
	* @return boolean status
	*/
	public function _check_installer_permissions()
	{
		$return = true;
		if(is_writable('application/controllers') === false && is_readable('application/controllers') === false)
		{
			$return = false;
		} else if(is_writable('application/core') === false && is_readable('application/core') === false) {
			$return = false;
		} else if(is_writable('application/config') === false && is_readable('application/config') === false) {
			$return = false;
		} else if(is_writable('application/config/acme') === false && is_readable('application/config/acme') === false) {
			$return = false;
		} else if(file_exists('application/core/acme/engine_files/installer_dump_database.sql') === false || 
				  file_exists('application/core/acme/engine_files/installer_template_acme_installer.php') === false ||
				  file_exists('application/core/acme/engine_files/installer_template_application_settings.php') === false) {
			$return = false;
		}
		return $return;
	}
	
	/**
	* _analyze_system_requirements()
	* Verifica as configurações do sistema com base no necessário para que o sistema ACME Engine
	* possa ser instalado (configurações de php, banco de dados e extensões). Retorna true em caso
	* de sucesso, ou um array associativo com mensagens de erro. Parametro array com configuracoes
	* de banco de dados deve ser encaminhado.
	* @param array db_params
	* @return mixed status/array
	*/
	public function _analyze_system_requirements($db_params = array())
	{
		// Mensagem de retorno
		$return = array();
		
		// PHP 5.3.5 ou superior
		if(!is_php('5.3.5'))
		{
			$return['php_version'] = lang('PHP 5.3.5 ou superior');
		}
		
		// MySQL 5.0 ou superior
		$output = @shell_exec('mysql -V'); 
		@preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version); 
		$version = isset($version[0]) ? abs($version[0]) : 0;
		if($version < 5)
		{
			$return['mysql_version'] = lang('MySQL 5.0 ou superior');
		}
		
		// Extensão do mysql no php
		if(!extension_loaded('mysql'))
		{
			$return['php_mysql_extension'] = lang('Extensão <u>mysql</u> ativada no PHP');
		}
		
		// Testa conexão mysql (todos os campos devem estar preenchidos)
		if(get_value($db_params, 'hostname') != '' && get_value($db_params, 'username') != '' && get_value($db_params, 'port') != '' && get_value($db_params, 'database') != '')
		{
			// Abre link de conexão com banco
			$link = @mysqli_connect($db_params['hostname'], $db_params['username'], $db_params['password'], null, $db_params['port']);
			
			// Link com problemas
			if(!$link) {
				$return['mysql_connection'] = mysqli_connect_error();
			} else {
				// Link sem problemas, testa também permissões de usuário
				@mysqli_select_db($link, 'mysql');
				$result = @mysqli_query($link, "SELECT user, select_priv, insert_priv, create_priv FROM mysql.user WHERE host = '" . $db_params['hostname'] . "' AND user = '" . $db_params['username'] . "'");
				$result = @mysqli_fetch_assoc($result);
				
				// Testa privilégios do usuário
				if(strtolower(get_value($result, 'select_priv')) != 'y')
				{
					$return['mysql_user_permission_select'] = lang('Acesso ao banco de dados: usuário sem permissão para realização de consultas - SELECT');
				} elseif(strtolower(get_value($result, 'insert_priv')) != 'y') {
					$return['mysql_user_permission_insert'] = lang('Acesso ao banco de dados: usuário sem permissão para realização de inserções - INSERT');
				} elseif(strtolower(get_value($result, 'create_priv')) != 'y') {
					$return['mysql_user_permission_create'] = lang('Acesso ao banco de dados: usuário sem permissão para criação de tabelas e schemas - CREATE');
				} else {
					// Testa se existe banco de dados com o nome informado
					$result = @mysqli_query($link, "SELECT count(*) AS COUNT_CREATE FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . $db_params['database'] . "'");
					$result = @mysqli_fetch_assoc($result);
					
					if(get_value($result, 'COUNT_CREATE') > 0)
					{
						$return['mysql_database_exists'] = lang('Acesso ao banco de dados: schema') . ' <u>' . $db_params['database'] . '</u> ' . lang('já existe');
					}
				}
				
				// Fecha conexão
				@mysqli_close($link);
			}
		} else {
			$return['mysql_connection_information'] = lang('Informações de conexão não encaminhadas');
		}
		
		// Corrige retorno (caso array não tenha sido preenchido 
		// em nenhum lugar, então não ocorreram erros)
		if(count($return) <= 0)
		{
			unset($return);
			$return = true;
		}
		
		// Retorno ajustado
		return $return;
	}
	
	/**
	* analyze_install()
	* Verifica as informações encaminhadas por post de um formulário de instalação do sistema. Retorna
	* true em caso de sucesso ou um array de mensagens de erro.
	* @param array settings (post)
	* @return mixed status/array
	*/
	public function _analyze_install($settings = array())
	{
		$return = array();
		
		// -------------------------------------------------------------
		// VALIDA DIRETÓRIOS (na versão inicial, paths serão engessados)
		// -------------------------------------------------------------
		/*
		if(get_value($settings, 'dir_img') == '')
		{
			$return['dir_img'] = lang('Diretório de imagens não informado');
		} else if(!$this->validation->is_letter_number_chr_specials(get_value($settings, 'dir_js'))) {
			$return['dir_img'] = lang('Diretório de imagens deve conter apenas letras, números, pontos ou underscores');
		}
		if(get_value($settings, 'dir_css') == '')
		{
			$return['dir_css'] = lang('Diretório de estilos (css) não informado');
		} else if(!$this->validation->is_letter_number_chr_specials(get_value($settings, 'dir_js'))) {
			$return['dir_css'] = lang('Diretório de estilos (css) deve conter apenas letras, números, pontos ou underscores');
		}
		if(get_value($settings, 'dir_js') == '')
		{
			$return['dir_js'] = lang('Diretório de scripts não informado');
		} else if(!$this->validation->is_letter_number_chr_specials(get_value($settings, 'dir_js'))) {
			$return['dir_js'] = lang('Diretório de scripts deve conter apenas letras, números, pontos ou underscores');
		}
		*/
		
		
		// ------------------------------------
		// VALIDA INFORMAÇÕES DA NOVA APLICAÇÃO
		// ------------------------------------
		if(get_value($settings, 'info_app_name') == '')
		{
			$return['info_app_name'] = lang('Nome da nova aplicação não informado');
		} else if(!$this->validation->is_letter_number_chr_specials(get_value($settings, 'info_app_name'))) {
			$return['info_app_name'] = lang('Nome da nova aplicação deve conter apenas letras, números, pontos ou underscores');
		}

		if(get_value($settings, 'info_app_title') == '')
		{
			$return['info_app_name'] = lang('Título padrão das páginas da nova aplicação não informado');
		} 
		
		// Valida e upload de logo (se der certo sempre vai substituir o logo atual)
		$config['overwrite'] = true;
		$config['file_name'] = 'logo';
		$config['upload_path'] = PATH_INCLUDE . '/img/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= '2000';
		$config['max_width']  = '180';
		$config['max_height']  = '180';
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload('info_app_logo'))
		{
			$return['info_app_logo'] = $this->upload->display_errors('<span>','</span>');
		}
		
		// DEBUG:
		// $upload_data = $this->upload->data();
		// print_r($upload_data);
		
		if(isset($_FILES['info_app_favicon']['name']))
		{
			if($_FILES['info_app_favicon']['name'] != '')
			{
				unset($config);
				$config['overwrite'] = true;
				$config['file_name'] = '_favicon';
				$config['upload_path'] = PATH_INCLUDE . '/img/';
				$config['allowed_types'] = 'ico';
				$config['max_size']	= '500';
				$config['max_width']  = '16';
				$config['max_height']  = '16';
				$this->upload->initialize($config);
				if(!$this->upload->do_upload('info_app_favicon'))
				{
					$upload_data = $this->upload->data();
					$return['info_app_favicon'] = $this->upload->display_errors('<span>','</span>');
					
					//DEBUG
					// print_r($upload_data);
					// print_r($this->upload);
				}
			}
		} 
		
		
		// ------------------------------------
		// VALIDA INFORMAÇÕES DO USUARIO-MESTRE
		// ------------------------------------
		if(get_value($settings, 'usr_name') == '')
		{
			$return['usr_name'] = lang('Nome do usuário não informado');
		}
		
