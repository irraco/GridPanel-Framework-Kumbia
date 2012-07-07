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
 * @package Filter
 * @copyright Copyright (c) 2007-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @copyright Copyright (c) 2007-2007 Emilio Rafael Silveira Tovar(emilio.rst at gmail.com)
 * @copyright Copyright (c) 2007-2007 Deivinson Tejeda Brito (deivinsontejeda at gmail.com)
 * @license http://www.kumbia.org/license.txt GNU/GPL
 */

/**
 * @see FilterInterface
 */
require "library/kumbia/filter/interface.php";


/**
 * @see FilterException
 */
require "library/kumbia/filter/exception.php";

/**
 * @see AlnumFilter
 */
require "library/kumbia/filter/base_filters/alnum.php";

/**
 * @see AlphaFilter
 */
require "library/kumbia/filter/base_filters/alpha.php";

/**
 * @see DateFilter
 */
require "library/kumbia/filter/base_filters/date.php";

/**
 * @see DigitsFilter
 */
require "library/kumbia/filter/base_filters/digits.php";

/**
 * @see HtmlentitiesFilter
 */
require "library/kumbia/filter/base_filters/htmlentities.php";

/**
 * @see IntFilter
 */
require "library/kumbia/filter/base_filters/int.php";

/**
 * @see IPv4Filter
 */
require "library/kumbia/filter/base_filters/ipv4.php";

/**
 * @see LowerFilter
 */
require "library/kumbia/filter/base_filters/lower.php";

/**
 * @see NumericFilter
 */
require "library/kumbia/filter/base_filters/numeric.php";

/**
 * @see StripspacesFilter
 */
require "library/kumbia/filter/base_filters/stripspace.php";

/**
 * @see StriptagsFilter
 */
require "library/kumbia/filter/base_filters/striptags.php";

/**
 * @see UpperFilter
 */
require "library/kumbia/filter/base_filters/upper.php";

/**
 * Implementaci&oacute;n de Filtros para Kumbia
 *
 * @category Kumbia
 * @package Filter
 * @copyright Copyright (c) 2007-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @copyright Copyright (c) 2007-2007 Emilio Rafael Silveira Tovar(emilio.rst at gmail.com)
 * @copyright Copyright (c) 2007-2007 Deivinson Tejeda Brito (deivinsontejeda at gmail.com)
 * @license http://www.kumbia.org/license.txt GNU/GPL
 */
class Filter extends Object {

	/**
	 * Filtros a que se aplicaran atrav�s del metodo "apply"
	 *
	 * @var array
	 */
	private $filters = array();

	/**
	 * Temporal para los filtros que se aplicaran atrav�s de "apply_filter"
	 *
	 * @var array
	 */
	private $buffer_filters;

	/**
	 * Obtiene los parametros por nombre de los Filtros
	 *
	 * @param array $params
	 * @return array
	 */
	private function get_params($params){
		$data = array();
		$i = 0;
		foreach ($params as $p) {
			if(is_string($p) && preg_match("/([a-z_0-9]+\.?[a-z_0-9]+[:][ ]).+/", $p, $regs)){
				$p = str_replace($regs[1], "", $p);
				$n = str_replace(": ", "", $regs[1]);
				$data[$n] = $p;
			} else $data[$i] = $p;
			$i++;
		}
		return $data;
	}

	/**
	 * Constructor de la clase Filter
	 *
	 */
	public function __construct() {
		$this->buffer_filters = array();
		$this->filters = array();
		$params = func_get_args();
		call_user_func_array(array($this, 'add_filter'), $params);
	}

	/**
	 * Agrega un filtro a la cola de filtros
	 *
	 */
	public function add_filter() {

		$params = $this->get_params(func_get_args());

		/**
		 * Cargo los atributos para los filtros en un array
		 */
		$attributes = array();
		foreach($params as $attr => $value) {
			if(!is_numeric($attr)) {
				$attributes[$attr] = $value;
			}
		}

		/**
		 * Agrego los filtros
		 */
		for($i=0; isset($params[$i]); $i++) {
			//si es un filtro apartir de un objeto
			if(is_object($params[$i])) {
				foreach($attributes as $attr => $value) {
					if(preg_match("/([a-z_0-9]+)\.([a-z_0-9]+)/", $attr, $regs)) {
						$filter = ucfirst(camelize($regs[1])).'Filter';
						if($params[$i] instanceof $filter) {
							$params[$i]->$regs[2] = $value;
						}
					} else {
						$params[$i]->$attr = $value;
					}
				}
				array_push($this->filters, $params[$i]);

			} else { //es un filtro atraves de nombre
				$filter = ucfirst(camelize($params[$i])).'Filter';
				if(class_exists($filter)) {
					$obj =new $filter();
					foreach($attributes as $attr => $value) {
						if(preg_match("/([a-z_0-9]+)\.([a-z_0-9]+)/", $attr, $regs)) {
							$filter = ucfirst(camelize($regs[1])).'Filter';
							if($obj instanceof $filter) {
								$obj->$regs[2] = $value;
							}
						} else {
							$obj->$attr = $value;
						}
					}
					array_push($this->filters, $obj);
				}
			}
		}
	}

	/**
	 * Aplica un filtro
	 *
	 * @param Filter $s
	 */
	public function apply(&$s) {
		if(is_array($s)){
			foreach($s as $key => $value){
				if(is_array($value) || is_object($value)){
					$this->apply($s[$key]);
				} else {
					foreach($this->filters as $f) {
						$s[$key] = $f->execute($value);
					}
				}
			}
		} elseif(is_object($s)) {
			foreach(get_object_vars($s) as $attr => $value){
				if(is_array($value) || is_object($value)){
					$this->apply($s->$attr);
				} else {
					foreach($this->filters as $f) {
						$s->$attr = $f->execute($value);
					}
				}
			}
		} else {
			foreach($this->filters as $f) {
				$s = $f->execute($s);
			}
		}
	}

	/**
	 * Aplica un filtro
	 *
	 * @param array $s
	 */
	public function apply_filter(&$s){
		//para cargar los filtros
		if(func_num_args()>1) {
			$this->buffer_filters = array();
			$params = $this->get_params(func_get_args());

			//cargo los atributos para los filtros en un array
			$attributes = array();
			foreach($params as $attr => $value) {
				if(!is_numeric($attr)) {
					$attributes[$attr] = $value;
				}
			}

			//agrego los filtros (recordar que $params[0] es el parametro a filtrar)
			for($i=1; isset($params[$i]); $i++) {
				if(is_object($params[$i])) {
					foreach($attributes as $attr => $value) {
						if(preg_match("/([a-z_0-9]+)\.([a-z_0-9]+)/", $attr, $regs)) {
							$filter = ucfirst(camelize($regs[1])).'Filter';
							if($params[$i] instanceof $filter) {
								$params[$i]->$regs[2] = $value;
							}
						} else {
							$params[$i]->$attr = $value;
						}
					}
					array_push($this->buffer_filters, $params[$i]);
				} else {
					$filter = ucfirst(camelize($params[$i])).'Filter';
					if(class_exists($filter)) {
						$obj =new $filter();
						foreach($attributes as $attr => $value) {
							if(preg_match("/([a-z_0-9]+)\.([a-z_0-9]+)/", $attr, $regs)) {
								$filter = ucfirst(camelize($regs[1])).'Filter';
								if($obj instanceof $filter) {
									$obj->$regs[2] = $value;
								}
							} else {
								$obj->$attr = $value;
							}
						}
						array_push($this->buffer_filters, $obj);
					} else {
						throw new FilterException("No existe el filtro '$filter'");
					}
				}
			}
		}


		//aplico los filtros
		if(is_array($s)){
			foreach($s as $key => $value){
				if(is_array($value) || is_object($value)){
					$this->apply_filter($s[$key]);
				} else {
					foreach($this->buffer_filters as $f) {
						$s[$key] = $f->execute($value);
					}
				}
			}
		} elseif(is_object($s)) {
			foreach(get_object_vars($s) as $attr => $value){
				if(is_array($value) || is_object($value)){
					$this->apply_filter($s->$attr);
				} else {
					foreach($this->buffer_filters as $f) {
						$s->$attr = $f->execute($value);
					}
				}
			}
		} else {
			foreach($this->buffer_filters as $f) {
				$s = $f->execute($s);
			}
		}

		return $s;
	}

}

?>