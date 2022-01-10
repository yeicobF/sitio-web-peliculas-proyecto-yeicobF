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
      <section class="comments__container col-12 col-md-8" id="comments-container">
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
        $disabled = "";
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
          $disabled = "disabled";
          $btn_classes .= " {$disabled}";
        }
        ?>
        <form id="comment-form" action="<?php echo $action; ?>" method="POST" class="comments__form pretty-shadow">
          <?php
          echo "
            {$post_method}
            {$input_movie_id}
            {$input_user_id}
            ";
          ?>

          <textarea required <?php echo $disabled; ?> class="<?php echo $disabled; ?>" placeholder="<?php echo $textarea_placeholder; ?>" name="comentario" id="nuevo-comentario" rows="5"></textarea>
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

            <button <?php echo $disabled; ?> id="publish-comment-btn" type="submit" value="Publicar" class="<?php echo $btn_classes; ?>">
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
        // include $path . VIEWS . "components/posted-comment.php";
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
  if (Login::isUserLoggedIn() && Controller::idExists(false, $_SESSION)) {
  ?>
    <script defer src="<?php echo FOLDERS_WITH_LOCALHOST["JS"] . "xml-http-request.js"; ?>"></script>
    <script defer src="<?php echo FOLDERS_WITH_LOCALHOST["JS"] . "comment-interactions.js"; ?>"></script>
    <script defer src="<?php echo FOLDERS_WITH_LOCALHOST["JS"] . "get.js"; ?>"></script>
    <script defer>
      // Hay que saber si el usuario ha iniciado sesión.

      const isUserLoggedIn =
        <?php
        // json_encode porque si aquí da false, no se guarda nada en JS.
        echo json_encode(Login::isUserLoggedIn() && Controller::idExists(false, $_SESSION));
        ?>;
      const userId = <?php echo Usuario::getId(); ?>;

      const controllerUrl = "<?php echo FOLDERS_WITH_LOCALHOST["CONTROLLERS"] . "like-comentario.php"; ?>";
      const publishCommentBtn = document.getElementById("publish-comment-btn");
      const commentForm = document.getElementById("comment-form");
      const interactionBtnClass = "comments__interaction__button";
      const interactionFormClass = "comments__interaction__info";
      const commentsContainerId = "comments-container";
      const selectedClass = "selected";

      /** 
       *
       * Contenedor de todos los comentarios.
       *
       *
       * Así obtenemos todos los padres que contienen los botones de like y
       * dislike y no los tenemos que obtener de forma individual. 
       *
       * Utilizamos propagación de eventos. 
       */
      const commentsContainer = document.getElementById(commentsContainerId);
      const interactionForms = document.querySelectorAll(`form.${interactionFormClass}`);
      commentsContainer.addEventListener("click", (event) => {
        postCommentInteraction({
          event,
          isUserLoggedIn,
          userId,
          controllerUrl,
          interactionFormClass,
          interactionBtnClass,
        });
      });
    </script>
  <?php
  }
  ?>
</body>

</html>
