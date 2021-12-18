<?php

include_once "model.php";

class Usuario extends Model
{
  public ?int $_id;
  public string $_nombres;
  public string $_apellidos;
  public string $_username;
  public string $_password;
  private int $_rol;
  public ?string $_foto_perfil;

  /**
   * Tipo de los parámetros para cuando se utilicen en PDO.
   */
  const PDO_PARAMS = [
    "id" => PDO::PARAM_INT,
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

  /**
   * Llave primaria.
   */
  const PRIMARY_KEY = "id";

  /**
   * Atributos únicos de la tabla Usuario sin incluir las llaves primarias o
   * foráneas.
   */
  const UNIQUE_ATTRIBUTES = [
    "username"
  ];

  /**
   * Valor del índice de cada uno del los roles, dado a que están almacenados
   * como un `ENUM` con 2 posibles valores. 
   *
   * En los `ENUM` el índice 0 guarda los valores que se ingresaron como `NULL`.
   */
  const ROLES_ENUM_INDEX = [
    "administrador" => 1,
    "normal" => 2,
  ];


  public function __construct(
    string $nombres,
    string $apellidos,
    string $username,
    string $password,
    string | int $rol,
    // Parámetros opcionales solo es posible ponerlos al final.
    ?int  $id = null,
    ?string $foto_perfil = null
  ) {
    $this->_id = $id;
    $this->_nombres = $nombres;
    $this->_apellidos = $apellidos;
    $this->_username = $username;
    $this->_password = $password;
    $this->_foto_perfil = $foto_perfil;
    $this->setRol($rol);
  }

  /**
   * Establecer el rol.
   *
   * Este puede ser pasado como int o string, por eso es el setter.
   * 
   * Si se envía un valor no válido, lo guardaremos como índice 0.
   *
   * @param int | string $rol Los posibles valores son: 1, 2 o "administrador",
   * "normal".
   * @return void
   */
  public function setRol(int | string $rol): void
  {
    // El rol puede ser ingresado como número (1, 2) o como cadena. Si es una
    // cadena, entonces hay que utilizar la constante de los roles.
    // 
    // Revisamos que el rol se encuentre entre los posibles (que exista).
    if (
      gettype($rol) === "string"
      && array_key_exists($rol, self::ROLES_ENUM_INDEX)
    ) {
      $this->_rol = self::ROLES_ENUM_INDEX[$rol];
      return;
    }
    // Revisamos que el número de rol exista entre los posibles.
    if (in_array($rol, self::ROLES_ENUM_INDEX, true)) {
      // El rol es un número directamente.
      $this->_rol = $rol;
      return;
    }
    // Si no entró en ninguna de las anteriores condiciones, asignar un 0 al 
    // rol, indicando que no existe el rol dado.
    $this->_rol = 0;
  }

  /**
   * Obtener arreglo con el nombre y valor de cada atributo del objeto.
   *
   * @return array Array asociativo con parámetro y valor.
   */
  public function getParamValues(): array
  {
    return [
      "id" => $this->_id,
      "nombres" => $this->_nombres,
      "apellidos" => $this->_apellidos,
      "username" => $this->_username,
      "password" => $this->_password,
      "rol" => $this->_rol,
      "foto_perfil" => $this->_foto_perfil,
    ];
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
  public function insertUsuario(): int
  {
    return parent::insertRecord(
      self::TABLE_NAME,
      $this->getParamValues(),
      self::PDO_PARAMS,
      self::UNIQUE_ATTRIBUTES
    );
  }

  /**
   * Actualizar un usuario.
   * 
   * Esta función se llama desde una instancia con el ID del usuario a
   * actualizar, pero con sus nuevos datos.
   *
   * @return boolean Se actualizó o no.
   */
  public function updateInfo(): int
  {
    $param_values = $this->getParamValues();
    // Quitar el ID de los parámetros, ya que no lo actualizaremos y solo lo
    // utilizaremos en el WHERE.
    unset($param_values["id"]);
    // Solo permitir actualizar el usuario por su ID.
    return parent::updateRecord(
      table: self::TABLE_NAME,
      param_values: $param_values,
      where_clause: [
        "name" => "id",
        "value" => $this->_id,
      ],
      unique_attributes: self::UNIQUE_ATTRIBUTES,
      pdo_params: self::PDO_PARAMS
    );
  }
  public function delete(): bool
  {
    return parent::deleteRecord(
      table: self::TABLE_NAME,
      where_clause: [
        "name" => "id",
        "value" => $this->_id
      ],
      pdo_params: self::PDO_PARAMS
    );
  }

  /**
   * Obtener un usuario por su ID mediante una query.
   *
   * @param int $id
   * @return array Arreglo asociativo para representar como JSON en JavaScript.
   */
  //   public static function getById($id)
  //   {
  //     // Obtenemos el resultado de la ejecución del query.
  //     $query = parent::getEveryRecord(
  //       "usuario",
  //       attributeName: "id",
  //       attributeValue: $id
  //     );
  // 
  //     // Obtenemos el elemento, que sería 1 porque no se puede repetir ID.
  //     $row = $query->fetch(PDO::FETCH_ASSOC);
  // 
  //     $usuario = new Usuario(
  //       $row["id"],
  //       $row["nombres"],
  //       $row["apellidos"],
  //       $row["username"],
  //       $row["password"],
  //       $row["rol"],
  //       $row["foto_perfil"],
  // 
  //     );
  // 
  //     // Regresamos objeto como JSON para obtenerlo en JS mediante AJAX.
  //     $usuario->returnJSON();
  //   }

  /**
   * Los datos de inicio de sesión son correctos o no.
   *
   * @param string $username
   * @param string $password
   * @return bool | array False si no es correcta y devuelve el arreglo con los datos si es
   * correcta.
   */
  public static function isLoginDataCorrect($username, $password)
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

      $query->bindParam(":username", $username, self::PDO_PARAMS["username"]);
      $query->bindParam(":password", $password, self::PDO_PARAMS["password"]);
      $query->execute();

      // Si no hay filas, devolver false, indicando que no se hizo la inserción.
      if ($query->rowCount() == 0) return false;

      // Si hubo resultado, obtener arreglo asociativo con los datos del
      // usuario.
      return $query->fetch(pdo::FETCH_ASSOC);
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
  //   public static function getEveryElement()
  //   {
  //     // Obtenemos el resultado de la ejecución del query.
  //     $query = parent::getEveryRecord(self::TABLE_NAME);
  // 
  //     // Arreglo con todos los elementos de la tabla.
  //     $elements = [];
  // 
  //     while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
  //       // var_dump($row);
  //       $current_user = new Usuario(
  //         $row["id"],
  //         $row["nombres"],
  //         $row["apellidos"],
  //         $row["username"],
  //         $row["password"],
  //         $row["rol"],
  //         $row["foto_perfil"]
  //       );
  //       // Agregamos el elemento actual al arreglo.
  //       array_push(
  //         $elements,
  //         $current_user
  //       );
  //       // echo "<pre>" . var_export($elements, true) . "</pre>";
  //       // Regresamos los elementos.
  //     }
  //     return $elements;
  //   }

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
