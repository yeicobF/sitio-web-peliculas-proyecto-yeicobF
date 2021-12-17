<?php

include_once "model.php";

class Usuario extends Model
{
  public $_id;
  public $_nombres;
  public $_apellidos;
  public $_username;
  public $_password;
  public $_rol;
  public $_foto_perfil;

  /**
   * Tipo de los parámetros para cuando se utilicen en PDO.
   */
  const PDO_PARAMS = [
    "id" => PDO::PARAM_NULL,
    "nombres" => PDO::PARAM_STR,
    "apellidos" => PDO::PARAM_STR,
    "username" => PDO::PARAM_STR,
    "password" => PDO::PARAM_STR,
    "rol" => PDO::PARAM_INT,
    "foto_perfil" => PDO::PARAM_STR,
  ];

  const TABLE_NAME = "usuario";
  // Roles de usuario con su índice respecto al ENUM que se definió en la BD.
  // Los índices del ENUM comienzan desde 1, ya que el enum 0 está reservado
  // para los errores, tal como se indica en la documentación:
  // https://dev.mysql.com/doc/refman/8.0/en/enum.html#:~:text=The%20index%20value%20of%20the%20empty%20string%20error%20value%20is%200.%20This%20means%20that%20you%20can%20use%20the%20following%20SELECT%20statement%20to%20find%20rows%20into%20which%20invalid%20ENUM%20values%20were%20assigned
  const ROLES_ENUM_INDEX = [
    "administrador" => 1,
    "normal" => 2,
  ];

  public function __construct(
    $id,
    $nombres,
    $apellidos,
    $username,
    $password,
    $rol,
    $foto_perfil = ""
  ) {
    $this->_id = $id;
    $this->_nombres = $nombres;
    $this->_apellidos = $apellidos;
    $this->_username = $username;
    $this->_password = $password;
    $this->_rol = $rol;
    $this->_foto_perfil = $foto_perfil;
  }

  /* --------------------------------- QUERIES --------------------------------
  */

  /**
   * Inserción de un nuevo elemento.
   *
   * @param string $_nombres
   * @param string $_apellidos
   * @param string $_username
   * @param string $_password
   * @param string $_rol
   * @param string $_foto_perfil
   * @return bool Se insertó o no el elemento.
   */
  public static function insertNewUsuario(
    string $nombres,
    string $apellidos,
    string $username,
    string $password,
    int $rol,
    string $foto_perfil = NULL
  ): bool {
    $param_values = [
      "id" => NULL,
      "nombres" => $nombres,
      "apellidos" => $apellidos,
      "username" => $username,
      "password" => $password,
      "rol" => $rol,
      "foto_perfil" => $foto_perfil,
    ];
    return parent::insertNew(self::TABLE_NAME, $param_values, self::PDO_PARAMS);
  }



  public static function updateUsuario(
    string $_id,
    string $_nombres = "",
    string $_apellidos = "",
    string $_username = "",
    string $_password = "",
    string $_foto_perfil = ""
  ): bool {
    // Volver si ya existe el nombre de usuario.
    if (parent::attributeExists(self::TABLE_NAME, "username", $_username)) {
      return false;
    }

    // Si los campos están vacíos, no agregarlos a la query.
    $query_params = [
      // ", nombres = :nombres"
      "nombres" => $_nombres,
      "apellidos" => $_apellidos,
      "username" => $_username,
      "password" => $_password,
      "foto_perfil" => $_foto_perfil,
    ];

    // Si ningún parámetro está especificado, regresar.
    // Recorrido, y si se encuentra que todos los elementos no tienen contenido,
    // regresar false.
    // 
    // La validación debería estar en el frontend, pero ya la agregué.
    $empty_params = 0;
    foreach ($query_params as $param) {
      if ($param === '' || $param === null) {
        $empty_params++;
      }
    }

    if ($empty_params === count($query_params)) {
      // No se enviaron elementos
      return false;
    }
    /* -------------------------------------------------------------------------- */


    // Armamos una string dependiendo de los elementos a modificar.
    $query_string = "UPDATE usuario
      SET ";

    // Parámetros existentes para hacer el bindParam.
    $existent_parameters = [];

    // Agregamos los valores a cambiar.
    // Asignamos si el parámetro no se envió vacío.
    foreach ($query_params as $param_name => $param_value) {
      // Si es envío texto, agregar el parámetro a la query.
      if (!empty($param_value)) {
        // ", nombres = :nombres"
        $query_string .= ", {$param_name} = :{$param_name}";
        array_push($existent_parameters, $param_name);
      }
    }

    // Indicar última parte del query.
    $query_string .= " 
      WHERE id = :id
    ";

    /* -------------------------------------------------------------------------- */

    $query = parent::$db_connection->prepare($query_string);

    // Hacer bindParam.
    $query->bindParam(":id", $_id, PDO::PARAM_INT);
    foreach ($existent_parameters as $param_name) {
      $query->bindParam(
        ":{$param_name}",
        // El nombre del parámetro lo mandamos al arreglo asociativo del inicio.
        $query_params[$param_name],
        PDO::PARAM_STR
      );
    }

    $query->execute();
    // Si no hay filas, devolver false, indicando que no se hizo la inserción.
    return $query->rowCount() == 0 ? false : true;
  }
  public static function deleteUsuario()
  {
  }

  /**
   * Obtener un usuario por su ID mediante una query.
   *
   * @param int $id
   * @return array Arreglo asociativo para representar como JSON en JavaScript.
   */
  public static function getById($id)
  {
    // Obtenemos el resultado de la ejecución del query.
    $query = parent::getEveryField(
      "usuario",
      attributeName: "id",
      attributeValue: $id
    );

    // Obtenemos el elemento, que sería 1 porque no se puede repetir ID.
    $row = $query->fetch(PDO::FETCH_ASSOC);

    $usuario = new Usuario(
      $row["id"],
      $row["nombres"],
      $row["apellidos"],
      $row["username"],
      $row["password"],
      $row["rol"],
      $row["foto_perfil"],

    );

    // Regresamos objeto como JSON para obtenerlo en JS mediante AJAX.
    $usuario->returnJSON();
  }

  /**
   * Un usuario se encuentra en la base de datos o no.
   *
   * @param string $username
   * @param string $password
   * @return bool
   */
  public static function foundUsuario($username, $password)
  {
    try {
      $query = parent::$db_connection->prepare(
        "SELECT *
        FROM usuario
        WHERE 
          username = :username
          AND password = :password
        "
      );

      $query->bindParam(":username", $username, PDO::PARAM_STR);
      $query->bindParam(":password", $password, PDO::PARAM_STR);
      $query->execute();

      // Si no hay filas, devolver false, indicando que no se hizo la inserción.
      return $query->rowCount() == 0 ? false : true;
    } catch (PDOException $e) {
      error_log("Error de conexión - {$e}", 0);
      exit();
    }
  }

  /**
   * Obtener todos los elementos, que, en este caso, son usuarios.
   * 
   * Devuelve un arreglo con cada uno de los elementos que se encontró.
   *
   * @return array Arreglo asociativo con los elementos encontrados.
   */
  public static function getEveryElement()
  {
    // Obtenemos el resultado de la ejecución del query.
    $query = parent::getEveryField(self::TABLE_NAME);

    // Arreglo con todos los elementos de la tabla.
    $elements = [];

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      // var_dump($row);
      $current_user = new Usuario(
        $row["id"],
        $row["nombres"],
        $row["apellidos"],
        $row["username"],
        $row["password"],
        $row["rol"],
        $row["foto_perfil"]
      );
      // Agregamos el elemento actual al arreglo.
      array_push(
        $elements,
        $current_user
      );
      // echo "<pre>" . var_export($elements, true) . "</pre>";
      // Regresamos los elementos.
    }
    return $elements;
  }

  /* -------------------------------------------------------------------------- */
  public function returnJson()
  {
    // Declaramos arreglo. Es un arreglo asociativo. 
    // Sigue siendo un objeto, por lo que, hay que transformarlo a JSON.
    $usuario = array();
    $usuario["id"] = $this->_id;
    $usuario["nombres"] = $this->_nombres;
    $usuario["apellidos"] = $this->_apellidos;
    $usuario["username"] = $this->_username;
    $usuario["password"] = $this->_password;
    $usuario["rol"] = $this->_rol;
    $usuario["foto_perfil"] = $this->_foto_perfil;
    /**
     * Convertimos a JSON. Recibe un objeto y lo hace cadena. 
     *
     * Transformamos todo nuestro objeto a una cadena JSON para leerla en JS. 
     */
    echo json_encode($usuario);
  }
}
