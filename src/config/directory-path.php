<?php

/**
 * Clase para obtener los directorios de diversos elementos.
 * 
 * Las variables son estáticas.
 */
class DirectoryPath
{
  // Variables no estáticas, ya que, si lo hacemos así, nos da error por no ser
  // constantes.
  //
  // $_SERVER["HTTP_HOST"]: Obtiene el host (localhost:8012), pero no lo puedo
  // asignar a una constante. Serviría en este caso si la clase la pudiera
  // instanciar y utilizar variables e clase, pero por el momento no lo
  // requiero.
  // const LOCALHOST = "http://localhost:8012/";
  // const BARE_PROJECT_FOLDER_DIR = "fdw-2021-2022-a/proyecto-yeicobF/";

  /* ----------------- LOS DIRECTORIOS SON SIN EL LOCALHOST. ---------------- */

  // const SRC = self::BARE_PROJECT_FOLDER_DIR . "src/";
  // const CONFIG = self::SRC . "config/";
  // const ASSETS = self::SRC . "assets/";
  // const PAGE_LOGO = self::ASSETS . "/iconscout/office-icon-pack-by-gunaldi-yunus/svg/film-1505229.svg";
  // const MODELS = self::SRC . "models/";
  // const CONTROLLERS = self::SRC . "controllers/";
  // const VIEWS = self::SRC . "views/";
  // const LAYOUTS = self::VIEWS . "layouts/";
  // const COMPONENTS = self::VIEWS . "components/";
  /**
   * Directorio desde el directorio del proyecto sin incluir el localhost. Esto
   * es más que nada útil en los casos en donde utilizamos include, ya que, no
   * permite rutas con un enlace, y el localhost incluye el protocolo http.
   *
   * Este directorio comienza desde la carpeta de htdocs, pero sin el localhost.
   *
   * - Si queremos el Localhost en la ruta, podemos concatenar la constante al
   *   inicio de cualquiera de estos valores.
   */
  //   const PATH_WITHOUT_LOCALHOST = [
  //     "src" =>
  //     ,
  // 
  //     "config" =>
  //     self::PATH_WITHOUT_LOCALHOST["src"]
  //       . "config/",
  // 
  //     "assets" =>
  //     self::PATH_WITHOUT_LOCALHOST["src"]
  //       . "assets/",
  // 
  //     "models" =>
  //     self::PATH_WITHOUT_LOCALHOST["src"]
  //       . "models/",
  //     "controllers" =>
  //     self::PATH_WITHOUT_LOCALHOST["src"]
  //       . "controllers/",
  //     "views" => self::PATH_WITHOUT_LOCALHOST["src"]
  //       . "views/",
  //   ];

  /**
   * Obtener ruta de un directorio con una ruta inicial.
   * 
   * Podríamos obtener la ruta de un directorio a partir del localhost o de la
   * raíz física del proyecto con la variable `$_SERVER["DOCUMENT_ROOT"]}`.
   *
   * @param string $starting_path_constant Directorio raíz.
   * @param array $path_constants Constantes de los directorios a obtener.
   * @return array Directorios con el formato especificado.
   */
  public static function getPathWithStartingPath(
    string $starting_path_constant,
    array $path_constants
  ): array {
    $constants_with_starting_path = [];

    foreach ($path_constants as $constant) {
      $constant_key = mb_strtolower($constant, INTERNAL_ENCODING);
      $constants_with_starting_path[$constant_key]
        = $starting_path_constant . constant($constant);
    }
    return $constants_with_starting_path;
  }
}
