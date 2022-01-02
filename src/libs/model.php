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

  /**
   * Obtener arreglo con el nombre y valor de cada atributo del objeto.
   *
   * @return array Array asociativo con parámetro y valor.
   */
  protected function getParamValues(): array
  {
    // Con la función get_object_vars($object) podemos obtener las propiedades
    // no estáticas accesibles del objeto dependiendo del scope, por lo que, al
    // llamarla desde aquí, podremos obtener todas las variables.
    // https://www.php.net/manual/es/function.get-object-vars.php
    return get_object_vars($this);
  }

  public function returnJson()
  {
    /**
     * Convertimos a JSON. Recibe un objeto y lo hace cadena. 
     *
     * Transformamos todo nuestro objeto a una cadena JSON para leerla en JS. 
     */
    return json_encode($this->getParamValues());
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
    array $where_clause_names,
    array $where_clause_values,
    array $pdo_params
  ): bool {
    try {

      $query_select = "SELECT * FROM {$table}";
      $query_select .= self::createQueryWherePart(
        $where_clause_names
      );

      // Esta opción devolverá 0 o 1 resultados. Esto ayudará a ver si existe o
      // no.
      $query = self::$db_connection->prepare($query_select);


      $query->execute(self::bindWhereClauses(
        where_clauses: $where_clause_names,
        values: $where_clause_values
      ));

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
   * Hay registros que tienen más de un campo único a la vez. Revisar que,
   * combinados no existan juntos.
   * 
   * ! Lo que hace esta función podría dividirlo en varias funciones, pero por
   * el momento así quedará.
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
    // Si los atributos únicos no se encuentran en los `$param_values`, indicar
    // que no existen.
    $encountered_params = 0;

    foreach ($unique_attributes as $type => $values) {
      $current_value = 0;
      foreach ($values as $name) {
        if (array_key_exists($name, $param_values)) {
          $encountered_params++;
        } else {
          // Eliminar el elemento que no se encontró.
          unset($unique_attributes[$type][$current_value]);
        }
        $current_value++;
      }
    }

    if ($encountered_params === 0) {
      return false;
    }

    $fk_names = null;
    $pk_names = array_key_exists("pk", $unique_attributes)
      ? array_values($unique_attributes["pk"])
      : null;
    $unique_names = array_key_exists("unique", $unique_attributes)
      ? array_values($unique_attributes["unique"])
      : null;

    // Se trata de llaves foráneas.
    if (array_key_exists("fk", $unique_attributes)) {
      $fk_names = $unique_attributes["fk"];
      $fk_values = [];

      foreach ($fk_names as $name) {
        if (isset($param_values[$name])) {
          array_push($fk_values, $param_values[$name]);
        }
      }
      // Revisar que se hayan encontrado valores para revisar el registro.
      if (
        count($fk_names) === count($fk_values)
        && count($fk_values) > 0
      )
        if (self::recordExists(
          $table,
          $fk_names,
          $fk_values,
          $pdo_params
        )) return true;

      // Quitamos las llaves foráneas para revisar solo las únicas y primarias,
      // ya que, esas se revisan de forma individual.
      unset($unique_attributes["fk"]);
    }


    $unique_attributes = array_merge(
      $pk_names,
      $unique_names,
    );

    /** 
     * En este momento, el arreglo ya no tendría las llaves únicas, por lo que,
     * los elementos se revisarán de forma individual. 
     *
     * Recorrer los elementos y revisar. 
     *
     * - Si alguno existe, ya devolvemos true. 
     * - Si son llaves foráneas, revisarlas en conjunto. Si es un campo único o
     *   una llave primaria, revisar solo. 
     */
    foreach ($unique_attributes as $name) {
      if (empty($name) || !isset($name)) {
        continue;
      }
      if (self::recordExists(
        $table,
        [$name],
        [$param_values[$name]],
        $pdo_params
      )) return true;
    }

    // Si no se encontró ningún elemento, regresar false.
    return false;
  }

  /* ----------------------------- GET (SELECT) ----------------------------- */

  /**
   * Agregar símbolos de porcentaje para un `WHERE` con `LIKE` en lugar de `=`.
   *
   * @param array $use_like Arreglo indicando si se utiliza like al final,
   * inicio de la sentencia o en los dos.
   * @param string $attribute Atributo actual.
   * @return string
   */
  public static function addLikeSymbolsToWhereClause(
    array $use_like,
    string $attribute_to_bind
  ): string {
    $beginning = ($use_like["beginning"] ? "'%', " : "");
    $ending = ($use_like["ending"] ? ", '%'" : "");
    return "CONCAT("
      . $beginning
      . $attribute_to_bind
      . $ending
      . ")";
  }

  /**
   * Crear parte del query en donde va el WHERE.
   * 
   * Si hay más de un where clause, concatenar con AND.
   *
   * @param array $where_clause_names
   * @return string
   */
  public static function createQueryWherePart(
    array $where_clause_names,
    array $use_like = null
  ) {
    $query_where_part = "";
    for ($i = 0; $i < count($where_clause_names); $i++) {
      if ($i === 0) {
        $query_where_part
          .= " WHERE";
      } else {
        $query_where_part .= " AND";
      }

      // Atributo con los 2 puntos al inicio para hacer el bindParam.
      $attribute_to_bind = ":{$where_clause_names[$i]}";
      $equal = "=";

      // Modificar atributos si es que hay que utilizar el LIKE.
      // Si los 2 son false, no cambiar la query, ya que no requiere el LIKE.
      if (
        (isset($use_like[$i])
          && !empty($use_like[$i])
        )
        && (array_key_exists("beginning", $use_like[$i])
          || array_key_exists("ending", $use_like[$i])
        )
      ) {
        $current_use_like = $use_like[$i];
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
        $attribute_to_bind = self::addLikeSymbolsToWhereClause(
          use_like: $current_use_like,
          attribute_to_bind: $attribute_to_bind
        );
      }

      $query_where_part
        .= " {$where_clause_names[$i]} {$equal} {$attribute_to_bind}";
    }

    return $query_where_part;
  }


  /**
   * Crear query de `SELECT`.
   *
   * Si no se le envían los otros 2 parámetros, no se le agrega el `WHERE`.
   *
   * @param string $table
   * @param array|null $where_clause_names
   * @param array use_like Indicar si se usará el mecanismo like o no. Esto solo
   * se puede utilizar cuando se hace uso del `WHERE`. Es un arreglo que indica
   * en qué posición se encontrará el modificador.
   * @param array|null $pdo_params
   * @return string
   */
  public static function createSelectQuery(
    string $table,
    array $where_clause_names = null,
    array $use_like = null,
    array $pdo_params = null
  ): string {
    $query = "SELECT * FROM {$table}";

    // Early return si no están establecidas dichas variables.
    if (
      !isset($where_clause_names) && empty($where_clause_names)
      && !isset($pdo_params) && empty($pdo_params)
    ) {
      return $query;
    }

    $query .= self::createQueryWherePart(
      $where_clause_names,
      $use_like
    );

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
    array $where_clause_names,
    array $where_clause_values,
    ?array $pdo_params
  ): array|null {
    try {
      $query_select = self::createSelectQuery(
        table: $table,
        where_clause_names: $where_clause_names,
        pdo_params: $pdo_params
      );

      $query = self::$db_connection->prepare($query_select);

      $query->execute(self::bindWhereClauses(
        $where_clause_names,
        $where_clause_values
      ));

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
    array $where_clause_names,
    array $where_clause_values,
    array $use_like,
    array $pdo_params
  ): array {
    try {
      $query_select = self::createSelectQuery(
        $table,
        $where_clause_names,
        $use_like,
        $pdo_params
      );

      $query = self::$db_connection->prepare($query_select);

      $query->execute(
        self::bindWhereClauses(
          $where_clause_names,
          $where_clause_values
        )
      );

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
    array $where_clause_names,
  ): string {
    $query = "UPDATE {$table} SET ";

    for ($i = 0; $i < count($attribute_names); $i++) {
      $query .= "{$attribute_names[$i]} = :{$attribute_names[$i]}";
      $query .=
        ($i === count($attribute_names) - 1)
        ? " "
        : ", ";
    }

    $query .= self::createQueryWherePart($where_clause_names);

    return $query;
  }

  /**
   * Actualizar un registro de la BD.
   *
   * Si no se actualizó nada del registro, devuelve true aunque haya encontrado
   * un registro.
   *
   * - Si regresa 0, significa que no se realizó la inserción. Esto puede
   *   suceder porque no se actualizó ningún campo.
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
    array $where_clause_names,
    array $where_clause_values,
    array $unique_attributes,
    array $pdo_params
  ): int {
    // El récord (registro) no existe.
    if (!self::recordExists(
      $table,
      $where_clause_names,
      $where_clause_values,
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
        $where_clause_names
      );

      // echo $update_query . "<br>";

      $query = self::$db_connection->prepare($update_query);

      // Parámetros y su valor en un arreglo asociativo.
      $params = self::bindEveryParamToArray($param_values);

      // Agregamos el nombre de los where, ya que también forman parte de los
      // parámetros.
      $params = array_merge(
        $params,
        self::bindWhereClauses(
          $where_clause_names,
          $where_clause_values
        )
      );

      // echo var_dump($param_values) . "<br>";

      $query->execute($params);

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
   * @return int
   */
  public static function deleteRecord(
    string $table,
    array $where_clause_names,
    array $where_clause_values,
    array $pdo_params
  ): int {
    // El récord (registro) no existe.
    if (!self::recordExists(
      $table,
      $where_clause_names,
      $where_clause_values,
      $pdo_params
    )) {
      // Campo por actualizar no existente.
      return 3;
    }

    try {
      $delete_query = self::createDeleteQuery($table, $where_clause_names);
      $query = self::$db_connection->prepare($delete_query);

      $query->execute(
        self::bindWhereClauses($where_clause_names, $where_clause_values)
      );

      return $query->rowCount() > 0;
    } catch (PDOException $e) {
      error_log("Error en la query - {$e}");
      return 5;
    }
  }

  /**
   * Obtener el alias de una tabla en una query.
   *
   * El alias sirve para identificar de otra manera a un elemento que, en este
   * caso es una tabla. Este sigue después de la palabra reservada `AS`.
   *
   * Ejemplo:
   *
   * ```sql
   * SELECT * FROM 
   * `usuario` AS u
   * LEFT JOIN `comentario_pelicula` AS cp
   *   ON u.id = cp.usuario_id
   * LEFT JOIN `calificacion_usuario_pelicula` AS cup
   *   ON u.id = cup.usuario_id
   * LEFT JOIN `like_comentario` AS lc
   *   ON u.id = lc.usuario_id
   * WHERE u.id = ":u_id"
   * ```
   *
   * @param array $table_names Nombres de las tablas.
   * @return array Arreglo asociativo con key = nombre de la tabla y value =
   * alias.
   */
  public static function getQueryAliases(array $table_names): array
  {
    $query_aliases = [];

    foreach ($table_names as $table_name) {
      /**
       * Array con las palabras de la tabla en cada índice. Estas palabras se
       * separan mediante un guión, pero en el arreglo se guardan sin este.
       */
      $table_name_words = explode("_", $table_name);

      /**
       * Obtener la primera letra de cada palabra. Imagino que habrá una función
       * para unir los elementos siguiendo esta condición, pero por el momento,
       * no encontré lo que buscaba.
       * 
       * Esto aumentará la complejidad de la operación.
       * 
       * Dado el hecho de que, el encoding de nuestra aplicación es UTF-8, se
       * requiere de un multibyte encoding, y si no lo ponemos así, podríamos
       * solo obtener el primer byte en lugar del primer caracter.
       * 
       * Por esta razón, utilizamos: `mb_substr()`.
       */
      for ($i = 0; $i < count($table_name_words); $i++) {
        /**
         * https://stackoverflow.com/a/1972111/13562806
         *
         * "
         * Yes. Strings can be seen as character arrays, and the way to access a
         * position of an array is to use the [] operator. Usually there's no
         * problem at all in using $str[0] (and I'm pretty sure is much faster
         * than the substr() method).
         *
         * There is only one caveat with both methods: they will get the first
         * byte, rather than the first character. This is important if you're
         * using multibyte encodings (such as UTF-8). If you want to support
         * that, use mb_substr(). Arguably, you should always assume multibyte
         * input these days, so this is the best option, but it will be slightly
         * slower.
         * "
         */
        $table_name_words[$i] = mb_substr(
          $table_name_words[$i],
          0,
          1,
          INTERNAL_ENCODING
        );
      }

      /**
       * El alias se conforma de la primera letra de cada palabra de la tabla.
       */
      $alias = join("_", $table_name_words);

      /**
       * Si el alias ya existe y sigue existiendo, agregar un número secuencial
       * al final para diferenciarlos. Puede suceder que más de una tabla tenga
       * las mismas iniciales.
       */
      while (array_key_exists($alias, $query_aliases)) {
        $counter = 0;

        $alias .= "_{$counter}";
      }

      $query_aliases[$table_name] = $alias;
    }

    return $query_aliases;
  }

  public static function getJoinWhereClauseNames(
    $table_aliases,
    $where_clause_names
  ) {
    $join_where_clause_names = [];

    foreach ($table_aliases as $alias) {
      foreach ($where_clause_names as $name) {
        /**                       "  u.id  =    :u_id" */
        $join_where_clause_names["{$alias}.{$name}"] = ":{$alias}_{$name}";
      }
    }

    return $join_where_clause_names;
  }

  public static function createLeftJoinQueryPart(
    $main_table,
    $reference_tables,
    $table_aliases
  ) {
    $left_join = "";
    $main_table_alias = $table_aliases[$main_table];

    foreach ($reference_tables as $table) {
      $table_alias = $table_aliases[$table];

      $left_join .= "LEFT JOIN `{$table}` AS {$table_alias}";
      $left_join
        .= " ON {$main_table_alias}.id = {$table_alias}.{$main_table}_id";

      $left_join .= " ";
    }

    return $left_join;
  }



  /**
   * Crear la parte del WHERE de una query con JOIN.
   * 
   * Esto es como la otra función `Model::createQueryWherePart()`, pero con
   * menos complejidad, ya que, aquí no se puede hacer lo siguiente:
   * 
   * ```sql
   * WHERE u.id = ":u.id"
   * ```
   * 
   * Sino que, tenemos que reemplazar ese punto del bound param por otro
   * símbolo, y en la otra función eso no se hace. Hacerlo ahí sería aumentarle
   * la complejidad.
   *
   * @param array $join_where_clause_names Arreglo asociativo con el nombre
   * original de la igualación (`u.id`) y el valor del bindParam (`:u_id`).
   * @return void
   */
  public static function createJoinQueryWherePart($join_where_clause_names)
  {
    $query_where_part = " WHERE";

    $i = 0;
    foreach ($join_where_clause_names as $key => $value) {
      if ($i > 0) {
        $query_where_part .= " AND";
      }

      /**                  "  u.id  =    :u_id" */
      $query_where_part .= " {$key} = {$value}";

      $i++;
    }

    return $query_where_part;
  }

  public static function createDeleteJoinQuery(
    string $main_table,
    array $reference_tables,
    array $table_aliases,
    array $join_where_clause_names
  ) {

    /**
     * Alias en cadena para poder especificar su eliminación.
     * 
     * https://stackoverflow.com/a/5593036/13562806
     * 
     * ```php
     * echo substr('a,b,c,d,e,', 0, -1);
     * # => 'a,b,c,d,e'
     * ```
     * 
     * Después, eliminamos los 2 últimos caracteres: ", ".
     */
    $string_aliases = mb_substr(
      join(", ", $table_aliases),
      0,
      -2,
      INTERNAL_ENCODING
    );

    $query =
      "DELETE "
      . $string_aliases
      . "FROM {$main_table} AS {$table_aliases[$main_table]}";

    $query .= self::createLeftJoinQueryPart(
      $main_table,
      $reference_tables,
      $table_aliases
    );


    $query .= self::createJoinQueryWherePart($join_where_clause_names);

    return $query;
  }

  public static function bindJoinWhereClauses(
    $join_where_clause_names,
    $where_clause_values
  ) {
    $bound_params = [];
    $i = 0;

    foreach ($join_where_clause_names as $bind_param) {
      $bound_params[$bind_param] = $where_clause_values[$i];
      $i++;
    }

    return $bound_params;
  }

  /**
   * Elimina un registro y sus incidencias (referencias) en otras tablas.
   * 
   * Utilizamos `LEFT JOIN` en lugar de `INNER JOIN`.
   * 
   * Este es un artículo que explica su diferencia:
   * - https://www.sqlshack.com/learn-sql-inner-join-vs-left-join/
   *
   * @param string $table
   * @param array $reference_tables
   * @param array $where_clause_names
   * @param array $where_clause_values
   * @param array $pdo_params
   * @return : int
   */
  public static function deleteRecordAndReferences(
    string $main_table,
    array $reference_tables,
    array $where_clause_names,
    array $where_clause_values,
    array $pdo_params
  ): int {
    // El récord (registro) no existe.
    if (!self::recordExists(
      $main_table,
      $where_clause_names,
      $where_clause_values,
      $pdo_params
    )) {
      // Campo por actualizar no existente.
      return 3;
    }

    $table_aliases = self::getQueryAliases(
      array_merge(
        [$main_table],
        $reference_tables
      )
    );

    $join_where_clause_names = self::getJoinWhereClauseNames(
      $table_aliases,
      $where_clause_names
    );

    $delete_query = self::createDeleteJoinQuery(
      $main_table,
      $reference_tables,
      $table_aliases,
      $join_where_clause_names
    );

    try {
      $query = self::$db_connection->prepare($delete_query);

      $query->execute(
        self::bindJoinWhereClauses(
          $join_where_clause_names,
          $where_clause_values
        )
      );

      return $query->rowCount() > 0;
    } catch (PDOException $e) {
      error_log("Error en la query - {$e}");
      return 5;
    }
  }
}
