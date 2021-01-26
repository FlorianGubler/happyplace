<?php
    class Apprentices{
        private $id;
        private $firstname;
        private $lastname;
        private $marker;

        public function __construct($id, $firstname, $lastname, $marker){
            $this->id = $id;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->marker = $marker;
        }
        
        public function getName(){
            return $this->firstname.", ".$this->lastname;
        }
    }
?>