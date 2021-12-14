<?php

$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/directory-path.php";

include_once $path
  . DirectoryPath::LAYOUTS
  . "base-html-head.php";

$src_folder = DirectoryPath::getPathWithLocalhost(DirectoryPath::SRC);
$views_folder = DirectoryPath::getPathWithLocalhost(DirectoryPath::VIEWS);
$img_folder =
  DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS)
  . "img/";

$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Detalles de película",
  _includeOwnFramework: true,
  _includeFontAwesome: true
);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  echo $baseHtmlHead->getHtmlBaseHead();
  ?>

  <!-- CSS -->
  <!-- CSS Propios -->
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/config.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/components/components.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/menu/menu.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/utilities/utilities.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/transformations/rotate.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/movies/movies.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/movies/movie-details.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/movies/comments.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/footer/footer.css">

  <!-- SCRIPTS -->
  <script defer src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::SRC) . "js/navbar.js"; ?>" type="module"></script>


  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body class="body-container">
  <?php
  include $path . DirectoryPath::LAYOUTS . "navbar.php";
  ?>

  <div class="fill-height-flex container-fluid container-lg">
    <main class="movie-details__container row">
      <img class="movie-poster__img movie-details__img col-12 col-sm-4" src="<?php echo $img_folder; ?>movie-posters/spiderman-no-way-home/1.jpg" alt="Póster de película">
      <section class="movie-details col-12 col-sm-8">
        <!-- Título original y en español. -->
        <header class="movie-details__title">
          <h1>Nombre español</h1>
          <h2>Nombre original</h2>
        </header>
        <section class="movie-details__info">
          <time datetime="2021" class="movie-details__year">2021</time>
          <!-- Calificaciones de la película. -->
          <data value="4.5" class="movie-details__user-rating">4.5/5</data>
          <time datetime="PT2H28M">2h 38m</time>
          <data value="18" class="movie-details__age-rating">
            Clasificación: 18+
          </data>
        </section>
        <p class="movie-details__synopsis">
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sint iste, ratione consequatur aliquid dolores cupiditate facere molestiae alias officia nisi totam modi ullam. Praesentium adipisci expedita iusto ullam deserunt illo?

          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sint iste, ratione consequatur aliquid dolores cupiditate facere molestiae alias officia nisi totam modi ullam. Praesentium adipisci expedita iusto ullam deserunt illo?
        </p>
        <ul class="movie-details__cast">
          <li class="movie-details__cast__type">
            <h3 class="movie-details__cast__type__title">
              Director/es:
            </h3>
            <ul>
              <li class="movie-details__cast__member">Lorem, ipsum dolor.</li>
              <li class="movie-details__cast__member">Lorem, ipsum dolor.</li>
              <li class="movie-details__cast__member">Lorem, ipsum dolor.</li>
              <li class="movie-details__cast__member">Lorem, ipsum.</li>
            </ul>
          </li>
          <li class="movie-details__cast__type">
            <h3 class="movie-details__cast__type__title">
              Actor/es:
            </h3>
            <ul>
              <li class="movie-details__cast__member">Lorem, ipsum dolor.</li>
              <li class="movie-details__cast__member">Lorem, ipsum.</li>
            </ul>
          </li>
          <li class="movie-details__cast__type">
            <h3 class="movie-details__cast__type__title">
              Géneros:
            </h3>
            <ul>
              <li class="movie-details__cast__member">Comedia</li>
              <li class="movie-details__cast__member">Acción</li>
            </ul>
          </li>
        </ul>
      </section>
    </main>
    <div class="row">
      <section class="comments__container col-12 col-sm-8">
        <h2>Comentarios</h2>
        <form action="" method="post" class="comments__form">
          <!-- Hay que definir el método a utilizar con un input hidden. -->
          <input type="hidden" name="_method" value="POST">
          
          <textarea placeholder="Ingresa un comentario" name="nuevo-comentario" id="" rows="5"></textarea>
          <input type="submit" value="Publicar" class="btn btn-info">
        </form>
      </section>
      <aside class="best-movies-container col-12 col-sm-4">
        <h2 class="best-movies-container__title">Mejores Películas</h2>
        <!-- Póster de películas. -->
        <figure class="row movie-poster   col-12">
          <!--
            Contenedor para que el año tenga posición relativa al póster y
            no a todo el contenedor.
          -->
          <div class="movie-poster__year-image col-6">
            <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
              <img class="movie-poster__img" src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS); ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
            </a>
            <time datetime="2021" class="movie-poster__year">2021</time>
          </div>
          <figcaption class="col-6">
            <ul class="best-movies__details-list">
              <li class="movie-poster__title">Spiderman: No Way Home</li>
              <li><data value="4.5">4.5/5</data></li>
              <li><time datetime="PT2H28M">2h 38m</time></li>
            </ul>
          </figcaption>
        </figure>
        <figure class="row movie-poster   col-12">
          <!--
            Contenedor para que el año tenga posición relativa al póster y
            no a todo el contenedor.
          -->
          <div class="movie-poster__year-image col-6">
            <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
              <img class="movie-poster__img" src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS); ?>img/movie-posters/friday-the-13th/1.jpg" alt="Friday the 13th">
            </a>
            <time datetime="2021" class="movie-poster__year">1980</time>
          </div>
          <figcaption class="col-6">
            <ul class="best-movies__details-list">
              <li class="movie-poster__title">Friday the 13th</li>
              <li><data value="4">4/5</data></li>
              <li><time datetime="PT2H35M">1h 35m</time></li>
            </ul>
          </figcaption>
        </figure>
        <!-- Póster de películas. -->
        <figure class="row movie-poster   col-12">
          <!--
            Contenedor para que el año tenga posición relativa al póster y
            no a todo el contenedor.
          -->
          <div class="movie-poster__year-image col-6">
            <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
              <img class="movie-poster__img" src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS); ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
            </a>
            <time datetime="2021" class="movie-poster__year">2021</time>
          </div>
          <figcaption class="col-6">
            <ul class="best-movies__details-list">
              <li class="movie-poster__title">Spiderman: No Way Home</li>
              <li><data value="4.5">4.5/5</data></li>
              <li><time datetime="PT2H28M">2h 38m</time></li>
            </ul>
          </figcaption>
        </figure>
        <figure class="row movie-poster   col-12">
          <!--
            Contenedor para que el año tenga posición relativa al póster y
            no a todo el contenedor.
          -->
          <div class="movie-poster__year-image col-6">
            <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
              <img class="movie-poster__img" src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS); ?>img/movie-posters/friday-the-13th/1.jpg" alt="Friday the 13th">
            </a>
            <time datetime="2021" class="movie-poster__year">1980</time>
          </div>
          <figcaption class="col-6">
            <ul class="best-movies__details-list">
              <li class="movie-poster__title">Friday the 13th</li>
              <li><data value="4">4/5</data></li>
              <li><time datetime="PT2H35M">1h 35m</time></li>
            </ul>
          </figcaption>
        </figure>
      </aside>
    </div>
  </div>




  <?php
  include $path . DirectoryPath::LAYOUTS . "footer.php";
  ?>
</body>

</html>
