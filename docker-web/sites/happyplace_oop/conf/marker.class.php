<?php
    class Marker{
        private $id;
        private $latitude;
        private $longitude;
        private $color;

        public function __construct($id, $lat, $lon, $color){
            $this->id = $id;
            $this->latitude = $lat;
            $this->longitude = $lon;
            $this->color = $color;
        }

        public function ShowMarkerOnMap(){
            $latpoint = $this->latitude;
            $lngpoint = $this->longitude;
            $markcolor = urlencode($this->color);
            return "add_map_point($lngpoint, $latpoint, '$markcolor');\n";
        }

        public function __get($name)
        {
            if (!empty($this->$name)){
                return $this->$name;
            }
            else{
                return false;
            } 
        }
    }
?>