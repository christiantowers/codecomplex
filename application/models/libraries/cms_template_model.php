<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe CMS_Template_model
*
* Gerencia dados que estarão disponíveis no template do website, isto é, gerencia a camada
* de modelo da biblioteca template template.
* 
* @since		01/08/2013
* @location		models.cms_template_model
*
*/
class CMS_Template_Model extends CI_Model {
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
		$this->load->database();
	}
	
	/**
	* get_website_menus()
	* Função que retorna o conjunto de menus do website
	* @return array menus
	*/
	public function get_website_menus()
	{
		// Seleciona todo conjunto de menus em um unico resultset
		$sql = "SELECT m.*,
					   m.id_website_menu AS id_menu,
					   IFNULL(id_website_menu_parent, 0) AS id_menu_parent
				  FROM cms_website_menu m
				 WHERE m.dtt_inative IS NULL
			  ORDER BY m.id_website_menu_parent, m.order";
		$data = $this->db->query($sql);
		$data = $data->result_array();
		return (isset($data)) ? $data : array();
	}
}
