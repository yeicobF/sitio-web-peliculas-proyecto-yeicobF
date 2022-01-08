<?php

namespace Libs;

use DateTime;
use DateTimeZone;

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

    // Nos aseguramos de que existe el enlace antes de redirigir. if (
    // isset($file_headers) && !empty($file_headers) && $file_headers[0] ===
    // "HTTP/1.1 200 OK"
    // ) {

    header("Location: " . $redirect_path);
    // Se deja de parsear el archivo, ya que pasaremos a uno nuevo. exit();
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
   * Esto ayuda a que, si importamos `Controllers\Usuario` en
   * `Controllers\Login`, no se hagan los procedimientos de `Usuario` en
   * `Login`.
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
   * Revisar si el controlador fue incluido después de otro controlador.
   *
   * Esto puede parecer confuso, pero es porque en ocasiones se incluye un
   * controlador en otro controlador y termina haciendo su procedimiento antes
   * de lo deseado.
   *
   * Esto permitirá revisar que no se incluyó otro controlador antes de este
   * archivo y que se evite su ejecución.
   *
   * @return bool
   */
  public static function wasControllerIncludedAfterController(string $controller_name)
  {
    $include_history = get_included_files();

    /**
     * Array con los controladores sin incluir el que se envió.
     */
    $controllers_without_sent = array_filter(
      $include_history,
      // https://stackoverflow.com/a/10712844/13562806 PHP array_filter with
      // arguments
      function ($include_element) use ($controller_name) {
        return str_contains($include_element, "controllers\\") && !str_contains($include_element, $controller_name);
      }
    );

    // Si el arreglo tiene más de 0 elementos, entonces encontró otros
    // controladores.
    return count($controllers_without_sent) > 0;
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
      // && !str_contains( $_SERVER["SCRIPT_FILENAME"], "login/"
      // )
      // && !str_contains( $_SERVER["SCRIPT_FILENAME"], "views/"
      // )
    ) {
      Controller::redirectView($view_path);
      // exit;
    }
  }
  /**
   * Revisar si la llave "id" existe en un arreglo.
   *
   * @param boolean $check_in_get Indica si se va a revisar en la variable
   * `$_GET`.
   * @param array $find_here Si `$check_in_get = false`, buscar si se encuentra
   * el ID en este arreglo.
   * @return bool
   */
  public static function idExists(
    bool $check_in_get = true,
    array $find_here = []
  ): bool {
    return $check_in_get
      ? Controller::getKeyExist("id") && is_numeric($_GET["id"])
      : array_key_exists("id", $find_here) && is_numeric($find_here["id"]);
  }

  /**
   * Redirigir a vista si no se encuentra el ID.
   *
   * Se puede buscar directamente en `$_GET` o en un arreglo que se envíe por
   * parámetro.
   *
   * @param string $redirect_view
   * @param boolean $check_in_get Indica si se va a revisar en la variable
   * `$_GET`.
   * @param array $find_here Si `$check_in_get = false`, buscar si se encuentra
   * el ID en este arreglo.
   * @return void
   */
  public static function redirectIfIdNotFound(
    string $view_path = "index.php",
    bool $check_in_get = true,
    array $find_here = []
  ) {
    $redirect = false;

    // No existe la llave en GET o si existe, no es numérica.
    $redirect = !self::idExists($check_in_get, $find_here);

    if ($redirect) {
      Controller::redirectView(
        view_path: $view_path,
        error: "No se especificó un ID correcto."
      );
    }

    // Creo que después del redireccionamiento el programa sigue corriendo, por
    // lo que, hay que indicar que redireccionamos.
    return $redirect;
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

  /**
   * Obtener el tiempo que ha pasado desde la hora actual.
   *
   * - Converting timestamp to time ago in PHP e.g 1 day ago, 2 days ago...:
   *   https://stackoverflow.com/a/18602474/13562806
   *
   * ## Solución con JavaScript
   *
   * De hecho, esto podría hacerlo inicialmente aquí para mostrar el tiempo que
   * ha pasado inicialmente, pero con JavaScript seguir actualizando el tiempo
   * que ha concurrido.
   *
   *
   * > En StackOverflow encontré respuestas que me podrían ser de ayuda:
   * https://stackoverflow.com/a/5092038/13562806
   *
   * ## Formato del tiempo en MySQL
   *
   * En la tabla de MySQL se guardan los datos como:
   *
   * - `fecha`: 2022-01-05
   * - `hora`: 13:19:30
   *
   * ## Condiciones
   *
   * El tiempo que ha pasado dependerá de ciertas condiciones:
   *
   * - Si no ha pasado menos de un minuto, regresar: "Hace un momento".
   * - Si ha pasado más de un minuto, mostrar el número de minutos.
   * - Si han pasado más de 60 minutos, mostrar el número de horas.
   * - Si han pasado más de 24 horas, mostrar el número de días.
   * - Si han pasado más de 31 días, mostrar el número de meses que han pasado.
   *
   * - Si han pasado más de 12 meses, mostrar el número de años y meses que han
   *   pasado.
   *   - Si no ha pasado ningún mes en dichos años, solo mostrar el año.
   *
   * ## Fuentes
   *
   * - PHP Date and Time Recipes:
   *   https://css-tricks.com/php-date-and-time-recipes/
   *
   * - Converting timestamp to time ago in PHP e.g 1 day ago, 2 days ago...:
   *   https://stackoverflow.com/a/18602474/13562806
   *
   * @param string $date Fecha en el siguiente formato: `YYYY-MM-DD`, ejemplo:
   * `2022-01-05`.
   * @param string $time Hora en formato de 24 horas con el siguiente formato:
   * `HH:MM:SS`, ejemplo: `13:19:30`.
   * @return string
   */
  public static function getTimeElapsed(
    string $date,
    string $time,
    string $defaultTimeZone = DEFAULT_TIME_ZONE_STRING
  ) {
    $date_time_zone = new DateTimeZone(DEFAULT_TIME_ZONE_STRING);

    // Obtener el tiempo actual.
    $now = new DateTime(
      timezone: $date_time_zone
    );

    // "2022-01-05 13:19:30"
    $given_date_string = "{$date} {$time}";
    // Objeto con fecha obtenida por los parámetros.
    $given_date = new DateTime(
      $given_date_string,
      timezone: $date_time_zone
    );

    // Diferencia entre tiempo actual y fecha dada.
    $date_interval = $now->diff($given_date);

    /**
     * DateInterval::$invert
     *
     * Is 1 if the interval is inverted and 0 otherwise
     */
    if (!$date_interval->invert) {
      // Si el tiempo dato no es menor al actual (sería raro), regresar null.
      return null;
    }

    // Número de semanas, ya que, no es una propiedad default del objeto.
    $date_interval->w = floor($date_interval->d / 7);

    $time_format = [
      'y' => 'año',
      'm' => 'mes',
      'w' => 'semana',
      'd' => 'día',
      'h' => 'hora',
      'i' => 'minuto',
      's' => 'segundo',
    ];

    // Arreglo en donde, guardaremos los valores que encontremos de
    // $time_format.
    $time_ago = [];

    // Recorrer los tiempos y ver cuáles encontramos.
    foreach ($time_format as $type => $format) {

      // Si no existe una diferencia de tiempo en el tipo actual (y, m, w, ...)
      if (!$date_interval->$type) {
        continue;
      }

      // Concatenamos: Valor del tipo actual + nombre del tipo + agregar "s"
      // si es mayor a 1 el valor.
      $time_ago[$type] =
        "{$date_interval->$type} {$format}";

      // El valor es mayor a 1, hay que convertir el formato a plural.
      if ($date_interval->$type > 1) {
        // Por mes"e"s -> meses
        if ($type === "m") {
          $time_ago[$type] .= "e";
        }
        $time_ago[$type] .= "s";
      }
    }

    /**
     * Revisar desde lo mayor a lo menor, así hacemos early returns. 
     * 
     * Este lo ponemos en una condicional distinta porque no solo iría el año,
     * sino que, también puede llevar el mes. 
     */
    if (array_key_exists("y", $time_ago) && $time_ago["y"] > 0) {
      $string = "Hace {$time_ago["y"]}";

      // Si existe el mes, concatenarlo.
      if (array_key_exists("m", $time_ago) && $time_ago["m"] > 0) {
        $string .= ", {$time_ago["m"]}";
      }

      return $string;
    }

    /**
     * Los demás valores van solos, es decir, no se concatenan con algo más, por
     * lo que, con regresar la primera posición del arreglo está bien. 
     * 
     * - Si hay más de un elemento en el arreglo, regresar solo el primer tiempo
     *   y convertimos a cadena con implode():
     * 
     *   "Hace 1 mes | semana | minuto | segundo"
     * 
     * - Si no hay elementos, significa que acaba de ser agregado.
     * 
     * Basado en: https://stackoverflow.com/a/18602474/13562806
     * 
     * Si quisiera todos los elementos concatenados:
     *   implode(", ", $time_ago) -> "1 día, 2 horas, 54 minutos, 37 segundos"
     */
    return
      count($time_ago) > 0
      ? "Hace " . implode(array_slice($time_ago, 0, 1))
      : "Hace un momento";
  }

  /**
   * Obtener la fecha en nuestro propio formato, no el de la BD.
   *
   * @param string $date Fecha en formato `YYYY-MM-DD`.
   * @return string Fecha en nuestro formato, ejemplo: `20 / enero / 2022`
   */
  public static function getDateAsOwnFormat(
    string $date,
    string $date_format = "Y-m-d"
  ) {
    $provided_date = DateTime::createFromFormat($date_format, $date);
    // https://www.php.net/manual/en/datetime.format.php
    $own_format_date = $provided_date->format("j/m/Y");
    $split_date = explode("/", $own_format_date);
    $months = [
      "01" => "enero",
      "02" => "febrero",
      "03" => "marzo",
      "04" => "abril",
      "05" => "mayo",
      "06" => "junio",
      "07" => "julio",
      "08" => "agosto",
      "09" => "septiembre",
      "10" => "octubre",
      "11" => "noviembre",
      "12" => "diciembre",
    ];

    // Si no existe el mes, no regresar nada.
    if (!array_key_exists($split_date[1], $months)) {
      return null;
    }

    $split_date[1] = $months[$split_date[1]];

    return implode(" de ", $split_date);
  }

  /**
   * Obtener fecha con nuestro formato: "h:i A".
   *
   *  https://www.php.net/manual/en/datetime.format.php
   *
   *  Ejemplo de este formato "h:i A": 03:45 AM
   *
   * @param string $time
   * @param string $time_format
   * @return void
   */
  public static function getTimeAsOwnFormat(
    string $time,
    string $time_format = "H:i:s"
  ) {
    $provided_time = DateTime::createFromFormat($time_format, $time);
    // https://www.php.net/manual/en/datetime.format.php
    // Ejemplo de este formato "h:i A": 03:45 AM
    return $provided_time->format("h:i A");
  }

  // /**
  //  * Hacer un `return` que detendrá el proceso si el archivo actual es una
  //  * vista.
  //  *
  //  * @return void
  //    */
  // public static function returnIfFileIsView()
  // {
  //   // Si nos encontramos en una view, no hacer el proceso. if
  //   (self::isCurrentFileView()) { return;
  //   }
  // }
}
