<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* Classe support
* 
* Abstração da página URL de suporte. Quando necessário, URLs são resolvidas aqui.
*
* @since		08/08/2013
* @location		controllers.support
*
*/
class support extends cms_website {
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
	* ajax_save_feedback()
	* Salva um registro de feedback, encaminhado ou por formulário de contato ou caixa de feedback.
	* @return void
	*/
	public function ajax_save_feedback()
	{
		if($this->input->post() != '')
		{
			// Realiza cadastro (gera uma chave de download, que permitirá acesso no próximo método)
			$args['name'] = ($this->input->post('name') != '') ? $this->input->post('name') : '';
			$args['email'] = ($this->input->post('email') != '') ? $this->input->post('email') : '';
			$args['subject'] = ($this->input->post('subject') != '') ? $this->input->post('subject') : '';
			$args['message'] = ($this->input->post('message') != '') ? $this->input->post('message') : '';
			$args['ip_address'] = $this->input->ip_address();
			
			// Insere no banco de dados
			$this->db->insert('wbs_feedback', $args);
		}
	}
	
	/**
	* ajax_save_newsletter()
	* Salva um registro de email na lista de newsletter.
	* @return void
	*/
	public function ajax_save_newsletter()
	{
		if($this->input->post('email') != '')
		{
			$email = $this->input->post('email');
			$nl = $this->db->get_where('wbs_newsletter', array('email' => $email));
			$nl = $nl->result_array();
			
			// Se email nao estiver cadastrado (eita preguiça de fazer um insert on duplicate key)
			if(count($nl) <= 0)
				$this->db->insert('wbs_newsletter', array('email' => $email));
		}
	}
}
