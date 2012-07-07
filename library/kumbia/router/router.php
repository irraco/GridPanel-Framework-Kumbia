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
 * @package Router
 * @abstract
 * @copyright  Copyright (c) 2005-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @license http://www.kumbia.org/license.txt GNU/GPL
 */

/**
 * @see RouterException
 */
require_once "library/kumbia/router/exception.php";

/**
 * Clase que Actua como router del Front-Controller
 *
 * @category Kumbia
 * @package Router
 * @copyright Copyright (c) 2005-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @license http://www.kumbia.org/license.txt GNU/GPL
 *
 */
abstract class Router extends Object {

	/**
	 * Nombre de la aplicación actual
	 *
	 * @var string
	 */
	static private $application;

	/**
	 * Nombre del modulo actual
	 *
	 * @var string
	 */
	static private $module;

	/**
	 * Nombre del controlador actual
	 *
	 * @var string
	 */
	static private $controller;

	/**
	 * Nombre de la accion actual
	 *
	 * @var string
	 */
	static private $action;

	/**
	 * Nombre del primer parametro despues de action
	 *
	 * @var string
	 */
	static private $id;

	/**
	 * Lista de Todos los parametros de la URL
	 *
	 * @var array
	 */
	static private $all_parameters;

	/**
	 * Lista de Parametros Adicionales de la URL
	 *
	 * @var array
	 */
	static private $parameters;

	/**
	 * Indica si esta pendiente la ejecución de una ruta por
	 * parte del dispatcher
	 *
	 * @var boolean
	 */
	static private $routed;

	/**
	 * Detector de enrutamiento ciclico
	 */
	static private $routed_cyclic;

	/**
	 * Toma $url y la descompone en controlador, accion y argumentos
	 *
	 * @param string $url
	 */
	static function rewrite($url){

		$url_items = explode("/", $url);
        $usedefect=false;
		/**
		 * El router puede detectar si el controlador corresponde a una aplicación
		 * o a un controlador en
		 */
		$config = Config::read("config.ini");  

		if(isset($config->$url_items[0])){  

			self::$application = $url_items[0];
            $usedefect=true;
			/**
			 * Hay algun controlador?
			 */
			if(isset($url_items[1])&&$url_items[1]){
				self::$controller = $url_items[1];
			}

			/**
			 * Hay alguna accion
			 */
			if(isset($url_items[2])&&$url_items[2]){
				self::$action = $url_items[2];
			}

			/**
		 	 * Hay algun id
		 	 */
			if(isset($url_items[3])&&$url_items[3]){
				self::$id = $url_items[3];
			}
            
		} else {

			self::$application = $config->kumbia->default_app;
			self::$controller = $url_items[0];

			/**
			 * Hay alguna accion
			 */
			if(isset($url_items[1])&&$url_items[1]){
				self::$action = $url_items[1];
			}

			/**
		 	 * Hay algun id
		 	 */
			if(isset($url_items[2])&&$url_items[2]){
				self::$id = $url_items[2];
			}
            
		}

		$controllers_dir = $config->{self::$application}->controllers_dir;
		if(!empty(self::$controller)){  

			self::$controller = str_replace("/", "", self::$controller);
			self::$controller = str_replace("\\", "", self::$controller);
			self::$controller = str_replace("..", "", self::$controller);
            
            
			if(is_dir("apps/".$controllers_dir."/".self::$controller)){ 
                 /* dirname( __FILE__ ) */       
				if(self::$application=="default"){   

					self::$module = $url_items[0];

					/**
			 	 	 * Hay algun controlador
			 	 	 */
					if(isset($url_items[1])&&$url_items[1]){
						self::$controller = $url_items[1];
					} else {
						self::$controller = "index";
					}

					/**
			 	 	* Hay alguna accion
			 		 */
					if(isset($url_items[2])&&$url_items[2]){
						self::$action = $url_items[2];
					} else {
						self::$action = "index";
					}

					/**
			 	 	 * Hay algun id?
			 	 	 */
					if(isset($url_items[3])&&$url_items[3]){
						self::$id = $url_items[3];
					} else {
						self::$id = null;
					}

					//En parameters quedan los valores de parametros por URL
					unset($url_items[0], $url_items[1], $url_items[2]);

				} else {       
                         
					self::$module = $url_items[1];

					/**
			 	 	 * Hay algun controlador
			 	 	 */
					if(isset($url_items[2])&&$url_items[2]){
						self::$controller = $url_items[2];
					} else {
						self::$controller = "index";
					}

					/**
			 	 	* Hay alguna accion
			 		 */
					if(isset($url_items[3])&&$url_items[3]){
						self::$action = $url_items[3];
					} else {
						self::$action = "index";
					}

					/**
			 	 	 * Hay algun id?
			 	 	 */
					if(isset($url_items[4])&&$url_items[4]){
						self::$id = $url_items[4];
					} else {
						self::$id = null;
					}

					//En parameters quedan los valores de parametros por URL
					unset($url_items[0], $url_items[1], $url_items[2], $url_items[3]);

				}

			} else {
				//En parameters quedan los valores de parametros por URL
				if($usedefect){				 
				 unset($url_items[0], $url_items[1], $url_items[2]);
                }else{
                 unset($url_items[0], $url_items[1]);
				} 
			}

		}

		self::$all_parameters = $url_items;
		self::$parameters = array_values($url_items);
		if(empty(self::$action)){
			self::$action = "index";
		}

	}

	/**
 	 * Busca en la tabla de entutamiento si hay una ruta en config/routes.ini
 	 * para el controlador, accion, id actual
     *
     */
	static function if_routed(){
		unset($_SESSION['KUMBIA_STATIC_ROUTES']);
		if(!isset($_SESSION['KUMBIA_STATIC_ROUTES'])){
			$routes = Config::read('routes.ini');
			if(isset($routes->routes)){
				foreach($routes->routes as $source => $destination){
					if(count(explode("/", $source))!=3||count(explode("/", $destination))!=3){
						throw new RouterException("Pol&iacute;tica de enrutamiento invalida '$source' a '$destination' en config/routes.ini");
					} else {
						list($controller_source,
						$action_source,
						$id_source) = explode("/", $source);
						list($controller_destination,
						$action_destination,
						$id_destination) = explode("/", $destination);
						if(($controller_source==$controller_destination)&&
						($action_source==$action_destination)&&
						($id_source==$id_destination)){
							throw new KumbiaException("Politica de enrutamiento ciclica de '$source' a '$destination' en config/routes.ini");
						} else {
							$_SESSION['KUMBIA_STATIC_ROUTES'][$controller_source][$action_source][$id_source] =
							array("controller" => $controller_destination,
							"action" => $action_destination,
							"id" => $id_destination);
						}
					}
				}
			}
		}

		$controller = self::$controller;
		$action = self::$action;
		$id = self::$id;

		$new_route = array("controller" => '*', "action" => '*', "id" => '*');
		if(isset($_SESSION['KUMBIA_STATIC_ROUTES']['*'][$action]['*'])){
			$new_route = $_SESSION['KUMBIA_STATIC_ROUTES']['*'][$action]['*'];
		}
		if(isset($_SESSION['KUMBIA_STATIC_ROUTES'][$controller]['*']['*'])){
			$new_route = $_SESSION['KUMBIA_STATIC_ROUTES'][$controller]['*']['*'];
		}
		if(isset($_SESSION['KUMBIA_STATIC_ROUTES'][$controller]['*'][$id])){
			$new_route = $_SESSION['KUMBIA_STATIC_ROUTES'][$controller]['*'][$id];
		}
		if(isset($_SESSION['KUMBIA_STATIC_ROUTES'][$controller][$action]['*'])){
			$new_route = $_SESSION['KUMBIA_STATIC_ROUTES'][$controller][$action]['*'];
		}
		if(isset($_SESSION['KUMBIA_STATIC_ROUTES'][$controller][$action][$id])){
			$new_route = $_SESSION['KUMBIA_STATIC_ROUTES'][$controller][$action][$id];
		}
		if($new_route['controller']!='*'){
			self::$controller = $new_route['controller'];
		}
		if($new_route['action']!='*'){
			self::$action = $new_route['action'];
		}
		if($new_route['id']!='*'){
			self::$id = $new_route['id'];
		}
		return;
	}

	/**
	 * Devuelve el estado del router
	 *
	 * @return boolean
	 */
	public static function get_routed(){
		return self::$routed;
	}

	/**
	 * Devuelve el nombre de la aplicación actual
	 *
	 * @return string
	 */
	public static function get_application(){
		return self::$application;
	}

	/**
	 * Devuelve el nombre del modulo actual
	 *
	 * @return string
	 */
	public static function get_module(){
		return self::$module;
	}



	/**
	 * Devuelve el nombre del controlador actual
	 *
	 * @return string
	 */
	public static function get_controller(){
		return self::$controller;
	}

	/**
	 * Devuelve el nombre del controlador actual
	 *
	 * @return string
	 */
	public static function get_action(){
		return self::$action;
	}

	/**
	 * Devuelve el primer parametro (id)
	 *
	 * @return mixed
	 */
	public static function get_id(){
		return self::$id;
	}

	/**
	 * Devuelve los parametros de la ruta
	 *
	 * @return array
	 */
	public static function get_parameters(){
		return self::$parameters;
	}

	/**
	 * Devuelve los parametros de la ruta
	 *
	 * @return array
	 */
	public static function get_all_parameters(){
		return self::$all_parameters;
	}

	/**
	 * Establece el estado del Router
	 *
	 */
	public static function set_routed($value){
		self::$routed = $value;
	}

	/**
	 * Enruta el controlador actual a otro controlador, &oacute; a otra acción
	 * Ej:
	 * <code>
	 * kumbia::route_to("controller: nombre", ["action: accion"], ["id: id"])
	 * </code>
	 *
	 * @return null
	 */
	static public function route_to(){
		Router::$routed = false;
		$cyclic_routing = false;
		$url = get_params(func_get_args());
		if(isset($url['controller'])){
			if(Router::$controller==$url['controller']){
				$cyclic_routing = true;
			}
			Router::$controller = $url['controller'];
			Router::$all_parameters[0] = $url['controller'];
			Router::$action = "index";
			Router::$routed = true;
		}
		if(isset($url['action'])){
			if(Router::$action==$url['action']){
				$cyclic_routing = true;
			}
			Router::$action = $url['action'];
			Router::$all_parameters[1] = $url['action'];
			Router::$routed = true;
		}
		if(isset($url['id'])){
			if(Router::$id==$url['id']){
				$cyclic_routing = true;
			}
			Router::$id = $url['id'];
			Router::$all_parameters[2] = $url['id'];
			Router::$parameters[0] = $url['id'];
			Router::$routed = true;
		}
		if($cyclic_routing){
			self::$routed_cyclic++;
			if(self::$routed_cyclic>=1000){
				throw new RouterException("Se ha detectado un enrutamiento ciclico. Esto puede causar problemas de estabilidad", 1000);
			}
		} else {
			self::$routed_cyclic = 0;
		}
		return null;
	}

	/**
	 * Nombre de la aplicaci&oacute;n activa actual devuelve "" en caso de
	 * que la aplicación sea default
	 *
	 * @return string
	 */
	static public function get_active_app(){
		return self::$application != "default" ? self::$application : "";
	}
}

?>