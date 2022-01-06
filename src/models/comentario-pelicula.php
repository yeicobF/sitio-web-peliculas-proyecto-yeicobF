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

  const REQUIRED_FIELDS = [
    "pelicula_id",
    "usuario_id",
    "comentario",
    "fecha",
    "hora"
  ];

  const PRIMARY_KEY = "id";
  const UNIQUE_ATTRIBUTES = [
    "pk" => [
      "id"
    ],
    "fk" => [
      "pelicula_id",
      "usuario_id",
    ]
  ];

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
   * Obtener todos los comentarios de una película.
   *
   * @param integer $pelicula_id
   * @return array | null
   */
  public static function getEveryMovieComment(int $pelicula_id)
  {
    return parent::getRecords(
      table: self::TABLE_NAME,
      where_clause_names: ["pelicula_id"],
      where_clause_values: [$pelicula_id],
      pdo_params: self::PDO_PARAMS
    );
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

  public function delete(int $id): int
  {
    return parent::deleteRecord(
      table: self::TABLE_NAME,
      where_clause_names: ["id"],
      where_clause_values: [$id],
      pdo_params: self::PDO_PARAMS
    );
  }
}
