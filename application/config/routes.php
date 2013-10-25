<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['404_override'] = '';
$route['default_controller'] = "cms/cms_website/homepage";
$route['cms'] = "cms/cms_access";
$route['CMS'] = "cms/cms_access";
$route['CMS_(:any)'] = "cms/cms_$1";
$route['cms_(:any)'] = "cms/cms_$1";
$route['acme_(:any)'] = "acme/acme_$1";

// Carrega todas as rotas cadastradas na tabela de paginas url
include('application_settings.php');

// Força conexão com banco de dados
define('DB_PORT', $config['DB_PORT']);
define('DB_HOST', $config['DB_HOST']);
define('DB_USER', $config['DB_USER']);
define('DB_PASS', $config['DB_PASS']);
define('DB_DATABASE', $config['DB_DATABASE']);

//
require_once( BASEPATH .'database/DB'. EXT );
$db =& DB();
//print_r($db);
$query = $db->get_where('cms_page_url', "method_show = 'controller_exec' AND controller_action IS NOT NULL AND controller_action != ''");
$result = $query->result();
foreach( $result as $row )
{
	if($row->controller_action != '')
	{
		$route[trim($row->url, '/')] = $row->controller_action;
	}
}

// Regras de roteamento genéricas:
// Todo o controlador que tiver o prefixo acme_ será direcionado para controllers/acme/acme_...
$route['cms_website/show_page_url_preview/[0-9]+/(:any)'] = "cms/cms_website/show_page_url_preview/$1/$2";
$route[':any'] = "cms/cms_website/show_page_url/$1";

/* End of file routes.php */
/* Location: ./application/config/routes.php */