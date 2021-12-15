<?php

abstract class Model {
  public function __construct($db_connection)
  {
    try {
      $this->db_connection = $db_connection;
    } catch (PDOException $e) {
      error_log("Error de conexión con la BD - {$e}", 0);
      exit('Error de conexión con la BD.');
    }
  }
}
