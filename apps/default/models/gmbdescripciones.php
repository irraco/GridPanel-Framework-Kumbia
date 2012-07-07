<?php

     class Gmbdescripciones extends ActiveRecord{
     
       public function __construct(){
         $this->has_many('gmagrupos','gmbvalores');                
       }
     
     } 

?>