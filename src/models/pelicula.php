<?php

include_once __DIR__ . "/../libs/model.php";

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

  const TABLE_NAME = "pelicula";

  const REQUIRED_FIELDS = [
    "nombre_original",
    "duracion",
    "release_year",
    "restriccion_edad",
    "resumen_trama",
    "actores",
    "directores",
    "generos"
  ];

  const PRIMARY_KEY = "id";
  const UNIQUE_ATTRIBUTES = [
    "pk" => [
      "id"
    ]
  ];

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
    $this->id = $id;
    $this->nombre_es_mx = $nombre_es_mx;
    $this->poster = $poster;

    $this->actores = $actores;
    $this->directores = $directores;
    $this->generos = $generos;
  }



  /**
   * Obtener tiempo del formato `h:m:s` a un arreglo con cada cantidad separada.
   *
   * @return array
   */
  public function getSplitTime()
  {
    $split_time = explode(":", $this->duracion);
    return [
      "horas" => $split_time[0],
      "minutos" => $split_time[1],
      "segundos" => $split_time[2],
    ];
  }

  /**
   * Inserción de un nuevo elemento.
   */
  public function insert(): int
  {
    return parent::insertRecord(
      self::TABLE_NAME,
      $this->getParamValues(),
      self::PDO_PARAMS,
      self::UNIQUE_ATTRIBUTES
    );
  }

  public static function searchMovies(string $query)
  {
    /** Usar LIKE en el nombre original y español. */
    $use_like = [
      [
        "beginning" => true,
        "ending" => true,
      ],
      [
        "beginning" => true,
        "ending" => true,
      ],
    ];

    return parent::getRecordsLike(
      table: self::TABLE_NAME,
      where_clause_names: [
        "nombre_original",
        "nombre_es_mx",
      ],
      where_clause_values: [
        $query,
        $query,
      ],
      use_like: $use_like,
      pdo_params: self::PDO_PARAMS
    );
  }

  public static function getMovie(int $id): array | null
  {
    $db_movie = parent::getRecords(
      table: self::TABLE_NAME,
      where_clause_names: ["id"],
      where_clause_values: [$id],
      pdo_params: self::PDO_PARAMS
    );

    if (array_key_exists(0, $db_movie) && $db_movie[0] !== null) {
      return $db_movie[0];
    }
    
    return null;
  }
  public static function getEveryMovie()
  {
    return parent::getEveryRecord(self::TABLE_NAME);
  }

  public static function getMoviesByGenre($genre)
  {
    return parent::getRecordsLike(
      table: self::TABLE_NAME,
      where_clause_names: ["generos"],
      where_clause_values: [$genre],
      use_like: [
        [
          "beginning" => true,
          "ending" => true,
        ],
      ],
      pdo_params: self::PDO_PARAMS
    );
  }

  public static function getBestMovies()
  {
    // return parent::getRecords( table: self::TABLE_NAME, where_clause_names:
    //   ["id"], where_clause_values: [$id], pdo_params: self::PDO_PARAMS
    // );
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
      where_clause_names: [
        "id",
      ],
      where_clause_values: [
        $this->_id
      ],
      unique_attributes: self::UNIQUE_ATTRIBUTES,
      pdo_params: self::PDO_PARAMS
    );
  }

  public static function delete($id): int
  {
    return parent::deleteRecord(
      table: self::TABLE_NAME,
      where_clause_names: ["id"],
      where_clause_values: [$id],
      pdo_params: self::PDO_PARAMS
    );
  }
}
