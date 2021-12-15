<?php

abstract class Model
{
  // Protegida. Solo los children pueden acceder a esta propiedad.
  protected static $db_connection;
  public function __construct()
  {
    require_once "DB.php";

    try {
      self::$db_connection = DBConnection::getConnection();
    } catch (PDOException $e) {
      error_log("Error de conexión con la BD - {$e}", 0);
      exit('Error de conexión con la BD.');
    }
  }

  /**
   * Obtener un elemento por su ID en la tabla especificada.
   */

  /**
   * Undocumented function
   *
   * @param string $table
   * @param int $id
   * @return PDOStatement
   */
  public static function getById($table, $id)
  {
    // Inicializamos para que se pueda utilizar la función y se devuelva el tipo
    // del objeto que se espera en un inicio.
    $query = new PDOStatement();
    try {
      $query = self::$db_connection->prepare(
        "SELECT *
        FROM {$table}
        WHERE id = :id"
      );
      $query->bindParam(":id", $id, PDO::PARAM_INT);
      $query->execute();
    } catch (PDOException $e) {
      error_log("Error en la query - {$e}");
      exit();
    }
    // Regresamos el resultado de la query. Toca manualmente hacer el fetch
    // indicando los datos que requerimos.
    return $query;
  }
}
