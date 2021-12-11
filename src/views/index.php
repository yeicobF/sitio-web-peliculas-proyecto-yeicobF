<?php
include "../config/directory_path.php";
$dir = new DirectoryPath();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- favicon -->
  <link rel="shortcut icon" href="<?php echo $dir->ico_url; ?>iconscout/film.ico" type="image/x-icon">

  <!-- CSS -->

  <!-- Framework que hicimos obtenido de la GitHub Page que publicamos. -->
  <link rel="stylesheet" href="https://fundamentos2122.github.io/framework-css-yeicobF/css/framework.css">

  <!-- CSS Propios -->
  <link rel="stylesheet" href="css/config.css">
  <link rel="stylesheet" href="css/components/components.css">
  <link rel="stylesheet" href="css/menu/menu.css">
  <link rel="stylesheet" href="css/utilities/utilities.css">

  <!-- GOOGLE FONTS -->

  <!-- Quicksand -->
  <!-- font-family: 'Quicksand', sans-serif; -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Roboto -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

  <!-- Single Day -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Single+Day&display=swap" rel="stylesheet">

  <!-- Scripts -->

  <!-- FRAMEWORK -->
  <script defer src="https://fundamentos2122.github.io/framework-css-yeicobF/js/framework.js">
  </script>

  <!-- 
    FONT AWESOME 6 (BETA) -> Agregué el defer.

    En un artículo de FontAwesome se recomienda. Además, así se descarga
    asíncronamente y se ejecuta cuando termina de hacerse el parse del HTML. 

    https://fontawesome.com/v5.15/how-to-use/on-the-web/advanced/svg-asynchronous-loading
  -->
  <script defer src="https://kit.fontawesome.com/db6a4307fa.js" crossorigin="anonymous"></script>

  <title>Colección de películas | Inicio</title>
</head>

<body>
  <!-- 
      Un nav, que podrías ser un div, pero ayuda en cuanto a la metadata, 
      indicando que se trata de una navbar.
    -->
  <nav class="navbar">
    <a href="#" class="navbar-brand">
      <!-- https://developer.mozilla.org/es/docs/Learn/HTML/Multimedia_and_embedding/Responsive_images -->
      <img src="<?php echo $dir->assets_url; ?>/iconscout/office-icon-pack-by-gunaldi-yunus/svg/film-1505229.svg" alt="Colección de películas" srcset="" sizes="" class="logo">
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
          <a href="#" class="nav-link">
            Inicio
          </a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">Películas</a>
        </li>
        <li class="nav-item dropdown">

          <a href="#" class="nav-link dropdown-toggle">
            Géneros
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
</body>

</html>
