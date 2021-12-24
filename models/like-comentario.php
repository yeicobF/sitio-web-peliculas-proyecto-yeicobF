<?php

include_once __DIR__ . "/../libs/model.php";

class LikeComentario extends Model

{
  public int $comentario_pelicula_id;
  public int $usuario_id;
  public int $tipo;

  const TABLE_NAME = "like_comentario";

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
    "comentario_pelicula_id",
    "usuario_id",
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

  public static function getLikeComentario(
    int $comentario_pelicula_id,
    int $usuario_id
  ) {
    try {
      $where_clauses = [
        "comentario_pelicula_id",
        "usuario_id"
      ];

      $query_select = parent::createSelectQuery(
        self::TABLE_NAME,
      );

      $query_select .= parent::createQueryWherePart($where_clauses);

      $query = self::$db_connection->prepare($query_select);

      $query->execute(
        parent::bindWhereClauses(
          where_clauses: $where_clauses,
          values: [
            $comentario_pelicula_id,
            $usuario_id
          ]
        )
      );

      return self::getFetchedRecords($query);
    } catch (PDOException $e) {
      error_log("Error en la query - {$e}");
      exit();
    }
  }

  /**
   * Inserción de un nuevo elemento.
   */
  public function insertLikeComentario(): int
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
  public function update(): bool
  {
    $new_tipo = $this->tipo <= 1 ? 2 : 1;

    // Borramos el registro actual.
    $this->delete();

    // Instanciamos con nuevo tipo.
    $new_state  = new LikeComentario(
      $this->comentario_pelicula_id,
      $this->usuario_id,
      $new_tipo
    );

    // Insertamos el nuevo comentario con el nuevo tipo.
    return $new_state->insertLikeComentario();
  }

  public function delete(): bool
  {
    return parent::deleteRecord(
      table: self::TABLE_NAME,
      where_clauses: [
        "comentario_pelicula_id",
        "usuario_id"
      ],
      values: [
        $this->comentario_pelicula_id,
        $this->usuario_id,
      ],
      pdo_params: self::PDO_PARAMS
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
