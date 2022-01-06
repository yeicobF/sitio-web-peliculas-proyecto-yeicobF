<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../libs/model.php";
require_once __DIR__ . "/../models/pelicula.php";
require_once __DIR__ . "/../models/usuario.php";
require_once __DIR__ . "/../models/comentario-pelicula.php";require_once __DIR__ . "/usuario.php";
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
  public static function getEveryMovieComment(
    int $pelicula_id
  ) {
    $db_comments = ModelComentarioPelicula::getEveryMovieComment($pelicula_id);

    
  }
}
