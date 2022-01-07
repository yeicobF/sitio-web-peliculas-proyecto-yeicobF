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
   * Renderizar todos los comentarios de una película.
   *
   * Si el hay un usuario con sesión iniciada y el comentario es de él, agregar
   * botón para eliminar el comentario.
   *
   * @param array<ModelComentarioPelicula> $movie_comments
   * @return void
   */
  public static function renderMovieComment(
    ModelComentarioPelicula $movie_comments
  ) {
    
?>
    <article class="comments__posted">
      <figure class="comments__details">
        <img src="<?php echo IMG_FOLDER; ?>../avatar/1.jpg" alt="Username" class="circle-avatar">
        <figcaption class="comments__details__info">
          <h3 class="comments__details__title">Username Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic ad numquam natus quaerat maiores vitae voluptatem. Voluptates perspiciatis sequi delectus, illum adipisci earum modi, error, ad totam eos praesentium hic?</h3>
          <time class="comments__details__time-ago" datetime="PT3H">Hace 3 horas</time>
        </figcaption>
      </figure>
      <main class="comments__text">
        <p>
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Repellendus accusantium officiis deserunt cum temporibus iure in sed alias a corporis?
        </p>
      </main>
      <footer class="comments__interaction">
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

  public static function renderEveryMovieComment(
    int $pelicula_id
  ) {
    $db_comments = ModelComentarioPelicula::getEveryMovieComment($pelicula_id);
  }
}
