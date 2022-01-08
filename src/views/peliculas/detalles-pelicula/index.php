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

          <textarea required <?php echo $disabled; ?> placeholder="<?php echo $textarea_placeholder; ?>" name="comentario" id="nuevo-comentario" rows="5"></textarea>
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
  ?>
  <script defer src="<?php echo FOLDERS_WITH_LOCALHOST["JS"] . "xml-http-request.js"; ?>"></script>
  <script defer>
    // Hay que saber si el usuario ha iniciado sesión.

    const isUserLoggedIn =
      <?php
      // json_encode porque si aquí da false, no se guarda nada en JS.
      echo json_encode(Login::isUserLoggedIn() && Controller::idExists(false, $_SESSION));
      ?>;

    const controllerUrl = "<?php echo FOLDERS_WITH_LOCALHOST["CONTROLLERS"] . "like-comentario.php"; ?>";
    const publishCommentBtn = document.getElementById("publish-comment-btn");


    const commentForm = document.getElementById("comment-form");
    const interactionBtnClass = "comments__interaction__button";
    const interactionFormClass = "comments__interaction__info";
    const commentsContainerId = "comments-container";

    // Así obtenemos todos los padres que contienen los botones de like y
    // dislike y no los tenemos que obtener de forma individual. Utilizamos
    // propagación de eventos.
    const commentsContainer = document.getElementById(commentsContainerId);

    let formData;

    // Al dar click, llamar al método POST.
    commentsContainer.addEventListener("click", async (event) => {
      // Evitar que se recargue la página.
      event.preventDefault();

      let target = event.target;
      // Como los botones son SVG, pueden ser hijos del botón, pero también
      // click.
      let isSon = target
        .closest(
          `button.${interactionBtnClass}`
        ) === null ?
        false :
        true;
      let isButton = target.tagName === "BUTTON" &&
        target.classList.contains(`${interactionBtnClass}`);

      // console.log("target", target);
      // console.log("target.tagName", target.tagName);
      // console.log("target.classList", target.classList);
      // console.log("isUserLoggedIn", isUserLoggedIn);
      // console.log("isSon", isSon);
      // console.log("isButton", isButton);

      // Si no se trata del botón, regresar.
      // console.log("!target || !isUserLoggedIn", !target || !isUserLoggedIn);
      // console.log("!isButton && !isSon", !isButton && !isSon);
      if (!target || !isUserLoggedIn) return;

      // Si no es botón aún puede ser el hijo.
      if (!isButton && !isSon) return;

      const userId = <?php echo Usuario::getId(); ?>;

      // Obtener el formulario padre.
      let form = target.closest(`form.${interactionFormClass}`);
      let likeBtn = form.querySelector(
        `button.${interactionBtnClass}[name="like"]`
      );
      let dislikeBtn = form.querySelector(
        `button.${interactionBtnClass}[name="dislike"]`
      );

      
      // https://developer.mozilla.org/en-US/docs/Web/API/FormData/FormData
      // Obtener todos los campos del formulario.
      // console.log("form", form);
      formData = new FormData(form);
      let comentarioPeliculaId = formData.get("comentario_pelicula_id");
      let getUrl = `${controllerUrl}?comentario_pelicula_id=${comentarioPeliculaId}&usuario_id=${userId}`;
      let dbLikeComentario;
      let clickedButton = isSon ?
        target.closest(
          `button.${interactionBtnClass}`
        ) :
        target;
      // Obtener botón al que dimos click para comparar con su interacción.
      let currentInteraction = clickedButton.getAttribute("name");
      let method = "";
      let isMethodSelected = false;

      /**
       * Obtener datos de la BD, no del DOM, por si fueron actualizados y el DOM
       * no. Obtener botones de like y dislike.
       *
       * https://stackoverflow.com/a/37534034/13562806
       * How to return data from promise [duplicate]
       */
      await getData(getUrl)
        .then((responseData) => {
          dbLikeComentario = responseData;
          console.log("get response: ", dbLikeComentario);
        })
        .catch((error) => {
          console.log(`error: ${error}`);
          return;
        });

      let userInteraction =
        dbLikeComentario === null ?
        false :
        Object.hasOwn(dbLikeComentario, "user_interaction");


      // Si no hay interacción actualmente, hacer inserción.
      if (!userInteraction) {
        method = "POST";
        isMethodSelected = true;
      }

      /**
       * Si no se ha seleccionado método, elegirlo ahora. No puede ser PUT y
       * DELETE al mismo tiempo. 
       */
      if (!isMethodSelected) {
        console.log("dbLikeComentario.user_interaction", dbLikeComentario.user_interaction);
        console.log("currentInteraction", currentInteraction);
        // Si el comentario ya tiene interacción, eliminarla.
        // La interacción ya está seleccionada, por lo que hay que actualizar.
        if (dbLikeComentario.user_interaction === currentInteraction) {
          method = "DELETE";
        }

        /** 
         * Ya que obtuvimos la interacción actual del comentario y el usuario, ver
         * si esta existe con el usuario actual, y si, el botón presionado es
         * distinto al de la interacción de la BD, hacer PUT.
         * 
         * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/hasOwn
         */
        if (dbLikeComentario.user_interaction !== currentInteraction) {
          // La actualización la hace automáticamente su respectivo método.
          method = "PUT";
          // La interacción actual es la contraria al botón que presionamos.
          currentInteraction = dbLikeComentario.user_interaction;
        }
      }

      console.log("method", method);
      console.log("currentInteraction", currentInteraction);
      formData.set("_method", method);
      formData.set("tipo", currentInteraction);

      // Display the keys
      // for (var key of formData.keys()) {
      //   console.log(key);
      // }
      // for (var value of formData.values()) {
      //   console.log(value);
      // }

      // https://stackoverflow.com/a/69374442/13562806
      await sendData(controllerUrl, Object.fromEntries(formData))
        .then((responseData) => {
          dbLikeComentario = responseData;
          console.log("post response: ", dbLikeComentario);
        })
        .catch((error) => {
          console.log(`error: ${error}`);
          return;
        });

      await getData(getUrl)
        .then((responseData) => {
          dbLikeComentario = responseData;
          console.log("datos actualizados - get response: ", dbLikeComentario);
        })
        .catch((error) => {
          console.log(`error: ${error}`);
          return;
        });
    });
  </script>
</body>

</html>
