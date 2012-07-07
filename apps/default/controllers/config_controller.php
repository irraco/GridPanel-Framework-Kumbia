<?php
			
	class ConfigController extends ApplicationController {
         
		public function index(){                
		}
	    public function menu(){
        echo "<script>
                menu('config/create_xml','toolbar_zone');
              </script>";              
        }
		public function create_xml(){
        header('Content-type:text/xml');	   
		$dom = "<?xml version='1.0' encoding='iso-8859-1'?>"; ?>
<?      $dom.= "\n<toolbar disableType=\"image\" absolutePosition=\"auto\" toolbarAlign=\"left\">
	             <ImageButton src=\"".KUMBIA_PATH."public/img/gmg/config/menu/iconSave.gif\" height=\"25\" width=\"25\" id=\"0_save\" tooltip=\"Guardar\"/>
	             <ImageButton src=\"".KUMBIA_PATH."public/img/gmg/config/menu/iconPrint.gif\" height=\"25\" width=\"25\" id=\"0_print\" tooltip=\"Imprimir\"/>
	             <divider id=\"div_1\"/>
	             <ImageButton src=\"".KUMBIA_PATH."public/img/gmg/config/menu/iconNewNewsEntry.gif\" height=\"25\" width=\"25\" id=\"0_new\" tooltip=\"Nuevo\"/>
	             <ImageButton src=\"".KUMBIA_PATH."public/img/gmg/config/menu/icon20.gif\" height=\"25\" width=\"25\" id=\"0_form\" tooltip=\"Formulario\"/>
	             <divider id=\"div_2\"/>
	             <ImageButton src=\"".KUMBIA_PATH."public/img/gmg/config/menu/iconSearch.gif\" height=\"25\" width=\"25\" id=\"0_search\" tooltip=\"Buscar...\"/>
	             <ImageButton src=\"".KUMBIA_PATH."public/img/gmg/config/menu/iconFilter.gif\" height=\"25\" width=\"25\" id=\"0_filter\" tooltip=\"Filtrar\"/>
	             <divider id=\"div_3\"/>
	             <ImageButton src=\"".KUMBIA_PATH."public/img/gmg/config/menu/iconDelete.gif\" height=\"25\" width=\"100px\" id=\"0_delete\" tooltip=\"Borrar\"/>
                 </toolbar>";
		
		print $dom;
       }

	}
	
?>
