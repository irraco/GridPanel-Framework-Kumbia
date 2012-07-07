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
 * @copyright  Copyright (c) 2005-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @copyright  Copyright (C) 2007-2007 Emilio Rafael Silveira Tovar (emilio.rst@gmail.com)
 * @license http://www.kumbia.org/license.txt GNU/GPL
 */

/**
 * Establece permisos
 */
//error_reporting(E_ALL ^ E_STRICT);

//Load Session
session_register('queryTemp', 'dumps', 'session_data');

/**
 * Cambia el directorio a la raiz
 */
chdir('..');

/**
 * @see Kumbia
 */
require_once "library/kumbia/kumbia.php";

/**
 * @see KumbiaException
 */
require_once "library/kumbia/exception.php";

/**
 * @see Config
 */
require_once "library/kumbia/config/config.php";

/**
 * @see Router
 */
require_once "library/kumbia/router/router.php";

/**
 * @see Plugin
 */
require_once "library/kumbia/plugin/plugin.php";


try {

	if(version_compare(PHP_VERSION, "5.2.0", "<")){
		throw new KumbiaException("Debe tener instalado PHP version 5.20 � superior para utilizar este framework");
	}

	if(!isset($_REQUEST['url'])){
		$_REQUEST['url'] = "";
	}

	Router::rewrite($_REQUEST["url"]);

	/**
     * Kumbia reinicia las variables de aplicaci�n cuando cambiamos
     * entre una aplicaci�n y otra. Init Application define KUMBIA_PATH
     */
	Kumbia::init_application();

	/**
	 * Atender la petici&oacute;n
	 */
	Kumbia::main();

}
catch(KumbiaException $e){

	/**
     * @see Flash
     */
	require_once "library/kumbia/messages/flash.php";

	/**
      * @see Helpers
	  */
	require_once "library/kumbia/helpers/helpers.php";

	ob_start();
	$e->show_message();
	Kumbia::$content = ob_get_contents();
	ob_end_clean();
	xhtml_template('white');

}


?>