<?php

class Model
{
  // Protegattributea. Solo los children pueden acceder a esta propiedad.
  protected static $db_connection;
  protected function __construct()
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
   * Obtener todos los campos de un query.
   *
   * @param string $table Tabla de la cual se obtendrán los datos.
   * @param string $attribute Si no es especificado, se obtendrán todos los de
   * la tabla especificada.
   * @return PDOStatement
   */
  protected static function getEveryField($table, $attribute = "")
  {
    // Inicializamos para que se pueda utilizar la función y se devuelva el tipo
    // del objeto que se espera en un inicio.
    $query = new PDOStatement();
    try {
      $query_string =
        "SELECT *
        FROM {$table}";

      // Agregar attribute si fue especificado.
      if (empty($attribute)) {
        $query_string .= " WHERE attribute = :attribute";
        $query->bindParam(":attribute", $attribute, PDO::PARAM_INT);
      }

      $query = self::$db_connection->prepare(
        $query_string
      );

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
