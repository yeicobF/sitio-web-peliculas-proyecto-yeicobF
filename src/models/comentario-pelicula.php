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
    // "fk" => [
    //   "pelicula_id",
    //   "usuario_id",
    // ]
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
   * Estos habrá que obtenerlos por orden descendiente, del más nuevo al más
   * antiguo.
   *
   * Ejemplo query:
   *
   * ```sql
   * SELECT * FROM comentario_pelicula WHERE pelicula_id = 10 ORDER BY fecha DESC, hora DESC;
   * ```
   *
   * @param integer $pelicula_id
   * @return array | null
   */
  public static function getEveryMovieComment(int $pelicula_id)
  {
    $where_clause_names  = ["pelicula_id"];
    $where_clause_values = [$pelicula_id];

    try {
      $query_select = self::createSelectQuery(
        table: self::TABLE_NAME,
        where_clause_names: $where_clause_names,
        pdo_params: self::PDO_PARAMS
      );

      // Ordenamos del más reciente al más antiguo por fecha y hora.
      // Esto lo agregamos a la parte de la query que ya creamos con el WHERE y
      // la tabla.
      $query_select .= " ORDER BY fecha DESC, hora DESC;";

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

  public static function delete(int $id): int
  {
    return parent::deleteRecord(
      table: self::TABLE_NAME,
      where_clause_names: ["id"],
      where_clause_values: [$id],
      pdo_params: self::PDO_PARAMS
    );
  }
}
