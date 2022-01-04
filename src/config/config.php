<?php

/**
 * Constantes a utilizar en el sitio web. Su acceso es global.
 *
 * CONST vs DEFINE: https://stackoverflow.com/a/3193704/13562806
 *
 * "The fundamental difference between those two ways is that const defines
 * constants at compile time, whereas define defines them at run time."
 */

/* --------------------------- BASES DEL PROYECTO --------------------------- */

// C:/xampp/htdocs/
define("DOCUMENT_ROOT", "{$_SERVER["DOCUMENT_ROOT"]}/");
define("LOCALHOST_URL", "http://localhost:8012/");
define("BARE_PROJECT_FOLDER_DIR", "fdw-2021-2022-a/proyecto-yeicobF/");
define("INTERNAL_ENCODING", "UTF-8");

/* ----------------------------------- SRC ---------------------------------- */
define("SRC", BARE_PROJECT_FOLDER_DIR . "src/");
define("CONFIG", SRC . "config/");
define("LIBS", SRC . "libs/");
define("PUBLIC_FOLDER", SRC . "public/");

/* ------------------------------ CONTENIDO SRC ----------------------------- */

define("MODELS", SRC . "models/");
define("CONTROLLERS", SRC . "controllers/");
define("VIEWS", SRC . "views/");

/* -------------------------------- SRC/VIEWS ------------------------------- */

define("LAYOUTS", VIEWS . "layouts/");
define("COMPONENTS", VIEWS . "components/");

/* --------------------------------- PUBLIC --------------------------------- */

define("CSS", PUBLIC_FOLDER . "css/");
define("JS", PUBLIC_FOLDER . "js/");
define("ASSETS", PUBLIC_FOLDER . "assets/");

/* ----------------------------- PUBLIC / ASSETS ---------------------------- */

define("PAGE_LOGO_ORIGINAL_PATH", ASSETS . "iconscout/office-icon-pack-by-gunaldi-yunus/svg/film-1505229.svg");
define("PAGE_LOGO", ASSETS . "page-logo/film-1505229.svg");
define("IMG", ASSETS . "img/");



/* --------------------- VARIABLES RESPECTO AL LOCALHOST -------------------- */
require_once DOCUMENT_ROOT . CONFIG . "directory-path.php";

/**
 * Arreglo con todas las constantes definidas para los directorios.
 */
$path_constants_array = [
  "BARE_PROJECT_FOLDER_DIR",
  "SRC",
  "CONFIG",
  "LIBS",
  "PUBLIC_FOLDER",
  "MODELS",
  "CONTROLLERS",
  "VIEWS",
  "LAYOUTS",
  "COMPONENTS",
  "CSS",
  "JS",
  "ASSETS",
  "IMG",
  "PAGE_LOGO_ORIGINAL_PATH",
  "PAGE_LOGO",
];

/**
 * Arreglo asociativo con cada directorio incluyendo el localhost al inicio.
 */
define(
  "FOLDERS_WITH_LOCALHOST",
  DirectoryPath::getPathWithStartingPath(
    LOCALHOST_URL,
    $path_constants_array
  )
);

/* ------------------- VARIABLES RESPECTO AL DOCUMENT_ROOT ------------------ */
define(
  "FOLDERS_WITH_DOCUMENT_ROOT",
  DirectoryPath::getPathWithStartingPath(
    DOCUMENT_ROOT,
    $path_constants_array
  )
);

/* --------- Directorios como variables para acceder más fácilmente. -------- */

define("SRC_FOLDER", FOLDERS_WITH_LOCALHOST["SRC"]);
define("VIEWS_FOLDER", FOLDERS_WITH_LOCALHOST["VIEWS"]);
define("ASSETS_FOLDER", FOLDERS_WITH_LOCALHOST["ASSETS"]);
define("CSS_FOLDER", FOLDERS_WITH_LOCALHOST["CSS"]);
define("IMG_FOLDER", FOLDERS_WITH_LOCALHOST["IMG"]);
define("LIBS_FOLDER", FOLDERS_WITH_LOCALHOST["LIBS"]);
define("CONTROLLERS_FOLDER", FOLDERS_WITH_LOCALHOST["CONTROLLERS"]);
define("CONFIG_FOLDER", FOLDERS_WITH_LOCALHOST["CONFIG"]);

/* ------------------------- ENLACE PARA CADA PÁGINA ------------------------ */

define("USER_URL", VIEWS_FOLDER . "user/");

define("ADMIN_URL", USER_URL . "admin/");
define(
  "DETALLES_PELICULA_FOLDER",
  VIEWS_FOLDER . "peliculas/detalles-pelicula/"
);

// "\{\$url_page\["[a-z-]*"\]\}
// \$url_page\["[a-z-]*

define(
  "URL_PAGE",
  [
    "inicio" => VIEWS_FOLDER . "index.php",
    "peliculas" => VIEWS_FOLDER . "peliculas/index.php",
    "generos" => VIEWS_FOLDER . "generos/index.php",
    "login" => VIEWS_FOLDER . "login/index.php",
    "registro" => VIEWS_FOLDER . "login/registro.php",
    "editar-perfil" => USER_URL . "editar-perfil/index.php",
    "detalles-perfil" => USER_URL . "index.php",
    "detalles-pelicula" => DETALLES_PELICULA_FOLDER . "index.php",
    "editar-pelicula" => DETALLES_PELICULA_FOLDER . "editar.php",
    "agregar-pelicula" => ADMIN_URL . "agregar-pelicula/index.php",
  ]
);

// var_dump(USER_URL);
// var_dump(URL_PAGE);
// var_dump(URL_PAGE["login"]);
// var_dump(URL_PAGE["registro"]);

// Obtener nombre de archivo actual sin su extensión.
// $current_file_name = basename(__DIR__, ".php");

/**
 * Imprimir elementos de un arreglo con salto de línea.
 *
 * @param array $elements
 * @return void
 */
function showElements(array $elements = [])
{
  echo "<br><br>";
  var_dump($elements);
  foreach ($elements as $value) {
    echo "<br><br>" . var_dump($value) . "<br><br>";
  }
  echo "<br><br>";
}
