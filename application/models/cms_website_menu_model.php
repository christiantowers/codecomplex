<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe cms_website_menu_Model (Arquivo gerado com construtor de módulos)
* 
* Módule cms_website_menu: 
*
* @since		30/07/2013
* @location		models.cms_website_menu_model
*
*/
class cms_website_menu_Model extends Base_Module_Model {
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
	* get_list_module()
	* Retorna um array de menus do website.
	* @return array menus
	*/
	public function get_list_module()
	{
		$sql = "
			SELECT x.*, IFNULL(x.id_menu_parent, 0) AS id_menu_parent FROM (
			SELECT m.id_website_menu AS id_menu,
				   m.id_website_menu_parent AS id_menu_parent,
				   m.*
			  FROM cms_website_menu m
			  ORDER BY m.order) x ORDER BY x.id_menu_parent ";
		$data = $this->db->query($sql);
		$data = $data->result_array();
		return (isset($data)) ? $data : array();
	}
	
	/**
	* get_list_menus()
	* Retorna um array de menus do website.
	* @return array menu
	*/
	public function get_list_menus()
	{
		$sql = "SELECT m.id_website_menu AS id_menu,
					   m.rotule
				  FROM cms_website_menu m 
			  ORDER BY m.id_website_menu_parent, m.order";
		$data = $this->db->query($sql);
		$data = $data->result_array();
		return (isset($data)) ? $data : array();
	}
}
