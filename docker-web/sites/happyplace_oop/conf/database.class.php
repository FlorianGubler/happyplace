<?php

class Database
{
  private $host, $database, $username, $password;
  public $connection;
  private $port = 3306;
  
  function __construct($host, $username, $password, $database, $port = 3306, $autoconnect = true)
  {
    $this->host = $host;
    $this->database = $database;
    $this->username = $username;
    $this->password = $password;
    $this->port = $port;
    if ($autoconnect) $this->open();
  }
  
  function open()
  {
    $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
  }
  
  function close()
  {
    $this->connection->close();
  }
  
  function query($query)
  {
    return $this->connection->query($query);
  }
  
  function escape($string)
  {
    return $this->connection->escape_string($string);
  }
}