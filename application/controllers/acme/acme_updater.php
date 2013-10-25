<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe Acme_Updater
* 
* Classe que faz abstração do módulo de atualizações do sistema. Um pacote de atualização é composto
* pela seguinte estrutura:
* Um arquivo zip contendo:
* + 1 arquivo chamado hash.md5. Este arquivo contém a string hash md5 do arquivo package.zip. Os
*   valores são testados para garantir a integridade do pacote.
* + 1 arquivo chamado package.zip. Este pacote contém em seu interior:
*   - 1 arquivo chamado install.xml. Este arquivo contém a definição da instalação.
*   - pastas utilizada pelo install.xml
*
* Exemplo de arquivo install.xml:
* <?xml version="1.0" encoding="UTF-8"?>
* <package>
* 	 <package-version value="1.0.3"/>
* 	 <package-name value="acme-update-1.0.3" />
* 	 <package-version-father value="" />
* 	 <package-dtt-available value="2013-04-07" />
* 	 <package-description>Este pacote contém atualizações para os módulos ABC, XYZ.</package-description>
* 	 <package-dependencies>
*       <version="1.0.2" />
*       <version="1.0.1" />
* 	 </package-dependencies>
* 	 <package-actions>
* 	 	<rename from="application/controllers/example.php" to="application/controllers/example2.php" order="1" />
* 	 	<run-sql-file value="sql/create_table.sql" order="2" />
* 	 	<run-sql-file value="sql/create_table2.sql" order="3" />
* 	 	<rmdir value="application/nonsense2" order="4" />
* 	 	<copy-replace-file from="files/application/controllers/acme/acme_module_manager.php" to="application/controllers/acme/acme_module_manager.php" order="5" />
* 	 	<rmdir value="application/nonsense" order="6" />
* 	 	<copy-replace-file from="files/application/controllers/acme/acme_module_manager2.php" to="application/controllers/acme/acme_module_manager2.php" order="7" />
* 	 	<mkdir value="application/nonsense" order="8" />
* 	 	<run-sql-file value="sql/create_table4.sql" order="9" />
* 	 	<unlink value="application/models/example_model.php" order="10" />
* 	 </package-actions>
* </package>
*
* @since		15/07/2013
* @location		acme.controllers.acme_updater
*
*/
class Acme_Updater extends Acme_Engine {
	// Definição de atributos
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
		parent::__construct(__CLASS__);
		
		// Valida acesso (Sessão)
		$this->access->validate_session();
		
		// Carrega model necessário
		$this->load->model('acme/acme_updater_model');
		
		// Verificações básicas (paths + extensão zip)
		if($this->_check_updater_permissions() !== true)
		{
			$this->error->show_exception_page(lang('Não é possível abrir o módulo de atualizações. Verifique as possíveis causas:<br /><br />
			&bull;&nbsp;Diretório <strong>' . $this->path_module_packages . '</strong> sem permissões de leitura e/ou escrita<br />
			&bull;&nbsp;Arquivo <strong>application/core/acme/engine_files/updater_template_acme_version.php</strong> faltando<br />'));
		} else if ($this->_check_updater_requirements() !== true) {
			$msg = '';
			foreach($this->_check_updater_requirements() as $error)
			{
				$msg .= '<br /><h6>&bull;&nbsp;' . $error. '</h6>';
			}
			$this->error->show_exception_page(lang('Não é possível abrir o módulo de atualizações. Os seguintes problemas foram detectados:') . $msg, URL_ROOT . '/acme_updater');
		}
	}
	
	/**
	* index()
	* Método 'padrão' do controlador. É invocado automaticamente quando 
	* o action deste controlador não é informado na URL. Por padrão seu efeito
	* é exibir a tela de listagem de entrada do módulo.
	* @param int actual_page
	* @return void
	*/
	public function index()
	{
		// Valida a permissão de entrada
		$this->access->validate_permission('acme_updater', 'ENTER');
		
		// Conjunto de dados de pacotes de atualização
		$args['packages'] = $this->acme_updater_model->get_list_module();
		$args['count_packages'] = count($args['packages']);
		
		// Localiza pacotes instalados
		$obj_table = $this->array_table->get_instance();
		$obj_table->add_column('<a href="' . URL_ROOT . '/acme_updater/package_details/{0}" title="' . lang('Detalhes do Pacote') . '"><img src="' . URL_IMG . '/icon_view.png" /></a>');
		$obj_table->add_column('<a href="javascript:void(0);" onclick="download_file(\'' . URL_ROOT . '/acme_updater/package_download/{0}\')" title="' . lang('Download do Pacote') . '"><img src="' . URL_IMG . '/icon_download.png" /></a>');
		$obj_table->set_data($args['packages']);
		
		// html da tabela de dados
		$args['module_table'] = $obj_table->get_html();
		
		// Carrega view
		$this->template->load_page('_acme/acme_updater/index', $args);
	}
	
	/**
	* package_upload()
	* Página de upload de pacote de atualização.
	* @return void
	*/
	public function package_upload()
	{
		// Valida permissão de instalação de pacote
		$this->access->validate_permission('acme_updater', 'INSTALL_PACKAGE_UPDATE');
		
		// Carrega view
		$this->template->load_page('_acme/acme_updater/package_upload');
	}
	
	/**
	* package_review()
	* Revisão do conteúdo do pacote de atualização.
	* @return void
	*/
	public function package_review()
	{
		// Valida permissão de instalação de pacote
		$this->access->validate_permission('acme_updater', 'INSTALL_PACKAGE_UPDATE');
		
		// Array de configurações do upload
		$config['overwrite'] = true;
		$config['upload_path'] = $this->path_module_packages;
		$config['allowed_types'] = 'zip';
		$config['file_name'] = uniqid() . '_' . $_FILES['package']['name'];
		
		// Carrega biblioteca de upload
		$this->load->library('upload', $config);
		
		// Faz o upload, efetivamente
		if(!$this->upload->do_upload('package'))
		{
			// caso ocorra algum erro ao carregar a imagem.
			$this->error->show_exception_page(lang('Não foi possível fazer o upload do arquivo selecionado. Verifique os erros abaixo e tente novamente:<br />') . $this->upload->display_errors('<h6>&bull;&nbsp;', '</h6>'), URL_ROOT . '/acme_updater/package_upload');
			return false;
		} else {
			// Coleta o nome do path onde está o arquivo, para validá-lo
			$file_data = $this->upload->data();		
			$file_path = $this->path_module_packages . '/' . $file_data['orig_name'];
			
			// Valida se o pacote contém erros e coleta os dados deste
			$validation = $this->_analyze_package_update($file_path);
			if($validation === true)
			{
				// Coleta dados do pacote para sumário
				$args['package'] = $this->_process_package_update($file_path);
				
				// DEBUG:
				// print_r($args['package']);
				// die;
				
				// Faz verificação se pacote já está instalado
				$package = $this->db->get_where('acm_app_package_update', array('version' => $args['package']['package-version']));
				$pacakge = $package->result_array();
				$package_installed = (count($pacakge) > 0) ? true : false;
				
				// Verifica dependencias de pacote (busca no banco de dados se pacotes já estão instalados)
				$dependency = false;
				$dependencies = array();
				$count_dependencies = count($args['package']['package-dependencies']['version']);
				for($i = 0; $i < $count_dependencies; $i++)
				{
					$data = $this->db->get_where('acm_app_package_update', array('version' => $args['package']['package-dependencies']['version'][$i]));
					$package_dependency = $data->result_array();
					$package_dependency = (isset($package_dependency[0])) ? $package_dependency[0] : array();
					
					// Caso pacote não esteja instalado
					if(count($package_dependency) <= 0) {
						$dependencies[] = $args['package']['package-dependencies']['version'][$i];
						$dependency = true;
					}
				}
				
				// Variáveis para view
				$args['package_installed'] = $package_installed;
				$args['dependency'] = $dependency;
				$args['dependencies'] = $dependencies;
				$args['action_form'] = ($dependency || $package_installed) ? URL_ROOT . '/acme_updater/package_delete' : URL_ROOT . '/acme_updater/package_install';
				$args['package_file_name'] = $file_data['orig_name'];
				
				// Caso pacote contenha dependencias ou ja esteja instalado, então dropa o 
				// arquivo uploadeado
				if($dependencies || $package_installed)
					@unlink($file_path);
					
				// Carrega view
				$this->template->load_page('_acme/acme_updater/package_review', $args);
			} else {
			
				// Calcula as mensagens de erro
				$msg = '';
				foreach($validation as $error)
					$msg .= '<h6>&bull;&nbsp;' . $error . '</h6>';
				
				// Dropa arquivo com pau
				@unlink($file_path);
				
				// Carrega mensagem de erro
				$this->error->show_exception_page(lang('O arquivo de pacote selecionado contém problemas. Verifique os erros abaixo e tente novamente:') . $msg, URL_ROOT . '/acme_updater/package_upload');
			}
		}
	}
	
	/**
	* package_delete()
	* Quando há problemas na revisão do pacote de atualização, o action é mudado para este método,
	* desta forma o pacote é apagado do servidor evitando acumulo de pacotes desnecessários ou com
	* problemas.
	* @return void
	*/
	public function package_delete()
	{
		// Valida permissão de instalação de pacote
		$this->access->validate_permission('acme_updater', 'INSTALL_PACKAGE_UPDATE');
		
		// Tenta dropar o arquivo do servidor
		if($this->input->post('package_file_name') != '')
			@unlink($this->path_module_packages . '/' . $this->input->post('package_file_name'));
		
		// Faz um redirect para a página de upload de pacote
		redirect('acme_updater/package_upload');
	}
	
	/**
	* package_install()
	* Roda instalação de pacote.
	* @return void
	*/
	public function package_install()
	{
		// Valida permissão de instalação de pacote
		$this->access->validate_permission('acme_updater', 'INSTALL_PACKAGE_UPDATE');
		
		// Faz instalação caso o nome do pacote seja informado no post
		if($this->input->post('package_file_name') != '')
		{
			// Monta nome do pacote, já uploadeado
			$file_path = $this->path_module_packages . '/' . $this->input->post('package_file_name');
			
			// Faz as mesmas validações da revisão, por segurança
			// Valida se o pacote contém erros e coleta os dados deste
			$validation = $this->_analyze_package_update($file_path);
			if($validation === true)
			{
				// Encaminha o nome do arquivo a ser processado
				$args['file_name'] = $this->path_module_packages . '/' . $this->input->post('package_file_name');
				
				// Coleta dados do pacote para sumário
				$args['package'] = $this->_process_package_update($file_path);
				
				// Faz verificação se pacote já está instalado
				$package = $this->db->get_where('acm_app_package_update', array('version' => $args['package']['package-version']));
				$pacakge = $package->result_array();
				$package_installed = (count($pacakge) > 0) ? true : false;
				
				// Verifica dependencias de pacote (busca no banco de dados se pacotes já estão instalados)
				$dependency = false;
				$count_dependencies = count($args['package']['package-dependencies']['version']);
				for($i = 0; $i < $count_dependencies; $i++)
				{
					$data = $this->db->get_where('acm_app_package_update', array('version' => $args['package']['package-dependencies']['version'][$i]));
					$package_dependency = $data->result_array();
					$package_dependency = (isset($package_dependency[0])) ? $package_dependency[0] : array();
					
					// Caso pacote não esteja instalado
					if(count($package_dependency) <= 0)
						$dependency = true;
				}
				
				// Caso existam problemas de dependencia, redireciona para inicio
				// Isso quer dizer que o pacote foi encaminhado diretamente
				if($dependency || $package_installed) {
					redirect('acme_updater');
				} else {
					// FAZ A INSTALAÇÃO DO PACOTE (ATÉ QUE ENFIM!!!)
					// Carrega pagina de término de instalação
					$args['CI'] = $this;
					$this->template->load_page('_acme/acme_updater/package_install', $args, false, false);
				}
			} else {
				// Redireciona para pagina de entrada, caso pacote inválido
				redirect('acme_updater');
			}
		} else {
			// Redireciona para pagina de entrada, caso pacote nao informado
			redirect('acme_updater');
		}
	}
	
	/**
	* package_details()
	* Página de detalhes de um determinado pacote de version encaminhado.
	* @param string version
	* @return void
	*/
	public function package_details($version = 0)
	{
		// Valida permissão de instalação de pacote
		$this->access->validate_permission('acme_updater', 'INSTALL_PACKAGE_UPDATE');
		
		// Dados do pacote
		$args['package_data'] = $this->acme_updater_model->get_package_data($version);
		
		// Pacote resolvido (lido diretamente do arquivo)
		$args['package'] = $this->_process_package_update(get_value($args['package_data'], 'path_file'));
		
		// Instancia das tabelas utilizadas nos detalhes
		$obj_table_errors = $this->array_table->get_instance();
		$obj_table_dependencies = $this->array_table->get_instance();
		
		// Dependências do pacote
		$package_dependencies = $this->acme_updater_model->get_package_dependencies(get_value($args['package_data'], 'version'));
		$obj_table_dependencies->set_data($package_dependencies);
		$obj_table_dependencies->set_columns(array('VERSÃO', 'NOME', 'ARQUIVO', 'DISPONÍVEL EM', 'INSTALADO EM', 'DESCRIÇÃO'));
		$args['table_dependencies'] = $obj_table_dependencies->get_html();
		
		// Mensagens de erros do pacote
		$package_errors = $this->acme_updater_model->get_package_errors($version);
		$obj_table_errors->set_data($package_errors);
		$args['table_errors'] = $obj_table_errors->get_html();
		
		// Carrega view
		$this->template->load_page('_acme/acme_updater/package_details', $args);
	}
	
	/**
	* ajax_show_install_errors()
	* Exibe bloco html de erros da instalação. Utilizado após a instalação, pacote já instalado aqui.
	* @param string version
	* @return void
	*/
	public function ajax_show_install_errors($version = '')
	{
		// Valida permissão de instalação de pacote
		if($this->access->validate_permission('acme_updater', 'INSTALL_PACKAGE_UPDATE', false))
		{
			$args['errors'] = $this->acme_updater_model->get_package_errors($version);
			$this->template->load_page('_acme/acme_updater/ajax_show_install_errors', $args, false, false);
		}
	}
	
	/**
	* package_download()
	* Download de arquivo de pacote de version encaminhado.
	* @param string version
	* @return void
	*/
	public function package_download($version = 0)
	{
		// Valida permissão de instalação de pacote
		$this->access->validate_permission('acme_updater', 'INSTALL_PACKAGE_UPDATE');
		
		// Dados do pacote
		$package_data = $this->acme_updater_model->get_package_data($version);
		
		// Carrega helper para download
		$this->load->helper('download_helper');
		$data = @file_get_contents(get_value($package_data, 'path_file'));
		
		// Exporta arquivo
		force_download(basename(get_value($package_data, 'path_file')), $data);
	}
	
	/**
	* about_acme()
	* Página 'sobre' o acme engine, versão atual, licensa de uso, etc.
	* @return void
	*/
	public function about_acme()
	{
		$this->template->load_page('_acme/acme_updater/about_acme', array(), false, false);
	}
}
