<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe documentation
* 
* Abstração da página URL de documentação. Quando necessário, URLs são resolvidas aqui.
*
* @since		19/08/2013
* @location		controllers.documentation
*
*/
class documentation extends cms_website {
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
	* more_about_acme_engine()
	* Redireciona para seu primeiro filho: url /documentation/more-about-acme-engine/what-is
	* @return void
	*/
	public function more_about_acme_engine()
	{
		redirect('/documentation/more-about-acme-engine/what-is');
	}
}
