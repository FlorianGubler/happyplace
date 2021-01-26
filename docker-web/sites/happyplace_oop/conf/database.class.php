<?php
    class Database{
        private $connection;
        public $conn_err;

        public function __construct($servername, $username, $password, $dbname){
            $this->connection = new mysqli($servername, $username, $password, $dbname);

            if ($this->$connection->connect_error) {
                $this->conn_err = $this->$connection->connect_error;
            }
        }

        public function insert($table, $values, $filter = false){
            if(!$filer){
                foreach($values as $val){
                    $cols .= ""
                }
                $sql = "INSERT INTO '$table' (".$val[0].") VALUES (".$val[0].");";
            }
        }
    }
?>