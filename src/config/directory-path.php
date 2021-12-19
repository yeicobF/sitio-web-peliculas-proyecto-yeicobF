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
   * Obtener ruta con el localhost al inicio.
   * 
   * No evaluará el tipo de valor que se envíe.
   *
   * @param string $pathConstant Constante de la ruta a obtener.
   * @return string
   */
  public static function getPathWithLocalhost($pathConstant)
  {
    return LOCALHOST_URL . $pathConstant;
  }
}
