<?php
include_once "../views/layouts/base-html-head.php";
$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Inicio",
  _includeOwnFramework: true,
  _includeFontAwesome: true
);
// var_dump($_SERVER);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  echo $baseHtmlHead->getHtmlBaseHead();
  ?>

  <!-- CSS -->
  <!-- CSS Propios -->
  <link rel="stylesheet" href="../css/config.css">
  <link rel="stylesheet" href="../css/components/components.css">
  <link rel="stylesheet" href="../css/menu/menu.css">
  <link rel="stylesheet" href="../css/utilities/utilities.css">
  <link rel="stylesheet" href="../css/transformations/rotate.css">

  <!-- SCRIPTS -->
  <script defer type="module">
    import { activateToggleRotate180 } from "../js/toggle/toggle-rotate.js";
    // Activamos la rotación de los elementos del navbar con la clase rotate.
    activateToggleRotate180(".navbar-nav .nav-link", ".rotate");
  </script>
  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body>
  <?php
  include "../views/layouts/navbar.php";
  ?>

  <div class="container-fluid container-xl">
    <div class="row">
      
      <!-- Contenedor de películas. -->
      <div class="movies-container border border-primary col-12 col-sm-8">
        <h1>Películas</h1>
      </div>
      <!-- Mejores películas. Es un sidebar. -->
      <div class="best-movies-container border border-primary col-12 col-sm-4">
        <!-- <h1>Mejores Películas</h1> -->
      </div>
    </div>  
  </div>

  <footer>

  </footer>
</body>

</html>
