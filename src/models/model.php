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
    string $attributeValue,
    array $pdo_params
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

      $query->bindParam(
        ":attributeValue",
        $attributeValue,
        $pdo_params[$attributeName]
      );
      $query->execute();

      // Si no hay filas, devolver false, indicando que no hay coincidencias.
      return $query->fetch(PDO::FETCH_ASSOC)["COUNT(1)"] > 0;
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

  public static function createInsertQuery(
    string $table,
    array $attributes
  ): string {
    $query = "INSERT INTO `{$table}` (";

    // Recorrer los nombres de atributos para seguir con la query.
    for ($i = 0; $i < count($attributes); $i++) {
      $query .= $attributes[$i];
      // Si se encuentra en el último valor, no agregar coma, agregar el
      // paréntesis de cierre y agregar "VALUES".
      $query .=
        ($i === count($attributes) - 1)
        ? ") VALUES ("
        : ", ";
    }

    // Ahora, agregar los atributos en el formato de PDO.
    for ($i = 0; $i < count($attributes); $i++) {
      $query .= ":" . $attributes[$i];
      // Si se encuentra en el último valor, no agregar coma, agregar el
      // paréntesis de cierre y agregar "VALUES".
      $query .=
        ($i === count($attributes) - 1)
        ? ")"
        : ", ";
    }
    // var_dump($query);
    return $query;
  }

  /**
   * PDO::bindParam() en cada parámetro de forma automática.
   *
   * Creamos un array mapeado en donde guardamos el nombre del parámetro y su
   * valor. Esto luego lo podemos enviar por parámetro en el método
   * PDO::execute().
   */
  public static function bindEveryParamToArray(
    $param_values,
  ): array {
    $pdo_params = [];

    foreach ($param_values as $name => $value) {
      $pdo_params[":{$name}"] = $value;
    }
    var_dump($pdo_params);
    return $pdo_params;
  }

  /**
   * Insertar nuevos valores en la tabla especificada.
   *
   * @param string $table Tabla a la que insertar.
   * @param array $param_values Nombre de los atributos y su valor.
   * @param array $pdo_params Arreglo asociativo que contiene el nombre del
   * atributo en la tabla y su valor.
   * @return boolean Se realizó la inserción o no.
   */
  public static function insertNew(
    $table,
    array $param_values,
    array $pdo_params
  ): bool {
    try {

      $query = self::$db_connection->prepare(
        self::createInsertQuery($table, array_keys($param_values))
      );
      $param_names_and_values = self::bindEveryParamToArray($param_values);

      echo $query->debugDumpParams();

      // Con un mapa podemos ejecutar la query sin utilizar la función bindParam
      // por cada parámetro. La desventaja es que no se puede pasar el tipo de
      // dato como con bindParam, pero es más "sencillo". Intenté hacer los
      // bindParam de forma manual, pero me daba errores, por lo que, al menos
      // por el momento, dejaré así.
      $query->execute($param_names_and_values);


      // Si no hay filas, devolver false, indicando que no se hizo la inserción.
      return $query->rowCount() == 0 ? false : true;
    } catch (PDOException $e) {
      error_log("Error en la query - {$e}");
      exit();
    }
    return false;
  }
}
