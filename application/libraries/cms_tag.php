<?php
/**
*
* Classe CMS_Tag
*
* Esta biblioteca gerencia funções relacionadas ao uso de tags específicas dentro do gerenciador
* de conteúdo.
* 
* @since		05/08/2013
* @location		libraries.cms_tag
*
*/
class CMS_Tag {
	// Definição de Atributos
	var $tag_name = 'cmseval';
	var $CI = null;
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	/**
	* replace_php_tag()
	* Este método faz replace de tags <php></php> por tags de abertura e fechamento <?php e ?>
	* String contendo a tag: <php>echo message('info', '', '...');</php>
	* Resultado: <?php echo message('info', '', '...'); ?>
	* @param string content
	* @return string new_content
	*/
	public function replace_php_tag($string = '')
	{
		return preg_replace('/<\/php>?/i', ' ?>', preg_replace('/<php>?/i', '<?php ', $string));
	}
	
	/**
	* replace_php_delimiter()
	* Este método faz replace de delimitadores php para tags utilizáveis no editor. Inverso da função
	* replace_php_tag();
	* @param string content
	* @return string new_content
	*/
	public function replace_php_delimiter($string = '')
	{
		return str_replace('<?php', '<php> ', str_replace('?>', ' </php>', $string));
	}
}