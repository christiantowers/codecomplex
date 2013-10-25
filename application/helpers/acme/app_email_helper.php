<?php
/**
* -------------------------------------------------------------------------------------------------
* App_Email Helper
*
* Centraliza funções relativas ao envio de emails na aplicação. Utiliza os métodos da biblioteca
* App_Email, do ACME Engine.
*
* A chamada das funções contidas neste arquivo ajudante são alias para os métodos de mesmo nome
* localizados na respectiva biblioteca (App_Email). Sendo assim, as instruções abaixo retornam o mesmo
* resultado esperado:
*	example_function(); // função localizada neste arquivo
* 	$this->app_email->example_function();
* 
* @since		15/07/2013
* @location		acme.helpers.app_email
*
* -------------------------------------------------------------------------------------------------
*/

/**
* send_email()
* Envia email com base nos parametros encaminhados. Utiliza as configurações de email presentes
* em config/application_settings.php.
* @param string subject
* @param string message
* @param string email
* @param array cc
* @param array bcc
* @param array email_reply_to Example: array([0] => 'email@email.com', [1] => 'Your Name')
* @return boolean send
*/
function send_email($subject = '', $message = '', $email = '', $cc = array(), $bcc = array(), $reply_to = array())
{
	$CI =& get_instance();
	return $CI->app_email->send_email($subject, $message, $email, $cc, $bcc, $reply_to);
}