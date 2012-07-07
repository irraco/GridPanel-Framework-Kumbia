<?php

     class Gmagrupos extends ActiveRecord{
       public function __construct(){                 
        
        $this->has_and_belongs_to_many('gmamodulos','gmbdescripciones');
        $this->belongs_to('gmamodulos','gmbdescripciones');
       }
     } 

?>