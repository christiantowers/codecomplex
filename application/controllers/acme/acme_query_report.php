<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Acme_Query_Report
* 
* Classe abstração para o módulo de relatórios SQL genéricos do sistema.
*
* @since		24/07/2013
* @location		acme.controllers.acme_query_report
*
*/
class Acme_Query_Report  extends Acme_Base_Module {
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
	* Método 'padrão' do controlador. É invocado automaticamente quando 
	* o action deste controlador não é informado na URL. Por padrão seu efeito
	* é exibir a tela de listagem de entrada do módulo.
	* @param int actual_page
	* @return void
	*/
	public function index($actual_page = 0)
	{
		parent::index($actual_page);
	}
	
	/**
	* run_report()
	* Executa um relatório de id encaminhado. Segundo parametro diz qual pagina é a atual no conjunto
	* de dados do relatório.
	* @param int id_report
	* @param int actual_page
	* @return void
	*/
	public function run_report($id_report = 0, $actual_page = 0)
	{
		// Testa permissão de rodar relatório
		$this->validate_permission('RUN_REPORT');
		
		// Alguns relatórios podem demorar mais do que o esperado
		set_time_limit(0);
		
		// Dados do relatório
		$report = $this->acme_query_report_model->select($id_report);
		$report = (isset($report[0])) ? $report[0] : array();
		
		// Caso exista um executor para este relatorio, redireciona a pagina para este
		if(get_value($report, 'controller_action_executor') == '')
		{
			// Dados do relatório
			$report_data = query_array(get_value($report, 'sql'));
			
			// Carrega tabela de dados, seta o array de dados e limita a paginação
			$this->array_table->set_id('table_report_generic_' . uniqid());
			$this->array_table->set_data($report_data);
			$this->array_table->set_page_link('acme_query_report/run_report/' . $id_report);
			$this->array_table->set_actual_page($actual_page);
			
			// Processa a tabela html
			$args['module_table'] = $this->array_table->get_html();
			
			// Passa relatório para ser utilizado em view
			$args['report'] = $report;
			
			// Loga execução de relatório
			$this->log->db_log(lang('Execução de Relatório'), 'run_report', 'acm_query_report', $report);

			// Carrega view
			$this->template->load_page('_acme/acme_query_report/run_report', $args, false, false);
		} else {
			redirect(get_value($report, 'controller_action_executor') . '/' . $id_report);
		}
	}
	
	/**
	* export_csv()
	* Exporta o resultado de uma consulta de relatório para o formato .CSV.
	* @param int id_report
	* @return void
	*/
	public function export_csv($id_report = 0)
	{
		// Testa permissão de rodar relatório
		$this->validate_permission('RUN_REPORT');
		
		// Alguns relatórios podem demorar mais do que o esperado
		set_time_limit(0);
		
		// Dados do relatório
		$report = $this->acme_query_report_model->select($id_report);
		$report = (isset($report[0])) ? $report[0] : array();
		
		// Carrega classes necessárias para export
		$this->load->dbutil();
		$query = $this->db->query(get_value($report, 'sql'));
		
		// Monta conteúdo do arquivo
		$file_contents = $this->dbutil->csv_from_result($query, ";", "\r\n");
		
		// Exporta arquivo contendo dados do relatorio
		$this->load->helper('download_helper');
		$file_name = preg_replace("/[^A-Za-z0-9]/", "_", lang(get_value($report, 'lang_key_rotule')));
		$file_name = $file_name . date('_Y-m-d_His') . '.csv';
		force_download($file_name, $file_contents);
	}
}
