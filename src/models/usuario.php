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
    $_rol
  ) {
    parent::__construct();

    $this->$_id = $_id;
    $this->$_nombres = $_nombres;
    $this->$_apellidos = $_apellidos;
    $this->$_username = $_username;
    $this->$_password = $_password;
    $this->$_rol = $_rol;
  }

  /* --------------------------------- QUERIES --------------------------------
  */

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
      $row["rol"]
    );

    // Regresamos objeto como JSON para obtenerlo en JS mediante AJAX.
    $usuario->returnJSON();
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
    /**
     * Convertimos a JSON. Recibe un objeto y lo hace cadena. 
     *
     * Transformamos todo nuestro objeto a una cadena JSON para leerla en JS. 
     */
    echo json_encode($usuario);
  }
}
