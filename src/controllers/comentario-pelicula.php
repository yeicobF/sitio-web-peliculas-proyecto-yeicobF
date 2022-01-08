<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../libs/model.php";
require_once __DIR__ . "/../models/pelicula.php";
require_once __DIR__ . "/../models/usuario.php";
require_once __DIR__ . "/../models/comentario-pelicula.php";
require_once __DIR__ . "/../models/like-comentario.php";
require_once __DIR__ . "/usuario.php";
require_once __DIR__ . "/pelicula.php";
require_once __DIR__ . "/like-comentario.php";

use Pelicula as ModelPelicula;
use Usuario as ModelUsuario;
use ComentarioPelicula as ModelComentarioPelicula;
use LikeComentario as ModelLikeComentario;
use Model as Model;
use Libs\Controller;
use Controllers\Usuario;
use Controllers\Pelicula;
use Controllers\Login;
use View;

class ComentarioPelicula extends Controller
{
  /**
   * Renderizar todos los comentarios de una película.
   *
   * Si el hay un usuario con sesión iniciada y el comentario es de él, agregar
   * botón para eliminar el comentario.
   *
   * @param array<ModelComentarioPelicula> $movie_comments
   * @return bool Se insertó la información correctamente o no.
   */
  public static function renderMovieComment(
    ModelComentarioPelicula $movie_comment
  ) {
    // Obtener el tiempo que ha transcurrido desde la publicación del
    // comentario.
    $time_ago = Controller::getTimeElapsed(
      $movie_comment->fecha,
      $movie_comment->hora
    );
    // Obtener la fecha con propio formato.
    $date = Controller::getDateAsOwnFormat($movie_comment->fecha);
    $time = Controller::getTimeAsOwnFormat($movie_comment->hora);

    // Obtener el usuario de quien escribió el comentario.
    $db_user = ModelUsuario::getById($movie_comment->usuario_id);
    // Obtener los likes y dislikes del comentario.
    $db_interactions = ModelLikeComentario::getInteractionsComentario(
      $movie_comment->id,
    );

    /**
     * El usuario no existe, por lo que, hay que indicar que este comentario no
     * se agregó.
     */
    if (
      $db_user === null
      || $db_interactions === null
    ) {
      return false;
    }

    $user = new ModelUsuario(
      nombres: $db_user["nombres"],
      apellidos: $db_user["apellidos"],
      username: $db_user["username"],
      password: $db_user["password"],
      rol: $db_user["rol"],
      id: $db_user["id"],
      foto_perfil: $db_user["foto_perfil"]
    );

    $comment_interactions = LikeComentario::getInteractionsNumber($db_interactions);
    $likes = $comment_interactions["likes"];
    $dislikes = $comment_interactions["dislikes"];

    /**
     * Revisar interacción si el usuario ha iniciado sesión.
     */
    $user_interaction = false;
    /**
     * Clase para agregar selected si el usuario tuvo interacción.
     */
    $user_like = $user_dislike = "";
    // Botones deshabilitados si el usuario no ha iniciado sesión.
    $disabled = "disabled";

    if (Login::isUserLoggedIn()) {
      $disabled = "";

      $user_interaction = LikeComentario::getUserInteractionWithComment(
        $db_interactions,
        Usuario::getId()
      );

      if ($user_interaction === "like") {
        $user_like = "selected";
      }
      if ($user_interaction === "dislike") {
        $user_dislike = "selected";
      }
    }

    $user_details_url = URL_PAGE["detalles-perfil"] . "?id=" . $user->_id;
    $avatar_classes = "";
    $are_details_from_logged_user = false;
    // El administrador podrá borrar comentarios.
    $is_user_admin = Usuario::isAdmin();

    // Ver si los detalles del comentario son los del usuario con sesión
    // iniciada.
    if (Usuario::areDetailsFromLoggedUser($user)) {
      $avatar_classes = "own-avatar";
      $are_details_from_logged_user = true;
    }


?>
    <article class="comments__posted pretty-shadow">
      <figure class="comments__details">
        <a href="<?php echo $user_details_url; ?>">
          <?php
          Usuario::renderFotoPerfil(
            $user->_id,
            $user->_username,
            $user->_foto_perfil,
            $avatar_classes
          );
          ?>
        </a>
        <figcaption class="comments__details__info">
          <h3 class="comments__details__title">
            <?php echo $user->_username; ?>
          </h3>
          <!-- 
            El datetime lo podría obtener con una función o en la misma del timeago, pero por ahora no lo haré. 
          -->
          <!-- <time class="comments__details__time-ago" datetime="PT3H"> -->
          <div class="comments__details__time">
            <time class="comments__details__time-ago" datetime="">
              <?php echo $time_ago; ?>
            </time>
            <!-- https://unicode-table.com/es/00B7/ -->
            <span class="comments__details__date vertical-line"></span>
            <time class="comments__details__date" datetime="">
              <?php echo $date; ?>
            </time>
            <span class="comments__details__hour vertical-line"></span>
            <time class="comments__details__hour" datetime="">
              <?php echo $time; ?>
            </time>
          </div>
        </figcaption>
      </figure>
      <main class="comments__text">
        <p>
          <?php echo $movie_comment->comentario; ?>
        </p>
      </main>
      <footer class="comments__interaction">
        <!-- 
        Esto no será un formulario, sino que, se regirá por el id del 
        comentario. 
        -->
        <form class="comments__interaction__info" method="POST">
          <input type="hidden" name="comentario_pelicula_id" value="<? echo $movie_comment->id; ?>">
          <?php
          if (Login::isUserLoggedIn()) {
          ?>
            <input type="hidden" name="usuario_id" value="<? echo Usuario::getId(); ?>">
          <?php
          }
          ?>

          <div class="comments__interaction__likes">
            <button class="comments__interaction__button <?php echo "{$user_like} {$disabled}"; ?>" type="button" title="like" name="like" value="<?php echo $user_like; ?>">
              <i class="fas fa-thumbs-up"></i>
            </button>
            <data value="<?php echo $likes; ?>"><?php echo $likes; ?></data>
          </div>

          <div class="comments__interaction__likes">
            <button class="comments__interaction__button <?php echo "{$user_dislike} {$disabled}"; ?>" type="button" title="dislike" name="dislike" value="<?php echo $user_dislike; ?>">
              <i class="fas fa-thumbs-down"></i>
            </button>
            <data value="<?php echo $dislikes; ?>"><?php echo $dislikes; ?></data>
          </div>
        </form>
        <?php
        // Si el comentario es del usuario con sesión iniciada, mostrar botón
        // para eliminar.
        if (
          $are_details_from_logged_user
          || $is_user_admin
        ) {
        ?>
          <form action="<?php echo FOLDERS_WITH_LOCALHOST["CONTROLLERS"] . "comentario-pelicula.php"; ?>" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="id" value="<?php echo $movie_comment->id; ?>">
            <input type="hidden" name="pelicula_id" value="<?php echo $movie_comment->pelicula_id; ?>">

            <button type="submit" class="fa-btn--danger" id="delete-comentario-pelicula">
              <i class="fa-solid fa-trash"></i>
            </button>
          </form>
        <?php
        }
        ?>
      </footer>
    </article>
<?php
  }

  /**
   * Renderizar todos los comentarios de una película.
   *
   * @param array<ModelComentarioPelicula> $db_comments
   * @return void
   */
  public static function renderEveryMovieComment(
    array $db_comments
  ): void {
    foreach ($db_comments as $db_comment) {
      $movie_comment = new ModelComentarioPelicula(
        id: $db_comment["id"],
        pelicula_id: $db_comment["pelicula_id"],
        usuario_id: $db_comment["usuario_id"],
        comentario: $db_comment["comentario"],
        fecha: $db_comment["fecha"],
        hora: $db_comment["hora"],
      );

      self::renderMovieComment($movie_comment);
    }
  }
}

Controller::startSession();
Model::initDbConnection();

if (
  Controller::isGet()
  && Controller::getKeyExist("id")
  && is_numeric($_GET["id"])
) {
  $db_comments = ModelComentarioPelicula::getEveryMovieComment($_GET["id"]);

  if ($db_comments === null) {
    return false;
  }

  ComentarioPelicula::renderEveryMovieComment($db_comments);

  return true;
}

if (
  Controller::isCurrentFileView()
  || Controller::isCurrentFileAnotherController("comentario-pelicula")
) {
  return;
}

/* ---------------- NO ES GET, ENTONCES TENDRÍA QUE SER POST ---------------- */
Controller::redirectIfNonExistentPostMethod("peliculas/index.php");

$view_path = "peliculas/index.php";
// Campos del formulario.
$form_fields = $_POST;
unset($form_fields["_method"]);

$non_empty_fields = Controller::getNonEmptyFormFields(
  $form_fields
);

// Hay que agregar la fecha y hora nosotros.
// 
$non_empty_fields["fecha"] = Model::getCurrentDate();
$non_empty_fields["hora"] = Model::getCurrentTime();


if (
  array_key_exists("pelicula_id", $non_empty_fields)
  && is_numeric($non_empty_fields["pelicula_id"])
) {
  $view_path = "peliculas/detalles-pelicula/index.php?id={$non_empty_fields["pelicula_id"]}";
}

// El usuario no puede hacer ningún procedimiento POST si no ha iniciado sesión.
if (!Login::isUserLoggedIn()) {
  Controller::redirectView(
    view_path: $view_path,
    error: "No has iniciado sesión."
  );
}

if (Controller::isMethodPost()) {

  // Revisar que todos los campos menos "fecha_nacimiento" tienen datos.
  if (!Controller::areRequiredFieldsFilled(
    ModelComentarioPelicula::REQUIRED_FIELDS,
    $non_empty_fields
  )) {
    Controller::redirectView(
      view_path: $view_path,
      error: "No se enviaron los datos correctamente."
    );
    return;
  }

  $comentario_pelicula = new ModelComentarioPelicula(
    pelicula_id: $non_empty_fields["pelicula_id"],
    usuario_id: $non_empty_fields["usuario_id"],
    comentario: $non_empty_fields["comentario"],
    fecha: $non_empty_fields["fecha"],
    hora: $non_empty_fields["hora"]
  );

  $result = $comentario_pelicula->insertComentarioPelicula();
}

if (Controller::isMethodDelete()) {
  $result = ModelComentarioPelicula::delete($non_empty_fields["id"]);
}

$message = Model::OPERATION_INFO[$result];

if ($result === 1) {
  Controller::redirectView(
    view_path: $view_path,
    message: $message

  );

  // Si publicamos el comentario, regresar el JSON.
  if (Controller::isMethodPost()) {
    return $comentario_pelicula->returnJson();
  }
  if (Controller::isMethodDelete()) {
    return $result;
  }

  return;
}

Controller::redirectView(
  view_path: $view_path,
  error: $message

);
