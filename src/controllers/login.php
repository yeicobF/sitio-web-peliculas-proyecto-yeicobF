<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../libs/model.php";
require_once __DIR__ . "/usuario.php";

use Libs\Controller;
use Controllers\Usuario;
use Model;
use Usuario as ModelUsuario;

class Login extends Controller
{
  public static function login(ModelUsuario $user)
  {
    Controller::startSession();

    // Agregar valores del usuario a la sesión.
    $_SESSION = array_merge(
      $_SESSION,
      Usuario::getSessionArrayElements($user)
    );
  }
}

// Si no se trata de Post o el método no existe, redirigir al login. Así
// evitamos que se entre desde la URL y que se envíen métodos inexistentes.
if (
  !Controller::isPost()
  || !Controller::isMethodExistent()
) {
  Controller::redirectView("login/index.php");
  // exit;
}

Model::initDbConnection();

// El método sí es POST.
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

Controller::redirectView();
