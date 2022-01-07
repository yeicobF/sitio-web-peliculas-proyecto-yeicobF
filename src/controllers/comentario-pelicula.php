<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../libs/model.php";
require_once __DIR__ . "/../models/pelicula.php";
require_once __DIR__ . "/../models/usuario.php";
require_once __DIR__ . "/../models/comentario-pelicula.php";
require_once __DIR__ . "/usuario.php";
require_once __DIR__ . "/pelicula.php";

use Pelicula as ModelPelicula;
use Usuario as ModelUsuario;
use ComentarioPelicula as ModelComentarioPelicula;
use Model as Model;
use Libs\Controller;
use Controllers\Usuario;
use Controllers\Pelicula;
use Controllers\Login;

class ComentarioPelicula extends Controller
{
  /**
   * Obtener el tiempo que ha pasado desde la hora actual.
   *
   * De hecho, esto podría hacerlo inicialmente aquí para mostrar el tiempo que
   * ha pasado inicialmente, pero con JavaScript seguir actualizando el tiempo
   * que ha concurrido.
   *
   * > En StackOverflow encontré respuestas que me podrían ser de ayuda:
   * https://stackoverflow.com/a/5092038/13562806
   *
   * ## Formato del tiempo en MySQL
   *
   * En la tabla de MySQL se guardan los datos como:
   *
   * - `fecha`: 2022-01-05
   * - `hora`: 13:19:30
   *
   * ## Condiciones
   *
   * El tiempo que ha pasado dependerá de ciertas condiciones:
   *
   * - Si no ha pasado más de un minuto, regresar: "Hace un momento".
   * - Si ha pasado más de un minuto, mostrar el número de minutos.
   * - Si han pasado más de 60 minutos, mostrar el número de horas.
   * - Si han pasado más de 24 horas, mostrar el número de días.
   * - Si han pasado más de 31 días, mostrar el número de meses que han pasado.
   *
   * - Si han pasado más de 12 meses, mostrar el número de años y meses que han
   *   pasado.
   *   - Si no ha pasado ningún mes en dichos años, solo mostrar el año.
   *
   * @param string $date Fecha en el siguiente formato: `YYYY-MM-DD`, ejemplo:
   * `2022-01-05`.
   * @param string $time Hora en formato de 24 horas con el siguiente formato:
   * `HH:MM:SS`, ejemplo: `13:19:30`.
   * @return string
   */
  public static function getTimeElapsed(string $date, string $time)
  {
  }

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

    // Obtener el usuario de quien escribió el comentario.
    $db_user = ModelUsuario::getById($movie_comment->usuario_id);

    /**
     * El usuario no existe, por lo que, hay que indicar que este comentario no
     * se agregó.
     */
    if ($db_user === null) {
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

    $user_details_url = URL_PAGE["detalles-perfil"] . "?id=" . $user->_id;
    // Obtener los likes y dislikes del comentario.
?>
    <article class="comments__posted">
      <figure class="comments__details">
        <a href="<?php echo $user_details_url; ?>">
          <?php
          Usuario::renderFotoPerfil(
            $user->_id,
            $user->_username,
            $user->_foto_perfil
          );
          ?>
        </a>
        <figcaption class="comments__details__info">
          <h3 class="comments__details__title">
            <?php echo $user->_username; ?>
          </h3>
          <time class="comments__details__time-ago" datetime="PT3H" id="time-ago">
            Hace 3 horas
          </time>
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
        <form name="comment-likes" class="" action="" method="post">
          <button class="comments__interaction__button" type="button"><i class="fas fa-thumbs-up"></i></button>
          <data value="2">2</data>
        </form>
        <form name="comment-dislikes" class="" action="" method="post">
          <button class="comments__interaction__button selected" type="button"><i class="fas fa-thumbs-down"></i></button>
          <data value="4">4</data>
        </form>
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
