<?php

class Model
{
  // Protegattributea. Solo los children pueden acceder a esta propiedad.
  protected static $db_connection;
  public static function initDbConnection()
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
   * 
   * 
   * @param string $username
   * @return boolean
   */

  /**
   * Revisar si el atributo se encuentra en la BD.
   *
   * https://stackoverflow.com/a/4254003/13562806
   * 
   * @param string $table Nombre de la tabla.
   * @param string $attributeName Nombre del atributo.
   * @param string $attributeValue Valor del atributo.
   * @return boolean
   */
  public static function attributeExists(
    string $table,
    string $attributeName,
    string $attributeValue
  ): bool {
    try {
      // Esta opción devolverá 0 o 1 resultados. Esto ayudará a ver si existe o
      // no.
      $query = self::$db_connection->prepare(
        "SELECT COUNT(1)
      FROM {$table}
      WHERE {$attributeName} = :attributeValue;
    "
      );

      $query->bindParam(":attributeValue", $attributeValue, PDO::PARAM_STR);
      $query->execute();

      // Si no hay filas, devolver false, indicando que no se hizo la inserción.
      return $query->rowCount() == 0 ? false : true;
    } catch (PDOException $e) {
      error_log("Error en la query - {$e}");
      exit();
    }

    // Si llegó hasta aquí significa que no regresó nada en el try-catch, entonces
    // regreso false.
    return false;
  }

  /**
   * Obtener todos los campos de un query.
   *
   * @param string $table Tabla de la cual se obtendrán los datos.
   * @param string $attributeName Nombre del atributo o columna que se quiere
   * obtener.
   * @param string $attributeValue Si no es especificado, se obtendrán todos los
   * de la tabla especificada.
   * @return PDOStatement
   */
  protected static function getEveryField($table, $attributeName = "", $attributeValue = "")
  {
    // Inicializamos para que se pueda utilizar la función y se devuelva el tipo
    // del objeto que se espera en un inicio.
    $query = new PDOStatement();
    try {
      $query_string =
        "SELECT *
        FROM {$table}";

      // Agregar attribute si fue especificado.
      if (!empty($attributeName && !empty($attributeValue))) {
        $query_string .= " WHERE {$attributeName} = :attribute";
        $query->bindParam(":attribute", $attributeValue, PDO::PARAM_INT);
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
