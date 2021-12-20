<?php

/**
 * Carga el modelo y la vista que tenemos que presentar.
 * 
 * Es el controlador base del cual los demás controladores estarán heredando.
 */
class Controller
{
  /**
   * Indicar qué vista queremos cargar.
   *
   * @var object
   */
  private $view;
  /**
   * Instancia del modelo.
   *
   * @var object
   */
  private $model;

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
     */
    $model_constructor_name = str_replace($model_constructor_name, "", "-");
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
    $url = "models/" . $model_name . ".php";

    if (file_exists($url)) {
      require_once $url;

      $model_constructor_name = $this->getConstructorNameFromModelFileName(
        $model_name
      );
      $this->model = new $model_constructor_name();
    }
  }
}
