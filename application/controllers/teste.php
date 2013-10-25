<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe teste
* 
* Abstração da página URL de downloads. Quando necessário, URLs são resolvidas aqui.
*
* @since		06/08/2013
* @location		controllers.downloads
*
*/
class teste extends cms_website {
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
	public function pgina_teste()
	{
		echo 'deu certo';
		die;
	}
}
