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

$src_folder = FOLDERS_WITH_LOCALHOST["SRC"];
$views_folder = FOLDERS_WITH_LOCALHOST["VIEWS"];
$assets_folder = FOLDERS_WITH_LOCALHOST["ASSETS"];
$css_folder = FOLDERS_WITH_LOCALHOST["CSS"];
$img_folder = FOLDERS_WITH_LOCALHOST["IMG"];
