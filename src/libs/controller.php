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
    if (!isset($_FILES) || empty(($_FILES))) {
      return false;
    }
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

  public static function getFile(string $file_key_name) {
    $tmp_name = $_FILES[$file_key_name]["tmp_name"];
    return file_get_contents($tmp_name);
  }

  public static function getEncodedImage(string $file) {
    return base64_encode($file);
  }

  /**
   * Redirigir a la vista especificada a partir del directorio `views/`.
   *
   * @param string $view_path Directorio existente a partir de "views/".
   * @return void
   */
  public static function redirectView(
    string $view_path = "index.php",
    string $message = "",
    string $error = ""
  ) {
    $redirect_path = FOLDERS_WITH_LOCALHOST["VIEWS"] . $view_path;

    $redirect_path
      .= strlen($error) > 0
      ? "?error={$error}"
      : "";
    $redirect_path
      .= strlen($message) > 0
      ? "?message={$message}"
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

  /**
   * El archivo en que nos encontramos actualmente es una vista.
   *
   * @return boolean
   */
  public static function isCurrentFileView()
  {
    return str_contains(
      $_SERVER["SCRIPT_FILENAME"],
      "views/"
    );
  }

  /**
   * Si no se trata de Post o el método no existe, redirigir al login. Así
   * evitamos que se entre desde la URL y que se envíen métodos inexistentes.
   *
   * @param string|null $view_path
   * @return void
   */
  public static function redirectIfNonExistentPostMethod(
    string $view_path = "index.php"
  ) {
    if (
      !Controller::isPost()
      || !Controller::isMethodExistent()
      // && !str_contains(
      //   $_SERVER["SCRIPT_FILENAME"],
      //   "login/"
      // )
      // && !str_contains(
      //   $_SERVER["SCRIPT_FILENAME"],
      //   "views/"
      // )
    ) {
      Controller::redirectView($view_path);
      // exit;
    }
  }

  /**
   * Eliminar espacios en blanco de los campos de un formulario con trim.
   *
   * @param array $form_fields Campos de formulario.
   * @return array
   */
  public static function removeFormFieldsWhitespaces(
    array $form_fields
  ): array {
    $trimmed_fields = [];

    foreach ($form_fields as $field_name => $field) {
      $trimmed_fields[$field_name] = trim($field);
    }

    return $trimmed_fields;
  }

  /**
   * Obtener arreglo con los campos no vacíos de un formulario.
   * 
   * Esto será útil sobre todo para las actualizaciones de campos, en donde,
   * solo se actualizarán los campos que no sean vacíos.
   *
   * @param array $form_fields Campos del formulario.
   * @return array Arreglo de campos no vacíos.
   */
  public static function getNonEmptyFormFields(array $form_fields): array
  {
    /**
     * Eliminar espacios en blanco del inicio y el final de los campos del
     * formulario.
     */
    $trimmed_fields = self::removeFormFieldsWhitespaces($form_fields);
    $non_empty_fields = [];

    foreach ($trimmed_fields as $field_name => $field) {
      if (strlen($field) > 0) {
        $non_empty_fields[$field_name] = trim($field);
      }
    }

    return $non_empty_fields;
  }

  // /**
  //  * Hacer un `return` que detendrá el proceso si el archivo actual es una
  //  * vista.
  //  *
  //  * @return void
  //  */
  // public static function returnIfFileIsView()
  // {
  //   // Si nos encontramos en una view, no hacer el proceso.
  //   if (self::isCurrentFileView()) {
  //     return;
  //   }
  // }
}
