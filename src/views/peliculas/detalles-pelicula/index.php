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

      /* -------------------------- CALIFICACIÓN PELÍCULA ------------------------- */

      const starsForm = document.getElementById("movie-stars-form");

      if (starsForm !== undefined || starsForm !== null) {

        const controllers = {
          calificacionUsuarioPelicula: "<?php echo FOLDERS_WITH_LOCALHOST["CONTROLLERS"] . "calificacion-usuario-pelicula.php"; ?>",
        };
        const starsClasses = {
          userSelection: "movie-details__stars--active--user-review",
          averageSelection: "movie-details__stars--active--every-review",
          star: "movie-details__stars__one-complete",
        };
        // const starsList = starsForm.querySelectorAll(`svg.${starsClasses.star}`);
        // const stars = [...starsList];
        // console.log(stars);

        const stars = document.getElementsByClassName(starsClasses.star);

        starsForm.addEventListener("click", (event) => {
          const target = event.target;
          console.log("target", target);
          console.log("target.tagName", target.tagName);

          const isButton = target.tagName === "svg" &&
            target.classList.contains(starsClasses.star);
          const isSon = !(target.closest(`svg.${starsClasses.star}`) === null);
          console.log("isButton", isButton);
          console.log("isSon", isSon);
          console.log(
            "target.closest(`svg.${starsClasses.star}`)",
            target.closest(`svg.${starsClasses.star}`)
          );

          // Si el usuario no está registrado o el target no existe.
          if (!target || !isUserLoggedIn) return;
          // Si no es botón aún puede ser el hijo.
          if (!isButton && !isSon) return;

          const starsFormData = new FormData(starsForm);
          const peliculaId = starsFormData.get("pelicula_id");
          const queryParams = {
            pelicula_id: peliculaId,
            usuario_id: userId,
          };

          // const getUrl = `${controllerUrl}?comentario_pelicula_id=${comentarioPeliculaId}&usuario_id=${userId}`;
          const getUrl = appendGetParamsToUrl({
            url: controllers.calificacionUsuarioPelicula,
            paramsObj: queryParams,
          });

          let clickedButton = isSon ?
            target.closest(`svg.${starsClasses.star}`) :
            target;
          const clickedButtonBeginningState = clickedButton;

          // Estrellas seleccionadas.
          let starsNumber = clickedButton.dataset.star;
          console.log(starsNumber);

          clickedButton.classList.add(starsClasses.userSelection);


          getData(getUrl)
            .then((response) => {
              console.log("firstGet response", response);

              console.log("stars");
              // Quitamos estilos a estrellas.
              for (let i = 0; i < stars.length; i++) {
                stars[i].classList.remove(starsClasses.userSelection);
                stars[i].classList.remove(starsClasses.averageSelection);
                console.log("staars i", stars[i]);
              }

              let isMethodSelected = false;
              if (!Object.hasOwn(response, "user_movie_stars")) {
                method = "POST";
                // starsFormData.set("_method", method);
              } else {
                if (response.user_movie_stars !== starsNumber) {
                  method = "PUT";
                }
                if (response.user_movie_stars === starsNumber) {
                  method = "DELETE";
                }
              }

              starsFormData.set("numero_estrellas", starsNumber);
              starsFormData.set("_method", method);
              console.log("numero_estrellas", starsNumber);
              console.log("_method", method);

              return sendData(getUrl, Object.fromEntries(starsFormData))
                .then((response) => {
                  console.log("post response: ", response);
                  return getData(getUrl);
                });
            })
            .then(
              (lastGet) => {
                updatedData = lastGet;
                console.log("last get - updated data", updatedData);
                for (let i = 0; i < stars.length; i++) {
                  if (
                    updatedData.average_movie_stars >=
                    (stars[i].dataset.star + 1)
                  ) {
                    stars[i].classList.add(starsClasses.averageSelection);
                  }
                  if (!Object.hasOwn(updatedData, "user_movie_stars")) {
                    if (
                      response.user_movie_stars >=
                      (stars[i].dataset.star + 1)
                    ) {
                      stars[i].classList.remove(starsClasses.averageSelection);
                      stars[i].classList.add(starsClasses.userSelection);
                    }
                  }
                }
              }
            )
            .catch((error) => {
              console.table("error:", error);
              // Si ocurre un error, regresar el botón del click al estado anterior.
              clickedButton = clickedButtonBeginningState;
            });
        });
      }
    </script>
  <?php
  }
  ?>
</body>

</html>
