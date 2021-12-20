<?php

/**
 * Carga el modelo y la vista que tenemos que presentar.
 * 
 * Es el controlador base del cual los demás controladores estarán heredando.
 * 
 * Esta clase la creé cuando estuve siguiendo el tutorial de Vida MRR.
 */
class OldController
{
  /**
   * Indicar qué vista queremos cargar.
   *
   * @var object
   */
  protected $view;
  /**
   * Instancia del modelo.
   *
   * @var object
   */
  protected $model;

  /**
   * Inicializar una nueva vista respecto al controlador.
   */
  public function __construct()
  {
    $this->view = new View();
  }

  /**
   * Obtener la string del constructor a partir del nombre del archivo de un
   * modelo.
   *
   * En mi caso, los archivos no están como camelCase como las clases, sino que,
   * están separadas las palabras por guiones, por lo que, a partir de los
   * guiones, tengo que formar la palabra con el camelCase. 
   * 
   * Ejemplo:
   *
   * `like-comentario` => `LikeComentario`
   *
   * @param string $model_name
   * @return string
   */
  public function getConstructorNameFromModelFileName(
    string $model_name
  ): string {
    /** 
     * Uppercase cada palabra, delimitada por un guión. 
     *
     * `like-comentario` => `Like-Comentario`
     */
    $model_constructor_name = ucwords($model_name, "-");
    /**
     * Quitamos el guión del medio para obtener nuestro nombre de constructor.
     * 
     * `Like-Comentario` => `LikeComentario`
     * 
     * https://www.php.net/manual/es/function.str-replace.php
     */
    $model_constructor_name = str_replace("-", "", $model_constructor_name);
    return $model_constructor_name;
  }

  /**
   * Cargar el archivo del modelo requerido del controlador asociado.
   *
   * @param string $model_name Modelo con `-` como delimitador por palabras,
   * p.ej: `calificacion-usuario-pelicula`, `comentario-pelicula`, ...
   * @return void
   */
  function loadModel($model_name)
  {
    /**
     * Construimos una URL con la ubicación del modelo requerido.
     */
    $url = "models/" . $model_name . ".php";

    if (file_exists($url)) {
      // Traemos el archivo.
      require_once $url;

      $model_constructor_name = $this->getConstructorNameFromModelFileName(
        $model_name
      );
      $this->model = new $model_constructor_name();
    }
  }
}
