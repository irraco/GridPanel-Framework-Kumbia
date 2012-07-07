<?php
			
	class ModulosController extends ApplicationController {

		public function index(){
          $this->superior=$this->Gmamodulos->find("conditions: id=idtype");          
		}
		
        public function modificar($id){
          $this->seleccionado=$this->Gmamodulos->find($id);          
		}
		
		public function modificando($id){
		  $this->Gmamodulos->find($id);
          $this->Gmamodulos->nombre=$this->request('nombre');
          $this->Gmamodulos->descripcion=$this->request('desc');
          $this->Gmamodulos->image=$this->upload_file('image','modulos');
          $this->Gmamodulos->autores=$this->request('autores');
          if($this->Gmamodulos->update()){
					Flash::success('Se guardo correctamente el registro');
					return $this->route_to('controller: config', 'action: index');
		  } else {
					Flash::error('No se pudo guardar el registro');
		  } 
		}
		
		public function listasubmodulos($id){
          $this->inferior=$this->Gmamodulos->find("conditions: id<>idtype and idtype=$id");
		}
		
		public function listadescripcion(){         
		}
		
		public function modificar_descripcion($id){
		  $this->elementos=$this->Gmbdescripciones->find($id); 
		}
		
		public function modifica_descripcion($id){
		  $this->Gmbdescripciones->find($id);
		  $this->Gmbdescripciones->nombret=$this->request('nombre');
		  $this->Gmbdescripciones->campot=$this->request('campo');
		  $this->Gmbdescripciones->descripcion=$this->request('desc');
		  if($this->Gmbdescripciones->update()){
					Flash::success('Se guardo correctamente el registro');
					return $this->route_to('controller: config', 'action: index');
		  } else {
					Flash::error('No se pudo guardar el registro');
		  }
		}
		
		public function elementosm($id){
		  $aa=date('Y');
		  $this->elementos=$this->Gmbvalores->find_first("conditions: gmbdescripciones_id=$id and year(fecha_at)=$aa"); 
		}
		
		public function modifica_elementos($id){
		  $this->Gmbvalores->find($id);		  
		  if ($this->Gmbvalores->opciones!=""){
		   $arra=explode("_",$this->Gmbvalores->opciones);		  		  		   
           foreach($arra as $n=>$v){
            $ban=true;
            if ($n==$this->request('opc')&&$this->request('antes')){
             $newa[]=$this->request('nuevo');
             $newa[]=$v; $ban=false;            
            }elseif ($n==$this->request('opc')&&$this->request('despues')){
             $newa[]=$v; $ban=false;
             $newa[]=$this->request('nuevo');
            }elseif ($n==$this->request('opc')&&$this->request('quitar')){
             $ban=false;             
            }	
            if ($ban)$newa[]=$v; 	     
           }  
           $this->Gmbvalores->opciones=implode("_",$newa);
          }else{ 
           $this->Gmbvalores->opciones=$this->request('nuevo');
          }                		  
		  if($this->Gmbvalores->update()){
					Flash::success('Se guardo correctamente el registro');
					return $this->route_to('controller: config', 'action: index');
		  } else {
					Flash::error('No se pudo guardar el registro');
		  }
		}
		
		public function elementosc($id){
		  $aa=date('Y');
		  $this->elementos=$this->Gmbvalores->find_first("conditions: gmbdescripciones_id=$id and year(fecha_at)=$aa");
		}
		
		public function modifica_fijo($id){
		  $this->Gmbvalores->find($id);		  
          $this->Gmbvalores->opciones=$this->request('nuevo');          
		  if($this->Gmbvalores->update()){
					Flash::success('Se guardo correctamente el registro');
					return $this->route_to('controller: config', 'action: index');
		  } else {
					Flash::error('No se pudo guardar el registro');
		  }
		}
		
	}
	
?>
