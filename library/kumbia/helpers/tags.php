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
 * @package Helpers
 * @copyright Copyright (c) 2005-2007 Andres Felipe Gutierrez (andresfelipe at vagoogle.net)
 * @copyright Copyright (C) 2007-2007 Roger Jose Padilla Camacho(rogerjose81 at gmail.com)
 * @license http://www.kumbia.org/license.txt GNU/GPL
 */

/**
 * Crea un enlace en una Aplicacion respetando
 * las convenciones de Kumbia
 *
 * @param string $action
 * @param string $text
 * @return string
 */
function link_to($action, $text=''){
	if(func_num_args()>2){
		$action = get_params(func_get_args());
	}
	if(is_array($action)){
		if(isset($action['confirm'])&&$action['confirm']){
			$action['onclick'] = "if(!confirm(\"{$action['confirm']}\")) { return false; }; ".$action['onclick'];
			unset($action['confirm']);
		}
		$code = "<a href='".get_kumbia_url($action)."' ";
		if(!isset($action['text'])||!$action['text']){
			$action['text'] = $action[1];
		}
		foreach($action as $key => $value){
			if(!is_numeric($key)&&$key!='text'){
				$code.=" $key='$value' ";
			}
		}
		$code.=">{$action['text']}</a>";
		return $code;
	} else {
		if(!$text) {
			$text = str_replace('_', ' ', $action);
			$text = str_replace('/', ' ', $text);
			$text = ucwords($text);
		}
		return "<a href='".get_kumbia_url($action)."'>$text</a>";
	}
}

/**
 * Crea un enlace a una accion dentro del controlador Actual
 *
 * @param string $action
 * @param string $text
 * @return string
 */
function link_to_action($action, $text=''){
	if(func_num_args()>2){
		$action = get_params(func_get_args());
	}
	$controller_name = Router::get_controller();
	if(is_array($action)){
		if(isset($action['confirm'])){
			$action['onclick'] = "if(!confirm(\"{$action['confirm']}\")) if(document.all) event.returnValue = false; else event.preventDefault(); ".$action['onclick'];
			unset($action['confirm']);
		}
		$code = "<a href='".get_kumbia_url("$controller_name/{$action[0]}")."' ";
		foreach($action as $key => $value){
			if(!is_numeric($key)){
				$code.=" $key='$value' ";
			}
		}
		$code.=">{$action[1]}</a>";
		return $code;
	} else {
		if(!$text) {
			$text = str_replace('_', ' ', $action);
			$text = str_replace('/', ' ', $text);
			$text = ucwords($text);
		}
		return "<a href='".get_kumbia_url("$controller_name/$action")."'>$text</a>";
	}
}

/**
 * Permite ejecutar una acci�n en la vista actual dentro de un contenedor
 * HTML usando AJAX
 *
 * confirm: Texto de Confirmaci�n
 * success: Codigo JavaScript a ejecutar cuando termine la petici�n AJAX
 * before: Codigo JavaScript a ejecutar antes de la petici�n AJAX
 * oncomplete: Codigo JavaScript que se ejecuta al terminar la petici�n AJAX
 * update: Que contenedor HTML ser� actualizado
 * action: Accion que ejecutar� la petici�n AJAX
 * text: Texto del Enlace
 *
 * @return string
 */
function link_to_remote(){
	$params = get_params(func_get_args());
	if(!isset($params['jdiv'])||!$params['jdiv']){
		$update = isset($params[2]) ? $params[2] : "";
	} else {
		$update = $params['jdiv'];
	}  
	if(!isset($params['text'])||!$params['text']){
		$text = isset($params[1]) ? $params[1] : "";
	} else {
		$text = $params['text'];
	}     
	if(!$text){
		$text = $params[0];
	}
	if(!isset($params['action'])||!$params['action']){
		$action = get_kumbia_url($params[0]);
	} else {
		$action = get_kumbia_url($params['action']);
	} 
	
	/*if(isset($params['confirm'])){
		$code.= "if(confirm('{$params['confirm']}')) {";
	} */
	//$code = "<script>function show(){"."$".".ajax({url: '$action', cache: False";

//	$call = array();
	/*if(isset($params['before'])){
		$call["before"] = "before: function(){ {$params['before']} }";
	} 
	if(isset($params['complete'])){
		$call["complete"] = "complete: function(){ {$params['complete']} }";
	} 
	if(isset($params['success'])){
		$call["success"] = "success: function({$params['success']}){ $('#$update').empty();$('#$update').append({$params['success']}) }";
	}
	if(count($call)){
		$code.=", ";
		$code.=join(",", $call);
		//$code.="}";
	}
	$code.="})}</script>";
	/*if(isset($params['confirm'])){
		$code.=" }";
	} */
	$code.= "<a href=\"#\" onclick=\"show('".$action."','".$update."','".$params['success']."')\"";
//	unset($params['before']);
//	unset($params['complete']);
	unset($params['success']);
//	unset($params['loading']);
//	unset($params['url']);
//	unset($params['confirm']);
	foreach($params as $key => $value){
		if(!is_numeric($key)&&$key!="jdiv"&&$key!="action"){		
			$code.=" $key='$value' ";
		}
	}
	return $code.">$text</a>";
}

/**
 * Genera una etiqueta script que apunta a un archivo JavaScript
 * respetando las rutas y convenciones de Kumbia
 *
 * @param string $src
 * @param string $cache
 * @return unknown
 */
function javascript_include_tag($src='', $cache=true){
	if(!$src) {
		$src = Router::get_controller();
	}
	$src.=".js";
	if(!$cache) {
		$cache = md5(uniqid());
		$src.="?nocache=".$cache;
	}
	return "<script type='text/javascript' src='".KUMBIA_PATH."javascript/$src'></script>\r\n";
}

/**
 * Agrega una etiqueta script que apunta a un archivo en public/javascript/kumbia
 *
 * @param string $src
 * @return string
 */
function javascript_library_tag($src){
	return "<script type='text/javascript' src='".KUMBIA_PATH."javascript/kumbia/$src.js'></script>\r\n";
}

/**
 * Agrega una etiqueta link para incluir un archivo CSS respetando
 * las rutas y convenciones de Kumbia
 *
 * @param string $src
 * @param boolean $use_variables
 */
function stylesheet_link_tag($src='', $use_variables=false){
	if(!$src) {
		$src = Router::get_controller();
	}
	if($use_variables){
		$kb = substr(KUMBIA_PATH, 0, strlen(KUMBIA_PATH)-1);
		$code = "<link rel='stylesheet' type='text/css' href='".KUMBIA_PATH."css.php?c=$src&amp;p=$kb' />\r\n";
	} else {
		$code = "<link rel='stylesheet' type='text/css' href='".KUMBIA_PATH."css/$src.css' />\r\n";
	}
	Registry::prepend("KUMBIA_CSS_IMPORTS", $code);
	return $code;
}

/**
 * Permite incluir una imagen dentro de una vista respetando
 * las convenciones de directorios y rutas en Kumbia
 *
 * @param string $img
 * @return string
 */
function img_tag($img){
	$atts = get_params(func_get_args());
	$code = "";
	if(!isset($atts['src'])||!$atts['src']){
		$code.="<img src='".KUMBIA_PATH."img/".urldecode($atts[0])."' ";
	} else {
		$code.="<img src='{$atts['src']}' ";
	}
	unset($atts['src']);
	if(!isset($atts['alt'])||!$atts['alt']) {
		$atts['alt'] = "";
	}
	if(isset($atts['drag'])&&$atts['drag']) {
		$drag = true;
		unset($atts['drag']);
	} else {
		$drag = false;
	}
	if(isset($atts['reflect'])&&$atts['reflect']) {
		$reflect = true;
		unset($atts['reflect']);
	} else{
		$reflect = false;
	}
	if(is_array($atts)){
		if(!$atts['alt']) $atts['alt'] = "";
		foreach($atts as $at => $val){
			if(!is_numeric($at)){
				$code.="$at=\"".$val."\" ";
			}
		}
	}
	$code.= "/>\r\n";
	if($drag){
	//	$code.="<script type=\"text/javascript\">new Draggable('{$atts['id']}', {revert:true})</script>\r\n";
	}
	if($reflect){
	//	$code.="<script type=\"text/javascript\">new Reflector.reflect('{$atts['id']}')</script>\r\n";
	}
	return $code;
}

/**
 * Permite generar un formulario remoto
 *
 * @param string $data
 * @return string
 */    /*
function form_remote_tag($data){
	$data = get_params(func_get_args());
	if(!isset($data['action'])||!$data['action']) {
		$data['action'] = $data[0];
	}
	$data['callbacks']	= array();
        $id = Router::get_id();
	if(isset($data['complete'])&&$data['complete']){
		$data['callbacks'][] = " complete: function(){ ".$data['complete']." }";
	}
	if(isset($data['before'])&&$data['before']){
		$data['callbacks'][] = " before: function(){ ".$data['before']." }";
	}
	if(isset($data['success'])&&$data['success']){
		$data['callbacks'][] = " success: function(){ ".$data['success']." }";
	}
	if(isset($data['required'])&&$data['required']){
		$requiredFields = encomillar_lista($data['required']);
		$code = "<form action='".get_kumbia_url("{$data['action']}/$id")."' method='post'
		onsubmit='if(validaForm(this,new Array({$requiredFields}))){ return ajaxRemoteForm(this,\"{$data['update']}\",{".join(",",$data['callbacks'])."}); } else{ return false; }'";
	} else{
		$code = "<form action='".get_kumbia_url("{$data['action']}/$id")."' method='post'
		onsubmit='return ajaxRemoteForm(this, \"{$data['update']}\", { ".join(",", $data['callbacks'])." });'";
	}
	foreach($data as $at => $val){
		if(!is_numeric($at)&&(!in_array($at, array("action", "complete", "before", "success", "callbacks")))){
			$code.="$at=\"".$val."\" ";
		}
	}
	return $code.=">\r\n";
}


/**
 * Crea una etiqueta de formulario
 *
 * @param string $action
 */
function form_tag($action){
	if(func_num_args()>1){
		$atts = get_params(func_get_args());
	}
	$id = Router::get_id();
	if(!$action){
		$action = $atts[0] ? $atts[0] : $atts['action'];
	}
	if(!isset($atts['method'])||!$atts['method']) {
		$atts['method'] = "post";
	}
	if(isset($atts['confirm'])&&$atts['confirm']){
		$atts['onsubmit'].=$atts['onsubmit'].";if(!confirm(\"{$atts['confirm']}\")) { return false; }";
		unset($atts['confirm']);
	}
	$str = "<form action='".get_kumbia_url("$action/$id")."' ";
	foreach($atts as $key => $value){
		if(!is_numeric($key)){
			$str.= "$key = '$value' ";
		}
	}
	return $str.">\r\n";
}



/**
 * Etiqueta para cerrar un formulario
 *
 * @return $string_code
 */
function end_form_tag(){
	$str = "</form>\r\n";
	return $str;
}

/**
 * Crea un boton de submit para el formulario actual
 *
 * @param string $caption
 * @return html code
 */
function submit_tag($caption){
	$data = get_params(func_get_args());
	if(!isset($data['caption'])) {
		$data['caption'] = $data[0];
	} else {
		if(!$data['caption']) {
			$data['caption'] = $data[0];
		}
	}
	$code = "<input type='submit' value='{$data['caption']}' ";
	foreach($data as $key => $value){
		if(!is_numeric($key)){
			$code.="$key='$value' ";
		}
	}
	$code.=" />\r\n";
	return $code;
}

/**
 * Crea un boton de submit para el formulario remoto actual
 *
 * @param string $caption
 * @return html code
 */  /*
function submit_remote_tag($caption){
	$data = get_params(func_get_args());
	if(!$data['caption']) {
		$data['caption'] = $data[0];
	}
	$data['callbacks']	= array();
	if($data['complete']){
		$data['callbacks'][] = " complete: function(){ ".$data['complete']." }";
	}
	if($data['before']){
		$data['callbacks'][] = " before: function(){ ".$data['before']." }";
	}
	if($data['success']){
		$data['callbacks'][] = " success: function(){ ".$data['success']." }";
	}
	$code = "<input type='submit' value='{$data['caption']}' ";
	foreach($data as $at => $value){
		if(!is_numeric($at)&&(!in_array($at, array("action", "complete", "before", "success", "callbacks", "caption", "update")))){
			$code.="$at='$value' ";
		}
	}
	//{ ".join(",", $data['callbacks'])."}
	$code.=" onclick='return ajaxRemoteForm(this.form, \"{$data['update']}\")' />\r\n";
	return $code;
}

/**
 * Crea un boton de submit tipo imagen para el formulario actual
 *
 * @param string $caption
 * @return html code
 */
function submit_image_tag($caption, $src){
	$data = get_params(func_get_args());
	if(!$data['caption']) {
		$data['caption'] = $data[0];
	}
	if(!$data['src']) {
		$data['src'] = $data[1];
	}
	$code = "<input type='image' src='{$data['src']}' value='{$data['caption']}' ";
	foreach($data as $key => $value){
		if(!is_numeric($key)){
			$code.="$key='$value' ";
		}
	}
	$code.=" />\r\n";
	return $code;
}

/**
 * Crea un boton HTML
 *
 * @return string
 */
function button_tag(){
	$data = get_params(func_get_args());
	if(!isset($data['value'])) $data['value'] = $data[0];
	if(isset($data['id'])&&$data['id']&&!$data['name']) {
		$data['name'] = $data['id'];
	}
	if(!isset($data['id'])) {
		$data['id'] = isset($data['name']) ? $data['name'] : "";
	}
	$code = "<input type='button' ";
	foreach($data as $key => $value){
		if(!is_numeric($key)&&$key!=$data){
			$code.="$key=\"$value\" ";
		}
	}
	return $code." />\r\n";
}

/**
 * Obtiene el valor de un componente tomado
 * del mismo valor del nombre del campo en el modelo
 * del mismo nombre del controlador o el indice en
 * $_REQUEST
 *
 * @param string $name
 * @return mixed
 */
function get_value_from_action($name){
	$controller = Dispatcher::get_controller();
	if(isset($controller->$name)){
		return $controller->$name;
	} else {
		return "";
	}
}

# Helpers
/**
 * Crea una caja de Texto
 *
 * @param string $name
 * @return string
 */
function text_field_tag($name){
	$value = get_value_from_action($name);
	$name = get_params(func_get_args());
	if(!$name[0]) {
		$name[0] = $name['id'];
	}
	if(!isset($name['name'])||!$name['name']) {
		$name['name'] = $name[0];
	}
	if(!$value&&isset($name['value'])) {
		$value = $name['value'];
	}
	$code = "<input type='text' id='{$name[0]}' value='$value' ";
	foreach($name as $key => $value){
		if(!is_numeric($key)){
			$code.="$key='$value' ";
		}
	}
	$code.=" />\r\n";
	return $code;
}

/**
 * Crea un CheckBox
 *
 * @param string $name
 * @return string
 */
function checkbox_field_tag($name){
	$value = get_value_from_action($name);
	$name = get_params(func_get_args());
	if(!$name[0]) {
		$name[0] = $name['id'];
	}
	if(!$name['name']) {
		$name['name'] = $name[0];
	}
	if(!$value) {
		$value = $name['value'];
	}
	$code.="<input type='checkbox' id='{$name[0]}' value='$value' ";
	foreach($name as $key => $value){
		if(!is_numeric($key)){
			$code.="$key='$value' ";
		}
	}
	$code.=" />\r\n";
	return $code;
}

/**
 * Permite agregar
 *
 * @param string $name
 * @return string
 */
function numeric_field_tag($name){
	$value = get_value_from_action($name);
	$name = get_params(func_get_args());
	if(!$name[0]) {
		$name[0] = $name['id'];
	}
	if(!isset($name['name'])||!$name['name']){
		$name['name'] = $name[0];
	}
	if(!$value) {
		$value = isset($name['value']) ? $name['value'] : "";
	}
	if(!isset($name['onkeydown'])) {
		$name['onkeydown'] = "valNumeric(event)";
	} else {
		$name['onkeydown'].=";valNumeric(event)";
	}
	$code = "<input type='text' id='{$name[0]}' value='$value' ";
	foreach($name as $key => $value){
		if(!is_numeric($key)){
			$code.="$key='$value' ";
		}
	}
	$code.=" />\r\n";
	return $code;
}

/**
 * Crea una caja de texto que acepta solo texto en Mayuscula
 *
 * @param string $name
 * @return string
 */
function textupper_field_tag($name){
	$value = get_value_from_action($name);
	$name = get_params(func_get_args());
	if(!$name[0]) {
		$name[0] = $name['id'];
	}
	if(!$name['name']) {
		$name['name'] = $name[0];
	}
	if(!$value) {
		$value = $name['value'];
	}
	if(!isset($name['onblur'])) {
		$name['onblur'] = "keyUpper2(this)";
	} else {
		$name['onblur'].=";keyUpper2(this)";
	}
	$code.="<input type='text' id='{$name[0]}' value='$value' ";
	foreach($name as $key => $value){
		if(!is_numeric($key)){
			$code.="$key='$value' ";
		}
	}
	$code.=" />\r\n";
	return $code;
}

/**
 * Crea un campo que acepta solo fechas
 *
 * @param string $name
 * @return string
 */
function date_field_tag($name){
	//$config = Config::read('config.ini');
	//$active_app = Kumbia::$active_app;
	$value = get_value_from_action($name);
	$name = get_params(func_get_args());
	if(!$name[0]) {
		$name[0] = $name['id'];
	}
	if(!isset($name['name'])||!$name['name']) {
		$name['name'] = $name[0];
	}
	if(!$value) {
		$value = $name['value'];
	}

	if($value){
		$ano = substr($value, 0, 4);
		$mes = substr($value, 5, 2);
		$dia = substr($value, 8, 2);
	} else {
		$ano = 0;
		$mes = 0;
		$dia = 0;
	}
	$code ="<table><tr><td>";

	$meses = array(
	"01" => "Ene",
	"02" => "Feb",
	"03" => "Mar",
	"04" => "Abr",
	"05" => "May",
	"06" => "Jun",
	"07" => "Jul",
	"08" => "Ago",
	"09" => "Sep",
	"10" => "Oct",
	"11" => "Nov",
	"12" => "Dic",
	);
	$code .= "<select name='{$name[0]}_month' id='{$name[0]}_month'
	onchange=\"$('{$name[0]}').value = $('{$name[0]}_year').options[$('{$name[0]}_year').selectedIndex].value+'-'+$('{$name[0]}_month').options[$('{$name[0]}_month').selectedIndex].value+'-'+$('{$name[0]}_day').options[$('{$name[0]}_day').selectedIndex].value\"
	>";
	foreach($meses as $numero_mes => $nombre_mes){
		if($numero_mes==$mes){
			$code.="<option value='$numero_mes' selected='selected'>$nombre_mes</option>\n";
		} else {
			$code.="<option value='$numero_mes'>$nombre_mes</option>\n";
		}
	}
	$code.="</select></td><td>";

	$code.="<select name='{$name[0]}_day' id='{$name[0]}_day'
	onchange=\"$('{$name[0]}').value = $('{$name[0]}_year').options[$('{$name[0]}_year').selectedIndex].value+'-'+$('{$name[0]}_month').options[$('{$name[0]}_month').selectedIndex].value+'-'+$('{$name[0]}_day').options[$('{$name[0]}_day').selectedIndex].value\">";
	for($i=1;$i<=31;$i++){
		$n = sprintf("%02s", $i);
		if($n==$dia){
			$code.="<option value='$n' selected='selected'>$n</option>\n";
		} else {
			$code.="<option value='$n'>$n</option>\n";
		}
	}
	$code.="</select></td><td>";

	$code.="<select name='{$name[0]}_year' id='{$name[0]}_year'
	onchange=\"$('{$name[0]}').value = $('{$name[0]}_year').options[$('{$name[0]}_year').selectedIndex].value+'-'+$('{$name[0]}_month').options[$('{$name[0]}_month').selectedIndex].value+'-'+$('{$name[0]}_day').options[$('{$name[0]}_day').selectedIndex].value\"
	>";
	for($i=date("Y");$i>=1900;$i--){
		if($i==$ano){
			$code.="<option value='$i' selected='selected'>$i</option>\n";
		} else {
			$code.="<option value='$i'>$i</option>\n";
		}
	}
	$code.="</select></td><td>";
	$code.="</table>";

	$code.="<input type='hidden' id='{$name[0]}' name='{$name[0]}' value='$value' />";

	return $code;
}


/**
 * Crea un Input tipo Text
 *
 * @param string $name
 * @return string
 */
function file_field_tag($name){
	$value = get_value_from_action($name);
	$name = get_params(func_get_args());
	if(!$name[0]) {
		$name[0] = $name['id'];
	}
	if(!$name['name']) {
		$name['name'] = $name[0];
	}
	$code.="<input type='file' id='{$name[0]}' ";
	foreach($name as $key => $value){
		if(!is_numeric($key)){
			$code.="$key='$value' ";
		}
	}
	$code.=" />\r\n";
	return $code;
}

/**
 * Crea un input tipo Radio
 *
 * @param string $name
 * @return string
 */
function radio_field_tag($name){
	$value = get_value_from_action($name);
	$name = get_params(func_get_args());
	if(!$name[0]) {
		$name[0] = $name['id'];
	}
	if(!$name['name']) {
		$name['name'] = $name[0];
	}
	if(!$value) {
		$value = $name['value'];
	}
	$code = "<table>";    
	foreach($name[1] as $key=>$text){
	    if ($key%8==0)$code.= "<tr>";    
		if($value==$key){
			$code.= "<td><input type='radio' name='{$name[0]}' id='{$name[0]}' value='$key' checked='checked' /></td><td>$text</td>\r\n";
		} else {
			$code.= "<td>$a<input type='radio' name='{$name[0]}' id='{$name[0]}' value='$key' /></td><td>$text</td>\r\n";
		}
	 	//if ($key%2==0)$code.= "</tr>";
	}   
	$code.= "</table>";

	return $code;
}

/**
 * Crea un TextArea
 *
 * @param array $configuration
 * @return string
 */
function textarea_tag($configuration){
	if(func_num_args()==1){
		$value = get_value_from_action($configuration);
	} else{
		$value = get_value_from_action($configuration[0]);
	}
	if(func_num_args()==1){
		$configuration = func_get_args();
		return "<textarea id='{$configuration[0]}' name='{$configuration[0]}' cols='20px' rows='5px'>$value</textarea>\r\n";
	} else {
		$configuration = get_params(func_get_args());
		if(!$configuration['name']) {
			$configuration['name'] = $configuration[0];
		}
		if(!$configuration['cols']) {
			$configuration['cols'] = 20;
		}
		if(!$configuration['rows']) {
			$configuration['rows'] = 5;
		}
		if($value==null) {
			$value = $configuration['value'];
		}
		return "<textarea id='{$configuration['name']}' name='{$configuration['name']}' cols='{$configuration['cols']}px' rows='{$configuration['rows']}px'>$value</textarea>\r\n";
	}
}


/**
 * Crea un componente para capturar Passwords
 *
 * @param string $name
 * @return string
 */
function password_field_tag($name){
	$value = get_value_from_action($name);
	if(func_num_args()>1){
		$name = get_params(func_get_args());
	}
	if(!is_array($name)){
		return "<input type='password' id='$name' name='$name' value='$value' />\r\n";
	} else {
		if(!$name[0]) {
			$name[0] = $name['id'];
		}
		if(!isset($name['name'])||!$name['name']) {
			$name['name'] = $name[0];
		}
		$code = "<input type='password' id='{$name[0]}'";
		foreach($name as $key => $value){
			if(!is_numeric($key)){
				$code.="$key='$value' ";
			}
		}
		$code.=" />\r\n";
		return $code;
	}
}

/**
 * Crea un Componente Oculto
 *
 * @param string $name
 * @return string
 */
function hidden_field_tag($name){
	$value = get_value_from_action($name);
	if(func_num_args()>1){
		$name = get_params(func_get_args());
	}
	if(!is_array($name)){
		return "<input type='hidden' id='$name' name='$name' value='$value' />\r\n";
	} else {
		if(!$name[0]) {
			$name[0] = $name['id'];
		}
		if(!$name['name']) {
			$name['name'] = $name[0];
		}
		$code.="<input type='hidden' id='{$name[0]}'";
		foreach($name as $key => $value){
			if(!is_numeric($key)){
				$code.="$key='$value' ";
			}
		}
		$code.=" />\r\n";
		return $code;
	}
}

/**
 * Crea una lista SELECT
 *
 * @param string $name
 * @param array $data
 * @param string $values and/or $campos of table(BD)
 * @param string $selected(use for BD)
 */
function select_tag(){
	if(func_num_args()>1){
		$opts = get_params(func_get_args());
	}
	if(is_array($opts)){  
	    if($opts["values"]||$opts["campos"]){ 	             	       
           $opts[2]=isset($opts["values"])?$opts["values"]:$opts["campos"];
           if($opts["values"]&&$opts["campos"])$opts[3]=$opts["campos"]; 
        }    
        @$code.="<select id='{$opts[0]}' name='{$opts[0]}' ";
		if(is_array($opts)){           
			foreach($opts as $at => $val){
				if(!is_numeric($at)&&!in_array($at,array('selected','values','campos'))){
					$code.="$at = '".$val."' ";
				}elseif($at=="selected"&&$text==""){
                    @$text    = $at;
                    @$element = $val;
                }
			}
		}
		$code.=">\r\n";		
		$code.= $text!=""?"\t<option value=''></option>\r\n":"\t<option value='' selected></option>\r\n";
		if(is_array(@$opts[1])){
         if(is_object(@$opts[1][0])){           
           if (@$opts[3]==""){
            if(substr_count($opts[2],",")>0){
             $columns=explode(",",$opts[2]);
             $nca=sizeof($columns)-1;
		     foreach($opts[1] as $d){
               $code.=($element==$d->id&&$text!="")?"\t<option value='{$d->id}' $text>":"\t<option value='{$d->id}'>";
               foreach($columns as $nc=>$vc){
                if ($nc==$nca)
                 $code.=$d->$vc;
                else
                 $code.=$d->$vc." ";
               }
               $code.="</option>\r\n";
             } 
            }else{            
             foreach($opts[1] as $d){
		      $code.=($element==$d->id&&$text!="")?"\t<option value='{$d->id}' $text>{$d->$opts[2]}</option>\r\n":"\t<option value='{$d->id}'>{$d->$opts[2]}</option>\r\n";
		     }
		    }
		   }elseif($opts[2]!=""&&$opts[3]!=""){
		    $columns=explode(",",$opts[3]);
            $nca=sizeof($columns)-1;             
		    foreach($opts[1] as $d){
             $code.=($element==$d->$opts[2]&&$text!="")?"\t<option value='{$d->$opts[2]}' $text>":"\t<option value='{$d->$opts[2]}'>";
             foreach($columns as $nc=>$vc){
              if ($nc==$nca)
               $code.=$d->$vc;
              else
               $code.=$d->$vc." ";  
             }
             $code.="</option>\r\n";
            }
          }
         } else {		    			 
		    foreach($opts[1] as $d=>$va){		    
		      $code.=($element==$d&&$text!="")?"\t<option value='$d' $text>$va</option>\r\n":"\t<option value='$d'>$va</option>\r\n";		      
		    }
         } 			
		}				
		$code.= "</select>\r\n";
	} else {
		$code.="<select id='$opts' name='$opts'></select>";
	}
	return $code;
}

/**
 * Crea una opcion de un SELECT
 *
 * @param string $value
 * @param string $text
 */
function option_tag($value, $text){
	if(func_num_args()>1){
		$opts = get_params(func_get_args());
		$value = $opts[0];
		$text = $opts[1];
	} else {
		$value = '';
	}
	$code = "<option value='$value' ";
	if(is_array($opts)){
		foreach($opts as $at => $val){
			if(!is_numeric($at)){
				$code.="$at = '".$val."' ";
			}
		}
	}
	$code.= " >$text</option>\r\n";
	return $code;
}


/**
 * Crea un componente para Subir Imagenes
 *
 * @return string
 */
function upload_image_tag(){
	$opts = get_params(func_get_args());
	if(!$opts['name']){
		$opts['name'] = $opts[0];		
		$defecto="upload/";
	}else{
	    $opts[0]=$opts['name'];
        $defecto=$opts['dir']."/";        
    }
	$code.="<span id='{$opts['name']}_span_pre'>  
	<select name='{$opts[0]}' id='{$opts[0]}' onchange=\"show_upload_image(this,'".$opts['name']."')\">";
	$code.="<option value=''>Seleccione...\n";
	foreach(scandir("public/img/".$defecto) as $file){
		if($file!='index.html'&&$file!='.'&&$file!='..'&&$file!='Thumbs.db'&&$file!='desktop.ini'){
			$nfile = str_replace('.gif', '', $file);
			$nfile = str_replace('.jpg', '', $nfile);
			$nfile = str_replace('.png', '', $nfile);
			$nfile = str_replace('.bmp', '', $nfile);
			$nfile = str_replace('_', ' ', $nfile);
			$nfile = ucfirst($nfile);			
			if(urlencode("$defecto"."$file")==$opts['value']){
				$code.="<option  value='".$defecto.$file."' style='background: #EAEFFA' selected='selected'>$nfile</option>\n";
			} else {
				$code.="<option value='".$defecto.$file."'>$nfile</option>\n";
			}
		}
	}
	$code.="</select> <a href='#{$opts['name']}_up' name='{$opts['name']}_up' id='{$opts['name']}_up' onclick='enable_upload_file(\"{$opts['name']}\")'>Subir Imagen</a></span>
	<span style='display:none' id='{$opts['name']}_span'>
	<input type='file' id='{$opts['name']}_file' name='{$opts['name']}_file' value='' onchange='upload_file(\"{$opts['name']}\")' />
	<a href='#{$opts['name']}_can' name='{$opts['name']}_can' id='{$opts['name']}_can' style='color:red' onclick='cancel_upload_file(\"{$opts['name']}\")'>Cancelar</a></span>
	";
	if(!$opts['width']) {
		$opts['width'] = 128;
	}
	if($opts['value']){
		$opts['style']="border: 1px solid black;margin: 5px;".$opts['value'];
	} else {
		$opts['style']="border: 1px solid black;display:none;margin: 5px;".$opts['value'];
	}
	$code.="<div>".img_tag(urldecode($opts['value']), 'width: '.$opts['width'], 'style: '.$opts['style'], 'id: '.$opts['name']."_im")."</div>";	
	return $code;
}

function set_droppable($obj, $action=''){
	$opts = get_params(func_get_args());
	if(!$opts['name']){
		$opts['name'] = $opts[0];
	}
	return "<script type=\"text/javascript\">Droppables.add('{$opts['name']}', {hoverclass: '{$opts['hover_class']}',onDrop:{$opts['action']}})</script>";
}

function redirect_to($action, $seconds = 0.01){
	$seconds*=1000;
	return "<script type=\"text/javascript\">setTimeout('window.location=\"?/$action\"', $seconds)</script>";
}

function render_partial(){
	$params = get_params(func_get_args());

	if(isset($params['controller'])) {
		$controller_name = uncamelize($params['controller']);
	}elseif(preg_match("/^(.+)\/(.+)$/", $params[0], $data)) {
		$controller_name = $data[1];
		$partial_view = $data[2];
	} else {
		$controller_name = Router::get_controller();
	}

	if(!isset($partial_view)) $partial_view = $params[0];

	$views_dir = Kumbia::$active_views_dir;
	$partial_file = join_path($views_dir, $controller_name, "_$partial_view.phtml");
	
	if(file_exists($partial_file)){
		if(is_array(Kumbia::$models)){
			foreach(Kumbia::$models as $model_name => $model){
				$$model_name = $model;
			}
		}
		
		if(is_subclass_of(Dispatcher::get_controller(), "ApplicationController")){
			foreach(Dispatcher::get_controller() as $var => $value) {
				$$var = $value;
			}
		}

		if(isset($params[1])) $$partial_view = $params[1];
		include $partial_file;
	} else {
		throw new kumbiaException('Kumbia no puede encontrar la vista parcial: "'.$partial_view.'"', 0);
	}
}

function tr_break($x=''){
	static $l;
	if($x=='') {
		$l = 0;
		return;
	}
	if(!$l) {
		$l = 1;
	} else {
		$l++;
	}
	if(($l%$x)==0) {
		print "</tr><tr>";
	}
}

function br_break($x=''){
	static $l;
	if($x=='') {
		$l = 0;
		return;
	}
	if(!$l) {
		$l = 1;
	} else {
		$l++;
	}
	if(($l%$x)==0) {
		print "<br/>\n";
	}
}

function tr_color($colors){
	static $i;
	if(func_num_args()>1){
		$params = get_params(func_get_args());
	}
	if(!$i) {
		$i = 1;
	}
	print "<tr bgcolor=\"{$colors[$i-1]}\"";
	if(count($colors)==$i) {
		$i = 1;
	} else {
		$i++;
	}
	if(isset($params)){
		if(is_array($params)){
			foreach($params as $key => $value){
				if(!is_numeric($key)){
					print " $key = '$value'";
				}
			}
		}
	}
	print ">";
}

/**
 * Crea un Button que al hacer click carga
 * un controlador y una acci�n determinada
 *
 * @param string $caption
 * @param string $action
 * @param string $classCSS
 * @return HTML del Bot�n
 */
function button_to_action($caption, $action, $classCSS=''){
	return "<button class='$classCSS' onclick='window.location=\"".get_kumbia_url($action)."\"'>$caption</button>";
}

/**
 * Crea un Button que al hacer click carga
 * con AJAX un controlador y una acci�n determinada
 *
 * @param string $caption
 * @param string $action
 * @param string $classCSS
 * @return HTML del Bot�n
 */  /*
function button_to_remote_action($caption, $action, $classCSS=''){
	$opts = get_params(func_get_args());
	if(func_num_args()==2){
		$opts['action'] = $opts[1];
		$opts['caption'] = $opts[0];
	} else {
		if(!isset($opts['action'])||!$opts['action']) {
			$opts['action'] = $opts[1];
		}
		if(!isset($opts['caption'])||!$opts['caption']) {
			$opts['caption'] = $opts[0];
		}
	}
	if(!isset($opts['update'])){
		$opts['update'] = "";
	}
	$code = "<button onclick='AJAX.execute({action:\"{$opts['action']}\", container:\"{$opts['update']}\", callbacks: { success: function(){{$opts['success']}}, before: function(){{$opts['before']}} } })'";
	unset($opts['action']);
	unset($opts['success']);
	unset($opts['before']);
	unset($opts['complete']);
	foreach($opts as $k => $v){
		if(!is_numeric($k)&&$k!='caption'){
			$code.=" $k='$v' ";
		}
	}
	$code.=">{$opts['caption']}</button>";
	return $code;
}

/**
 * Crea un select multiple que actualiza un container
 * usando una accion ajax que cambia dependiendo del id
 * selecionado en el select
 * @param string $id
 * @return code
 */   /*
function updater_select($id){
	$opts = get_params(func_get_args());
	if(func_num_args()==1){
		$opts['id'] = $id;
	}
	if(!$opts['id']) $opts['id'] = $opts[0];
	if(!$opts['container']) $opts['container'] = $opts['update'];
	$code = "
	<select multiple onchange='AJAX.viewRequest({
		action: \"{$opts['action']}/\"+selectedItem($(\"{$opts['id']}\")).value,
		container: \"{$opts['container']}\"
	})' ";
	unset($opts['container']);
	unset($opts['update']);
	unset($opts['action']);
	foreach($opts as $k => $v){
		if(!is_numeric($k)){
			$code.=" $k='$v' ";
		}
	}
	$code.=">\n";
	return $code;
}

function text_field_with_autocomplete(){
	$name = get_params(func_get_args());
	$value = get_value_from_action($name[0]);
	$hash = md5(uniqid());
	if(!isset($name['name'])||!$name['name']) {
		$name['name'] = $name[0];
	}
	if(!isset($name['after_update'])||!$name['after_update']) {
		$name['after_update'] = "function(){}";
	}
	if(!isset($name['id'])||!$name['id']) {
		$name['id'] = $name['name'] ? $name['name'] : $name[0];
	}
	if(!isset($name['message'])||!$name['message']) {
		$name['message'] = "Consultando..";
	}
	$code = "<input type='text' id='{$name[0]}' name='{$name['name']}'";
	foreach($name as $key => $value){
		if(!is_numeric($key)&&$key!="action"&&$key!="after_update"){
			$code.="$key='$value' ";
		}
	}
	$code.= " />
	<span id='indicator$hash' style='display: none'><img src='".KUMBIA_PATH."img/spinner.gif' alt='{$name['message']}'/></span>
	<div id='{$name[0]}_choices' class='autocomplete'></div>
	<script type='text/javascript'>
	// <![CDATA[
	new Ajax.Autocompleter(\"{$name[0]}\", \"{$name[0]}_choices\", \$Kumbia.path+\"{$name['action']}\", { minChars: 2, indicator: 'indicator$hash', afterUpdateElement : {$name['after_update']}});
	// ]]>
	</script>
	";
	return $code;
}
          */

#Other functions
function truncate($word, $number=0){
	if($number){
		return substr($word, 0, $number);
	} else {
		return rtrim($word);
	}
}

/**
 * Envia la salida en buffer al navegador
 *
 */
function content(){
	print Kumbia::$content;
}

/**
 * Inserta un documento XHTML antes de una salida en buffer
 *
 * @param string $template
 */
function xhtml_template($template='template'){
	stylesheet_link_tag("style", true);
	print '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Kumbia PHP Framework</title>
  <meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />'."\n";
	kumbia::stylesheet_link_tags().
  print '</head>
 <body class="'.$template.'">';
  content();
 print '
 </body>
</html>';

}

function tab_tag($tabs, $color='green', $width=800){

	switch($color){
		case 'blue':
		$col1 = '#E8E8E8'; $col2 = '#C0c0c0'; $col3 = '#000000';
		break;

		case 'pink':
		$col1 = '#FFE6F2'; $col2 = '#FFCCE4'; $col3 = '#FE1B59';
		break;

		case 'orange':
		$col1 = '#FCE6BC'; $col2 = '#FDF1DB'; $col3 = '#DE950C';
		break;

		case 'green':
		$col2 = '#EAFFD7'; $col1 = '#DAFFB9'; $col3 = '#008000';
		break;
	}


	print "
			<table cellspacing=0 cellpadding=0 width=$width>
			<tr>";
	$p = 1;
	$w = $width;
	foreach($tabs as $tab){
		if($p==1) $color = $col1;
		else $color = $col2;
		$ww = (int) ($width * 0.22);
		$www = (int) ($width * 0.21);
		print "<td align='center'
				  width=$ww style='padding-top:5px;padding-left:5px;padding-right:5px;padding-bottom:-5px'>
				  <div style='width:$www"."px;border-top:1px solid $col3;border-left:1px solid $col3;border-right:1px solid $col3;background:$color;padding:2px;color:$col3;cursor:pointer' id='spanm_$p'
				  onclick='showTab($p, this)'
				  >".$tab['caption']."</div></td>";
		$p++;
		$w-=$ww;
	}
	print "
			<script>
				function showTab(p, obj){
				  	for(i=1;i<=$p-1;i++){
					    $('tab_'+i).hide();
					    $('spanm_'+i).style.background = '$col2';
					}
					$('tab_'+p).show();
					obj.style.background = '$col1'
				}
			</script>
			";
	$p = $p + 1;
	//$w = $width/2;
	print "<td width=$w></td><tr>";
	print "<td colspan=$p style='border:1px solid $col3;background:$col1;padding:10px'>";
	$p = 1;
	foreach($tabs as $tab){
		if($p!=1){
			print "<div id='tab_$p' style='display:none'>";
		} else {
			print "<div id='tab_$p'>";
		}
		render_partial($tab['partial']);
		print "</div>";
		$p++;
	}
	print "<br></td><td width=30></td>";
	print "</table>";
}

function render_view($view){
	$params = get_params(func_get_args());

	if(isset($params['controller'])) {
		$controller_name = uncamelize($params['controller']);
	}elseif(preg_match("/^(.+)\/(.+)$/", $params[0], $data)) {
		$controller_name = $data[1];
		$view = $data[2];
	}
	
	if(!isset($view)) $view = $params[0];
	
	$view_file = join_path(Kumbia::$active_views_dir, $controller_name, "$view.phtml");
	if(file_exists($view_file)){
		include $view_file;
	} else{
		throw new KumbiaException("La vista '$view' no existe o no se puede cargar");
	}
}

?>
