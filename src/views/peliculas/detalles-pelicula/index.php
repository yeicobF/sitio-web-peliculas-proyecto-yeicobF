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
    <main class="row">
      <article class="movie-details__container col-12 row">
        <img class="movie-poster__img movie-details__img col-4" src="<?php echo $img_folder; ?>movie-posters/spiderman-no-way-home/1.jpg" alt="Póster de película">
        <section class="movie-details col-8">
          <!-- Título original y en español. -->
          <header class="movie-details__title">
            <h1>Nombre español</h1>
            <h2>Nombre original</h2>
          </header>
          <section class="movie-details__info">
            <!-- Calificaciones de la película. -->
            <data value="4.5" class="movie-details__user-rating">4.5/5</data>
            <time datetime="PT2H28M">2h 38m</time>
            <data value="4.5" class="movie-details__age-rating">
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
      </article>
    </main>
  </div>




  <?php
  include $path . DirectoryPath::LAYOUTS . "footer.php";
  ?>
</body>

</html>
