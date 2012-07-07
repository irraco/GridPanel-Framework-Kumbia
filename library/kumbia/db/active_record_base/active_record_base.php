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
 * @package Db
 * @subpackage ActiveRecord
 * @copyright Copyright (c) 2005-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @copyright Copyright (C) 2007-2007 Roger Jose Padilla Camacho (rogerjose81 at gmail.com)
 * @copyright Copyright (C) 2007-2007 Emilio Rafael Silveira Tovar (emilio.rst at gmail.com)
 * @license    http://www.kumbia.org/license.txt GNU/GPL
 */

/**
 * ActiveRecordException
 */
require_once "library/kumbia/db/active_record_base/exception.php";

/**
 * ActiveRecordBase Clase para el Mapeo Objeto Relacional
 *
 * Active Record es un enfoque al problema de acceder a los datos de una
 * base de datos en forma orientada a objetos. Una fila en la
 * tabla de la base de datos (o vista) se envuelve en una clase,
 * de manera que se asocian filas &uacute;nicas de la base de datos
 * con objetos del lenguaje de programaci&oacute;n usado.
 * Cuando se crea uno de estos objetos, se a&ntilde;de una fila a
 * la tabla de la base de datos. Cuando se modifican los atributos del
 * objeto, se actualiza la fila de la base de datos.
 *
 * Propiedades Soportadas:
 * $db = Conexion al Motor de Base de datos
 * $source = Tabla que contiene la tabla que esta siendo mapeada
 * $fields = Listado de Campos de la tabla que han sido mapeados
 * $count = Conteo del ultimo Resultado de un Select
 * $primary_key = Listado de columnas que conforman la llave primaria
 * $non_primary = Listado de columnas que no son llave primaria
 * $not_null = Listado de campos que son not_null
 * $attributes_names = nombres de todos los campos que han sido mapeados
 * $debug = Indica si se deben mostrar los SQL enviados al RDBM en pantalla
 * $logger = Si es diferente de false crea un log utilizando la clase Logger
 * en library/kumbia/logger/logger.php, esta crea un archivo .txt en logs/ con todas las
 * operaciones realizadas en ActiveRecord, si $logger = "nombre", crea un
 * archivo con ese nombre
 *
 * Propiedades sin Soportar:
 * $dynamic_update : La idea es que en un futuro ActiveRecord solo
 * actualize los campos que han cambiado.  (En Desarrollo)
 * $dynamic_insert : Indica si los valores del insert son solo aquellos
 * que sean no nulos. (En Desarrollo)
 * $select_before_update: Exige realizar una sentencia SELECT anterior
 * a la actualizacion UPDATE para comprobar que los datos no hayan sido
 * cambiados (En Desarrollo)
 * $subselect : Permitira crear una entidad ActiveRecord de solo lectura que
 * mapearia los resultados de un select directamente a un Objeto (En Desarrollo)
 *
 * @category Kumbia
 * @package Db
 * @subpackage ActiveRecord
 * @copyright Copyright (c) 2005-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @copyright Copyright (C) 2007-2007 Roger Jose Padilla Camacho(rogerjose81 at gmail.com)
 * @license http://www.kumbia.org/license.txt GNU/GPL
 * @access public
 */
class ActiveRecordBase extends Object {

	//Soportados
	/**
	 * Resource de conexi�n a la base de datos
	 *
	 * @var DbBase
	 */
	public $db;

	/**
	 * Schema donde esta la tabla
	 *
	 * @var string
	 */
	protected $schema;

	/**
	 * Tabla utilizada para realizar el mapeo
	 *
	 * @var string
	 */
	protected $source;

	/**
	 * Numero de resultados generados en la ultima consulta
	 *
	 * @var integer
	 */
	public $count;

	/**
	 * Nombres de los atributos de la entidad
	 *
	 * @var array
	 */
	public $fields = array();

	/**
	 * LLaves primarias de la entidad
	 *
	 * @var array
	 */
	public $primary_key = array();

	/**
	 * Campos que no son llave primaria
	 *
	 * @var array
	 */
	public $non_primary = array();

	/**
	 * Campos que no permiten nulos
	 *
	 * @var array
	 */
	public $not_null = array();

	/**
	 * Nombres de atributos, es lo mismo que fields
	 *
	 * @var array
	 */
	public $attributes_names = array();

	/**
	 * Indica si la clase corresponde a un mapeo de una vista
	 * en la base de datos
	 *
	 * @var boolean
	 */
	public $is_view = false;

	/**
	 * Indica si el modelo esta en modo debug
	 *
	 * @var boolean
	 */
	public $debug = false;

	/**
	 * Indica si se logearan los mensajes generados por la clase
	 *
	 * @var mixed
	 */
	public $logger = false;

	/**
	 * Indica si los datos del modelo deben ser persistidos
	 *
	 * @var boolean
	 */
	public $persistent = false;

	//:Privados
	/**
	 * Campos que les ser� validado el tama�o
	 *
	 * @var array
	 */
	private $validates_length = array();

	/**
	 * Campos que ser�n validados si son numericos
	 *
	 * @var array
	 */
	private $validates_numericality = array();

	/**
	 * Campos que seran validados si son email
	 *
	 * @var array
	 */
	private $validates_email = array();

	/**
	 * Campos que ser�n validados si son Fecha
	 *
	 * @var array
	 */
	private $validates_date = array();

	/**
	 * Campos que seran validados si son unicos
	 *
	 * @var array
	 */
	private $validates_uniqueness = array();

	/**
	 * Campos que deberan tener valores dentro de una lista
	 * establecida
	 *
	 * @var array
	 */
	private $validates_inclusion = array();

	/**
	 * Campos que deberan tener valores por fuera de una lista
	 * establecida
	 *
	 * @var array
	 */
	private $validates_exclusion = array();

	/**
	 * Campos que seran validados contra un formato establecido
	 *
	 * @var array
	 */
	private $validates_format = array();

	/**
	 * Campos que terminan en _in
	 *
	 * @var array
	 */
	private $_in = array();

	/**
	 * Campos que terminan en _at
	 *
	 * @var array
	 */
	private $_at = array();

	/**
	 * Variable para crear una condicion basada en los
	 * valores del where
	 *
	 * @var string
	 */
	private $where_pk;

	/**
	 * Indica si ya se han obtenido los metadatos del Modelo
	 *
	 * @var boolean
	 */
	private $dumped = false;

	/**
	 * Indica si hay bloqueo sobre los warnings cuando una propiedad
	 * del modelo no esta definida-
	 *
	 * @var boolean
	 */
	private $dump_lock = false;

	/**
	 * Tipos de datos de los campos del modelo
	 *
	 * @var array
	 */
	private $data_type = array();

	/**
	 * Relaciones a las cuales tiene una cardinalidad *-1
	 *
	 * @var array
	 */
	private $_has_one = array();

	/**
	 * Relaciones a las cuales tiene una cardinalidad 1-1
	 *
	 * @var array
	 */
	private $_has_many = array();

	/**
	 * Relaciones a las cueles tiene una cardinalidad *-1
	 *
	 * @var array
	 */
	private $_belongs_to = array();

	/**
	 * Relaciones a las cuales tiene una cardinalidad n-n (muchos a muchos)
	 *
	 * @var array
	 */
	private $_has_and_belongs_to_many = array();

	/**
	 * Clases de las cuales es padre la clase actual
	 *
	 * @var array
	 */
	public $parent_of = array();

	/**
	 * Persistance Models Meta-data
	 */
	static public $models;


	/**
	 * Constructor del Modelo
	 *
	 */
	function __construct(){

		if(!$this->source){
			$this->model_name();
		}

		/**
		 * Inicializa el modelo en caso de que exista initialize
		 */
		if(method_exists($this, "initialize")){
			$this->initialize();
		}

		if(func_num_args()){
			$params = func_get_args();
			if(!isset($params[0])||!is_array($params[0])){
				$params = get_params($params);
			}
			$this->dump_result_self($params);
		}

	}

	/**
	 * Obtiene el nombre de la relacion en el RDBM a partir del nombre de la clase
	 *
	 */
	private function model_name(){
		if(!$this->source){
			$this->source = get_class($this);
		}
		if(ereg("([a-z])([A-Z])", $this->source, $reg)){
			$this->source = str_replace($reg[0], $reg[1]."_".strtolower($reg[2]), $this->source);
		}
		$this->source = strtolower($this->source);
	}

	/**
	 * Establece publicamente el $source de la tabla
	 *
	 * @param string $source
	 */
	public function set_source($source){
		$this->source = $source;
	}

	/**
	 * Devuelve el source actual
	 *
	 * @return string
	 */
	public function get_source(){
		return $this->source;
	}


	/**
	 * Pregunta si el ActiveRecord ya ha consultado la informacion de metadatos
	 * de la base de datos o del registro persistente
	 *
	 * @return boolean
	 */
	public function is_dumped(){
		return $this->dumped;
	}

	/**
	 * Valida que los valores que sean leidos del objeto ActiveRecord esten definidos
	 * previamente o sean atributos de la entidad
	 *
	 * @param string $property
	 */
	function __get($property){
		$this->connect();  
		if(!$this->dump_lock){
			if(!isset($this->$property)){
				$mmodel = str_replace("_", " ", $property);
				$mmodel = ucwords($mmodel);
				$mmodel = str_replace(" ", "", $mmodel);
				if(isset(kumbia::$models[$mmodel])){
					if(in_array($property, $this->_belongs_to)){
						$assoc_obj = kumbia::$models[$mmodel]->find_first(addslashes($this->{$property."_id"}));
						return $assoc_obj;
					} else {     
						if(in_array($property, $this->_has_and_belongs_to_many)){
							$source = $this->source;
							if($source>$property){
								$table = $property."_".$source;
							} else {
								$table = $source."_".$property;
							}
							$assoc_objs = kumbia::$models[$mmodel]->find_all_by_sql("SELECT $property.* FROM $property, $table, $source
									WHERE $table.{$source}_id = '".addslashes($this->id)."'
									AND $table.{$property}_id = $property.id
									AND $table.{$source}_id = $source.id
									ORDER BY $property.id");
							return $assoc_objs;
						} else {
							ActiveRecordException::display_warning("Propiedad no definida", "Propiedad indefinida '$property' leida de el modelo '$this->source'", $this->source);
							return null;
						}
					}
				} else {
					ActiveRecordException::display_warning("Propiedad no definida", "Propiedad indefinida '$property' leida de el modelo '$this->source'", $this->source);
					return null;
				}
			}
		}
		return $this->$property;
	}

	/**
	 * Valida que los valores que sean asignados al objeto ActiveRecord esten definidos
	 * o sean atributos de la entidad
	 *
	 * @param string $property
	 * @param mixed $value
	 */
	function __set($property, $value){
		$this->connect();
		if(!$this->dump_lock){
			if(!isset($this->$property)){
				$mmodel = str_replace("_", " ", $property);
				$mmodel = ucwords($mmodel);
				$mmodel = str_replace(" ", "", $mmodel);
				if(isset(kumbia::$models[$mmodel])){
					if(in_array($property, $this->_has_one)||in_array($property, $this->_belongs_to)){
						if($value->save()){
							$this->{$property."_id"} = $value->id;
						} else {
							ActiveRecordException::display_warning("Propiedad no definida", "Propiedad indefinida '$property' asignada en el modelo '$this->source' ($value)", $this->source);
						}
					} else {
						ActiveRecordException::display_warning("Propiedad no definida", "Propiedad indefinida '$property' asignada en el modelo '$this->source' ($value)", $this->source);
					}
				}
			}
			if($property=="source"){
				$value = ActiveRecord::sql_item_sanizite($value);
			}
		}
		$this->$property = $value;
	}

	/**
	 * Devuelve un valor o un listado dependiendo del tipo de Relaci&oacute;n
	 *
	 */
	public function __call($method, $args=array()){

		$has_relation = false;

		if(substr($method, 0, 8)=="find_by_"){
			$field = substr($method, 8);
			ActiveRecord::sql_item_sanizite($field);
			if(isset($args[0])){
				$arg = array("conditions: $field = '{$args[0]}'");
			} else {
				$arg = array();
			}
			return call_user_func_array(array($this, "find_first"), array_merge($arg, $args));
		}

		if(substr($method, 0, 9)=="count_by_"){
			$field = substr($method, 9);
			ActiveRecord::sql_item_sanizite($field);
			if(isset($args[0])){
				$arg = array("conditions: $field = '{$args[0]}'");
			} else {
				$arg = array();
			}
			return call_user_func_array(array($this, "count"), array_merge($arg, $args));
		}

		if(substr($method, 0, 12)=="find_all_by_"){
			$field = substr($method, 12);
			ActiveRecord::sql_item_sanizite($field);
			if(isset($args[0])){
				$arg = array("conditions: $field = '{$args[0]}'");
			} else {
				$arg = array();
			}
			return call_user_func_array(array($this, "find"), array_merge($arg, $args));
		}

		$model = ereg_replace("^get", "", $method);
		$mmodel = strtolower($model);
		if(in_array($mmodel, $this->_belongs_to)){
			$has_relation = true;
			if(kumbia::$models[$model]){
				$named_args = get_params($args);
				return kumbia::$models[$model]->find_first($this->{$mmodel."_id"});
			}
		}
		if(in_array($mmodel, $this->_has_many)){
			$has_relation = true;
			if(kumbia::$models[$model]){
				if($this->id){
					return kumbia::$models[$model]->find($this->source."_id={$this->id}");
				} else {
					return array();
				}
			}
		}
		if(in_array($mmodel, $this->_has_one)){
			$has_relation = true;
			if(kumbia::$models[$model]){
				if($this->id){
					return kumbia::$models[$model]->find_first($this->source."_id={$this->id}");
				} else {
					return array();
				}
			}
		}
		try {
			if(method_exists($this, $method)){
				call_user_func_array(array($this, $method), $args);
			} else {
				if($has_relation){
					throw new ActiveRecordException("No existe el modelo '$model' para relacionar con ActiveRecord::{$this->source}");
				} else {
					throw new ActiveRecordException("No existe el m&eacute;todo '$method' en ActiveRecord::".get_class($this));
				}
			}
		}
		catch(Exception $e){
			$this->exceptions($e);
		}
		return $this->$method($args);

	}

	/**
	 * Se conecta a la base de datos y descarga los meta-datos si es necesario
	 *
	 * @param boolean $new_connection
	 */
	private function connect($new_connection=false){
		if(!is_object($this->db)||$new_connection){
			$this->db = db::raw_connect();
		}
		$this->db->debug = $this->debug;
		$this->db->logger = $this->logger;
		$this->dump();
	}

	/**
	 * Cargar los metadatos de la tabla
	 *
	 */
	public function dump_model(){
		$this->connect();
	}

	/**
	 * Verifica si la tabla definida en $this->source existe
	 * en la base de datos y la vuelca en dump_info
	 *
	 * @return boolean
	 */
	protected function dump(){
		if($this->dumped){
			return false;
		}
		if($this->source) {
			$this->source = str_replace(";", "", strtolower($this->source));
		} else {
			$this->model_name();
			if(!$this->source){
				return false;
			}
		}
		$table = $this->source;
		$schema = $this->schema;
		if(!count(ActiveRecord::get_meta_data($this->source))){
			$this->dumped = true;
			if($this->db->table_exists($table, $schema)){
				$this->dump_info($table, $schema);
			} else {
				throw new ActiveRecordException("No existe la tabla '$table' en la base de datos");
				return false;
			}
			if(!count($this->primary_key)){
				if(!$this->is_view){
					throw new ActiveRecordException("No se ha definido una llave primaria para la tabla '$table' esto imposibilita crear el ActiveRecord para esta entidad");
					return false;
				}
			}
		} else {
			if(!$this->is_dumped()){
				$this->dumped = true;
				$this->dump_info($table, $schema);
			}
		}
		return true;
	}

	/**
	 * Vuelca la informaci&oacute;n de la tabla $table en la base de datos
	 * para armar los atributos y meta-data del ActiveRecord
	 *
	 * @param string $table
	 * @return boolean
	 */
	private function dump_info($table, $schema=''){
		$this->dump_lock = true;
		if(!count(ActiveRecord::get_meta_data($table))){
			$meta_data = $this->db->describe_table($table, $schema);
			if($meta_data){
				ActiveRecord::set_meta_data($table, $meta_data);
			}
		}
		foreach(ActiveRecord::get_meta_data($table) as $field){
			if(!isset($this->$field['Field'])){
				$this->$field['Field'] = "";
			}
			$this->fields[] = $field['Field'];
			if($field['Key']=='PRI'){
				$this->primary_key[] = $field['Field'];
			} else $this->non_primary[] = $field['Field'];
			if($field['Null']=='NO'){
				$this->not_null[] = $field['Field'];
			}
			if($field['Type']){
				$this->data_type[$field['Field']] = strtolower($field['Type']);
			}
			if(substr($field['Field'], strlen($field['Field'])-3, 3)=='_at'){
				$this->_at[] = $field['Field'];
			}
			if(substr($field['Field'], strlen($field['Field'])-3, 3)=='_in'){
				$this->_in[] = $field['Field'];
			}
		}
		$this->attributes_names = $this->fields;
		$this->dump_lock = false;
		return true;
	}

	/**
	 * Commit a Transaction
	 *
	 * @return success
	 */
	public function commit(){
		$this->connect();
		return $this->db->commit();
	}

	/**
	 * Rollback a Transaction
	 *
	 * @return success
	 */
	public function rollback(){
		$this->connect();
		return $this->db->rollback();
	}

	/**
	 * Start a transaction in RDBM
	 *
	 * @return success
	 */
	public function begin(){
		$this->connect(true);
		return $this->db->begin();
	}

	/**
	 * Find all records in this table using a SQL Statement
	 *
	 * @param string $sqlQuery
	 * @return ActiveRecord Cursor
	 */
	public function find_all_by_sql($sqlQuery){
		$this->connect();
		$results = array();
		foreach($this->db->fetch_all($sqlQuery) as $result){
			$results[] = $this->dump_result($result);
		}
		return $results;
	}
	/**
	 * Find a record in this table using a SQL Statement
	 *
	 * @param string $sqlQuery
	 * @return ActiveRecord Cursor
	 */
	public function find_by_sql($sqlQuery){
		$this->connect();
		$row = $this->db->fetch_one($sqlQuery);
		if($row!==false){
			$this->dump_result_self($row);
			return $this->dump_result($row);
		} else {
			return false;
		}
	}

	/**
	 * Execute a SQL Statement directly
	 *
	 * @param string $sqlQuery
	 * @return int affected
	 */
	public function sql($sqlQuery){
		$this->connect();
		return $this->db->query($sqlQuery);
	}

	/**
	 * Return Fist Record
	 *
	 * @param mixed $what
	 * @param boolean $debug
	 * @return ActiveRecord Cursor
	 */
	public function find_first($what=''){
		$this->connect();
		$params = get_params(func_get_args());
		$select = "SELECT ";
		if(isset($params['columns'])){
			$select.= ActiveRecord::sql_sanizite($params['columns']);
		} else {
			$select.= join(",", $this->fields);
		}
		if($this->schema){
			$select.= " FROM {$this->schema}.{$this->source}";
		} else {
			$select.= " FROM {$this->source}";
		}
		
		$params['limit'] = 1;
		$select.= $this->convert_params_to_sql($params);
		$resp = false;
		try {

			$result = $this->db->fetch_one($select);
			if($result){
				$this->dump_result_self($result);
				$resp = $this->dump_result($result);
			}
		}
		catch(Exception $e){
			$this->exceptions($e);
		}
		return $resp;
	}

	/**
	 * Find data on Relational Map table
	 *
	 * @param string $what
	 * @return ActiveRecord Cursor
	 */
	public function find($what=''){
		$this->connect();
		$what = get_params(func_get_args());
		$select = "SELECT ";
		if(isset($what['columns'])){
			$select.= $what['columns'] ? ActiveRecord::sql_sanizite($what['columns']) : join(",", $this->fields);
		} else {
			$select.= join(",", $this->fields);
		}
		if($this->schema){
			$select.= " FROM {$this->schema}.{$this->source}";
		} else {
			$select.= " FROM {$this->source}";
		}
		$return = "n";
		if(isset($what['conditions'])&&$what['conditions']) {
			$select.= " WHERE {$what['conditions']} ";
		} else {
			if(!isset($this->primary_key[0])&&$this->is_view){
				$this->primary_key[0] = "id";
				ActiveRecord::sql_item_sanizite($this->primary_key[0]);
			}
			if(isset($what[0])){
				if(is_numeric($what[0])){
					$what['conditions'] = "{$this->primary_key[0]} = {$this->db->add_quotes($what[0])}";
					$return = "1";
				} else {
					if($what[0]==''){
						$what['conditions'] = "{$this->primary_key[0]} = ''";
					} else {
						$what['conditions'] = $what[0];
					}
					$return = "n";
				}
			}
			if(isset($what['conditions'])){
				$select.= " WHERE {$what['conditions']}";
			}
		}

		if(isset($what['order'])&&$what['order']) {
			ActiveRecord::sql_sanizite($what['order']);
			$select.= " ORDER BY ".$what['order'];
		}
		
		$limit_args = array($select);
		if(isset($what['limit'])) {
			array_push($limit_args, "limit: $what[limit]");
		}
		if(isset($what['offset'])) {
			array_push($limit_args, "offset: $what[offset]");
		}
		if(count($limit_args)>1) {
			$select = call_user_func_array(array($this,'limit'), $limit_args);
		}
		
		$results = array();
		$all_results = $this->db->in_query($select);
		foreach($all_results AS $result){
			$results[] = $this->dump_result($result);
		}
		$this->count = sizeof($results);
		
		if($return=="1"){
			if(!isset($results[0])){
				$this->count = 0;
				return false;
			} else {
				$this->dump_result_self($all_results[0]);
				$this->count = 1;
				return $results[0];
			}
		} else {  	
			return $results;
		}
	}

	/*
	* Arma una consulta SQL con el parametro $what, as�:
	* 	$what = get_params(func_get_args());
	* 	$select = "SELECT * FROM Clientes";
	*	$select.= $this->convert_params_to_sql($what);

	* @param string $what
	* @return string
	*/
	public function convert_params_to_sql($what = ''){
		$select = "";
		if(is_array($what)){
			if(isset($what['conditions'])&&$what['conditions']){
				$select.= " WHERE {$what["conditions"]} ";
			} else {
				if(!isset($this->primary_key[0]) && (isset($this->id) || $this->is_view)){
					$this->primary_key[0] = "id";
				}
				ActiveRecord::sql_item_sanizite($this->primary_key[0]);
				if(isset($what[0])){
					if(is_numeric($what[0])){
						$what['conditions'] = "{$this->primary_key[0]} = {$this->db->add_quotes($what[0])}";
					} else {
						if($what[0]==''){
							$what['conditions'] = "{$this->primary_key[0]} = ''";
						} else {
							$what['conditions'] = $what[0];
						}
					}
				}
				if(isset($what['conditions'])){
					$select.= " WHERE {$what['conditions']}";
				}
			}

			if(isset($what['order'])&&$what['order']) {
				ActiveRecord::sql_sanizite($what['order']);
				$select.=" ORDER BY {$what['order']}";
			} else {
				$select.=" ORDER BY 1";
			}
			
			$limit_args = array($select);
			if(isset($what['limit'])) {
				array_push($limit_args, "limit: $what[limit]");
			}
			if(isset($what['offset'])) {
				array_push($limit_args, "offset: $what[offset]");
			}
			if(count($limit_args)>1) {
				$select = call_user_func_array(array($this,'limit'), $limit_args);
			}
			
		} else {
			if(strlen($what)){
				if(is_numeric($what)){
					$select.= "WHERE {$this->primary_key[0]} = '$what'";
				} else {
					$select.= "WHERE $what";
				}
			}
		}
		return $select;
	}

	/*
	* Devuelve una clausula LIMIT adecuada al RDBMS empleado
	*
	* limit: maxima cantidad de elementos a mostrar
	* offset: desde que elemento se comienza a mostrar
	*
	* @param string $sql consulta select 
	* @return String clausula LIMIT adecuada al RDBMS empleado
	*/
	public function limit($sql){
		$args = func_get_args();
		return call_user_func_array(array($this->db, 'limit'), $args);
	}


	public function distinct($what=''){
		$this->connect();
		if(func_num_args()>1){
			$what = get_params(func_get_args());
		}
		if($this->schema){
			$table = $this->schema.".".$this->source;
		} else {
			$table = $this->source;
		}
		if(is_array($what)){
			if(!isset($what['column'])){
				$what['column'] = $what['0'];
			} else {
				if(!$what['column']) {
					$what['column'] = $what['0'];
				}
			}
			$select = "SELECT DISTINCT {$what["column"]} FROM $table " ;
			if(isset($what["conditions"])&&$what["conditions"]) {
				$select.=" WHERE {$what["conditions"]} ";
			}
			if(isset($what["order"])&&$what["order"]) {
				$select.=" ORDER BY {$what["order"]} ";
			} else {
				$select.=" ORDER BY 1 ";
			}

			$limit_args = array($select);
			if(isset($what['limit'])) {
				array_push($limit_args, "limit: $what[limit]");
			}
			if(isset($what['offset'])) {
				array_push($limit_args, "offset: $what[offset]");
			}
			if(count($limit_args)>1) {
				$select = call_user_func_array(array($this,'limit'), $limit_args);
			}
		} else {
			if($what!==''){
				$select = "SELECT DISTINCT $what FROM $table ORDER BY 1";
			}
		}
		$results = array();
		foreach($this->db->fetch_all($select) as $result){
			$results[] = $result[0];
		}
		return $results;
	}

	/**
	 * Ejecuta una consulta en el RDBM directamente
	 *
	 * @param string $sql
	 * @return resource
	 */
	public function select_one($sql){
		$this->connect();
		if(substr(ltrim($sql), 0, 7)!="SELECT") {
			$sql = "SELECT ".$sql;
		}
		$num = $this->db->fetch_one($sql);
		return $num[0];
	}

	static public function static_select_one($sql){
		$db = db::raw_connect();
		if(substr(ltrim($sql), 0, 7)!="SELECT") {
			$sql = "SELECT ".$sql;
		}
		$num = $db->fetch_one($sql);
		return $num[0];
	}

	/**
	 * Realiza un conteo de filas
	 *
	 * @param string $what
	 * @return integer
	 */
	public function count($what=''){
		$this->connect();
		if(func_num_args()>1){
			$what = get_params(func_get_args());
		}
		if($this->schema){
			$table = "{$this->schema}.{$this->source}";
		} else {
			$table = $this->source;
		}
		if(is_array($what)){
			if(isset($what['distinct'])&&$what['distinct']) {
				$select = "SELECT COUNT(DISTINCT {$what['distinct']}) FROM $table " ;
			} else {
				$select = "SELECT COUNT(*) FROM $table " ;
			}
			if(isset($what["conditions"])&&$what["conditions"]) {
				$select.=" WHERE {$what["conditions"]} ";
			}
			if(isset($what["order"])&&$what["order"]) {
				$select.=" ORDER BY {$what["order"]} ";
			}

		} else {
			if($what!==''){
				if(is_numeric($what)){
					if($this->is_view&&(!isset($this->primary_key[0])||!$this->primary_key[0])){
						$this->primary_key[0] = 'id';
					}
					ActiveRecord::sql_item_sanizite($this->primary_key[0]);
					$select = "SELECT COUNT(*) FROM $table WHERE {$this->primary_key[0]} = '$what' ORDER BY 1";
				} else {
					$select = "SELECT COUNT(*) FROM $table WHERE $what ORDER BY 1";
				}
			} else {
				$select = "SELECT COUNT(*) FROM $table ORDER BY 1";
			}
		}
		$num = $this->db->fetch_one($select);
		return $num[0];
	}

	/**
	 * Realiza un promedio sobre el campo $what
	 *
	 * @param string $what
	 * @return array
	 */
	public function average($what=''){
		$this->connect();
		$what = get_params(func_get_args());
		if(isset($what['column'])) {
			if(!$what['column']){
				$what['column'] = $what[0];
			}
		} else {
			$what['column'] = $what[0];
		}
		ActiveRecord::sql_item_sanizite($what['column']);
		if($this->schema){
			$table = "{$this->schema}.{$this->source}";
		} else {
			$table = $this->source;
		}
		$select = "SELECT AVG({$what['column']}) FROM $table " ;
		if(isset($what["conditions"])&&$what["conditions"]) {
			$select.=" WHERE {$what["conditions"]} ";
		}
		if(isset($what["order"])&&$what["order"]) {
			ActiveRecord::sql_item_sanizite($what['order']);
			$select.=" ORDER BY {$what["order"]} ";
		} else {
			$select.=" ORDER BY 1 ";
		}
		
		$num = $this->db->fetch_one($select);
		return $num[0];
	}

	public function sum($what=''){
		$this->connect();
		$what = get_params(func_get_args());
		if(isset($what['column'])) {
			if(!$what['column']){
				$what['column'] = $what[0];
			}
		} else {
			$what['column'] = $what[0];
		}
		ActiveRecord::sql_item_sanizite($what['column']);
		if($this->schema){
			$table = "{$this->schema}.{$this->source}";
		} else {
			$table = $this->source;
		}
		$select = "SELECT SUM({$what['column']}) FROM $table " ;
		if(isset($what["conditions"])&&$what["conditions"]) {
			$select.=" WHERE {$what["conditions"]} ";
		}
		if(isset($what["order"])&&$what["order"]) {
			ActiveRecord::sql_item_sanizite($what['order']);
			$select.=" ORDER BY {$what["order"]} ";
		} else {
			$select.=" ORDER BY 1 ";
		}

		$num = $this->db->fetch_one($select);
		return $num[0];
	}

	/**
	 * Busca el valor maximo para el campo $what
	 *
	 * @param string $what
	 * @return mixed
	 */
	public function maximum($what=''){
		$this->connect();
		$what = get_params(func_get_args());
		if(isset($what['column'])) {
			if(!$what['column']){
				$what['column'] = $what[0];
			}
		} else {
			$what['column'] = $what[0];
		}
		ActiveRecord::sql_item_sanizite($what['column']);
		if($this->schema){
			$table = "{$this->schema}.{$this->source}";
		} else {
			$table = $this->source;
		}
		$select = "SELECT MAX({$what['column']}) FROM $table " ;
		if(isset($what["conditions"])&&$what["conditions"]) {
			$select.=" WHERE {$what["conditions"]} ";
		}
		if(isset($what["order"])&&$what["order"]) {
			ActiveRecord::sql_item_sanizite($what['order']);
			$select.=" ORDER BY {$what["order"]} ";
		} else {
			$select.=" ORDER BY 1 ";
		}

		$num = $this->db->fetch_one($select);
		return $num[0];
	}

	/**
	 * Busca el valor minimo para el campo $what
	 *
	 * @param string $what
	 * @return mixed
	 */
	public function minimum($what=''){
		$this->connect();
		$what = get_params(func_get_args());
		if(isset($what['column'])) {
			if(!$what['column']){
				$what['column'] = $what[0];
			}
		} else {
			$what['column'] = $what[0];
		}
		ActiveRecord::sql_item_sanizite($what['column']);
		if($this->schema){
			$table = "{$this->schema}.{$this->source}";
		} else {
			$table = $this->source;
		}
		$select = "SELECT MIN({$what['column']}) FROM $table " ;
		if(isset($what["conditions"])&&$what["conditions"]) {
			$select.=" WHERE {$what["conditions"]} ";
		}
		if(isset($what["order"])&&$what["order"]) {
			ActiveRecord::sql_item_sanizite($what['order']);
			$select.=" ORDER BY {$what["order"]} ";
		} else {
			$select.=" ORDER BY 1 ";
		}

		$num = $this->db->fetch_one($select);
		return $num[0];
	}

	/**
	 * Realiza un conteo directo mediante $sql
	 *
	 * @param string $sqlQuery
	 * @return mixed
	 */
	public function count_by_sql($sqlQuery){
		$this->connect();
		$num = $this->db->fetch_one($sqlQuery);
		return $num[0];
	}

	/**
	 * Iguala los valores de un resultado de la base de datos
	 * en un nuevo objeto con sus correspondientes
	 * atributos de la clase
	 *
	 * @param array $result
	 * @return ActiveRecord
	 */
	function dump_result($result){
		$this->connect();
		$obj = clone $this;
		/**
		 * Consulta si la clase es padre de otra y crea el tipo de dato correcto
		 */
		if(isset($result['type'])){
			if(in_array($result['type'], $this->parent_of)){
				if(class_exists($result['type'])){
					$obj = new $result['type'];
					unset($result['type']);
				}
			}
		}
		$this->dump_lock = true;
		if(is_array($result)){
			foreach($result as $k => $r){
				if(!is_numeric($k)){
					$obj->$k = stripslashes($r);
				}
			}
		}
		$this->dump_lock = false;
		return $obj;
	}

	/**
	 * Iguala los valores de un resultado de la base de datos
	 * con sus correspondientes atributos de la clase
	 *
	 * @param array $result
	 * @return ActiveRecord
	 */
	public function dump_result_self($result){
		$this->connect();
		$this->dump_lock = true;
		if(is_array($result)){
			foreach($result as $k => $r){
				if(!is_numeric($k)){
					$this->$k = stripslashes($r);
				}
			}
		}
		$this->dump_lock = false;
	}

	/**
	 * Create a new Row using values from $_REQUEST
	 *
	 * @return boolean success
	 */
	public function create_from_request(){
		$this->connect();
		$values = array();
		foreach($_REQUEST as $k => $r){
			if(isset($this->$k)) {
				$values[$k] = $r;
			}
		}
		if(count($values)){
			return $this->create($values);
		} else{
			return false;
		}
	}

	/**
	 * Saves a new Row using values from $_REQUEST
	 *
	 * @return boolean success
	 */
	public function save_from_request(){
		$this->connect();
		foreach($_REQUEST as $k => $r){
			if(isset($this->$k)) {
				$this->$k = $r;
			}
		}
		return $this->save();
	}

	/**
	 * Creates a new Row in map table
	 *
	 * @param mixed $values
	 * @return success boolean
	 */
	public function create($values=''){
		$this->connect();
		if(func_num_args()>1){
			$values = get_params(func_get_args());
		}
		if(is_array($values)){
			if(is_array($values[0])){
				foreach($values as $v){
					foreach($this->fields as $f){
						$this->$f = "";
					}
					foreach($v as $k => $r){
						if(!is_numeric($k)){
							if(isset($this->$k)){
								$this->$k = $r;
							} else {
								throw new ActiveRecordException("No existe el Atributo '$k' en la entidad '{$this->source}' al ejecutar la inserci&oacute;n");
								return false;
							}
						}
					}
					if($this->primary_key[0]=='id'){
						$this->id = null;
					}
					return $this->save();
				}
			} else {
				foreach($this->fields as $f){
					$this->$f = "";
				}
				foreach($values as $k => $r){
					if(!is_numeric($k)){
						if(isset($this->$k)){
							$this->$k = $r;
						} else {
							throw new ActiveRecordException("No existe el Atributo '$k' en la entidad '{$this->source}' al ejecutar la inserci�n");
							return false;
						}
					}
				}
				if($this->primary_key[0]=='id'){
					$this->id = null;
				}
				return $this->save();
			}
		} else {
			if($values!==''){
				Flash::warning("Par&aacute;metro incompatible en acci&oacute;n 'create'. No se pudo crear ningun registro");
				return false;
			} else {
				if($this->primary_key[0]=='id'){
					$this->id = null;
				}
				return $this->save();
			}
		}
		return true;
	}

	/**
	 * Consulta si un determinado registro existe o no
	 * en la entidad de la base de datos
	 *
	 * @return boolean
	 */
	function exists($where_pk=''){
		$this->connect();
		if($this->schema){
			$table = "{$this->schema}.{$this->source}";
		} else {
			$table = $this->source;
		}
		if(!$where_pk){
			$where_pk = array();
			foreach($this->primary_key as $key){
				if($this->$key){
					$where_pk[] = " $key = '{$this->$key}'";
				}
			}
			if(count($where_pk)){
				$this->where_pk = join(" AND ", $where_pk);
			} else {
				return 0;
			}
			$query = "SELECT COUNT(*) FROM $table WHERE {$this->where_pk}";
		} else {
			if(is_numeric($where_pk)){
				$query = "SELECT(*) FROM $table WHERE id = '$where_pk'";
			} else {
				$query = "SELECT COUNT(*) FROM $table WHERE $where_pk";
			}
		}
		$num = $this->db->fetch_one($query);
		return $num[0];
	}

	/**
	 * Saves Information on the ActiveRecord Properties
	 *
	 * @return boolean success
	 */
	public function save(){

		$this->connect();

		$ex = $this->exists();

		if($this->schema){
			$table = $this->schema.".".$this->source;
		} else {
			$table = $this->source;
		}

		#Run Validation Callbacks Before
		if(method_exists($this, 'before_validation')){
			if($this->before_validation()=='cancel') {
				return false;
			}
		} else {
			if(isset($this->before_validation)){
				$method = $this->before_validation;
				if($this->$method()=='cancel') {
					return false;
				}
			}
		}
		if(!$ex&&method_exists($this, "before_validation_on_create")){
			if($this->before_validation_on_create()=='cancel') {
				return false;
			}
		} else {
			if(isset($this->before_validation_on_create)){
				$method = $this->before_validation_on_create;
				if($this->$method()=='cancel') {
					return false;
				}
			}
		}
		if($ex&&method_exists($this, "before_validation_on_update")){
			if($this->before_validation_on_update()=='cancel') {
				return false;
			}
		} else {
			if(isset($this->before_validation_on_update)){
				$method = $this->before_validation_on_update;
				if($this->$method()=='cancel') {
					return false;
				}
			}
		}

		if(is_array($this->not_null)){
			$e = false;
			for($i=0;$i<=count($this->not_null)-1;$i++){
				$f = $this->not_null[$i];
				if(is_null($this->$f)||$this->$f===''){
					if(!$ex&&$f=='id'){
						continue;
					}
					if(!$ex&&in_array($f, $this->_at)){
						continue;
					}
					if($ex&&in_array($f, $this->_in)){
						continue;
					}
					Flash::error("Error: El campo $f no puede ser nulo en $this->source");
					$e = true;
				}
			}
			if($e){
				return false;
			}
		}
		if(is_array($this->validates_length)){
			$e = false;
			foreach($this->validates_length as $f => $opt){
				if($opt['in']){
					$in = explode(":", $opt['in']);
					if(is_numeric($in[0])&&is_numeric($in[1])){
						$opt['minimum'] = $in[0];
						$opt['maximum'] = $in[1];
					}
				}
				if(is_numeric($opt['minimum'])){
					$n = $opt['minimum'];
					if(strlen($this->$f)<$n){
						if(!$opt['too_short']){
							Flash::error("Error: El campo $f debe tener como m&iacute;nimo $n caracteres");
							$e = true;
						} else {
							Flash::error($opt['too_short']);
							$e = true;
						}
					}
				}
				if(is_numeric($opt['maximum'])){
					$n = $opt['maximum'];
					if(strlen($this->$f)>$n){
						if(!$opt['too_long']){
							Flash::error("Error: El campo $f debe tener como m&aacute;ximo $n caracteres");
							$e = true;
						} else {
							Flash::error($opt['too_long']);
							$e = true;
						}
					}
				}
			}
			if($e){
				return false;
			}
			unset($f);
			unset($n);
			unset($in);
		}

		# Validates Inclusion
		if(count($this->validates_inclusion)){
			$e = false;
			if(is_array($this->validates_inclusion)){
				foreach($this->validates_inclusion as $finc => $list){
					if(!is_array($list)){
						Flash::error(ucwords($finc)." debe tener un valor entre ($list)");
						$e = true;
					} else {
						if(!in_array($this->$finc, $list)){
							Flash::error(ucwords($finc)." debe tener un valor entre (".join(",", $list).")");
							$e = true;
						}
					}
				}
			}
			if($e){
				return false;
			}
		}

		# Validates Exclusion
		if(count($this->validates_exclusion)){
			$e = false;
			if(is_array($this->validates_exclusion)){
				foreach($this->validates_exclusion as $finc => $list){
					if(!is_array($list)){
						Flash::error(ucwords($finc)." no debe tener un valor entre ($list)");
						$e = true;
					} else {
						if(in_array($this->$finc, $list)){
							Flash::error(ucwords($finc)." no debe tener un valor entre (".join(",", $list).")");
							$e = true;
						}
					}
				}
			}
			if($e){
				return false;
			}
		}

		# Validates Numericality
		if(count($this->validates_numericality)){
			$e = false;
			if(is_array($this->validates_numericality)){
				foreach($this->validates_numericality as $fnum){
					if(!is_numeric($this->$fnum)){
						Flash::error(ucwords($fnum)." debe tener un valor num&eacute;rico");
						$e = true;
					}
				}
			}
			if($e){
				return false;
			}
		}

		# Validates format
		if(count($this->validates_format)){
			$e = false;
			if(is_array($this->validates_format)){
				foreach($this->validates_format as $fkey => $format){
					if($this->$fkey!==''&&$this->$fkey!==null){
						if(!ereg($format, $this->$fkey)){
							Flash::error("Formato erroneo para ".ucwords($fkey));
							$e = true;
						}
					} else {
						Flash::error("Formato erroneo para ".ucwords($fkey));
						$e = true;
					}
				}
			}
			if($e){
				return false;
			}
		}

		# Validates date
		if(count($this->validates_date)){
			$e = false;
			if(is_array($this->validates_date)){
				foreach($this->validates_date as $fkey){
					if(!ereg("^[0-9]{4}[-/](0[1-9]|1[12])[-/](0[1-9]|[12][0-9]|3[01])$", $this->$fkey, $regs)){
						Flash::error("Formato de fecha ({$this->$fkey}) erroneo para ".ucwords($fkey));
						$e = true;
					}
				}
			}
			if($e){
				return false;
			}
		}

		# Validates e-mail
		if(count($this->validates_email)){
			$e = false;
			if(is_array($this->validates_email)){
				foreach($this->validates_email as $fkey){
					if(!ereg("^[a-zA-Z0-9_\.\+]+@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*$", $this->$fkey, $regs)){
						Flash::error("Formato de e-mail erroneo en el campo ".ucwords($fkey));
						$e = true;
					}
				}
			}
			if($e){
				return false;
			}
		}


		# Validates Uniqueness
		if(count($this->validates_uniqueness)){
			$e = false;
			if(is_array($this->validates_uniqueness)){
				foreach($this->validates_uniqueness as $fkey){
					ActiveRecord::sql_item_sanizite($fkey);
					if($ex){
						$copy = clone $this;
						$copy->find_first("$fkey = '{$this->$fkey}'");
						$number = $this->db->fetch_one("SELECT COUNT(*) FROM $table WHERE $fkey = '{$this->$fkey}' and $fkey <> '{$copy->$fkey}'");
					} else {
						$number = $this->db->fetch_one("SELECT COUNT(*) FROM $table WHERE $fkey = '{$this->$fkey}'");
					}
					if((int) $number[0]){
						Flash::error("El valor '{$this->$fkey}' ya existe para el campo ".ucwords($fkey));
						$e = true;
					}
				}
			}
			if($e){
				return false;
			}
			unset($number);
		}

		#Run Validation Callbacks After
		if(!$ex&&method_exists($this, "after_validation_on_create")){
			if($this->after_validation_on_create()=='cancel') {
				return false;
			}
		} else {
			if(isset($this->after_validation_on_create)){
				$method = $this->after_validation_on_create;
				if($this->$method()=='cancel') {
					return false;
				}
			}
		}
		if($ex&&method_exists($this, "after_validation_on_update")){
			if($this->after_validation_on_update()=='cancel') {
				return false;
			}
		} else {
			if(isset($this->after_validation_on_update)){
				$method = $this->after_validation_on_update;
				if($this->$method()=='cancel') return false;
			}
		}
		if(method_exists($this, 'after_validation')){
			if($this->after_validation()=='cancel') {
				return false;
			}
		} else {
			if(isset($this->after_validation)){
				$method = $this->after_validation;
				if($this->$method()=='cancel') {
					return false;
				}
			}
		}
		# Run Before Callbacks
		if(method_exists($this, "before_save")){
			if($this->before_save()=='cancel') {
				return false;
			}
		} else {
			if(isset($this->before_save)){
				$method = $this->before_save;
				if($this->$method()=='cancel') {
					return false;
				}
			}
		}
		if($ex&&method_exists($this, "before_update")){
			if($this->before_update()=='cancel') {
				return false;
			}
		} else {
			if(isset($this->before_update)){
				$method = $this->before_update;
				if($this->$method()=='cancel') {
					return false;
				}
			}
		}
		if(!$ex&&method_exists($this, "before_create")){
			if($this->before_create()=='cancel') {
				return false;
			}
		} else {
			if(isset($this->before_create)){
				$method = $this->before_create;
				if($this->$method()=='cancel') {
					return false;
				}
			}
		}
		$config = Config::read("environment.ini");
		if($ex){
			$fields = array();
			$values = array();
			foreach($this->non_primary as $np){
				$np = ActiveRecord::sql_item_sanizite($np);
				if(in_array($np, $this->_in)){
					if($config->database->type=='oracle'){
						$this->$np = date("Y-m-d");
					} else {
						$this->$np = date("Y-m-d G:i:s");
					}
				}
				$fields[] = $np;
				if(substr($this->$np, 0, 1)=="%"){
					$values[] = str_replace("%", "", $this->$np);
				} else {
					if(!$this->is_a_numeric_type($np)){
						/**
						 * Se debe especificar el formato de fecha en Oracle
						 */
						if($this->data_type[$np]=='date'&&$config->database->type=='oracle'){
							$values[] = "TO_DATE('".addslashes($this->$np)."', 'YYYY-MM-DD')";
						} else {
							$values[] = "'".addslashes($this->$np)."'";
						}
					} else {
						if($this->$np!==''&&$this->$np!==null){
							$values[] = "'".addslashes($this->$np)."'";
						} else {
							$values[] = "NULL";
						}
					}
				}
			}
			$val = $this->db->update($table, $fields, $values, $this->where_pk);
		} else {
			$fields = array();
			$values = array();
			foreach($this->fields as $field){
				if($field!='id'&&!$this->id){
					if(in_array($field, $this->_at)){
						if($config->database->type=='oracle'){
							$this->$field = date("Y-m-d");
						} else {
							$this->$field = date("Y-m-d G:i:s");
						}
					}
					if(in_array($field, $this->_in)){
						$this->$field = "NULL";
					}
					$fields[] = ActiveRecord::sql_sanizite($field);
					if(substr($this->$field, 0, 1)=="%"){
						$values[] = str_replace("%", "", $this->$field);
					} else {
						if($this->is_a_numeric_type($field)||$this->$field=="NULL"){
							$values[] = addslashes($this->$field!==''&&$this->$field!==null ? $this->$field : "NULL");
						} else {
							if($this->data_type[$field]=='date'&&$config->database->type=='oracle'){
								/**
								 * Se debe especificar el formato de fecha en Oracle
								 */
								$values[] = "TO_DATE('".addslashes($this->$field)."', 'YYYY-MM-DD')";
							} else {
								if($this->$field!==''&&$this->$field!==null){
									$values[] = "'".addslashes($this->$field)."'";
								} else {
									$values[] = "NULL";
								}
							}
						}
					}
				} else {
					/**
					 * Campos autonumericos en Oracle deben utilizar una sequencia auxiliar
					 */
					if($config->database->type=='oracle'){
						if(!$this->id){
							$fields[] = "id";
							$values[] = $this->source."_id_seq.NEXTVAL";
						}
					}
					if($config->database->type=='informix'){
						if(!$this->id){
							$fields[] = "id";
							$values[] = 0;
						}
					}
				}
			}
			$val = $this->db->insert($table, $values, $fields);
		}

		if(!isset($config->database->pdo)&&$config->database->type=='oracle'){
			$this->commit();
		}

		if(!$ex){
			$this->db->logger = true;
			$m = $this->db->last_insert_id($table, $this->primary_key[0]);
			$this->find_first($m);
		}

		if($val){
			if($ex&&method_exists($this, "after_update")){
				if($this->after_update()=='cancel') {
					return false;
				}
			} else {
				if(isset($this->after_update)){
					$method = $this->after_update;
					if($this->$method()=='cancel') {
						return false;
					}
				}
			}
			if(!$ex&&method_exists($this, "after_create")){
				if($this->after_create()=='cancel') {
					return false;
				}
			} else {
				if(isset($this->after_create)){
					$method = $this->after_create;
					if($this->$method()=='cancel') {
						return false;
					}
				}
			}
			if(method_exists($this, "after_save")){
				if($this->after_save()=='cancel') {
					return false;
				}
			} else {
				if(isset($this->after_save)){
					$method = $this->after_save;
					if($this->$method()=='cancel') {
						return false;
					}
				}
			}
			return $val;
		} else {
			return false;
		}
	}

	/**
	 * Find All data in the Relational Table
	 *
	 * @param string $field
	 * @param string $value
	 * @return ActiveRecod Cursor
	 */
	function find_all_by($field, $value){
		ActiveRecord::sql_item_sanizite($field);
		return $this->find("conditions: $field = '$value'");
	}

	/**
	 * Updates Data in the Relational Table
	 *
	 * @param mixed $values
	 * @return boolean sucess
	 */
	function update($values=''){
		$this->connect();
		if(func_num_args()>1){
			$values = get_params(func_get_args());
		}
		if(is_array($values)){
			foreach($values as $k => $r){
				if(!is_numeric($k)){
					if(isset($this->$k)){
						$this->$k = $r;
					} else {
						throw new ActiveRecordException("No existe el Atributo '$k' en la entidad '{$this->source}' al ejecutar la inserci�n");
						return false;
					}
				}
			}
			if($this->exists()){
				return $this->save();
			} else {
				Flash::error('No se puede actualizar porque el registro no existe');
				return false;
			}
		} else {
			if($this->exists()){
				return $this->save();
			} else {
				Flash::error('No se puede actualizar porque el registro no existe');
				return false;
			}
		}
		return false;
	}

	/**
	 * Deletes data from Relational Map Table
	 *
	 * @param mixed $what
	 */
	function delete($what=''){
		$this->connect();
		if(func_num_args()>1){
			$what = get_params(func_get_args());
		}
		if($this->schema){
			$table = $this->schema.".".$this->source;
		} else {
			$table = $this->source;
		}
		$conditions = "";
		if(is_array($what)){
			if($what["conditions"]) {
				$conditions = $what["conditions"];
			}
		} else {
			if(is_numeric($what)){
				ActiveRecord::sql_sanizite($this->primary_key[0]);
				$conditions = "{$this->primary_key[0]} = '$what'";
			} else{
				if($what){
					$conditions = $what;
				} else {
					ActiveRecord::sql_sanizite($this->primary_key[0]);
					$conditions = "{$this->primary_key[0]} = '{$this->{$this->primary_key[0]}}'";
				}
			}
		}
		if(method_exists($this, "before_delete")){
			if($this->id) {
				$this->find($this->id);
			}
			if($this->before_delete()=='cancel') {
				return false;
			}
		} else {
			if(isset($this->before_delete)){
				if($this->id) {
					$this->find($this->id);
				}
				$method = $this->before_delete;
				if($this->$method()=='cancel') {
					return false;
				}
			}
		}
		$val = $this->db->delete($table, $conditions);
		if($val){
			if(method_exists($this, "after_delete")){
				if($this->after_delete()=='cancel') {
					return false;
				}
			} else {
				if(isset($this->after_delete)){
					$method = $this->after_delete;
					if($this->$method()=='cancel') {
						return false;
					}
				}
			}
		}
		return $val;
	}

	/**
	 * Actualiza todos los atributos de la entidad
	 * $Clientes->update_all("estado='A', fecha='2005-02-02'", "id>100");
	 * $Clientes->update_all("estado='A', fecha='2005-02-02'", "id>100", "limit: 10");
	 *
	 * @param string $values
	 */
	function update_all($values){
		$this->connect();
		$params = array();
		if($this->schema){
			$table = $this->schema.".".$this->source;
		} else {
			$table = $this->source;
		}
		if(func_num_args()>1){
			$params = get_params(func_get_args());
		}
		if(!isset($params['conditions'])||!$params['conditions']){
			if(isset($params[1])){
				$params['conditions'] = $params[1];
			} else {
				$params['conditions'] = "";
			}
		}
		if($params['conditions']){
			$params['conditions'] = " WHERE ".$params['conditions'];
		}
		$sql = "UPDATE $table SET $values {$params['conditions']}";
		
		$limit_args = array($select);
		if(isset($params['limit'])) {
			array_push($limit_args, "limit: $params[limit]");
		}
		if(isset($params['offset'])) {
			array_push($limit_args, "offset: $params[offset]");
		}
		if(count($limit_args)>1) {
			$select = call_user_func_array(array($this,'limit'), $limit_args);
		}

		$config = Config::read("environment.ini");
		if(!isset($config->database->pdo)||!$config->database->pdo){
			if($config->database->type=="informix"){
				$this->db->set_return_rows(false);
			}
		}
		return $this->db->query($sql);
	}

	/**
	 * Delete All data from Relational Map Table
	 *
	 * @param string $conditions
	 * @return boolean
	 */
	function delete_all($conditions=''){
		$this->connect();
		$limit = "";
		if($this->schema){
			$table = $this->schema.".".$this->source;
		} else {
			$table = $this->source;
		}
		if(func_num_args()>1){
			$params = get_params(func_get_args());
			
			$limit_args = array($select);
			if(isset($params['limit'])) {
				array_push($limit_args, "limit: $params[limit]");
			}
			if(isset($params['offset'])) {
				array_push($limit_args, "offset: $params[offset]");
			}
			if(count($limit_args)>1) {
				$select = call_user_func_array(array($this,'limit'), $limit_args);
			}
		}
		return $this->db->delete($table, $conditions);
	}

	/**
	 * *********************************************************************************
	 * Metodos de Debug
	 * *********************************************************************************
	 */

	/**
	 * Imprime una version humana de los valores de los campos
	 * del modelo en una sola linea
	 *
	 */
	public function inspect(){
		$this->connect();
		$inspect = array();
		foreach($this->fields as $field){
			if(!is_array($field)){
				$inspect[] = "$field: {$this->$field}";
			}
		}
		return join(", ", $inspect);
	}

	/**
	 * *********************************************************************************
	 * Metodos de Validacion
	 * *********************************************************************************
	 */

	/**
	 * Validate that Attributes cannot have a NULL value
	 */
	protected function validates_presence_of(){
		$this->connect();
		if(!is_array($this->not_null)){
			$this->not_null = array();
		}
		if(func_num_args()){
			$params = func_get_args();
		} else {
			return true;
		}
		if(is_array($params[0])) {
			$params = $params[0];
		}
		foreach($params as $p){
			if(!in_array($p, $this->fields)){
				throw new ActiveRecordException('No se puede validar presencia de "'.$p.'"
					en el modelo '.$this->source.' porque no existe el atributo</u><br>
					Verifique que el atributo este bien escrito y/o exista en la relaci&oacute;n ');
				return false;
			}
			if(!in_array($p, $this->not_null)){
				$this->not_null[] = $p;
			}
		}
		return true;
	}

	/**
	 * Valida el tama&ntilde;o de ciertos campos antes de insertar
	 * o actualizar
	 *
	 * $this->validates_length_of("nombre", "minumum: 15")
	 * $this->validates_length_of("nombre", "minumum: 15", "too_short: El Nombre es muy corto")
	 * $this->validates_length_of("nombre", "maximum: 40", "too_long: El nombre es muy largo")
	 * $this->validates_length_of("nombre", "in: 15:40",
	 *      "too_short: El Nombre es muy corto",
	 *      "too_long: El nombre es muy largo (40 min)"
	 * )
	 *
	 * @return boolean
	 */
	protected function validates_length_of(){
		$this->connect();
		if(func_num_args()){
			$params = get_params(func_get_args());
		} else {
			return true;
		}
		if(!is_array($this->validates_length)){
			$this->validates_length = array();
		}
		if(is_array($params)){
			$this->validates_length[$params[0]] = array(
			"minimum" => $params['minimum'],
			"maximum" => $params['maximum'],
			"in" => $params["in"],
			"too_short" => $params["too_short"],
			"too_long" => $params["too_long"]
			);
		}
		return true;
	}

	/**
	 * Valida que el campo se encuentre entre los valores de una lista
	 * antes de insertar o actualizar
	 *
	 * $this->validates_inclusion_in("estado", array("A", "I"))
	 *
	 * @param string $campo
	 * @param array $list
	 * @return boolean
	 */
	protected function validates_inclusion_in($campo, $list){
		$this->connect();
		if(!is_array($this->validates_inclusion)){
			$this->validates_inclusion = array();
		}
		$this->validates_inclusion[$campo] = $list;
		return true;
	}

	/**
	 * Valida que el campo no se encuentre entre los valores de una lista
	 * antes de insertar o actualizar
	 *
	 * <code>
	 * $this->validates_exclusion_of("edad", range(1, 13))
	 * </code>
	 *
	 * @param string $campo
	 * @param array $list
	 * @return boolean
	 */
	protected function validates_exclusion_of($campo, $list){
		$this->connect();
		if(!is_array($this->validates_exclusion)){
			$this->validates_exclusion = array();
		}
		$this->validates_exclusion[$campo] = $list;
		return true;
	}

	/**
	 * Valida que el campo tenga determinado formato segun una expresion regular
	 * antes de insertar o actualizar
	 *
	 * $this->validates_format_of("email", "^(+)@((?:[?a?z0?9]+\.)+[a?z]{2,})$")
	 *
	 * @param string
	 * @param array $list
	 * @return boolean
	 */
	protected function validates_format_of($campo, $pattern){
		$this->connect();
		if(!is_array($this->validates_format)){
			$this->validates_format = array();
		}
		$this->validates_format[$campo] = $pattern;
		return true;
	}

	/**
	 * Valida que ciertos atributos tengan un valor numerico
	 * antes de insertar o actualizar
	 *
	 * $this->validates_numericality_of("precio")
	 */
	protected function validates_numericality_of(){
		$this->connect();
		if(!is_array($this->not_null)){
			$this->not_null = array();
		}
		if(func_num_args()){
			$params = func_get_args();
		} else {
			return true;
		}
		if(is_array($params[0])) {
			$params = $params[0];
		}
		foreach($params as $p){
			if(!in_array($p, $this->fields)&&!isset($this->$p)){
				throw new ActiveRecordException('No se puede validar presencia de "'.$p.'"
					en el modelo '.$this->source.' porque no existe el atributo</u><br>
					Verifique que el atributo este bien escrito y/o exista en la relaci&oacute;n ');
				return false;
			}
			if(!in_array($p, $this->validates_numericality)){
				$this->validates_numericality[] = $p;
			}
		}
		return true;
	}

	/**
	 * Valida que ciertos atributos tengan un formato de e-mail correcto
	 * antes de insertar o actualizar
	 *
	 * $this->validates_email_in("correo")
	 */
	protected function validates_email_in(){
		$this->connect();
		if(!is_array($this->not_null)){
			$this->not_null = array();
		}
		if(func_num_args()){
			$params = func_get_args();
		} else {
			return true;
		}
		if(is_array($params[0])) {
			$params = $params[0];
		}
		foreach($params as $p){
			if(!in_array($p, $this->fields)&&!isset($this->$p)){
				throw new ActiveRecordException('No se puede validar presencia de "'.$p.'"
					en el modelo '.$this->source.' porque no existe el atributo</u><br>
					Verifique que el atributo este bien escrito y/o exista en la relaci&oacute;n ');
				return false;
			}
			if(!in_array($p, $this->validates_email)){
				$this->validates_email[] = $p;
			}
		}
		return true;
	}

	/**
	 * Valida que ciertos atributos tengan un valor unico antes
	 * de insertar o actualizar
	 *
	 * $this->validates_uniqueness_of("cedula")
	 */
	protected function validates_uniqueness_of(){
		$this->connect();
		if(!is_array($this->not_null)){
			$this->not_null = array();
		}
		if(func_num_args()){
			$params = func_get_args();
		} else {
			return true;
		}
		if(is_array($params[0])) {
			$params = $params[0];
		}
		foreach($params as $p){
			if(!in_array($p, $this->fields)&&!isset($this->$p)){
				throw new ActiveRecordException('No se puede validar presencia de "'.$p.'"
					en el modelo '.$this->source.' porque no existe el atributo</u><br>
					Verifique que el atributo este bien escrito y/o exista en la relaci&oacute;n ');
				return false;
			}
			if(!in_array($p, $this->validates_uniqueness)){
				$this->validates_uniqueness[] = $p;
			}
		}
		return true;
	}

	/**
	 * Valida que ciertos atributos tengan un formato de fecha acorde al indicado en
	 * config/config.ini antes de insertar o actualizar
	 *
	 * $this->validates_date_in("fecha_registro")
	 */
	protected function validates_date_in(){
		$this->connect();
		if(!is_array($this->not_null)){
			$this->not_null = array();
		}
		if(func_num_args()){
			$params = func_get_args();
		} else return true;
		if(is_array($params[0])) {
			$params = $params[0];
		}
		foreach($params as $p){
			if(!in_array($p, $this->fields)&&!isset($this->$p)){
				throw new ActiveRecordException('No se puede validar presencia de "'.$p.'"
					en el modelo '.$this->source.' porque no existe el atributo</u><br>
					Verifique que el atributo este bien escrito y/o exista en la relaci�n ');
				return false;
			}
			if(!in_array($p, $this->validates_date)){
				$this->validates_date[] = $p;
			}
		}
		return true;
	}


	/**
	 * Verifica si un campo es de tipo de dato numerico o no
	 *
	 * @param string $field
	 * @return boolean
	 */
	public function is_a_numeric_type($field){
		if(
		strpos(" ".$this->data_type[$field], "int")||
		strpos(" ".$this->data_type[$field], "decimal")||
		strpos(" ".$this->data_type[$field], "number")
		){
			return true;
		} else return false;
	}

	/**
	 * Obtiene los datos de los metadatos generados por Primera vez en la Sesi&oacute;n
	 *
	 * @param string $table
	 * @return array
	 */
	static function get_meta_data($table){
		if(isset(self::$models[$table])){
			return self::$models[$table];
		} else {
			$active_app = Router::get_active_app();
			if(isset($_SESSION['KUMBIA_META_DATA'][$_SESSION['KUMBIA_PATH']][$active_app][$table])){
				self::set_meta_data($table, $_SESSION['KUMBIA_META_DATA'][$_SESSION['KUMBIA_PATH']][$active_app][$table]);
				return self::$models[$table];
			}
			return array();
		}
	}

	/**
	 * Crea un registro de meta datos para la tabla especificada
	 *
	 * @param string $table
	 * @param array $meta_data
	 */
	static function set_meta_data($table, $meta_data){
		$active_app = Router::get_active_app();
		if(!isset($_SESSION['KUMBIA_META_DATA'][$_SESSION['KUMBIA_PATH']][$active_app][$table])){
			$_SESSION['KUMBIA_META_DATA'][$_SESSION['KUMBIA_PATH']][$active_app][$table] = $meta_data;
		}
		self::$models[$table] = $meta_data;
		return true;
	}

	/**
	 * Elimina la informaci&oacute;n de cache del objeto y hace que sea cargada en la proxima operaci&oacute;n
	 *
	 */
	public function reset_cache_information(){
		$active_app = Router::get_active_app();
		unset($_SESSION['KUMBIA_META_DATA'][$_SESSION['KUMBIA_PATH']][$active_app][$this->source]);
		$this->dumped = false;
		if(!$this->is_dumped()){
			$this->dump();
		}
	}


	/*******************************************************************************************
	* Metodos para generacion de relaciones
	*******************************************************************************************/

	/**
	 * Crea una relacion 1-1 entre dos modelos
	 *
	 * @param string $relation
	 */
	protected function has_one($relation){
		$relations =  func_get_args();
		foreach($relations as $relation){
			if(!in_array($relation, $this->_has_one)){
				$this->_has_one[] = $relation;
			}
		}
	}

	/**
	 * Crea una relacion 1-1 inversa entre dos modelos
	 *
	 * @param string $relation
	 */
	protected function belongs_to($relation){
		$relations =  func_get_args();
		foreach($relations as $relation){
			if(!in_array($relation, $this->_belongs_to)){
				$this->_belongs_to[] = $relation;
			}
		}
	}

	/**
	 * Crea una relacion 1-n entre dos modelos
	 *
	 * @param string $relation
	 */
	protected function has_many($relation){
		$relations =  func_get_args();
		foreach($relations as $relation){
			if(!in_array($relation, $this->_has_many)){
				$this->_has_many[] = $relation;
			}
		}
	}

	/**
	 * Crea una relacion 1-n entre dos modelos
	 *
	 * @param string $relation
	 */
	protected function has_and_belongs_to_many($relation){
		$relations =  func_get_args();   
		foreach($relations as $relation){
			if(!in_array($relation, $this->_has_and_belongs_to_many)){
				$this->_has_and_belongs_to_many[] = $relation;  
			}
		} 
	}

	/**
	 * Herencia Simple
	 */

	/**
	 * Especifica que la clase es padre de otra
	 *
	 * @param string $parent
	 */
	public function parent_of($parent){
		$parents = func_get_args();
		foreach($parents as $parent){
			if(!in_array($parent, $this->parent_of)){
				$this->parent_of[] = $parent;
			}
		}
	}

	/**
	 * Elimina caracteres que podrian ayudar a ejecutar
	 * un ataque de Inyeccion SQL
	 *
	 * @param string $sql_item
	 */
	public static function sql_item_sanizite($sql_item){
		$sql_item = trim($sql_item);
		if($sql_item!==''&&$sql_item!==null){
			$sql_item = ereg_replace("[ ]+", "", $sql_item);
			if(!ereg("^[a-zA-Z0-9_]+$", $sql_item)){
				throw new ActiveRecordException("Se esta tratando de ejecutar una operacion maliciosa!");
			}
		}
		return $sql_item;
	}

	/**
	 * Elimina caracteres que podrian ayudar a ejecutar
	 * un ataque de Inyeccion SQL
	 *
	 * @param string $sql_item
	 */
	public static function sql_sanizite($sql_item){
		$sql_item = trim($sql_item);
		if($sql_item!==''&&$sql_item!==null){
			$sql_item = ereg_replace("[ ]+", "", $sql_item);
			if(!ereg("^[a-zA-Z_0-9\,\(\)\.]+$", $sql_item)){
				throw new ActiveRecordException("Se esta tratando de ejecutar una operacion maliciosa!");
			}
		}
		return $sql_item;
	}

	/**
	 * Al sobreescribir este metodo se puede controlar las excepciones de un modelo
	 *
	 * @param unknown_type $e
	 */
	protected function exceptions($e){
		throw $e;
	}

	/**
	 * Implementacion de __toString Standard
	 *
	 */
	public function __toString(){
		return "<".get_class()." Object>";
	}

}

?>
