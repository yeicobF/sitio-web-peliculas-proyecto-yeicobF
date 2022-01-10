<?php

include_once __DIR__ . "/../libs/model.php";

class CalificacionUsuarioPelicula extends Model

{
  public int $pelicula_id;
  public int $usuario_id;
  public float $numero_estrellas;

  const TABLE_NAME = "calificacion_usuario_pelicula";

  const PDO_PARAMS = [
    "pelicula_id" => PDO::PARAM_INT,
    "usuario_id" => PDO::PARAM_INT,
    /**
     * No hay tipo de parámetro para los float. Hay que utilizar el string.
     * 
     * https://stackoverflow.com/a/2718737/13562806
     */
    "numero_estrellas" => PDO::PARAM_STR,
  ];

  const UNIQUE_ATTRIBUTES = [
    // "fk" => [
    //   "pelicula_id",
    //   "usuario_id",
    // ]
  ];

  public function __construct(
    int $pelicula_id,
    int $usuario_id,
    float $numero_estrellas
  ) {
    $this->pelicula_id = $pelicula_id;
    $this->usuario_id = $usuario_id;
    $this->setNumeroEstrellas($numero_estrellas);
  }

  /**
   * Establecer el número de estrellas.
   * 
   * - Si se envía un valor mayor a 5, se le dan 5 estrellas. 
   * - Si se envía un valor menor a 0, se le da 0. 
   * - Los números solo pueden ir de .5 en .5, no hay decimales intermedios.
   *
   * https://www.kavoir.com/2012/10/php-round-to-the-nearest-0-5-1-0-1-5-2-0-2-5-etc.html
   * https://www.php.net/manual/es/function.round.php
   * 
   * @param float $numero_estrellas
   * @return void
   */
  public function setNumeroEstrellas(float $numero_estrellas)
  {
    if ($numero_estrellas > 5) {
      $this->numero_estrellas = 5;
    }
    if ($numero_estrellas < 0) {
      $this->numero_estrellas = 5;
    }
    /**
     * Returns the rounded value of val to specified precision (number of digits
     * after the decimal point).
     *
     * https://www.php.net/manual/es/function.round.php
     *
     * - echo round(3.4);         // 3 
     * - echo round(3.5);         // 4
     */
    $this->numero_estrellas = round(
      num: $numero_estrellas * 2,
      mode: PHP_ROUND_HALF_UP
    );
  }

  public static function getCalificacionesPelicula(int $pelicula_id)
  {
    return parent::getRecords(
      table: self::TABLE_NAME,
      where_clause_names: [
        "pelicula_id"
      ],
      where_clause_values: [
        $pelicula_id
      ],
      pdo_params: self::PDO_PARAMS
    );
  }

  public static function getCalificacionUsuarioPelicula(
    int $pelicula_id,
    int $usuario_id
  ): array|null {
    $where_clauses = [
      "pelicula_id",
      "usuario_id"
    ];

    return parent::getRecords(
      table: self::TABLE_NAME,
      where_clause_names: $where_clauses,
      where_clause_values: [
        $pelicula_id,
        $usuario_id
      ],
      pdo_params: self::PDO_PARAMS
    );
  }

  /**
   * Inserción de un nuevo elemento.
   */
  public function insertCalificacionUsuarioPelicula(): int
  {
    return parent::insertRecord(
      self::TABLE_NAME,
      $this->getParamValues(),
      self::PDO_PARAMS,
      self::UNIQUE_ATTRIBUTES
    );
  }

  /**
   * Actualizar like o dislike de comentario.
   * 
   * Se eliminará el valor actual y se pondrá el que fue seleccionado (valor
   * contrario al que fue eliminado).
   *
   * @return boolean
   */
  public function update($numero_estrellas): int
  {
    // Instanciamos con nuevo numero_estrellas.
    $new_state  = new CalificacionUsuarioPelicula(
      $this->pelicula_id,
      $this->usuario_id,
      $numero_estrellas
    );
    $param_values = $new_state->getParamValues();
    unset($param_values["pelicula_id"]);
    unset($param_values["usuario_id"]);


    return parent::updateRecord(
      table: self::TABLE_NAME,
      param_values: $param_values,
      where_clause_names: [
        "pelicula_id",
        "usuario_id"
      ],
      where_clause_values: [
        $this->pelicula_id,
        $this->usuario_id
      ],
      unique_attributes: self::UNIQUE_ATTRIBUTES,
      pdo_params: self::PDO_PARAMS
    );
  }

  public function delete(
    int $pelicula_id,
    int $usuario_id
  ): bool {
    return parent::deleteRecord(
      table: self::TABLE_NAME,
      where_clause_names: [
        "pelicula_id",
        "usuario_id"
      ],
      where_clause_values: [
        $pelicula_id,
        $usuario_id,
      ],
      pdo_params: self::PDO_PARAMS
    );
  }
}
