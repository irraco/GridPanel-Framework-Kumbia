<?php
	
	class Gmamodulos extends ActiveRecord {
		public function __construct(){
         $this->has_many('gmagrupos');
       }
	}

?>