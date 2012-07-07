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
 * @subpackage ApplicationController
 * @copyright Copyright (c) 2005-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @license http://www.kumbia.org/license.txt GNU/GPL
 */

/**
 * @see ApplicationControllerException
 */
require_once "library/kumbia/controller/application/exception.php";


/**
 * ApplicationController Es la clase principal para controladores de Kumbia
 *
 * @category Kumbia
 * @package Controller
 * @subpackage ApplicationController
 * @copyright Copyright (c) 2005-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @license http://www.kumbia.org/license.txt GNU/GPL
 *
 */
class ApplicationController extends Controller  {

	/**
	 * Indica el tipo de salida generada por el controlador
	 *
	 * @var string
	 */
	public $response = "";

	/**
	 * Visualiza una vista en el controlador actual
	 *
	 * @param string $view
	 */
	function render($view){

		$views_dir = Kumbia::$active_views_dir;
		$controller = Router::get_controller();

		if(file_exists("$views_dir/$controller/$view.phtml")){
			if(is_array(kumbia::$models)){
				foreach(kumbia::$models as $model_name => $model){
				 	$$model_name = $model;
				}
			}
			foreach($this as $var => $value){
				$$var = $value;
			}
			include "$views_dir/$controller/$view.phtml";
		} else {
			throw new ApplicationControllerException("No existe la Vista &oacute; No se puede encontrar la vista");
		}
	}


	/**
	 * Visualiza un Texto en la Vista Actual
	 *
	 * @param string $text
	 */
	function render_text($text){
		print $text;
	}

	/**
	 * Visualiza una vista parcial en el controlador actual
	 * 
	 * controller: controlador de donde tomara la vista
	 * @param string $partial parcial a mostrar, soporta formato controller/view
	 */
	function render_partial(){
		$params = func_get_args();
		call_user_func_array('render_partial',$params);
	}

	/**
	 * Visualiza una acción ???
	 *
	 * @param string $action
	 */
	function render_action($action){

	}

}


?>