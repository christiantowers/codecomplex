<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe downloads
* 
* Abstração da página URL de downloads. Quando necessário, URLs são resolvidas aqui.
*
* @since		06/08/2013
* @location		controllers.downloads
*
*/
class downloads extends cms_website {
	// Definição de atributos
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
		// Carrega construtor superior (website)
		parent::__construct();
	}
	
	/**
	* register()
	* Registro do formulário da página de download. Caso form preenchido, libera o download.
	* @return void
	*/
	public function register()
	{
		// Realiza cadastro (gera uma chave de download, que permitirá acesso no próximo método)
		$args['name'] = $this->input->post('name');
		$args['email'] = $this->input->post('email');
		$args['company'] = $this->input->post('company');
		$args['function'] = $this->input->post('function');
		$args['ip_address'] = $this->input->ip_address();
		$args['no_register'] = $this->input->post('no_register') == 'Y' ? 'Y' : 'N';
		$args['hash_download_key'] = md5(uniqid());
		
		// Insere no banco de dados
		$this->db->insert('wbs_register_download', $args);
		
		// Caso o checkbox esteja marcado para inserir na newsletter
		if($this->input->post('newsletter') == 'Y')
		{
			$email = $this->input->post('email');
			$nl = $this->db->get_where('wbs_newsletter', array('email' => $email));
			$nl = $nl->result_array();
			
			// Se email nao estiver cadastrado (eita preguiça de fazer um insert on duplicate key)
			if(count($nl) <= 0)
				$this->db->insert('wbs_newsletter', array('email' => $email));
		}
		
		// Redireciona para página de download
		redirect('/downloads/acme-engine/actual-stable/get/' . $args['hash_download_key']);
	}
	
	/**
	* get_acme_engine_actual_stable()
	* Página que abre o download do acme engine na versão estável atual.
	* @param string hash
	* @return void
	*/
	public function get_acme_engine_actual_stable($hash = '')
	{
		if($hash != '')
		{
			// Coleta se cadastro está valido
			$download = $this->db->get_where('wbs_register_download', array('hash_download_key' => $hash));
			$download = $download->result_array();
			$download = isset($download[0]) ? $download[0] : array();
			
			if(count($download) > 0)
				$this->template->load_page('downloads/get_acme_engine_actual_stable', array('hash' => $hash));
			else
				redirect('/downloads');
		} else {
			redirect('/downloads');
		}
	}
	
	/**
	* download_acme_engine_actual_stable()
	* Download efetivamente do ACME Engine.
	* @param string hash
	* @return void
	*/
	public function download_acme_engine_actual_stable($hash = '')
	{
		if($hash != '')
		{
			// Coleta se cadastro está valido
			$download = $this->db->get_where('wbs_register_download', array('hash_download_key' => $hash));
			$download = $download->result_array();
			$download = isset($download[0]) ? $download[0] : array();
			
			// Libera download
			if(count($download) > 0)
			{
				$path = PATH_UPLOAD . '/website_files/acme_engine/actual_stable';
				$actual_stable = parse_ini_file($path . '/change_log.ini');
				$file = get_value($actual_stable, 'file');
				
				// Força download da bagaça
				$this->load->helper('download');
				force_download($file, file_get_contents($path . '/' . $file));
			}
		}
	}
	
	/**
	* packages_updates()
	* Página de listagem de pacotes de atualização disponíveis.
	* @return void
	*/
	public function packages_updates()
	{
		// Abre o diretorio de atualizações
		$path = 'application/uploads/website_files/packages_updates';
		$this->load->helper('directory');
		$args['packages'] = directory_map($path);
		$this->template->load_page('downloads/packages_updates', $args);
	}
	
	/**
	* get_package_update()
	* Exporta um pacote de atualização de versão encaminhada.
	* @param string version
	* @return void
	*/
	public function get_package_update($version = '')
	{
		// Abre o diretorio de atualizações
		$path = 'application/uploads/website_files/packages_updates/' . $version;
		$package = parse_ini_file($path. '/change_log.ini');
	
		// Espera-se que em $package existam as chaves file, version e description
		$file = get_value($package, 'file');
		
		// Força download da bagaça
		$this->load->helper('download');
		force_download($file, file_get_contents($path . '/' . $file));
	}
}
