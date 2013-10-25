<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe cms_website_Model (Arquivo gerado com construtor de módulos)
* 
* Módule cms_website: Leitor de conteúdos, responsável por ser bootstrap do website atual.
*
* @since		30/07/2013
* @location		models.cms_website_model
*
*/
class cms_website_Model extends Base_Module_Model {
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
	* get_page_url()
	* Retorna dados de uma página URL com base em uma URL encaminhada.
	* @param string url
	* @return void
	*/
	public function get_page_url($url = '')
	{
		$data = $this->db->get_where('cms_page_url', array('url' => $url));
		$data = $data->result_array();
		return (isset($data[0])) ? $data[0] : array();
	}
}
