/***************************************************************************
* GNU/GPL Kumbia - PHP Rapid Development Framework
* Simple Object Manipulation Base Functions
****************************************************************************
* (c) 2007 Andres Felipe Gutierrez <andresfelipe at vagoogle.net>
****************************************************************************/
jQuery.noConflict();
var ahiva;
function show(action,jdiv,type){
 jQuery.ajax({
  url: action,
  cache: false,
  async: false,
  success: function(type){
    jQuery("#"+jdiv).empty();
    jQuery("#"+jdiv).append(type);
  }
 });
}

function onButtonClick(ae,itemValue){
/* var ruta=get_kumbia_url("config/index");
 jQuery(itemId).click(show(ruta,'formularios','html'));*/
 switch (ae){
  case "0_select": bk.oQ("0_check");
                   bk.lx("0_antes");bk.lx("0_despues");bk.lx("0_borrar");bk.lx("0_actualizar");
                   jQuery(ae).click(ixixx('',itemValue));
                   ahiva=itemValue;
                   break; 
  case "0_check": if (ahiva!=null&&ahiva!=""){
                   bk.lx("0_check");bk.oQ("0_antes");bk.oQ("0_despues");bk.oQ("0_borrar");
                   jQuery(ae).click(ixixx(true,ahiva));
                  }
                  break;
  case "0_antes": if (ahiva!=null&&ahiva!=""){
                   jQuery(ae).click(addxx('^',ahiva));
                   bk.lx("0_antes");bk.lx("0_despues");bk.lx("0_borrar");bk.oQ("0_actualizar");
                  }
                  break;
  case "0_despues": if (ahiva!=null&&ahiva!=""){
                     jQuery(ae).click(addxx('v',ahiva));
                     bk.lx("0_antes");bk.lx("0_despues");bk.lx("0_borrar");bk.oQ("0_actualizar");
                    }
                    break;
  case "0_borrar": if (ahiva!=null&&ahiva!=""){
                    jQuery(ae).click(delxx());
                    bk.lx("0_antes");bk.lx("0_despues");bk.oQ("0_actualizar");
                   } 
                   break;
  case "0_actualizar": if (ahiva!=null&&ahiva!=""){
                        jQuery(ae).click(ixixx('',ahiva));
                        bk.oQ("0_check");bk.lx("0_antes");bk.lx("0_despues");bk.lx("0_borrar");bk.lx("0_actualizar");
                       }
                       break;
 }
}   
 
function menu(dir,div){
 var l = get_kumbia_url(dir);
 bk=new bb(document.getElementById(div),'100%',16,'');
 bk.bl(onButtonClick);
 bk.loadXML(l);
 bk.eE();
}

function calendar(){
 jQuery("#trigger").show();
 jQuery("#bo").show();
 jQuery("#hb").hide(); 
 Calendar.setup({"ifFormat":"%Y-%m-%d","daFormat":"%Y/%m/%d","firstDay":1,"timeFormat":12,"inputField":"jscalendar1","button":"trigger"});
}
/*
var dummy = function(){}

Object.extend(Array.prototype, {
	append: function(item){
		this[this.length] = item;
	}
})

Object.extend(Number.prototype, {

	upto: function(up, iterator){
		$R(this, up).each(iterator);
    	return this;
	},

	downto: function(down, iterator){
		$A($R(down, this)).reverse().each(iterator);
    	return this;
	},

	step: function(limit, step, iterator){
		range = []
		if(step>0){
			for(i=this;i<=limit;i+=step){
				range.append(i)
			}
		} else {
			for(i=this;i>=limit;i+=step){
				range.append(i)
			}
		}
		range.each(iterator);
    	return this;
	},

	next: function(){
		return this+1;
	}

})

//Obtiene una referencia a un ob
function $O(obj){
	if($("flid_"+obj)){
		return $("flid_"+obj);
	}
	return $(obj);
}    */

function get_kumbia_url(action){
	url = $Kumbia.path
	if($Kumbia.app){
		url+=$Kumbia.app+"/"
	}
	if($Kumbia.module){
		url+=$Kumbia.module+"/"
	}
	url+=action
	return url
}

//Redirecciona la Ventana padre a un accion determinada
function redirect_parent_to_action(url){
	redirect_to_action(url, window.parent);
}

//Redirecciona una ventana a un url definido
function redirect_to_action(url, win){
	win = win ? win : window;
	win.location = get_kumbia_path(url)
}
/*
// Obtiene una referencia a un objeto del formulario generado
// o un document.getElementById
function $C(obj){
	return $("flid_"+obj);
}

// Obtiene el valor de un objeto de un formulario generado
function $V(obj){
	return $F("flid_"+obj);
}


/****************************************************
* Auth Functions
****************************************************/ /*
//Funcion que envia un formulario via AJAX
function ajaxRemoteForm(form, up, callback){
	new Ajax.Updater(up, form.action, {
		 method: "post",
		 asynchronous: true,
         evalScripts: true,
         onSuccess: function(transport){
			$(up).update(transport.responseText)
		},
		onLoaded: callback.before!=undefined ? callback.before: function(){},
		onComplete: callback.success!=undefined ? callback.success: function(){},
  		parameters: Form.serialize(form)
    });
  	return false;
}

var AJAX = new Object();

AJAX.xmlRequest = function(params){
	this.options = $H()
	if(!params.url && params.action){
		this.url = get_kumbia_url(params.action)
	}
	if(params.parameters){
		this.url+= "/&"+params.parameters
	}
	if(params.debug){
		alert(this.url)
	}
	if(this.action) {
		this.action = params.action;
	}
	if(params.asynchronous==undefined) {
		this.options.asynchronous = true
	} else {
		this.options.asynchronous = params.asynchronous
	}
	if(params.callbacks){
		if(params.callbacks.oncomplete){
			this.options.onComplete = params.callbacks.oncomplete
		}
		if(params.callbacks.before){
			this.options.onLoading = params.callbacks.before
		}
		if(params.callbacks.success){
			this.options.onSuccess = params.callbacks.success
		}
	}
	try {
		return new Ajax.Request(this.url, this.options)
	}
	catch(e){
		alert("KumbiaError: "+e.message+"["+e.name+"]");
	}
}


AJAX.viewRequest = function(params){
	this.options = {}
	if(!params.action){
		alert("KumbiaError: Ajax Action is not set!");
		return;
	}

	this.url = get_kumbia_url(params.action);
	if(params.parameters){
		this.url+="&"+params.parameters;
	}
	this.action = params.action;
	if(params.debug){
		alert(this.action)
	}
	if(params.asynchronous==undefined) {
		this.asynchronous = true
	} else {
		this.asynchronous = params.asynchronous
	}

	if(params.callbacks){
		if(params.callbacks.oncomplete){
			this.options.onComplete = params.callbacks.oncomplete
		}
		if(params.callbacks.before){
			this.options.onLoading = params.callbacks.before
		}
		if(params.callbacks.success){
			this.options.onSuccess = params.callbacks.success
		}
	}

	container = params.container;
	this.options.evalScripts = true

	if(!$(container)){
		window.alert("KumbiaError: Container Ajax Object '"+container+"' Not Found")
		return null
	}

	try {
		return new Ajax.Updater(container, this.url, this.options)
	}
	catch(e){
		alert("KumbiaError: "+e.message+" ["+e.name+"]");
	}

}

AJAX.execute = function(params){
	this.options = {}
	if(!params.action){
		alert("KumbiaError: AJAX Action is not set!");
		return;
	}
	this.url = get_kumbia_url(params.action);
	if(params.parameters){
		this.url+="&"+params.parameters;
	}
	this.action = params.action;
	if(params.debug){
		alert(this.action)
	}
	if(params.asynchronous==undefined) {
		this.asynchronous = false
	} else {
		this.asynchronous = params.asynchronous
	}

	if(params.callbacks){
		if(params.callbacks.oncomplete){
			this.options.onComplete = params.callbacks.onend
		}
		if(params.callbacks.before){
			this.options.onLoading = params.callbacks.before
		}
		if(params.callbacks.success){
			this.options.onSuccess = params.callbacks.success
		}
	}
	try {
		return new Ajax.Request(this.url, this.options)
	}
	catch(e){
		alert("KumbiaError: "+e.message+" ["+e.name+"]");
	}
}

AJAX.query = function(qaction){
	var me;
	new Ajax.Request(get_kumbia_url(qaction), {
			asynchronous: false,
			onSuccess: function(resp){
				xml = resp.responseXML
				data = xml.getElementsByTagName("data");
				if(document.all){
					xmlValue = data[0].text
				} else {
					xmlValue = data[0].textContent
				}
				me = xmlValue
			}
		}
	)
	return me
} */

function enable_upload_file(file){   
	jQuery("#"+file+"_span").show()
	jQuery("#"+file+"_span_pre").hide()
	document.getElementById(file).selectedIndex = 0;	
	if(document.all){
		jQuery("#"+file+"_file").click()
	}
	jQuery("#"+file+"_im").hide()
}

function upload_file(file){
/*	$(file+"_file").id = file+"_tmp"
	$(file).id = file+"_file"
	$(file+"_file").name = file+"_file"
	$(file+"_tmp").id = file
	$(file).name = file
  if (document.getElementById(file+"_file").value!=""){		
	/*im=new Image();
	im.id=file+"_x"
	$("#"+im.id).show()
	im.src=document.getElementById(file+"_file").value;
	
	//$("#"+im.id).show()
	//document.getElementById(file+"_i").src="1"
	//alert(document.getElementById(file+"_i").src)encodeURIComponent	
	document.getElementById(file+"_i").src = 'file:///'+encodeURIComponent(document.getElementById(file+"_file").value)
    alert(document.getElementById(file+"_i").src)
    $("#"+file+"_i").show()	
  }else{
    $('#'+file+'_i').hide()
  }	*/  	
}  

function cancel_upload_file(file){
	/*$(file+"_file").id = file+"_tmp"
	$(file).id = file+"_file"
	$(file+"_file").name = file+"_file"
	$(file+"_tmp").id = file
	$(file).name = file */
	document.getElementById(file+"_file").value="";		
	jQuery("#"+file+"_span").hide()
	jQuery("#"+file+"_span_pre").show()
}
 
function show_upload_image(file,i){        
	if(file.options[file.selectedIndex].value!=''){
	    jQuery('#'+i+'_im').show()
		document.getElementById(i+'_im').src = $Kumbia.path + "/img/" + file.options[file.selectedIndex].value				
	} else {
		jQuery('#'+i+'_im').hide()
	}
}     