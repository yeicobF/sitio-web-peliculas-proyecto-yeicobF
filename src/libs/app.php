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
  public function __construct()
  {
    /**
     * Validar que exista una URL.
     * 
     * Si existe, le agregaremos a la URL el valor del campo y si no, dar NULL.
     * 
     */
    $url = isset($_GET["url"]) ? $_GET["url"] : null;
    /** 
     * Luego, dividiremos la URL por slashes para asignar a cada una de las
     * partes de la diagonal. Asignamos a cada una de las partes de las
     * diagonales sus constructores y parámetros.
     *
     * Aquí, eliminamos cualquier diagonal que se encuentre al final con
     * `rtrim`.
     */
    $url = rtrim($url, "/");
    /**
     * Dividir la URL en un arreglo con los elementos separados por cada slash.
     * Así podremos identificar a qué controlador hay que mandar la solicitud y
     * qué método del controlador hay que ejecutar.
     */
    $url = explode("/", $url);

    /**
     * Si no hay un nombre de controlador en el URL, redirigir al Login.
     */
    if (empty($url[0])) {
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
  }
}
