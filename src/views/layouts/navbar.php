<?php
// __DIR__ obtiene el directorio del archivo actual, por lo que, al incluirlo en
// otros archivos, no llame al relativo al archivo que lo incluye. 
//
// Ya que, si incluyo a este archivo desde otro con una estructura diferente de
// archivos, el include lo haría relativamente al archivo que llama y no a este.
// include_once __DIR__ . "/" . "../../config/directory-path.php";

/* -------------------------------------------------------------------------- */

// Obtener la raíz del proyecto en el disco duro y no en el URL como en __DIR__.
// https://stackoverflow.com/questions/13369529/full-url-not-working-with-php-include
$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/config.php";

require_once FOLDERS_WITH_DOCUMENT_ROOT["MODELS"] . "pelicula.php";
require_once FOLDERS_WITH_DOCUMENT_ROOT["LIBS"] . "controller.php";

/* -------------------------------------------------------------------------- */
/* ----------------------- ARCHIVO ACTUAL Y CLASES CSS ---------------------- */

// Archivo de la página actual (el padre por decirlo de alguna manera, no este
// como con __DIR__).
$current_active_page_filename = $_SERVER["SCRIPT_FILENAME"];

// Clase de CSS para indicar en el navbar que nos encontramos en una página.
$css_current_page = " navbar__current-page";

// Nombres de archivos que son páginas para poner clase de página activa.
$inicio = "views/index.php";
$peliculas = "views/peliculas/";
$generos = "views/generos/";

// Si nos encontramos en una página, agregarle la clase de CSS que lo indica.
$add_css_current_page = [
  // strrpos: Última ocurrencia de una cadena en otra.
  // str_ends_with(): La cadena termina de esa manera.
  // str_contains(): Una cadena contiene una subcadena.
  "inicio" => str_contains($current_active_page_filename, $inicio)
    ? $css_current_page
    : "",
  // Peliculas y géneros pueden tener otros subelementos. Con que contenga la
  // cadena está bien, no tiene que terminar con ella.
  "peliculas" => str_contains($current_active_page_filename, $peliculas)
    ? $css_current_page
    : "",
  "generos" => str_contains($current_active_page_filename, $generos)
    ? $css_current_page
    : ""
];

$nav_pelicula = "<p>Películas</p>";

// Ver si estamos en los detalles de una película o su edición.
if (
  Libs\Controller::containsSpecificViewPath("/detalles-pelicula/")
  && Libs\Controller::getKeyExist("id")
  && is_numeric($_GET["id"])
) {
  $current_movie = Pelicula::getMovie($_GET["id"]);
  $current_movie_name = $current_movie["nombre_original"];

  // Símbolo ">"
  $chevron_right =
    "<i class='fas fa-chevron-right navbar__current-movie__icon'></i>";
  
  // Leyenda que se muestra dependiendo si nos encontramos en los detalles o en
  // la edición de detalles de película.
  $legend = "Detalles";
  $legend_class = "navbar__current-movie__legend";

  // El nombre de la película solo se mostrará en resoluciones menores en el
  // navbar.
  $p_movie = "<p class='navbar__current-movie__name'>{$current_movie_name}</p>";
  
  // Inicializamos aquí, ya que, en el if puede cambiar, pero si no entra, queda
  // este valor.
  $nav_pelicula_max_767 = $p_movie;

  if (Libs\Controller::containsSpecificViewPath("editar.php")) {
    $legend = "Editar";
    // La leyenda no llevará clase, por lo que, siempre se mostrará.
    $legend_class = "";

    // Div que se ocultará cuando se llegue a los 768px.
    // Se muestra "Películas > "Editar" > $p_movie
    $nav_pelicula_max_767 =
      "<div class='navbar__current_movie__edit--hide-on-767px'>"
      . $chevron_right
      . $p_movie
      . "</div>";
  }

  // Resoluciones mayores a 768px no muestran el nombre de la película, solo la
  // leyenda "Detalles" o "Editar".
  $nav_pelicula_min_768 =
    "<p class='{$legend_class}'>"
    . $legend
    . "</p>";

  /**
   * Agregamos primero la leyenda, porque cuando se muestra en la pantalla
   * "Detalles" no importa su orden, ya que se oculta, pero cuando estamos en la
   * pantalla "Editar", debe ir primero y nunca se oculta.
   *
   * Así evitamos más condicionales, aprovechando estos parámetros.
   */
  $nav_pelicula .= 
    $chevron_right
    . $nav_pelicula_min_768 
    . $nav_pelicula_max_767;
}
?>

<!-- 
    Un nav, que podrías ser un div, pero ayuda en cuanto a la metadata, 
    indicando que se trata de una navbar.
  -->
<nav class="navbar">
  <a rel="noopener noreferrer" href="<?php echo URL_PAGE["inicio"]; ?>" class="navbar-brand">
    <!-- https://developer.mozilla.org/es/docs/Learn/HTML/Multimedia_and_embedding/Responsive_images -->
    <img src="<?php echo FOLDERS_WITH_LOCALHOST["PAGE_LOGO"]; ?>" alt="Colección de películas" srcset="" sizes="" class="logo">
    <!-- p.navbar__title -->
  </a>
  <!-- 
      Para la función de despliegue u ocultado del menú. 
      Esto es para el diseño responsivo, que cuando se cambie de tamaño se ocupe o no.

      Solo se va a mostrar cuando estemos en un dispositivo móvil, y cuando 
      estemos en uno más grande, lo vamos a ocultar.
    -->
  <!-- button.navbar-toggle[type="button"] -->
  <button class="navbar-toggle btn btn-primary" type="button">
    <i class="fa-solid fa-bars"></i>
  </button>

  <div class="navbar-collapse">
    <!-- Barra de búsqueda. -->
    <form action="" method="get" class="search-container">
      <button type="submit" title="search" id="search-button">
        <i class="fa-solid fa-magnifying-glass fa-sm"></i>
      </button>
      <input type="text" name="search-input" id="search-input" placeholder="Buscar...">
    </form>

    <?php
    require_once __DIR__ . "/navbar/session-buttons.php";
    ?>

    <!-- Contenedor de links del menú. -->
    <ul class="navbar-nav">
      <!-- Elementos individuales. -->
      <li class="nav-item">
        <a rel="noopener noreferrer" href="<?php echo URL_PAGE["inicio"]; ?>" id="nav-inicio" class="nav-link<?php echo $add_css_current_page["inicio"]; ?>">
          Inicio
        </a>
      </li>

      <li class="nav-item">
        <a rel="noopener noreferrer" href="<?php echo URL_PAGE["peliculas"]; ?>" id="nav-peliculas" class="nav-link<?php echo $add_css_current_page["peliculas"]; ?>">
          <?php echo $nav_pelicula; ?>
        </a>
      </li>
      <li class="nav-item dropdown">

        <a rel="noopener noreferrer" href="#" id="nav-generos" class="nav-link dropdown-toggle">
          <p class="<?php echo $add_css_current_page["generos"]; ?>">Géneros</p>
          <i class="rotate fas fa-angle-down"></i>
          <!-- <i class="submenu-indicator-tooltip">v</i> -->
        </a>

        <!-- Dropdown menu desplegable de categorías. -->
        <ul class="dropdown-menu">
          <!-- 
              El menú sale oculto por default, pero con la clase "show", la
              cual se agregará con Javascript, mostrará el menú con la
              propiedad "display: block;".
            -->
          <li>
            <a rel="noopener noreferrer" href="<?php echo URL_PAGE["generos"]; ?>?genero=acción" class="dropdown-item">
              Acción
            </a>
          </li>
          <li>
            <a rel="noopener noreferrer" href="<?php echo URL_PAGE["generos"]; ?>?genero=terror" class="dropdown-item">
              Terror
            </a>
          </li>
          <li>
            <a rel="noopener noreferrer" href="<?php echo URL_PAGE["generos"]; ?>?genero=comedia" class="dropdown-item">
              Comedia
            </a>
          </li>
          <li>
            <a rel="noopener noreferrer" href="<?php echo URL_PAGE["generos"]; ?>?genero=todos" class="dropdown-item">
              Ver todos
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
