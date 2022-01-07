<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../libs/model.php";
require_once __DIR__ . "/usuario.php";

use Libs\Controller;
use Model;
use Usuario as ModelUsuario;

class Login extends Controller
{
  public static function login(ModelUsuario $user)
  {
    Controller::startSession();

    // Agregar valores del usuario a la sesión.
    $_SESSION = Usuario::getSessionArrayElements($user);
    $_SESSION["logged_in"] = true;
    Usuario::$current_user = $user;
  }

  public static function isUserLoggedIn()
  {
    return (Controller::isSessionActive()
      && isset($_SESSION["logged_in"])
      && $_SESSION["logged_in"] === true
    );
  }
  public static function redirectIfUserLoggedIn($redirect_view = "index.php")
  {
    if (self::isUserLoggedIn()) {
      self::redirectView($redirect_view);
    }
  }

  public static function redirectIfUserNotLoggedIn($redirect_view = "index.php")
  {
    if (!self::isUserLoggedIn()) {
      self::redirectView($redirect_view);
    }
  }
}

// Si nos encontramos en una view, no hacer el proceso.
// if (
//   str_contains(
//     $_SERVER["SCRIPT_FILENAME"],
//     "views/"
//   )
//   // && !str_contains(
//   //   $_SERVER["SCRIPT_FILENAME"],
//   //   "login/"
//   // )
// ) {
//   return;
// }
if (
  Controller::isCurrentFileView()
  || Controller::isCurrentFileAnotherController("login")
) {
  return;
}


// // Si no se trata de Post o el método no existe, redirigir al login. Así
// // evitamos que se entre desde la URL y que se envíen métodos inexistentes.
// if (
//   (!Controller::isPost()
//     || !Controller::isMethodExistent())
//   // && !str_contains(
//   //   $_SERVER["SCRIPT_FILENAME"],
//   //   "login/"
//   // )
//   // && !str_contains(
//   //   $_SERVER["SCRIPT_FILENAME"],
//   //   "views/"
//   // )
// ) {
//   Controller::redirectView("login/index.php");
//   // exit;
// }

Controller::redirectIfNonExistentPostMethod("login/index.php");


// Esto podría ir en una inicialización del Controller.
Model::initDbConnection();

// El método sí es POST.
// Ahora revisar el tipo de método. Si es uno, no puede ser el otro, por lo que,
// no es necesario poner un else por cada sentencia.

if (Controller::isMethodDelete()) {
  Controller::startSession();
  session_destroy();
}

if (Controller::isMethodPost()) {
  $username = Controller::getPostValue("username");
  $password = Controller::getPostValue("password");

  $login = ModelUsuario::isLoginDataCorrect($username, $password);

  if (!$login) {
    // Error.
    Controller::redirectView("login/index.php", "Error en inicio de sesión.");
    exit;
  }

  $user = new ModelUsuario(
    $login["nombres"],
    $login["apellidos"],
    $login["username"],
    $login["password"],
    $login["rol"],
    $login["id"],
    $login["foto_perfil"],
  );


  Login::login($user);

  // showElements([
  //   $_SESSION
  // ]);

}

// Al final de cualquiera de los procedimientos, redirigir a la pestaña
// principal.
Controller::redirectView();
