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
 * @package Cache
 * @copyright Copyright (c) 2005-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @copyright Copyright (C) 2007-2007 Roger Jose Padilla Camacho(rogerjose81 at gmail.com)
 * @license http://www.kumbia.org/license.txt GNU/GPL
 */

/**
 * @see CacheException
 */
include "library/kumbia/cache/exception.php";

/**
 * Clase que implementa un componente de cacheo (En Desarrollo)
 *
 * @category Kumbia
 * @package Cache
 * @copyright Copyright (c) 2005-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @license http://www.kumbia.org/license.txt GNU/GPL
 * @access public
 */
class Cache extends Object {

	/**
	 * Nombre de la cache actual
	 *
	 * @var string
	 */
	private $cache_name;

	/**
	 * Nivel de Output Buffering
	 */
	private $ob_level = 0;

	/**
	 * Constructor de la clase
	 *
	 */
	public function __construct(){

	}

	/**
	 * Carga una variable cacheada con nombre $cache_name y tiempo de expiracion $expire_time
	 *
	 * @param string $cache_name
	 * @param integer $expire_time
	 * @return mixed
	 */
	public function load($cache_name, $expire_time=0){

		$controller_name = Router::get_controller();
		$sid = session_id();

		$file = "cache/$sid/$controller_name/$cache_name";
		if(file_exists($file)){
			if($expire_time>0){
				if($expire_time+filemtime($file)<time()){
					return null;
				}
			}
			return unserialize(file_get_contents($file));
		} else {
			return null;
		}

	}

	/**
	 * Guarda la cache con nombre $cache_name y valor $value
	 *
	 * @param string $cache_name
	 * @param mixed $value
	 * @return boolean
	 */
	public function save($cache_name, $value){

		$value = serialize($value);

		$controller_name = Router::get_controller();
		$sid = session_id();

		if(!file_exists("cache/$sid/$controller_name")){
			if(!file_exists("cache/$sid/")){
				if(!@mkdir("cache/$sid/")){
					throw new CacheException("No se puede escribir en el directorio cache/, por favor revise permisos");
				}
			}
			if(!file_exists("cache/$sid/$controller_name")){
				if(!@mkdir("cache/$sid/$controller_name")){
					throw new CacheException("No se puede escribir en el directorio cache/, por favor revise permisos");
				}
			}
		}

		if(!is_writable("cache/$sid/$controller_name")){
			throw new CacheException("No se puede escribir en el directorio cache/, por favor revise permisos");
		}

		if(file_put_contents("cache/$sid/$controller_name/$cache_name", $value)){
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Inicia el cacheo del buffer de salida hasta que se llame a end
	 *
	 * @param string $cache_name
	 * @param integer $expire_time
	 * @return boolean
	 */
	public function start($cache_name, $expire_time = 0){
		ob_start();
		$this->cache_name = $cache_name;
		$this->ob_level = ob_get_level();
		$val = $this->load($cache_name, $expire_time);
		if($val==null){
			return false;
		} else {
			print $val;
			return true;
		}
	}

	/**
	 * Termina el buffer de salida
	 *
	 * @return boolean
	 */
	public function end(){
		if(ob_get_length()>0){
			$ob_cached = ob_get_contents();
			$this->save($this->cache_name, $ob_cached);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Borrar un cache del directorio cache/
	 *
	 * @param string $cache_name
	 * @return string
	 */
	public function clean($cache_name){
		$controller_name = Router::get_controller();
		$sid = session_id();
		return @unlink("cache/$sid/$controller_name/$cache_name");
	}

}

?>