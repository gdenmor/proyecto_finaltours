<?php
    namespace App\Event;
    use Symfony\Contracts\EventDispatcher\Event;
    class CorreoEvent extends Event{

        private $data;
        public function __construct($data){
            $this->data = $data;
        }

        public function getData(){
            return $this->data;
        }

        public function setData($data){
            $this->data = $data;
        }
    }