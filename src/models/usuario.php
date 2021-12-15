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

  public function __construct(
    $db_connection,
    $_id,
    $_nombres,
    $_apellidos,
    $_username,
    $_password,
    $_rol
  ) {
    parent::__construct($db_connection);

    $this->setId($_id);
    $this->setNombres($_nombres);
    $this->setApellidos($_apellidos);
    $this->setUsername($_username);
    $this->setPassword($_password);
    $this->setRol($_rol);
  }

  /**
   * Get the value of _id
   */
  public function getId()
  {
    return $this->_id;
  }

  /**
   * Set the value of _id
   *
   * @return  self
   */
  public function setId($_id)
  {
    $this->_id = $_id;

    return $this;
  }

  /**
   * Get the value of _nombres
   */
  public function getNombres()
  {
    return $this->_nombres;
  }

  /**
   * Set the value of _nombres
   *
   * @return  self
   */
  public function setNombres($_nombres)
  {
    $this->_nombres = $_nombres;

    return $this;
  }

  /**
   * Get the value of _apellidos
   */
  public function getApellidos()
  {
    return $this->_apellidos;
  }

  /**
   * Set the value of _apellidos
   *
   * @return  self
   */
  public function setApellidos($_apellidos)
  {
    $this->_apellidos = $_apellidos;

    return $this;
  }

  /**
   * Get the value of _username
   */
  public function getUsername()
  {
    return $this->_username;
  }

  /**
   * Set the value of _username
   *
   * @return  self
   */
  public function setUsername($_username)
  {
    $this->_username = $_username;

    return $this;
  }

  /**
   * Get the value of _password
   */
  public function getPassword()
  {
    return $this->_password;
  }

  /**
   * Set the value of _password
   *
   * @return  self
   */
  public function setPassword($_password)
  {
    $this->_password = $_password;

    return $this;
  }

  /**
   * Get the value of _rol
   */
  public function getRol()
  {
    return $this->_rol;
  }

  /**
   * Set the value of _rol
   *
   * @return  self
   */
  public function setRol($_rol)
  {
    $this->_rol = $_rol;

    return $this;
  }

  /* -------------------------------------------------------------------------- */
  public function returnJson()
  {
    // Declaramos arreglo. Es un arreglo asociativo. 
    // Sigue siendo un objeto, por lo que, hay que transformarlo a JSON.
    $usuario = array();
    $usuario["id"] = $this->getId();
    $usuario["nombres"] = $this->getNombres();
    $usuario["apellidos"] = $this->getApellidos();
    $usuario["username"] = $this->getUsername();
    $usuario["password"] = $this->getPassword();
    $usuario["rol"] = $this->getRol();
    /**
     * Convertimos a JSON. Recibe un objeto y lo hace cadena. 
     *
     * Transformamos todo nuestro objeto a una cadena JSON para leerla en JS. 
     */
    echo json_encode($usuario);
  }
}
