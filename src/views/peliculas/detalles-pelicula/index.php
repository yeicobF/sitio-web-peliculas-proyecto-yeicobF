<?php

use Libs\Controller;
use Controllers\Login;
use Controllers\Usuario;

$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/config.php";

include_once $path
  . LAYOUTS
  . "base-html-head.php";

include_once
  FOLDERS_WITH_DOCUMENT_ROOT["LIBS"]
  . "controller.php";
include_once
  FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
  . "usuario.php";
include_once
  FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
  . "login.php";

if (Controller::redirectIfIdNotFound(view_path: "peliculas/index.php")) {
  return;
}

$id_pelicula = $_GET["id"];
$nombre_pelicula;

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
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>config.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>components/components.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>menu/menu.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>utilities/utilities.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>transformations/rotate.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>movies/movies.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>movies/movie-details.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>movies/comments.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>footer/footer.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>form/form.css">

  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body class="body-container">
  <?php
  include $path . LAYOUTS . "navbar.php";
  ?>

  <div class="fill-height-flex container-fluid container-lg">
    <main class="movie-details__container row">

      <?php
      include_once
        FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
        . "pelicula.php";
      ?>

    </main>
    <div class="row">
      <section class="comments__container col-12 col-md-8">
        <h2>Comentarios</h2>
        <?php
        $logged_in = false;
        $action = "";
        // Hay que definir el método a utilizar con un input hidden.
        $input_movie_id =
          "<input type='hidden' name='pelicula_id' value='{$id_pelicula}'>";
        $post_method = "";
        $input_user_id = "";
        $textarea_placeholder = "Inicia sesión para comentar.";
        $profile_picture = "";
        $btn_classes = "comments__form__btn btn";
        // El formulario depende de si el usuario ha iniciado sesión o no.
        if (
          Login::isUserLoggedIn()
          && Controller::idExists(false, $_SESSION)
          && is_numeric($_SESSION["id"])
        ) {
          $logged_in = true;
          $post_method =
            "<input type='hidden' name='_method' value='POST'>";
          $input_user_id =
            "<input type='hidden' name='usuario_id' value='{$_SESSION["id"]}'>";
          $action =
            FOLDERS_WITH_LOCALHOST["CONTROLLERS"] . "comentario-pelicula.php";
          $textarea_placeholder = "Agrega un comentario.";
        } else {
          $btn_classes .= " disabled";
        }
        ?>
        <form action="<?php echo $action; ?>" method="POST" class="comments__form">
          <?php
          echo "
            {$post_method}
            {$input_movie_id}
            {$input_user_id}
            ";
          ?>

          <textarea placeholder="<?php echo $textarea_placeholder; ?>" name="nuevo-comentario" id="nuevo-comentario" rows="5"></textarea>
          <footer class="comments__form__buttons">
            <?php
            if ($logged_in) {
              Usuario::renderFotoPerfil(
                $_SESSION["id"],
                $_SESSION["username"],
                $_SESSION["foto_perfil"],
                "own-avatar"
              );
            }
            ?>

            <button type="submit" value="Publicar" class="<?php echo $btn_classes; ?>">
              Publicar
            </button>
          </footer>
        </form>

        <!-- Comentarios ya publicados. -->
        <?php
        require_once
          FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
          . "comentario-pelicula.php";
        ?>

        <?php
        include $path . VIEWS . "components/posted-comment.php";
        ?>
      </section>
      <aside class="best-movies-container col-12 col-md-4">
        <?php
        require_once FOLDERS_WITH_DOCUMENT_ROOT["LAYOUTS"] . "movies/mejores-peliculas.php";
        ?>
      </aside>
    </div>
  </div>




  <?php
  include $path . LAYOUTS . "footer.php";
  ?>
</body>

</html>
