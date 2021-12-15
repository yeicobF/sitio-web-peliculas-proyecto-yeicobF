<?php

include_once "model.php";

class Usuario extends Model
{
  private $_id;
  private $_nombres;
  private $_apellidos;
  private $_username;
  private $_password;
  private $_rol;
  private $_foto_perfil;

  public function __construct(
    $_id,
    $_nombres,
    $_apellidos,
    $_username,
    $_password,
    $_rol,
    $_foto_perfil = ""
  ) {
    parent::__construct();

    $this->$_id = $_id;
    $this->$_nombres = $_nombres;
    $this->$_apellidos = $_apellidos;
    $this->$_username = $_username;
    $this->$_password = $_password;
    $this->$_rol = $_rol;
    $this->$_foto_perfil = $_foto_perfil;
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
  public function insertNew(
    $_nombres,
    $_apellidos,
    $_username,
    $_password,
    $_rol,
    $_foto_perfil = ""
  ) {
    $query = parent::$db_connection->prepare(
      "INSERT INTO usuario 
      VALUES(
        NULL, 
        :nombres
        :apellidos
        :username
        :password
        :rol
        :foto_perfil
      )"
    );

    $query->bindParam(":nombres", $_nombres, PDO::PARAM_STR);
    $query->bindParam(":apellidos", $_apellidos, PDO::PARAM_STR);
    $query->bindParam(":username", $_username, PDO::PARAM_STR);
    $query->bindParam(":password", $_password, PDO::PARAM_STR);
    $query->bindParam(":rol", $_rol, PDO::PARAM_STR);
    $query->bindParam(":foto_perfil", $_foto_perfil, PDO::PARAM_STR);

    $query->execute();

    return $query->rowCount();
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
    $query = parent::getEveryField("usuario", id: $id);

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

      // Si la rowCount == 0, no se encontraron resultados.
      return $query->rowCount();
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
    $query = parent::getEveryField("usuario");

    // Arreglo con todos los elementos de la tabla.
    $elements = [];

    // Obtenemos el elemento, que sería 1 porque no se puede repetir ID.
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      // Agregamos el elemento actual al arreglo.
      array_push(
        $elements,
        new Usuario(
          $row["id"],
          $row["nombres"],
          $row["apellidos"],
          $row["username"],
          $row["password"],
          $row["rol"],
          $row["foto_perfil"]
        )
      );

      // Regresamos los elementos.
      return $elements;
    }
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
