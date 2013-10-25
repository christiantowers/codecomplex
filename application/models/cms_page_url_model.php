<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe cms_page_url_Model (Arquivo gerado com construtor de módulos)
* 
* Módule cms_page_url: Este módulo gerencia conteúdos de páginas URL cadastradas no website atual.
*
* @since		30/07/2013
* @location		models.cms_page_url_model
*
*/
class cms_page_url_Model extends Base_Module_Model {
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
	* get_options_page_url()
	* Retorna array de paginas URL para utilização em combo de formulario.
	* @return void
	*/
	public function get_options_page_url()
	{
		$sql = "SELECT id_page_url, url FROM cms_page_url ORDER BY url";
		$data = $this->db->query($sql);
		$data = $data->result_array();
		return (isset($data)) ? $data : array();
	}
	
	/**
	* get_page_url_preview()
	* Retorna uma página URL preview.
	* @param int id_preview_page_url
	* @return void
	*/
	public function get_page_url_preview($id_preview_page_url = 0)
	{
		$sql = "SELECT * FROM cms_page_url_preview WHERE id_page_url = $id_preview_page_url";
		$data = $this->db->query($sql);
		$data = $data->result_array();
		return (isset($data[0])) ? $data[0] : array();
	}
}
