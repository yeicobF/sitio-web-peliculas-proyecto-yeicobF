<?php

include_once __DIR__ . "/../libs/model.php";

class ComentarioPelicula extends Model
{
  public ?int $id;
  public int $pelicula_id;
  public int $usuario_id;
  public string $comentario;
  public string $fecha;
  public string $hora;

  const PDO_PARAMS = [
    "id" => PDO::PARAM_INT,
    "pelicula_id" => PDO::PARAM_INT,
    "usuario_id" => PDO::PARAM_INT,
    "comentario" => PDO::PARAM_STR,
    "fecha" => PDO::PARAM_STR,
    "hora" => PDO::PARAM_STR,
  ];

  const TABLE_NAME = "comentario_pelicula";
  const PRIMARY_KEY = "id";
  const UNIQUE_ATTRIBUTES = [];

  public function __construct(
    int $pelicula_id,
    int $usuario_id,
    string $comentario,
    string $fecha,
    string $hora,
    ?int $id = null,
  ) {
    $this->id = $id;
    $this->pelicula_id = $pelicula_id;
    $this->usuario_id = $usuario_id;
    $this->comentario = $comentario;
    $this->fecha = $fecha;
    $this->hora = $hora;
  }

  /**
   * Obtener arreglo con el nombre y valor de cada atributo del objeto.
   *
   * @return array Array asociativo con parámetro y valor.
   */
  public function getParamValues(): array
  {
    // Con la función get_object_vars($object) podemos obtener las propiedades
    // no estáticas accesibles del objeto dependiendo del scope, por lo que, al
    // llamarla desde aquí, podremos obtener todas las variables.
    // https://www.php.net/manual/es/function.get-object-vars.php
    return get_object_vars($this);
  }

  /**
   * Inserción de un nuevo elemento.
   */
  public function insertComentarioPelicula(): int
  {
    return parent::insertRecord(
      self::TABLE_NAME,
      $this->getParamValues(),
      self::PDO_PARAMS,
      self::UNIQUE_ATTRIBUTES
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

  public function returnJson()
  {
    /**
     * Convertimos a JSON. Recibe un objeto y lo hace cadena. 
     *
     * Transformamos todo nuestro objeto a una cadena JSON para leerla en JS. 
     */
    echo json_encode($this->getParamValues());
  }
}
