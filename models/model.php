<?php

class Model
{
  public static $db_connection;

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

  /* ----------------------------- GET (SELECT) ----------------------------- */


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
  public static function recordExists(
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
   * Crear query de `SELECT`.
   *
   * Si no se le envían los otros 2 parámetros, no se le agrega el `WHERE`.
   *
   * @param string $table
   * @param array|null $where_clause
   * @param array use_like Indicar si se usará el mecanismo like o no. Esto solo
   * se puede utilizar cuando se hace uso del `WHERE`. Es un arreglo que indica
   * en qué posición se encontrará el modificador.
   * @param array|null $pdo_params
   * @return string
   */
  public static function createSelectQuery(
    string $table,
    string $where_clause = null,
    array $use_like = null,
    array $pdo_params = null
  ): string {
    $query = "SELECT * FROM {$table}";

    // Early return si no están establecidas dichas variables.
    if (
      !isset($where_clause) && empty($where_clause)
      && !isset($pdo_params) && empty($pdo_params)
    ) {
      return $query;
    }
    $attr = ":{$where_clause}";
    $equal = "=";

    // Modificar atributos si es que hay que utilizar el LIKE.
    if (isset($use_like) && !empty($use_like)) {

      // Si los 2 son false, no cambiar la query, ya que no requiere el LIKE.
      if ($use_like["beggining"] || $use_like["ending"]) {
        // Si se estableció el like, agregarlo en lugar del `=`.
        $equal = "LIKE";
        // Agregar símbolo al inicio, al final o en los 2 lados dependiendo de los
        // valores. Siento que se puede escribir de otra manera para no redundar,
        // pero no sé cómo. Tal vez podría ser con un loop.
        // 
        // Para utilizar el LIKE hay que hacer uso del CONCAT().
        // Aquí se especifica el por qué:
        // https://stackoverflow.com/a/7357296/13562806
        // Técnicamente por la preparación del query y SQL injection.
        $attr =
          "CONCAT("
          . ($use_like["beggining"] ? "'%', " : "")
          . $attr
          . ($use_like["ending"] ? ", '%'" : "")
          . ")";
      }
    }

    $query .= " WHERE {$where_clause} {$equal} {$attr}";

    echo "<br><br>" . $query . "<br><br>";

    return $query;
  }

  /**
   * Obtener arreglo asociativo con los resultados de un query.
   *
   * @param PDOStatement $pdo_statement
   * @return array
   */
  public static function getFetchedRecords(PDOStatement $pdo_statement): array
  {
    $records = [];

    while ($row = $pdo_statement->fetch(PDO::FETCH_ASSOC)) {
      array_push($records, $row);
    }

    return $records;
  }

  /**
   * Obtener registro o registros, ya que se puede obtener más de uno.
   *
   * @param string $table
   * @param array $where_clause
   * @param array $pdo_params
   * @return (array | null) Aquí se encuentran los resultados. Se regresa el
   * arreglo con los valores.
   */
  public static function getRecord(
    string $table,
    array $where_clause,
    ?array $pdo_params
  ): array {
    try {
      $query_select = self::createSelectQuery(
        $table,
        $where_clause["name"],
        null,
        $pdo_params
      );

      $query = self::$db_connection->prepare($query_select);
      $query->bindParam(
        ":{$where_clause["name"]}",
        $where_clause["value"],
        $pdo_params[$where_clause["name"]]
      );
      $query->execute();

      return self::getFetchedRecords($query);
    } catch (PDOException $e) {
      error_log("Error en la query - {$e}");
      exit();
    }
  }

  /**
   * Obtener un registro que contenga la cadena inicial busqué coincidencias ya
   * siendo que sean igual a la cadena o que comiencen con ese valor.
   * 
   * Se hace uso del símbolo `%` al final para la búsqueda.
   *
   * @return array
   */
  public static function getRecordLike(
    string $table,
    array $where_clause,
    array $use_like,
    array $pdo_params
  ): array {
    try {
      $query_select = self::createSelectQuery(
        $table,
        $where_clause["name"],
        $use_like,
        $pdo_params
      );

      $query = self::$db_connection->prepare($query_select);
      $query->bindParam(
        ":{$where_clause["name"]}",
        $where_clause["value"],
        $pdo_params[$where_clause["name"]]
      );
      $query->execute();

      return self::getFetchedRecords($query);
    } catch (PDOException $e) {
      error_log("Error en la query - {$e}");
      exit();
    }
  }

  /**
   * Obtener todos los campos de un query.
   *
   * @param string $table
   * @return array | null Arreglo con los registros obtenidos. Este arreglo
   * puede ser null si no se obtuvo nada.
   */
  public static function getEveryRecord(string $table): array | null
  {
    // Inicializamos para que se pueda utilizar la función y se devuelva el tipo
    // del objeto que se espera en un inicio.
    $query = self::$db_connection;
    try {
      $query_string = self::createSelectQuery($table);
      $query = self::$db_connection->prepare(
        $query_string
      );
      $query->execute();

      return self::getFetchedRecords($query);
    } catch (PDOException $e) {
      error_log("Error en la query - {$e}");
      exit();
    }
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
    // var_dump($pdo_params);
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
  public static function insertRecord(
    $table,
    array $param_values,
    array $pdo_params
  ): bool {
    try {

      $query = self::$db_connection->prepare(
        self::createInsertQuery($table, array_keys($param_values))
      );
      $param_names_and_values = self::bindEveryParamToArray($param_values);

      // echo $query->debugDumpParams();

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

  /* -------------------------- ACTUALIZACIÓN (PUT) ------------------------- */
  public static function createUpdateQuery(
    string $table,
    array $attribute_names,
    string $where_clause
  ): string {
    $query = "UPDATE {$table} SET ";

    for ($i = 0; $i < count($attribute_names); $i++) {
      $query .= "{$attribute_names[$i]} = :{$attribute_names[$i]}";
      $query .=
        ($i === count($attribute_names) - 1)
        ? " "
        : ", ";
    }

    $query .= "WHERE {$where_clause} = :{$where_clause}";

    return $query;
  }

  /**
   * Actualizar un registro de la BD.
   * 
   * Si no se actualizó nada del registro, devuelve true aunque haya encontrado
   * un registro.
   *
   * @param string $table
   * @param array $param_values Array con nombre y valor.
   * @param array $where_clause Where en donde se actualizará.
   * @param array $pdo_params
   * @return boolean Se actualizó o no.
   */
  public static function updateRecord(
    string $table,
    array $param_values,
    array $where_clause,
    array $pdo_params
  ): bool {
    if (!self::recordExists(
      $table,
      $where_clause["name"],
      $where_clause["value"],
      $pdo_params
    )) {
      return false;
    }
    try {
      $update_query = self::createUpdateQuery(
        $table,
        array_keys($param_values),
        $where_clause["name"]
      );

      // echo $update_query . "<br>";

      $query = self::$db_connection->prepare($update_query);

      // Agregamos el nombre del where y su valor, ya que también forman parte
      // de los parámetros.
      $param_values[$where_clause["name"]] = $where_clause["value"];

      // echo var_dump($param_values) . "<br>";

      $query->execute(self::bindEveryParamToArray($param_values));

      return $query->rowCount() > 0;
    } catch (PDOException $e) {
      error_log("Error en la query - {$e}");
      exit();
    }
  }

  /* ------------------------- ELIMINACIÓN (DELETE) ------------------------- */

  public static function createDeleteQuery(
    $table,
    $where_clause,
  ) {
    $query =
      "DELETE FROM {$table} 
      WHERE {$where_clause} = :{$where_clause}";

    return $query;
  }
  /**
   * Eliminar un registro.
   *
   * @param string $table
   * @param array $where_clause ["name"], ["value"].
   * @param array $pdo_params
   * @return bool
   */
  public static function deleteRecord(
    string $table,
    array $where_clause,
    array $pdo_params
  ): bool {
    $delete_query = self::createDeleteQuery($table, $where_clause["name"]);
    $query = self::$db_connection->prepare($delete_query);
    $query->bindParam(
      ":{$where_clause["name"]}",
      $where_clause["value"],
      $pdo_params[$where_clause["name"]]
    );
    $query->execute();

    return $query->rowCount() > 0;
  }
}
