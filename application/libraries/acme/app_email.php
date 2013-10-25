<?php
/**
*
* Classe App_Email
*
* Esta versão suporta apenas envios de email através do protocolo SMTP.
* 
* Esta biblioteca abriga funções de envio de email da aplicação. Utiliza a biblioteca Email nativa 
* do codeigniter. Em geral, a utilidade desta biblioteca está em não precisar instanciar as configurações
* de email toda vez que um envio for solicitado. Além disso as configurações de email são coletadas
* do próprio ACME Engine.
*
* @since		14/07/2013
* @location		acme.libraries.app_email
*
*/
class App_Email {
	// Definição de Atributos
	var $CI = null;
	
	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
	}
	
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
	public function send_email($subject = '', $message = '', $email = '', $cc = array(), $bcc = array(), $reply_to = array())
	{
		// Coleta instancia do codeigniter (para poder utilizar as funcoes nativas)
		$CI =& get_instance();
		
		// Carrega biblioteca email
		$CI->load->library('email');
		
		// Seta o from (automatico do application_settings.php)
		$CI->email->from(EMAIL_GLOBAL_ADDRESS_FROM, EMAIL_GLOBAL_NAME_FROM);
		
		// Seta destinatário
		$CI->email->to($email);
		
		// Seta assunto
		$CI->email->subject($subject);
		
		// Seta corpo da mensagem
		$CI->email->message($message);
		
		// Seta cópias ou cópias ocultas (caso informados)
		if(count($cc) > 0) {
			foreach($cc as $email_cc)
				$CI->email->cc($email_cc);
		}
		if(count($bcc) > 0) {
			foreach($bcc as $email_bcc)
				$CI->email->bcc($email_bcc);
		}
		
		// Seta o reply_to, caso informado
		if(count($reply_to) > 1){
			$CI->email->reply_to($reply_to[0], $reply_to[1]);
		}
		
		// Retorna booleano de envio
		return @$CI->email->send();
	}
}