<?php

/**
 * Kumbia PHP Framework
 *
 * LICENSE
 *
 * This source file is subject to the GNU/GPL that is bundled
 * with this package in the file docs/LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.kumbia.org/license.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kumbia@kumbia.org so we can send you a copy immediately.
 *
 * @category Kumbia
 * @package Controller
 * @copyright Copyright (c) 2005-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @license http://www.kumbia.org/license.txt GNU/GPL
 */

/**
 * @see ControllerException
 */
require_once "library/kumbia/controller/exception.php";

/**
 * Es la clase padre de ApplicationController y de StandardForm
 *
 * @category Kumbia
 * @package Controller
 * @copyright Copyright (c) 2005-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @license http://www.kumbia.org/license.txt GNU/GPL
 */
class Controller extends ControllerBase {

	/**
	 * Nombre del controlador actual
	 *
	 * @var string
	 */
	public $controller_name;

	/**
	 * Nombre de la acción actual
	 *
	 * @var string
	 */
	public $action_name;

	/**
	 * Nombre del primer parametro despues de action
	 * en la URL
	 *
	 * @var string
	 */
	public $id;

	/**
	 * Numero de minutos que ser&aacute; cacheada la vista actual
	 *
	 * @var integer
	 */
	protected $cache_view = 0;

	/**
	 * Numero de minutos que ser&aacute; cacheada el layout actual
	 *
	 * @var integer
	 */
	protected $cache_layout = 0;

	/**
	 * Numero de minutos que ser&aacute; cacheado el template actual
	 *
	 * @var integer
	 */
	protected $cache_template = 0;

	/**
	 * Indica si el controlador soporta persistencia
	 *
	 * @var boolean
	 */
	protected $persistance = true;

	/**
	 * Tipo de Respuesta que sera generado
	 *
	 * @var string
	 */
	public $response = "";

	/**
	 * Indica si el controlador es persistente o no
	 *
	 * @var boolean
	 */
	static public $force = false;

	/**
	 * Logger implicito del controlador
	 *
	 * @var string
	 */
	protected $logger;

	/**
	 * Cache la vista correspondiente a la accion durante $minutes
	 *
	 * @param $minutes
	 */
	public function cache_view($minutes){
		$this->cache_view = $minutes;
	}

	/**
	 * Obtiene el valor en minutos para el cache de la
	 * vista actual
	 *
	 */
	public function get_view_cache(){
		return $this->cache_view;
	}

	/**
	 * Cache la vista en views/layouts/
	 * correspondiente al controlador durante $minutes
	 *
	 * @param $minutes
	 */
	public function cache_layout($minutes){
		$this->cache_layout = $minutes;
	}

	/**
	 * Obtiene el valor en minutos para el cache del
	 * layout actual
	 *
	 */
	public function get_layout_cache(){
		return $this->cache_layout;
	}

	/**
	 * Hace el enrutamiento desde un controlador a otro, o desde
	 * una acción a otra.
	 *
	 * Ej:
	 * <code>
	 * return $this->route_to("controller: clientes", "action: consultar", "id: 1");
	 * </code>
	 *
	 */
	public function route_to(){
		$args = func_get_args();
		return call_user_func_array(array("Router", "route_to"), $args);
	}

	/**
	 * Obtiene un valor del arreglo $_POST
	 *
	 * @param string $param_name
	 * @return mixed
	 */
	public function post($param_name){
		if(func_num_args()>1){
			$args = func_get_args();
			$args[0] = isset($_POST[$param_name]) ? $_POST[$param_name] : "";
			$filter = new Filter();
			return call_user_func_array(array($filter, "apply_filter"), $args);
		}
		return isset($_POST[$param_name]) ? $_POST[$param_name] : "";
	}

	/**
	 * Obtiene un valor del arreglo $_GET
	 *
	 * @param string $param_name
	 * @return mixed
	 */
	public function get($param_name){
		if(func_num_args()>1){
			$args = func_get_args();
			$args[0] = isset($_GET[$param_name]) ? $_GET[$param_name] : "";
			$filter = new Filter();
			return call_user_func_array(array($filter, "apply_filter"), $args);
		}
		return isset($_GET[$param_name]) ? $_GET[$param_name] : "";
	}

	/**
	 * Obtiene un valor del arreglo $_REQUEST
 	 *
	 * @param string $param_name
	 * @return mixed
	 */
	public function request($param_name){
		/**
		 * Si hay mas de un argumento, toma los demas como filtros
		 */
		if(func_num_args()>1){
			$args = func_get_args();
			$args[0] = isset($_REQUEST[$param_name]) ? $_REQUEST[$param_name] : "";
			$filter = new Filter();
			return call_user_func_array(array($filter, "apply_filter"), $args);
		}
		return isset($_REQUEST[$param_name]) ? $_REQUEST[$param_name] : "";
	}


	/**
	 * Sube un archivo al directorio img/upload si esta en $_FILES
	 *
	 * @param string $name
	 * @return string
	 */
	public function upload_image($name){
		if(isset($_FILES[$name])){
			move_uploaded_file($_FILES[$name]['tmp_name'], htmlspecialchars("public/img/upload/{$_FILES[$name]['name']}"));
			return urlencode(htmlspecialchars("upload/".$_FILES[$name]['name']));
		} else {
			return urlencode($this->request($name));
		}
	}

	/**
	 * Sube un archivo al directorio $dir si esta en $_FILES
	 *
	 * @param string $name
	 * @return string
	 */
	public function upload_file($name, $dir){ 
		if($_FILES[$name."_file"]['name']&&$this->request($name)==""){   
		    $name=$name."_file";		    
		    //mkdir("public/img/$dir");
			move_uploaded_file($_FILES[$name]['tmp_name'], htmlspecialchars("public/img/$dir/{$_FILES[$name]['name']}"));
			return urlencode(htmlspecialchars("$dir/".$_FILES[$name]['name']));
		} elseif($this->request($name)!=""&&$_FILES[$name."_file"]['name']=="") {     
			    return urlencode($this->request($name));
		} 
		
	}

	/**
	 * Indica si un controlador va a ser persistente, en este
	 * caso los valores internos son automaticamente almacenados
	 * en sesion y disponibles cada vez que se ejecute una acción
	 * en el controlador
	 *
	 * @param boolean $value
	 */
	public function set_persistance($value){
		$this->persistance = $value;
	}

	/**
	 * Redirecciona la ejecución a otro controlador en un
	 * tiempo de ejecución determinado
	 *
	 * @param string $controller
	 * @param integer $seconds
	 */
	public function redirect($controller, $seconds=0.5){
		$config = Config::read();
		$seconds*=1000;
		if(headers_sent()){
			print "
				<script type='text/javascript'>
					window.setTimeout(\"window.location='".KUMBIA_PATH."$controller'\", $seconds);
				</script>\n";
		} else {
			header("Location: ".KUMBIA_PATH."$controller");
		}
	}

	/**
	 * Indica el tipo de Respuesta dada por el controlador
	 *
	 * @param string $type
	 */
	public function set_response($type){
		$this->response = $type;
	}

	/**
	 * Reescribir este metodo permite controlar las excepciones generadas en un controlador
	 *
	 * @param Exception $e
	 */
	public function exceptions($exception){
		throw $exception;
	}

	/**
	 * Borra las vistas almacenadas en el cache de la sesion actual
	 *
	 */
	public function delete_cache($type){

		switch($type){
			case 'all':
				foreach(scandir('cache/') as $cache_dir){
					if($cache_dir!='.'&&$cache_dir!='..'){
						if(is_dir('cache/'.$cache_dir)){
							foreach(scandir('cache/'.$cache_dir) as $cache_controller_dir){
								if($cache_controller_dir!='.'&&$cache_controller_dir!='..'){
									if(is_dir('cache/'.$cache_dir."/".$cache_controller_dir)){
										foreach(scandir('cache/'.$cache_dir."/".$cache_controller_dir) as $cache_file){
											if($cache_file != '.' && $cache_file != '..'){
												unlink('cache/'.$cache_dir."/".$cache_controller_dir.'/'.$cache_file);
											}
										}
										rmdir('cache/'.$cache_dir."/".$cache_controller_dir);
									} else {
										unlink('cache/'.$cache_dir."/".$cache_controller_dir);
									}
								}
							}
						}
						rmdir('cache/'.$cache_dir);
					}
				}
			break;
			default:
				throw new ControllerException("Opcion '$type' indefinida para delete_cache");
		}

	}

	/**
	 * Crea un log sino existe y guarda un mensaje
	 *
	 * @param string $msg
	 * @param integer $type
	 */
	public function log($msg, $type=Logger::DEBUG){
		if(is_array($msg)){
			$msg = print_r($msg, true);
		}
		if(!$this->logger){
			$this->logger = new Logger($this->controller_name.".txt");
		}
		$this->logger->log($msg, $type);
	}

	/**
	 * Al deserializar asigna 0 a los tiempos del cache
	 */
	public function __wakeup(){
		$this->logger = false;
		$this->cache_view = 0;
		$this->cache_layout = 0;
	}

}

