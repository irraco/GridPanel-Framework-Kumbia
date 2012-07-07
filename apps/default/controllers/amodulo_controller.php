<?php
			
	class AmoduloController extends ApplicationController {
        public $anio;
        public function __construct() {
         $this->anio=$anio=date('Y');
        }
		public function index(){        
         $this->jscript = "<script>
                mygrid=new D('gridbox');
                mygrid.setImagePath('".KUMBIA_PATH."javascript/codebase/imgs/');
                mygrid.setHeader('Modulo,Descripcion,Fecha Creacion,Ultima Modificacion,Logo del Modulo');
                mygrid.setInitWidths('250,950,150,150,150');
                mygrid.bw('left,left,center,center,center');
                mygrid.be('ro,ed,ro,ro,img');
                mygrid.bm('str,str,date,date,str');               
                mygrid.wL('nombre,descripcion,fecha_at,fecha_in,image');
                mygrid.setSkin('light');
                mygrid.init();
                mygrid.loadXML('".KUMBIA_PATH."default/amodulo/generateXML');
                aad=new Iq('".KUMBIA_PATH."default/amodulo/updateXML');
                aad.ahP(true);
                aad.xU(1,ka);                
                aad.JI('GET');
                aad.init(mygrid);
                function ka(value,Sg){
                 if(value.toString().PA()==''){
                  jE('El valor de la celda del campo <b>'+Sg+'</b> no puede ser vacio.');
                  return false
                 }else return true;
                }               
                function jE(msg){
                 var msger=document.getElementById('msn');
                 msger.innerHTML=msg;
                 clearTimeout(toRef);
                 toRef=setTimeout(\"jE('&nbsp;')\",5500)
                }
                var toRef;
               </script>";
		}
		public function generateXML(){
		 header('Content-type:text/xml');
         $cad="";
         $cad.= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>
<?		 $cad.="<rows>";
         foreach($this->Gmamodulos->find() as $co){
		  $cad.="<row id='".$co->id."'>";
		  $cad.="<cell>";
		  $cad.=utf8_encode($co->nombre);//$co->nombre;//
		  $cad.="</cell>"; 
		  $cad.="<cell>";
		  $cad.=utf8_encode($co->descripcion);//$co->descripcion;//
		  $cad.="</cell>";
		  $cad.="<cell>";
		  $an="";$dd="";$mm="";
		  $an=substr($co->fecha_at,0,4);$dd=substr($co->fecha_at,8,2);$mm=substr($co->fecha_at,5,2);
		  $cad.=$mm."/".$dd."/".$an;
		  $cad.="</cell>";
		  $cad.="<cell>";
		  $an="";$dd="";$mm="";
		  $an=substr($co->fecha_in,0,4);$dd=substr($co->fecha_in,8,2);$mm=substr($co->fecha_in,5,2);
		  $cad.=$mm."/".$dd."/".$an;
		  $cad.="</cell>";
		  $cad.="<cell>";
		  $cad.=KUMBIA_PATH."img/".$co->image;
		  $cad.="</cell>";
		  $cad.="</row>\n";
		 }
		 $cad.="</rows>";		
		 print $cad;
        }
        public function updateXML(){
         header('Content-type:text/xml');
         $cad="";
         $cad.="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>
<?       $this->Gmamodulos->find($this->request('gr_id'));
         $this->Gmamodulos->descripcion=$this->request('descripcion');
         $this->Gmamodulos->update();
         $action = "update";
         $cad.="<data>";         
         $cad.="<action type='".$action."' sid='".$this->request('gr_id')."' tid='".$this->request('gr_id')."'/>";
         $cad.="</data>";
         print $cad;
        }
        /* Second half */
        public function index1(){
         $this->modulo=$this->Gmamodulos->find("conditions: id<>idtype");
         $this->jscript = "<script>
                menu('amodulo/create_xml','toolbar_zone');
                mygrid=new D('gridbox'); 
                mygrid.gN=true;
                mygrid.eg='".KUMBIA_PATH."javascript/codebase/imgs/csh_bluebooks/';                
                mygrid.setHeader('Descripcion,Fecha Creacion,Ultima Modificacion');
                mygrid.setInitWidths('1000,150,150');
                mygrid.bw('left,center,center');
                mygrid.be('tree,ro,ro');                
                mygrid.wL('descripcion,fecha_at,fecha_in'); 
                mygrid.setSkin('light');
                mygrid.bm('str,date,date');               
                mygrid.init();                  
                mygrid.loadXML('".KUMBIA_PATH."default/amodulo/generateXML1'); 
                aad=new Iq('".KUMBIA_PATH."default/amodulo/updateXML1');
                aad.ahP(true);
                aad.xU(0,ka);                
                aad.alx('error',myErrorHandler);
                aad.JI('GET');
                aad.init(mygrid);               
                function myErrorHandler(obj){
                 alert('Ocurrio un Error.'+obj.firstChild.nodeValue);
                 aad.amF=true;return false;
                }
                function ka(value,Sg){
                 if(value.toString().PA()==''){
                  jE('El valor de la celda del campo <b>'+Sg+'</b> no puede ser vacio.');
                  return false
                 }else return true;
                }
                function jE(msg){
                 var msger=document.getElementById('msn');
                 msger.innerHTML=msg;
                 clearTimeout(toRef);
                 toRef=setTimeout(\"jE('&nbsp;')\",5500)
                }
                var toRef;
               </script>";                        		
        }
        public function generateXML1(){
		 header('Content-type:text/xml');
         $cad="";
         $cad.= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>
<?		 $cad.="<rows id='0'>";
         foreach($this->Gmbdescripciones->find() as $co){
		  $cad.="<row id='".$co->id."'>";		  
		  $cad.="<cell image='folder.gif'>";
		  $cad.=utf8_encode($co->descripcion);
		  $cad.="</cell>";
		  $cad.="<cell>";
		  $an="";$dd="";$mm="";
		  $an=substr($co->fecha_at,0,4);$dd=substr($co->fecha_at,8,2);$mm=substr($co->fecha_at,5,2);
		  $cad.=$mm."/".$dd."/".$an;
		  $cad.="</cell>";
		  $cad.="<cell>";
		  $an="";$dd="";$mm="";
		  $an=substr($co->fecha_in,0,4);$dd=substr($co->fecha_in,8,2);$mm=substr($co->fecha_in,5,2);
		  $cad.=$mm."/".$dd."/".$an;
		  $cad.="</cell>";           
          foreach($this->Gmbvalores->find("conditions: gmbdescripciones_id={$co->id} and year(fecha_at)={$this->anio}") as $asig){
           $nnu=0;
           $opc=explode("_",utf8_encode($asig->opciones));
           $an="";$dd="";$mm="";$ani="";
		   $an=substr($asig->fecha_at,0,4);$dd=substr($asig->fecha_at,8,2);$mm=substr($asig->fecha_at,5,2);
		   $ani=$mm."/".$dd."/".$an;
		   $an="";$dd="";$mm="";$anio="";
		   $an=substr($asig->fecha_in,0,4);$dd=substr($asig->fecha_in,8,2);$mm=substr($asig->fecha_in,5,2);
		   $anio=$mm."/".$dd."/".$an;
           foreach($opc as $vv=>$e){
            $cad.="<row id='".$asig->id.".".$nnu."'>";
		    $cad.="<cell image='leaf.gif'>".$e."</cell><cell>".$ani."</cell><cell>".$anio."</cell>";
            $cad.="</row>\n";$nnu++;
		   }
          } 	  
		  $cad.="</row>\n";
		 }
		 $cad.="</rows>";
		 print $cad;
        }
        
        public function updateXML1(){
         header('Content-type:text/xml');
         $cad="";
         $cad.="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>
<?       $codigo=explode(".",$this->request('gr_id'));                                                              
         if(isset($_GET["!nativeeditor_status"]) && trim($_GET["!nativeeditor_status"])=="inserted"){          
          $this->Gmbvalores->find($codigo[0]);
          $elemento=explode("_",$this->Gmbvalores->opciones);
          foreach($elemento as $n=>$value){
           if($codigo[3]=="A"){
            if($elemento[$codigo[1]]==$value){
             $arrn[]=$this->request('descripcion');
             $arrn[$n+1]=$value;
            }else{ 
              $arrn[]=$value;
            }
           }elseif($codigo[3]=="D"){
            if($elemento[$codigo[1]]==$value){
             $arrn[]=$value;
             $arrn[$n+1]=$this->request('descripcion');
            }else{
              $arrn[]=$value;
            }
           }
          }
          if ($this->Gmbvalores->opciones!=implode("_",$arrn)){ 
           $this->Gmbvalores->opciones=implode("_",$arrn);
           $this->Gmbvalores->update();   
          } 
          $action = "insert";
         }else if(isset($_GET["!nativeeditor_status"]) && $_GET["!nativeeditor_status"]=="deleted"){          
          $this->Gmbvalores->find($codigo[0]);
          $elemento=explode("_",$this->Gmbvalores->opciones);
          unset($elemento[$codigo[1]]);
          $nuevo=sizeof($elemento)==1?$elemento[$codigo[1]]:implode("_",$elemento);
          $this->Gmbvalores->opciones=$nuevo;
          $this->Gmbvalores->update(); 
          $action = "delete";
         }elseif (!isset($codigo[1])){
              $this->Gmbdescripciones->find($this->request('gr_id'));
              if ($this->Gmbdescripciones->descripcion!=$this->request('descripcion')){
               $this->Gmbdescripciones->descripcion=$this->request('descripcion');
               $this->Gmbdescripciones->update();
              }
              $action = "update";
         }else{              
              $this->Gmbvalores->find($codigo[0]);              
              $elemento=explode("_",$this->Gmbvalores->opciones);
              if($codigo[3]=='D'){$elemento[$codigo[1]+1]=$this->request('descripcion');
              }else{               
              $elemento[$codigo[1]]=$this->request('descripcion');
              }                             
              $nuevo=sizeof($elemento)==1?$elemento[$codigo[1]]:implode("_",$elemento); 
              if ($this->Gmbvalores->opciones!=$nuevo){
               $this->Gmbvalores->opciones=$nuevo;
               $this->Gmbvalores->update();
              }
              $action = "update";
         } 
         $cad.="<data>";
         if (isset($codigo)&&$codigo!=""){
          $cad.="<action type='".$action."' sid='".$this->request('gr_id')."' tid='".$this->request('gr_id')."'/>";
         }else{
          $cad.="<action type='error'>Presione el boton Actualizar por favor.</action>";
         }
         $cad.="</data>";
         print $cad;    
        }
        
        public function generateXML2($id){
		 header('Content-type:text/xml');
         $cad="";
         $cad.= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>
<?		 $cad.="<rows>";
         $this->Gmamodulos->find($id);
         foreach($this->Gmamodulos->getGmagrupos() as $co){
           if (($this->request('adidas')==1&&$co->getGmbdescripciones()->opcconst==0)||($this->request('adidas')=="")){
    		  $cad.="<row id='".$co->getGmbdescripciones()->id."'>";
    		  $cad.="<cell image='folder.gif'>";
    		  $cad.=utf8_encode($co->getGmbdescripciones()->descripcion);
    		  $cad.="</cell>";
    		  $cad.="<cell>";
    		  $an="";$dd="";$mm="";
    		  $an=substr($co->getGmbdescripciones()->fecha_at,0,4);$dd=substr($co->fecha_at,8,2);$mm=substr($co->getGmbdescripciones()->fecha_at,5,2);
    		  $cad.=$mm."/".$dd."/".$an;
    		  $cad.="</cell>";
    		  $cad.="<cell>";
    		  $an="";$dd="";$mm="";
    		  $an=substr($co->getGmbdescripciones()->fecha_in,0,4);$dd=substr($co->getGmbdescripciones()->fecha_in,8,2);$mm=substr($co->getGmbdescripciones()->fecha_in,5,2);
    		  $cad.=$mm."/".$dd."/".$an;
    		  $cad.="</cell>";
              foreach($this->Gmbvalores->find("conditions: gmbdescripciones_id={$co->getGmbdescripciones()->id} and year(fecha_at)={$this->anio}") as $asig){
               $nnu=0;
               $opc=explode("_",utf8_encode($asig->opciones));
               $an="";$dd="";$mm="";$ani="";
    		   $an=substr($asig->fecha_at,0,4);$dd=substr($asig->fecha_at,8,2);$mm=substr($asig->fecha_at,5,2);
    		   $ani=$mm."/".$dd."/".$an;
    		   $an="";$dd="";$mm="";$anio="";
    		   $an=substr($asig->fecha_in,0,4);$dd=substr($asig->fecha_in,8,2);$mm=substr($asig->fecha_in,5,2);
    		   $anio=$mm."/".$dd."/".$an;
               foreach($opc as $vv=>$e){
                $cad.="<row id='".$asig->id.".".$nnu."'>";
    		    $cad.="<cell image='leaf.gif'>".$e."</cell><cell>".$ani."</cell><cell>".$anio."</cell>";
                $cad.="</row>\n";$nnu++;
    		   }
              } 
		      $cad.="</row>\n";
		   }   
		 }
		 $cad.="</rows>";
		 print $cad;
        }
        
        public function create_xml(){
        header('Content-type:text/xml');
		$dom = "<?xml version='1.0' encoding='UTF-8'?>"; ?>
<?      $dom.= "<toolbar disableType=\"image\" absolutePosition=\"auto\" toolbarAlign=\"center\">";
        $dom.= "<SelectButton id=\"0_select\" width=\"600px\" height=\"16px\">";
        $dom.= "<option value=''> </option>";
        foreach($this->Gmamodulos->find("conditions: id<>idtype") as $co){
          $dom.= "<option value=\"".$co->id."\">".$co->nombre."</option>";
        }
        $dom.= "</SelectButton>";
        $dom.= "<ImageButton src=\"".KUMBIA_PATH."public/img/gmg/config/menu/images/iconCheck.gif\" width=\"18px\" height=\"18px\" id=\"0_check\" tooltip=\"Filtrar\" disableImage=\"".KUMBIA_PATH."public/img/gmg/config/menu/images/iconCheck_dis.gif\"/>";                  
        $dom.= "<divider id=\"div_1\"/>"; 
	    $dom.= "<ImageButton src=\"".KUMBIA_PATH."public/img/gmg/config/menu/images/icon20.gif\" width=\"18px\" height=\"18px\" id=\"0_antes\" tooltip=\"Adicionar Antes\" disableImage=\"".KUMBIA_PATH."public/img/gmg/config/menu/images/icon20_dis.gif\"/>"; 
	    $dom.= "<ImageButton src=\"".KUMBIA_PATH."public/img/gmg/config/menu/images/icon10.gif\" width=\"18px\" height=\"18px\" id=\"0_despues\" tooltip=\"Adicionar Despues\" disableImage=\"".KUMBIA_PATH."public/img/gmg/config/menu/images/icon10_dis.gif\"/>"; 	             
	    $dom.= "<divider id=\"div_2\"/>
	             <ImageButton src=\"".KUMBIA_PATH."public/img/gmg/config/menu/images/iconDelete.gif\" width=\"18px\" height=\"18px\" id=\"0_borrar\" tooltip=\"Borrar\" disableImage=\"".KUMBIA_PATH."public/img/gmg/config/menu/images/iconDelete_dis.gif\"/>
	             <ImageButton src=\"".KUMBIA_PATH."public/img/gmg/config/menu/images/iconSearch.gif\" width=\"18px\" height=\"18px\" id=\"0_actualizar\" tooltip=\"Actualizar\" disableImage=\"".KUMBIA_PATH."public/img/gmg/config/menu/images/iconSearch_dis.gif\"/>";       	             
        $dom.= "</toolbar>";
		print $dom;
       }
       /* Tree half */
       public function index2(){
        $this->realidad=$this->Gmbvalores->count("year(fecha_at)={$this->anio}");
        $this->modulo=$this->Gmamodulos->find("conditions: id<>idtype");
        $this->jscript = "<script>
                mygrid=new D('gridbox');
                mygrid.gN=true;
                mygrid.eg='".KUMBIA_PATH."javascript/codebase/imgs/csh_bluebooks/';
                mygrid.setHeader('Descripcion,Fecha Creacion,Ultima Modificacion');
                mygrid.setInitWidths('1000,150,150');
                mygrid.bw('left,center,center');
                mygrid.be('tree,ro,ro');
                mygrid.wL('descripcion,fecha_at,fecha_in');
                mygrid.setSkin('light');
                mygrid.bm('str,date,date');
                mygrid.init();
                mygrid.loadXML('".KUMBIA_PATH."default/amodulo/generateXML1');
               </script>";
        if ($this->realidad==0)$this->anio--;       
       }                   
       public function copypast(){
        $baseDatos=$this->Gmbvalores->find("conditions: year(fecha_at)={$this->anio}");        
        foreach($baseDatos as $asig){
         $this->Gmbvalores->gmbdescripciones_id=$asig->gmbdescripciones_id;
         $this->Gmbvalores->opciones=$asig->opciones;
         $this->Gmbvalores->create();
        }
        $this->anio++;
        Flash::success("Modulos {$this->anio} creados correctamente.");
       }
       /* Four half */
       public function index3(){}
	}
	
?>
