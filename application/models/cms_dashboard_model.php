<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe cms_dashboard_Model (Arquivo gerado com construtor de módulos)
* 
* Módule CMS_Dashboard: Dashboard da área administrativa do Gerenciador de Conteúdo ACME CMS.
*
* @since		30/07/2013
* @location		models.cms_dashboard_Model
*
*/
class cms_dashboard_Model extends Base_Module_Model {
	// Definição de Atributos
	
	/**
	* __construct()
	* Construtor de classe. Chama o construtor pai, que abre uma conexão com
	* o banco de dados, automaticamente.
	* @return object
	*/
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	* example()
	* Método de 'exemplo' da camada modelo. Quando um controlador invocar através da 
	* chamada $this->cms_dashboard_Model->example(), este método será disparado.
	* @return void
	*/
	public function example()
	{
		// EXEMPLO DE COMO MANIPULAR QUERIES
		/*
		$sql = "SELECT * FROM table";
		$data = $this->db->query($sql);
		$data = $data->result_array();
		return (isset($data)) ? $data : array();
		*/
	}
}
