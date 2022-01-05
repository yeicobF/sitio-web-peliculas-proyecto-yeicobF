<?php

namespace Libs;

require_once __DIR__ . "/../config/config.php";

class Controller
{
  const FILES = [
    "login" => FOLDERS_WITH_LOCALHOST["CONTROLLERS"] . "login.php",
    "pelicula" => FOLDERS_WITH_LOCALHOST["CONTROLLERS"] . "pelicula.php",
    "usuario" => FOLDERS_WITH_LOCALHOST["CONTROLLERS"] . "usuario.php",
  ];

  /**
   * Revisar si el método al que se llama es GET.
   *
   * @return boolean
   */
  public static function isGet()
  {
    return $_SERVER["REQUEST_METHOD"] === "GET";
  }

  /**
   * Verificar si una key existe en la URL.
   * 
   * Esto es del método GET.
   *
   * @param string $key
   * @return bool
   */
  public static function getKeyExist(string $key)
  {
    if (self::isGet()) {
      return array_key_exists($key, $_GET) && $_GET[$key] !== null;
    }

    return false;
  }

  /**
   * Obtener el ID del URL con GET.
   * 
   * Hay que verificar si sí es numérico.
   *
   * @return int | null
   */
  //   public static function getIdFromGet() {
  // 
  //   }

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

  public static function getFile(string $file_key_name)
  {
    $tmp_name = $_FILES[$file_key_name]["tmp_name"];
    return file_get_contents($tmp_name);
  }

  public static function getEncodedImage(string $file)
  {
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

    $query_params_symbol = str_contains($view_path, "?") ? "&" : "?";

    $redirect_path
      .= strlen($error) > 0
      ? "{$query_params_symbol}error={$error}"
      : "";
    $redirect_path
      .= strlen($message) > 0
      ? "{$query_params_symbol}message={$message}"
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
   * Revisar si la ruta del archivo actual contiene el directorio especificado.
   *
   * @param string $path_from_view_folder Dirección relativa al directorio
   * `views/`.
   * @return boolean
   */
  public static function containsSpecificViewPath($path_from_view_folder)
  {
    if (self::isCurrentFileView()) {
      return str_contains(
        $_SERVER["SCRIPT_FILENAME"],
        $path_from_view_folder
      );
    }

    return false;
  }

  public static function isCurrentFileController()
  {
    return str_contains(
      $_SERVER["SCRIPT_FILENAME"],
      "controllers/"
    );
  }

  /**
   * Revisar si el archivo actual es un controlador distinto al del controlador
   * del archivo que hace los procedimientos.
   *
   * Esto ayuda a que, si importamos `Controllers\Usuario` en `Controllers\Login`,
   * no se hagan los procedimientos de `Usuario` en `Login`.
   *
   * @return boolean
   */
  public static function isCurrentFileAnotherController(
    string $current_controller_file_name
  ) {
    if (self::isCurrentFileController()) {
      // Contiene el archivo actual o no.
      return !str_contains(
        $_SERVER["SCRIPT_FILENAME"],
        "controllers/{$current_controller_file_name}.php"
      );
    }
    return false;
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

  /**
   * Revisar que todos los datos de registro hayan sido especificados.
   *
   * @param array $form_fields
   * @return bool
   */
  public static function areRequiredFieldsFilled($required_fields, $form_fields)
  {
    /**
     * an array containing all the entries from array1 that are not present in
     * any of the other arrays.
     * 
     * Si es igual a 0, significa que encontró todos los valores.
     */
    $diff = array_diff($required_fields, array_keys($form_fields));
    return count($diff) === 0;
  }

  public static function isTimeSpecified($hours, $minutes, $seconds)
  {
    return
      strlen($hours) > 0
      && strlen($minutes) > 0
      && strlen($seconds) > 0;
  }

  public static function getTime($hours, $minutes, $seconds)
  {
    if (self::isTimeSpecified($hours, $minutes, $seconds)) {
      return "{$hours}:{$minutes}:{$seconds}";
    }
    return null;
  }

  /**
   * Convierte cadena a arreglo con las palabras iniciando en mayúsucula.
   *
   * @param string $delimiter
   * @param string $element
   * @return array
   */
  public static function stringToUppercasePerWord(
    string $delimiter,
    string $element
  ): array {
    $words = explode($delimiter, $element);
    $uc_words = [];

    foreach ($words as $word) {
      $uc_word = ucfirst($word);
      array_push($uc_words, $uc_word);
    }

    return $uc_words;
  }

  /**
   * Obtener elementos en forma de lista.
   *
   * @param array $items
   * @return void
   */
  public static function getListItems(array $items, string $classes)
  {
    foreach ($items as $item) {
?>
      <li class="<?php echo $classes; ?>"><?php echo $item; ?></li>
<?php
    }
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
