<?php

include_once __DIR__ . "/../libs/model.php";

/**
 * Likes o dislikes de un comentario.
 */
class LikeComentario extends Model
{
  public int $comentario_pelicula_id;
  public int $usuario_id;
  public int $tipo;

  const TABLE_NAME = "like_comentario";

  const REQUIRED_FIELDS = [
    "comentario_pelicula_id",
    "usuario_id",
    "tipo",
  ];

  const PDO_PARAMS = [
    "comentario_pelicula_id" => PDO::PARAM_INT,
    "usuario_id" => PDO::PARAM_INT,
    "tipo" => PDO::PARAM_INT,
  ];

  const TIPO_ENUM_INDEX = [
    "like" => 1,
    "dislike" => 2,
  ];


  const UNIQUE_ATTRIBUTES = [
    // "fk" => [
    //   "comentario_pelicula_id",
    //   "usuario_id",
    // ]
  ];

  public function __construct(
    int $comentario_pelicula_id,
    int $usuario_id,
    int | string $tipo
  ) {
    $this->comentario_pelicula_id = $comentario_pelicula_id;
    $this->usuario_id = $usuario_id;
    $this->setTipo($tipo);
  }

  /**
   * Obtener likes y dislikes de un comentario.
   *
   * @param integer $comentario_pelicula_id
   * @param integer $usuario_id
   * @return array
   */
  public static function getInteractionsComentario(
    int $comentario_pelicula_id,
  ): array {
    return parent::getRecords(
      table: self::TABLE_NAME,
      where_clause_names: [
        "comentario_pelicula_id",
      ],
      where_clause_values: [
        $comentario_pelicula_id,
      ],
      pdo_params: self::PDO_PARAMS
    );
  }

  /**
   * Inserción de un nuevo elemento.
   */
  public function insertLikeComentario(): int | array
  {
    return parent::insertRecord(
      self::TABLE_NAME,
      $this->getParamValues(),
      self::PDO_PARAMS,
      self::UNIQUE_ATTRIBUTES,
      get_fetched_records: true
    );
  }

  /**
   * Devolver el la interacción como cadena.
   *
   * @return string "like" | "dislike"
   */
  public function getTipoAsString()
  {
    // Invertimos llave y valor del arreglo para que el número sea la llave y
    // acceder con mayor facilidad.
    $tipos = array_flip(self::TIPO_ENUM_INDEX);
    return $tipos[$this->tipo];
  }

  /**
   * Actualizar like o dislike de comentario.
   * 
   * Se eliminará el valor actual y se pondrá el que fue seleccionado (valor
   * contrario al que fue eliminado).
   *
   * @return boolean
   */
  public function update(): int
  {
    $new_tipo = $this->tipo <= 1 ? 2 : 1;

    // Borramos el registro actual.
    $this->delete(
      $this->comentario_pelicula_id,
      $this->usuario_id,
    );

    // Instanciamos con nuevo tipo.
    $new_state = new LikeComentario(
      $this->comentario_pelicula_id,
      $this->usuario_id,
      $new_tipo
    );

    // Insertamos el nuevo comentario con el nuevo tipo.
    $result = $new_state->insertLikeComentario();

    if (
      $result === 1
      || is_array($result)
    ) {
      $this->setTipo($new_tipo);
    }

    return $result;
  }

  public static function delete(
    int $comentario_pelicula_id,
    int $usuario_id
  ): int | array {
    if (
      !is_numeric($comentario_pelicula_id)
      || !is_numeric($usuario_id)
    ) return 0;

    return parent::deleteRecord(
      table: self::TABLE_NAME,
      where_clause_names: [
        "comentario_pelicula_id",
        "usuario_id"
      ],
      where_clause_values: [
        $comentario_pelicula_id,
        $usuario_id,
      ],
      pdo_params: self::PDO_PARAMS,
      get_fetched_records: true
    );
  }

  public function setTipo(int | string $tipo): void
  {
    // El tipo puede ser ingresado como número (1, 2) o como cadena. Si es una
    // cadena, entonces hay que utilizar la constante de los TIPO.
    // 
    // Revisamos que el tipo se encuentre entre los posibles (que exista).
    if (
      gettype($tipo) === "string"
      && array_key_exists($tipo, self::TIPO_ENUM_INDEX)
    ) {
      $this->tipo = self::TIPO_ENUM_INDEX[$tipo];
      return;
    }
    // Revisamos que el número de tipo exista entre los posibles.
    if (in_array($tipo, self::TIPO_ENUM_INDEX, true)) {
      // El tipo es un número directamente.
      $this->tipo = $tipo;
      return;
    }
    // Si no entró en ninguna de las anteriores condiciones, asignar un 0 al 
    // tipo, indicando que no existe el tipo dado.
    $this->tipo = 0;
  }
}
