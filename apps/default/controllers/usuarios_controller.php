<?php
			
	class UsuariosController extends ApplicationController {

		public function index(){
		}
		
		public function nuevo_usuario(){
         // $this->paises=$this->Gmbvalores->find(6);
        }
        
        public function registro_usuario(){
         if($this->request('ci')!=""&&$this->request('nombre')){        		  
          $this->Gmusuarios->cargo=$this->request('cargo');
		  $this->Gmusuarios->email=$this->request('mail');		  
		  $this->Gmusuarios->usuario=$this->request('ci');
		  $this->Gmusuarios->passow=sha1($this->request('ci').$this->request('mail').$this->request('cargo').$this->request('ci'));		  
		  if($this->Gmusuarios->save()){
		     $this->Gmuadministradores->gmusuarios_id=$this->Gmusuarios->id;
		     $this->Gmuadministradores->ci=$this->request('ci');
		     $this->Gmuadministradores->nombre=$this->request('nombre');		     
		     $this->Gmuadministradores->appaterno=$this->request('app');
		     $this->Gmuadministradores->apmaterno=$this->request('apm');
		     $this->Gmuadministradores->genero=$this->request('sex');
		     $this->Gmuadministradores->paisnac=$this->request('pais');
		     $this->Gmuadministradores->lugarnac=$this->request('lugar');
		     $this->Gmuadministradores->fechanac=$this->request('jscalendar1');
		     $this->Gmuadministradores->image=$this->upload_file('image','gmg/config/adm');   
		     if($this->Gmuadministradores->save()){
			   Flash::success('Se guardo correctamente el registro');
			   return $this->route_to('controller: config', 'action: index');
			 } else {
					Flash::error('No se pudo guardar el registro');
		     }
		  }
		 } else {
                Flash::error('No se pudo guardar el registro, necesita introducir los datos minimos');
			    return $this->route_to('controller: config', 'action: index'); 
         }
        }

	}
	
?>
