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

/* ------------------------- ENLACE PARA CADA PÁGINA ------------------------ */

$user_url = "{$views_folder}user/";
$url_page = [
  "inicio" => $views_folder . "index.php",
  "peliculas" => $views_folder . "peliculas/index.php",
  "generos" => $views_folder . "generos/index.php",
  "login" => $views_folder . "login/index.php",
  "registro" => $views_folder . "login/registro.php",
];

// var_dump($user_url);
// var_dump($url_page);
// var_dump($url_page["login"]);
// var_dump($url_page["registro"]);

// Obtener nombre de archivo actual sin su extensión.
// $current_file_name = basename(__DIR__, ".php");
?>

<!-- 
    Un nav, que podrías ser un div, pero ayuda en cuanto a la metadata, 
    indicando que se trata de una navbar.
  -->
<nav class="navbar">
  <a rel="noopener noreferrer" href="<?php echo $url_page["inicio"]; ?>" class="navbar-brand">
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

    <!-- Inicio de sesión. -->
    <form class="login-buttons">
      <!-- button.btn.btn-light#login-button -->
      <a rel="noopener noreferrer" href="<?php echo $url_page["login"]; ?>" class="btn btn-light" id="login-button">
        Iniciar sesión
      </a>
      <!-- button.btn.btn-info#register-button{Registrarse} -->
      <a rel="noopener noreferrer" href="<?php echo $url_page["registro"]; ?>" class="btn btn-info" id="register-button">
        Registrarse
      </a>
    </form>

    <!-- Contenedor de links del menú. -->
    <ul class="navbar-nav">
      <!-- Elementos individuales. -->
      <li class="nav-item">
        <a rel="noopener noreferrer" href="<?php echo $url_page["inicio"]; ?>" id="nav-inicio" class="nav-link<?php echo $add_css_current_page["inicio"]; ?>">
          Inicio
        </a>
      </li>

      <li class="nav-item">
        <a rel="noopener noreferrer" href="<?php echo $url_page["peliculas"]; ?>" id="nav-peliculas" class="nav-link<?php echo $add_css_current_page["peliculas"]; ?>">
          Películas
        </a>
      </li>
      <li class="nav-item dropdown">

        <a rel="noopener noreferrer" href="#" id="nav-generos" class="nav-link dropdown-toggle<?php echo $add_css_current_page["generos"]; ?>">
          <p>Géneros</p>
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
            <a rel="noopener noreferrer" href="<?php echo $url_page["generos"]; ?>?genero=acción" class="dropdown-item">
              Acción
            </a>
          </li>
          <li>
            <a rel="noopener noreferrer" href="<?php echo $url_page["generos"]; ?>?genero=terror" class="dropdown-item">
              Terror
            </a>
          </li>
          <li>
            <a rel="noopener noreferrer" href="<?php echo $url_page["generos"]; ?>?genero=comedia" class="dropdown-item">
              Comedia
            </a>
          </li>
          <li>
            <a rel="noopener noreferrer" href="<?php echo $url_page["generos"]; ?>?genero=todos" class="dropdown-item">
              Ver todos
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
