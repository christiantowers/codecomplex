<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe cms_page_url_category_Model (Arquivo gerado com construtor de módulos)
* 
* Módule cms_page_url_category: Este módulo gerencia categorias de páginas URL cadastradas no gerenciador CMS.
*
* @since		30/07/2013
* @location		models.cms_page_url_category_model
*
*/
class cms_page_url_category_Model extends Base_Module_Model {
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
	* get_options_categories()
	* Retorna array de categorias para utilização em combo de formulario.
	* @return void
	*/
	public function get_options_categories()
	{
		$sql = "SELECT id_page_url_category, name FROM cms_page_url_category ORDER BY name";
		$data = $this->db->query($sql);
		$data = $data->result_array();
		return (isset($data)) ? $data : array();
	}
}
