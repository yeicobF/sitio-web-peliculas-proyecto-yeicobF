<?php

class Model
{
  /**
   * Conexión con la base de datos. Se basa en el patrón de diseño `Singleton`.
   *
   * @var PDO
   */
  public static $db_connection;
  const DEFAULT_TIME_ZONE = 'America/Mexico_City';

  /**
   * Posibles errores de la BD. Esto ayudaría a la gestión de los mismos para
   * poder ser más específico al momento de tratarlos.
   */
  const OPERATION_INFO = [
    0 => "Operación NO exitosa",
    1 => "Operación exitosa",
    2 => "Intento de inserción o actualización con un campo único ya existente",
    3 => "Campo no existente",
    4 => "Error con la conexión de la base de datos",
    5 => "Error con la query",
  ];

  /**
   * Inicializar conexión con la BD. 
   *
   * Esto se hace antes de utilizar todo lo que tenga que ver con Modelos y sus
   * derivados.
   *
   * @return void
   */
  public static function initDbConnection()
  {
    require_once __DIR__ . "/DB.php";

    try {
      self::$db_connection = DBConnection::getConnection();
    } catch (PDOException $e) {
      error_log("Error de conexión con la BD - {$e}", 0);
      exit('Error de conexión con la BD.');
    }
  }

  /* -------------------------- OBTENCIÓN DE FECHA -------------------------- */

  /**
   * Obtener la hora actual en el formato de la BD.
   *
   * @param string $timeZone Zona horaria en la cual se basa la obtención.
   * https://www.php.net/manual/es/function.date.php#116914
   * https://www.php.net/manual/es/timezones.america.php
   * @return string
   */
  public static function getCurrentTime(
    string $defaultTimeZone = self::DEFAULT_TIME_ZONE
  ): string {
    if (date_default_timezone_get() != $defaultTimeZone) {
      date_default_timezone_set($defaultTimeZone);
    }
    return date("H:i:s");
  }

  /**
   * Obtener la fecha actual en el formato de la Base de Datos.
   *
   * ---
   * 'YYYY-MM-DD' -> The supported range is '1000-01-01' to '9999-12-31'.
   * https://dev.mysql.com/doc/refman/8.0/en/datetime.html#:~:text=MySQL%20retrieves%20
   *
   * ---
   *
   * Constantes para el formato:
   * https://www.php.net/manual/es/datetime.constants.php
   *
   * @param string $timeZone Zona horaria en la cual se basa la obtención.
   * https://www.php.net/manual/es/function.date.php#116914
   * https://www.php.net/manual/es/timezones.america.php
   * @return string
   */
  public static function getCurrentDate(
    string $defaultTimeZone = self::DEFAULT_TIME_ZONE
  ): string {
    if (date_default_timezone_get() != $defaultTimeZone) {
      date_default_timezone_set($defaultTimeZone);
    }
    return date("Y-m-d");
  }

  /* ------------------ REVISIÓN DE EXISTENCIA DE REGISTROS ----------------- */

  /**
   * Revisar si el atributo se encuentra en la BD.
   *
   * https://stackoverflow.com/a/4254003/13562806
   * 
   * @param string $table Nombre de la tabla.
   * @param string $attribute_name Nombre del atributo.
   * @param int | string $attribute_value Valor del atributo.
   * @return boolean
   */
  public static function recordExists(
    string $table,
    string $attribute_name,
    int | string $attribute_value,
    array $pdo_params
  ): bool {
    try {
      // Esta opción devolverá 0 o 1 resultados. Esto ayudará a ver si existe o
      // no.
      $query = self::$db_connection->prepare(
        "SELECT *
          FROM {$table}
          WHERE {$attribute_name} = :attribute_value;
        "
      );

      $query->bindParam(
        ":attribute_value",
        $attribute_value,
        $pdo_params[$attribute_name]
      );
      $query->execute();

      // Si no hay filas, devolver false, indicando que no hay coincidencias.
      // fetch devuelve false si no encuentra registros. Si encuentra registros,
      // devuelve un array, por lo que no puedo hacer una comprobación directa
      // true o false, pero lo puedo hacer con ternarios.
      //
      // Si hago una comprobación directa se devolverá el arreglo completo.
      $result = $query->fetch(PDO::FETCH_ASSOC);
      if (gettype($result) === "array" && count($result) > 0) return true;
      return false;
    } catch (PDOException $e) {
      error_log("Error en la query - {$e}");
      exit();
    }

    // Si llegó hasta aquí significa que no regresó nada en el try-catch, entonces
    // regreso false.
    return false;
  }

  /**
   * Revisar que registros únicos existan en la tabla.
   *
   * @param string $table
   * @param array $unique_attributes Valores únicos en arreglo asociativo:
   * nombre de atributo => valor.
   * @param array $pdo_params
   * @return boolean
   */
  public static function uniqueRecordsExist(
    string $table,
    array $param_values,
    array $unique_attributes,
    array $pdo_params
  ): bool {
    // Recorrer los elementos y revisar. Si alguno existe, ya devolvemos true.
    foreach ($unique_attributes as $attribute_name) {
      if (self::recordExists(
        $table,
        $attribute_name,
        $param_values[$attribute_name],
        $pdo_params
      )) return true;
    }

    // Si no se encontró ningún elemento, regresar false.
    return false;
  }

  /* ----------------------------- GET (SELECT) ----------------------------- */


  /**
   * Crear parte del query en donde va el WHERE.
   * 
   * Si hay más de un where clause, concatenar con AND.
   *
   * @param array $where_clause_names
   * @return string
   */
  public static function createQueryWherePart(
    array $where_clause_names
  ) {
    $query_where_part = "";
    for ($i = 0; $i < count($where_clause_names); $i++) {
      if ($i === 0) {
        $query_where_part
          .= " WHERE";
      } else {
        $query_where_part .= " AND";
      }
      $query_where_part .= " {$where_clause_names[$i]} = :{$where_clause_names[$i]}";
    }

    return $query_where_part;
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
    // Si los 2 son false, no cambiar la query, ya que no requiere el LIKE.
    if ((isset($use_like) && !empty($use_like))
      && ($use_like["beggining"] || $use_like["ending"])
    ) {
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

    $query .= " WHERE {$where_clause} {$equal} {$attr}";

    // echo "<br><br>" . $query . "<br><br>";

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
        table: $table,
        where_clause: $where_clause["name"],
        pdo_params: $pdo_params
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
   * @param array $unique_attributes Arreglo asociativo que contiene los nombres
   * de los atributos únicos. Esto ayudará a revisar si los registros ya son
   * existentes o no.
   * @return int Un resultado de si se hizo la inserción o no.
   */
  public static function insertRecord(
    $table,
    array $param_values,
    array $pdo_params,
    array $unique_attributes,
  ): int {
    try {
      // Si se intenta insertar valores con algún campo único ya existente,
      // indicarlo.
      if (self::uniqueRecordsExist(
        $table,
        $param_values,
        $unique_attributes,
        $pdo_params
      )) {
        // self::OPERATION_INFO[2];
        return 2;
      }

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
      return $query->rowCount() > 0;
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
    array $unique_attributes,
    array $pdo_params
  ): int {
    if (!self::recordExists(
      $table,
      $where_clause["name"],
      $where_clause["value"],
      $pdo_params
    )) {
      // Campo por actualizar no existente.
      return 3;
    }
    // Si se intenta insertar valores con algún campo único ya existente,
    // indicarlo.
    if (self::uniqueRecordsExist(
      $table,
      $param_values,
      $unique_attributes,
      $pdo_params
    )) {
      // self::OPERATION_INFO[2];
      return 2;
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
    $where_clause_names,
  ) {
    $query = "DELETE FROM {$table}";
    $query .= self::createQueryWherePart($where_clause_names);
    // WHERE {$where_clause} = :{$where_clause}";

    // Si hay más de una where clause, agregarla con un AND de por medio.

    return $query;
  }

  /**
   * Crear un mapa con las where clauses de un arreglo.
   *
   * @param array $where_clauses Arreglo con los nombres de las where clauses.
   * @param array $values Arreglo con los valores.
   * @return array
   */
  public static function bindWhereClauses(
    array $where_clauses,
    array $values
  ): array {
    $pdo_params = [];
    for ($i = 0; $i < count($where_clauses); $i++) {
      $name = $where_clauses[$i];
      $value = $values[$i];

      $pdo_params[":{$name}"] = $value;
    }

    return $pdo_params;
  }

  /**
   * Eliminar un registro.
   *
   * Si hay más de una WHERE CLAUSE, se agregará el operador lógico AND.
   *
   * @param string $table
   * @param array $where_clauses Arreglo con los nombres de las where clauses.
   * @param array $values Arreglo con los valores.
   * @param array $pdo_params
   * @return bool
   */
  public static function deleteRecord(
    string $table,
    array $where_clauses,
    array $values,
    array $pdo_params
  ): bool {
    $delete_query = self::createDeleteQuery($table, $where_clauses);
    $query = self::$db_connection->prepare($delete_query);

    $query->execute(self::bindWhereClauses($where_clauses, $values));

    return $query->rowCount() > 0;
  }
}
