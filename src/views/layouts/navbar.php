<?php
// __DIR__ obtiene el directorio del archivo actual, por lo que, al incluirlo en
// otros archivos, no llame al relativo al archivo que lo incluye. 
//
// Ya que, si incluyo a este archivo desde otro con una estructura diferente de
// archivos, el include lo haría relativamente al archivo que llama y no a este.
include_once __DIR__ . "/" . "../../config/directory-path.php";

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

// var_dump($add_css_current_page);

// Obtener nombre de archivo actual sin su extensión.
// $current_file_name = basename(__DIR__, ".php");
?>

<!-- 
    Un nav, que podrías ser un div, pero ayuda en cuanto a la metadata, 
    indicando que se trata de una navbar.
  -->
<nav class="navbar">
  <a href="#" class="navbar-brand">
    <!-- https://developer.mozilla.org/es/docs/Learn/HTML/Multimedia_and_embedding/Responsive_images -->
    <img src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS); ?>/iconscout/office-icon-pack-by-gunaldi-yunus/svg/film-1505229.svg" alt="Colección de películas" srcset="" sizes="" class="logo">
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
    <div class="login-buttons">
      <!-- button.btn.btn-light#login-button -->
      <button type="button" class="btn btn-light" id="login-button">
        Iniciar sesión
      </button>
      <!-- button.btn.btn-info#register-button{Registrarse} -->
      <button class="btn btn-info" id="register-button">
        Registrarse
      </button>
    </div>

    <!-- Contenedor de links del menú. -->
    <ul class="navbar-nav">
      <!-- Elementos individuales. -->
      <li class="nav-item">
        <a href="#" id="nav-inicio" class="nav-link<?php echo $add_css_current_page["inicio"]; ?>">
          Inicio
        </a>
      </li>

      <li class="nav-item">
        <a href="#" id="nav-peliculas" class="nav-link<?php echo $add_css_current_page["peliculas"]; ?>">
          Películas
        </a>
      </li>
      <li class="nav-item dropdown">

        <a href="#" id="nav-generos" class="nav-link dropdown-toggle<?php echo $add_css_current_page["generos"]; ?>">
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
            <a href="#" class="dropdown-item">
              Acción
            </a>
          </li>
          <li>
            <a href="#" class="dropdown-item">
              Terror
            </a>
          </li>
          <li>
            <a href="#" class="dropdown-item">
              Comedia
            </a>
          </li>
          <li>
            <a href="#" class="dropdown-item">
              Ver todos
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
