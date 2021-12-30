<?php

namespace Libs;

require_once __DIR__ . "/../config/config.php";

class Controller
{

  public static function isGet()
  {
    return $_SERVER["REQUEST_METHOD"] === "GET";
  }
  public static function isPost()
  {
    return $_SERVER["REQUEST_METHOD"] === "POST";
  }

  public static function getPostValue($name)
  {
    return $_POST[$name];
  }
  public static function getMethod()
  {
    if (isset($_POST["_method"])) {
      return $_POST["_method"];
    }
    return false;
  }

  public static function isMethodDelete()
  {
    return self::getMethod() === "DELETE";
  }

  public static function isMethodPost()
  {
    return self::getMethod() === "POST";
  }
  public static function isMethodPut()
  {
    return self::getMethod() === "PUT";
  }

  /**
   * El método solicitado existe o no.
   * 
   * Si no es POST, PUT o DELETE, no existe.
   *
   * @return boolean
   */
  public static function isMethodExistent()
  {
    if (
      isset($_POST["_method"])
      && !empty($_POST["_method"])
    ) {
      return
        $_POST["_method"] !== "POST"
        || $_POST["_method"] !== "PUT"
        || $_POST["_method"] !== "DELETE";
    }
    return false;
  }


  public static function fileExists(string $file_key_name): bool
  {
    // Accedemos al nombre temporal, ya que ahí se guarda el archivo
    // temporalmente.
    $tmp_name = $_FILES[$file_key_name]["tmp_name"];

    // Ahora, hay que verificar que se haya subido un archivo, ya que, a pesar
    // de que no se suba ningún archivo, el tamaño es mayor a 0 porque en el
    // array existe el elemento $file_key_name en este caso. 
    //
    // https://stackoverflow.com/a/946432/13562806
    return file_exists($tmp_name) || is_uploaded_file($tmp_name);
  }

  /**
   * Redirigir a la vista especificada a partir del directorio `views/`.
   *
   * @param string $view_path Directorio existente a partir de "views/".
   * @return void
   */
  public static function redirectView(
    string $view_path = "index.php",
    string $error = ""
  ) {
    $redirect_path = FOLDERS_WITH_LOCALHOST["VIEWS"] . $view_path;

    $redirect_path
      .= !empty($error)
      ? "?error={$error}"
      : "";

    /**
     * $file_headers = get_headers($redirect_path);
     *
     * Me estaba dando el siguiente error:
     *
     * Failed to open stream: HTTP request failed! in
     * C:\\xampp\\htdocs\\fdw-2021-2022-a\\proyecto-yeicobF\\src\\libs\\controller.php
     * on line 67
     *
     */

    // Nos aseguramos de que existe el enlace antes de redirigir.
    // if (
    //   isset($file_headers)
    //   && !empty($file_headers)
    //   && $file_headers[0] === "HTTP/1.1 200 OK"
    // ) {

    header("Location: " . $redirect_path);
    // Se deja de parsear el archivo, ya que pasaremos a uno nuevo.
    // exit();
    // return true;

    // }
    // return false;
  }

  /**
   * Eliminar espacios en blanco de los campos con trim.
   *
   * @param array $fields
   * @return void
   */
  public static function removeWhitespaces(array $fields)
  {
  }

  /**
   * Indica si la sesión ya inició.
   *
   * @return boolean
   */
  public static function isSessionActive()
  {
    return !(session_status() === PHP_SESSION_NONE);
  }

  public static function redirectIfSessionActive($redirect_view = "index.php")
  {
    if (self::isSessionActive()) {
      self::redirectView($redirect_view);
    }
  }

  /**
   * Comenzar una sesión si no está activa.
   *
   * @return void
   */
  public static function startSession()
  {
    if (!self::isSessionActive()) {
      session_start();
    }
  }
}
