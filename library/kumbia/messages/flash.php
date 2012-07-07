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
 * @category   Kumbia
 * @package Messages
 * @copyright  Copyright (c) 2005-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @license    http://www.kumbia.org/license.txt GNU/GPL
 */

/**
 * Flash Es la clase standard para enviar advertencias,
 * informacion y errores a la pantalla
 *
 * @category Kumbia
 * @package Messages
 * @abstract
 * @copyright Copyright (c) 2005-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @license http://www.kumbia.org/license.txt GNU/GPL
 * @access public
 */
abstract class Flash extends Object {

	/**
	 * Visualiza un mensaje de error
	 *
	 * @param string $err
	 */
	public static function error($err, $include_style=false){
		if(isset($_SERVER['SERVER_SOFTWARE'])){
			if($include_style){
				stylesheet_link_tag('style');
			}
    		print '<div id="kumbiaDisplay" class="error_message">'.$err.'</div>'."\n";
	    } else {
			print strip_tags($err)."\n";
		}
	}

	/**
	 * Visualiza una alerta de Error JavaScript
	 *
	 * @param string $err
	 */
	public static function jerror($err){
        	formsPrint("\r\nalert(\"$err\")\r\n");
	}

	/**
	 * Visualiza informacion en pantalla
	 *
	 * @param string $msg
	 */
	public static function notice($msg){
		if(isset($_SERVER['SERVER_SOFTWARE'])){
    			print '<div id="kumbiaDisplay" class="notice_message">'.$msg.'</div>'."\n";
		} else {
			print strip_tags($msg)."\n";
		}
	}

	/**
	 * Visualiza informacion de Suceso en pantalla
	 *
	 * @param string $msg
	 */
	public static function success($msg){
		if(isset($_SERVER['SERVER_SOFTWARE'])){
    			print '<div id="kumbiaDisplay" class="sucess_message">'.$msg.'</div>'."\n";
		} else {
			print strip_tags($msg)."\n";
		}
	}

	/**
	 * Visualiza un mensaje de advertencia en pantalla
	 *
	 * @param string $msg
	 */
	public static function warning($msg){
		if(isset($_SERVER['SERVER_SOFTWARE'])){
    			print '<div id="kumbiaDisplay" class="warning_message">'.$msg.'</div>'."\n";
		} else {
			print strip_tags($msg)."\n";
		}
	}

	/**
	 * Visualiza un Mensaje del interfactiveBuilder
	 *
	 * @param string $msg
	 */
	public static function interactive($msg){
		if(isset($_SERVER['SERVER_SOFTWARE'])){
    			print '<div id="kumbiaDisplay" class="interactive_message">'.$msg.'</div>'."\n";
		} else {
			print strip_tags($msg)."\n";
		}
	}

	/**
	 * Visualiza un Mensaje de Kumbia
	 *
	 * @param string $what
	 */
	public static function kumbia_error($message){
		self::error('<u>KumbiaError:</u> '.$message);
	}
}


?>
