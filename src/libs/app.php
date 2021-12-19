<?php

/**
 * Modificar la forma en la que hacemos solicitudes para rutearlas.
 *
 * Tener un archivo que cargue sin importar la dirección de nuestra app web y a
 * partir de ahí se obtendrán los elementos de la URL para redirigir al usuario
 * a la solicitud al controlador que corresponda. Dependiendo de los
 * parámetros,se redirigirá a los controladores necesarios.
 *
 * ## Archivo `.htaccess`
 *
 * > FUENTE: [#1 APLICACIÓN MVC COMPLETA EN PHP (SESIONES, AJAX, LOGIN, ROLES)
 * > PARTE 1 Curso PHP y MySQL #66](https://youtu.be/nXLeeGZ8bVE "#1 APLICACIÓN
 * > MVC COMPLETA EN PHP (SESIONES, AJAX, LOGIN, ROLES) PARTE 1 Curso PHP y
 * > MySQL #66")
 *
 * Este archivo permite modificar la forma en la que se hace una redirección
 * dependiendo de la solicitud. Las líneas del archivo permiten sobreescribir
 * las reglas del servidor para manejar las solicitudes.
 *
 * Que siempre redirija las solicitudes de la carpeta en donde se encuentra el
 * archivo a `index.php` y los parámetros que vayan a continuación los marque
 * con `$_GET["url"]`.
 *
 * Esto permite solo tener los últimos elementos del URL después del `GET`:
 *
 * - `controlador/métodoControlador`
 * - `user/updatePhoto`
 *
 * Esto permite hacer validaciones de los controladores.
 *
 * ## Redirección
 *
 * - [https://youtu.be/nXLeeGZ8bVE?t=1014](https://youtu.be/nXLeeGZ8bVE?t=1014)
 *
 * 1. Llamar al controlador de forma manual.
 * 2. Requerir el archivo.
 * 3. Crear nuevo objeto del controlador.
 * 4. Cargar modelo o vista.
 */
class App
{
  /**
   * Arreglo con elementos del URL separados por un slash (`/`).
   *
   * @var array<string>
   */
  private $url;
  /**
   * Controlador a utilizar.
   * 
   * Este controlador se recibe de los parámetros de la URL.
   *
   * @var object
   */
  private $controller;
  /**
   * Archivo del controlador a utilizar.
   * 
   * Este archivo puede existir o no. Se hará la validación necesaria.
   *
   * @var string
   */
  private $file_controller;
  /**
   * Número de parámetros del método del controlador.
   *
   * @var int
   */
  private $method_param_number;

  /**
   * Parámetros del controlador.
   * 
   * Estos se obtienen en el array `$this->url`.
   *
   * @var array<string>
   */
  private $method_params;

  /**
   * Constructor en donde se obtiene el URL mediante `$_GET["url"]`.
   */
  public function __construct()
  {
    /**
     * Validar que exista una URL.
     * 
     * Si existe, le agregaremos a la URL el valor del campo y si no, dar NULL.
     * 
     */
    $this->url = isset($_GET["url"]) ? $_GET["url"] : null;
    /** 
     * Luego, dividiremos la URL por slashes para asignar a cada una de las
     * partes de la diagonal. Asignamos a cada una de las partes de las
     * diagonales sus constructores y parámetros.
     *
     * Aquí, eliminamos cualquier diagonal que se encuentre al final con
     * `rtrim`.
     */
    $this->url = rtrim($this->url, "/");
    /**
     * Dividir la URL en un arreglo con los elementos separados por cada slash.
     * Así podremos identificar a qué controlador hay que mandar la solicitud y
     * qué método del controlador hay que ejecutar.
     */
    $this->url = explode("/", $this->url);
  }

  /**
   * Comienza la ejecución de la app.
   * 
   * Se obtiene el controlador, su método a ejecutar y sus parámetros del URL.
   * Para esto, se hacen las validaciones pertinentes.
   *
   * @return bool
   */
  public function start(): bool
  {
    /**
     * Si no hay un nombre de controlador en el URL, redirigir al Login.
     */
    if (empty($this->url[0])) {
      error_log("APP::__construct() -> No hay controlador especificado.");
      // Archivo del controlador.
      $file_controller = "controllers/login.php";
      // Requerir controlador.
      require_once $file_controller;
      // Crear instancia del controlador.
      $controller = new Login();
      // Cargar modelo del controlador.
      $controller->loadModel("login");
      // Renderizar la vista.
      $controller->render();
      // Indicar que no hubo controlador.
      return false;
    }
    // Como sí hay elementos en el URL, redirigir al controlador pertinente.
    $file_controller = "controllers/{$this->url[0]}.php";

    // Si no existe el archivo, salir o algo. Esto ayuda a hacer un early
    // return.
    if (file_exists($file_controller)) {
      // No existe el archivo, manda error.
    }

    // Si existe el controlador, llamarlo.
    require_once $file_controller;
    // NO todos los controladores requieren de un modelo.
    $controller = new $this->url[0];
    // Cargar modelo.
    $controller->loadModel($this->url[0]);

    // Early return si no existe el método en el URL.
    if (!isset($this->url[1])) {
      // Error, no existe el método a cargar. Se carga método por default.
      $controller->render();
    }
    // Error: No existe el método del URL.
    if (!method_exists($controller, $this->url[1])) {
      // Error: No existe el método del URL.
    }

    /** 
     * -------------------------------------------------------------------------
     * Una vez que pasamos las validaciones anteriores, ya no tenemos que poner
     * tantos if anidados, lo cual, hace que el código mejore en legibilidad.
     * -------------------------------------------------------------------------
     */

    /** 
     * Los demás índices permitirán validar si existen más parámetros para ver
     * el método que queremos cargar como controlador. Si no hay, llamar a uno
     * por default. if (isset($this->url[1])) { No podemos llamar un método que no
     * existe en una clase. Por cada parámetro, "inyectarlo" al método. 
     *
     * Ya no hay que poner la condición siguiente porque ya fue evaluada arriba.
     * `if (isset($this->url[2])) {`
     */
    if (!isset($this->url[2])) {
      // No hay parámetros, por lo que, llamamos al metodo de forma
      // dinámica.
      // Que cargue un controlador sin parámetros.
      $this->controller{$url[1]}();
    }

    /** 
     * Número de parámetros. Restamos 2 porque el primer parámetro del URL
     * es el nombre del controlador y el segundo es el método.
     *
     *  Si es == 0, no hay parámetros. Si es > a 0, hay parámetros que hay
     *  que agregar con una variable. 
     */
    $method_param_number = count($this->url) - 2;

    // Arreglo de parámetros.
    $method_params = [];

    // Agregamos los parámetros del método en el arreglo. Sumamos 2 por lo
    // mencionado con anterioridad respecto a los índices 0 y 1 de `$this->url`.
    for ($i = 0; $i < $method_param_number; $i++) {
      array_push($method_params, $this->url[$i + 2]);
    }

    // Ahora, pasamos al controlador el método y sus parámetros. No importa
    // el número de parámetros que tengamos, ya que es un solo argumento.
    $this->controller{$url[1]}($params);
  }
}
