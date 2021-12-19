<?php

include_once "model.php";

class Pelicula extends Model
{
  public ?int $id;
  public string $nombre_original;
  public ?string $nombre_es_mx;
  public string $duracion;
  public ?string $poster;
  public string $release_year;
  public string $restriccion_edad;
  public string $resumen_trama;
  // Estas podrían ser otras tablas, pero por tiempo serán un atributo de la
  // tabla.
  public string $actores;
  public string $directores;
  public string $generos;

  const PDO_PARAMS = [
    "id" => PDO::PARAM_INT,
    "nombre_original" => PDO::PARAM_STR,
    "nombre_es_mx" => PDO::PARAM_STR,
    "duracion" => PDO::PARAM_STR,
    "poster" => PDO::PARAM_STR,
    "release_year" => PDO::PARAM_STR,
    "restriccion_edad" => PDO::PARAM_STR,
    "resumen_trama" => PDO::PARAM_STR,
    "actores" => PDO::PARAM_STR,
    "directores" => PDO::PARAM_STR,
    "generos" => PDO::PARAM_STR,
  ];

  const TABLE_NAME = "pelicula";

  const PRIMARY_KEY = "id";
  const UNIQUE_ATTRIBUTES = [];

  public function __construct(
    string $nombre_original,
    string $duracion,
    string $release_year,
    string $restriccion_edad,
    string $resumen_trama,
    string $actores,
    string $directores,
    string $generos,
    ?int $id = null,
    ?string $nombre_es_mx = null,
    ?string $poster = null,
  ) {
    $this->nombre_original = $nombre_original;
    $this->duracion = $duracion;
    $this->release_year = $release_year;
    $this->restriccion_edad = $restriccion_edad;
    $this->resumen_trama = $resumen_trama;
    $this->actores = $actores;
    $this->directores = $directores;
    $this->generos = $generos;
    $this->id = $id;
    $this->nombre_es_mx = $nombre_es_mx;
    $this->poster = $poster;
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
   *
   * @param string $_nombres
   * @param string $_apellidos
   * @param string $_username
   * @param string $_password
   * @param string $_rol
   * @param string $_foto_perfil
   * @return bool Se insertó o no el elemento.
   */
  public function insertPelicula(): int
  {
    return parent::insertRecord(
      self::TABLE_NAME,
      $this->getParamValues(),
      self::PDO_PARAMS,
      self::UNIQUE_ATTRIBUTES
    );
  }

  /**
   * Actualizar una película.
   * 
   * Esta función se llama desde una instancia con el ID del pelicula a
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
    // Solo permitir actualizar el pelicula por su ID.
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
