<?php

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
  public static function getMethod()
  {
    return $_POST["_method"];
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
    return
      $_POST["_method"] !== "POST"
      && $_POST["_method"] !== "PUT"
      && $_POST["_method"] !== "DELETE";
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
  public static function redirectView(string $view_path)
  {
    $redirect_path = FOLDERS_WITH_LOCALHOST["VIEWS"] . $view_path;
    $file_headers = get_headers($redirect_path);
    // Nos aseguramos de que existe el enlace antes de redirigir.
    if (
      $file_headers[0] === "HTTP/1.1 200 OK"
    ) {
      header("Location: " . $redirect_path);
      // Se deja de parsear el archivo, ya que pasaremos a uno nuevo.
      exit();
      return true;
    }
    return false;
  }
}
