<?php
/**
*
* Classe App_Config
*
* Esta biblioteca abriga configurações gerais da aplicação, como configurações de ambiente, banco
* de dados, classes para autoload, entre outros. No construtor desta classe, toda configuração setada
* no arquivo application_settings.php automaticamente vira um define, que fica disponível para toda a 
* a aplicação (com exceção de variáveis que contenham múltiplos valores - i.e. arrays), bem como se 
* torna uma variável desta mesma classe.
* 
* @since		10/09/2012
* @location		acme.libraries.app_config
*
*/
class App_Config {
	// Definição de Atributos
	var $CI = null;
	
	/**
	* __construct()
	* Construtor de classe. Carrega arquivo application_settings.php e transforma array de configurações em
	* variáveis e defines.
	* @return object
	*/
	public function __construct()
	{
		$this->load_config_file('application_settings');
		$this->load_configs_db();
	}
	
	/**
	* load_config_file()
	* Carrega arquivo de configuração, de nome encaminhado. Cada índice do array do arquivo de 
	* configuração vira uma variável desta classe e um define global. O arquivo encaminhado como 
	* parametro nao deve ter a extensão .php declarada.
	* @param string file
	* @return void
	*/
	public function load_config_file($file = '')
	{
		include('application/config/' . $file . '.php');
		foreach($config as $key => $val)
		{
			if(!is_array($val))
			{
				if(!defined($key))
					define($key, $val);
			}	
			$this->{$key} = $val;
		}
	}
	
	/**
	* load_configs_db()
	* Carrega configurações globais da aplicação setadas na tabela de configurações da app
	* (acm_app_config). Para cada configuração é criado um atributo de mesmo nome, além de uma
	* constante global.
	* @return void
	*/
	public function load_configs_db()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('acme/validation');
		$this->CI->load->model('acme/libraries/app_config_model');
		$configs = $this->CI->app_config_model->get_app_configs_db();
		foreach($configs as $config)
		{
			if($this->CI->validation->is_variable_name($config['config']))
			{
				define($config['config'], $config['value']);	
				$this->{$config['config']} = $config['value'];
			}
		}
		unset($this->CI);
	}
	
	/**
	* get_app_config_db()
	* Retorna uma configuração da aplicação cadastrada no banco de dados, com base na chave inteligente
	* encaminhada. Isto é, localiza na tabela acm_app_config a configuração de config encaminhada.
	* @param string config
	* @return mixed value
	*/
	public function get_app_config_db($config = '')
	{
		$this->CI =& get_instance();
		$this->CI->load->model('acme/libraries/app_config_model');
		return $this->CI->app_config_model->get_app_config_db($config);
	}
	
	/**
	* get_input_configurations()
	* Retorna uma string contendo uma série de inputs HTML contendo o valor das configurações da
	* aplicacao (arquivo settings). Ideal para utilização em view. O segundo parametro define se
	* as configurações serão carregadas em modo seguro (recomendável). Desta forma, configurações
	* internas como de banco de dados NÃO SERÃO CARREGADAS.
	* @param boolean protected_mode
	* @return string html_inputs
	*/
	public function get_input_configurations($protected_mode = true)
	{
		$return = '';
		$escape = array('ENVIRONMENT', 
						'IS_MAINTAINING', 
						'APP_NAME', 
						'DB_HOST', 
						'DB_PORT', 
						'DB_USER', 
						'DB_PASS', 
						'DB_DATABASE', 
						'JS_FILES', 
						'CSS_FILES', 
						'PATH_HTML_COMPONENTS', 
						'EMAIL_PROTOCOL', 
						'EMAIL_SMTP_HOST', 
						'EMAIL_SMTP_PORT', 
						'EMAIL_SMTP_USER', 
						'EMAIL_SMTP_TIMEOUT', 
						'EMAIL_SMTP_PASS', 
						'EMAIL_CHAR_NEWLINE', 
						'EMAIL_CHAR_CRLF', 
						'EMAIL_CHARSET', 
						'EMAIL_GLOBAL_NAME_FROM', 
						'EMAIL_GLOBAL_ADDRESS_FROM', 
						'EMAIL_MAILTYPE');
		// Retorno só cria variáveis fora do escape
		foreach(get_object_vars($this) as $attribute => $value)
		{
			if(!is_object($value))
				$return .= (!in_array($attribute, $escape) || !$protected_mode) ? "<input type=\"hidden\" name=\"$attribute\" id=\"$attribute\" value=\"" . eval_replace($value) . "\" />\n" : '';
		}
		
		// Retorno
		return $return;
	}
}