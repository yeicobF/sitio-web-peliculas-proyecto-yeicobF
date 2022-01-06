<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../libs/model.php";
require_once __DIR__ . "/../models/usuario.php";
require_once __DIR__ . "/login.php";

use Usuario as ModelUsuario;
use Model as Model;
use Libs\Controller;
use Controllers\Login;

class Usuario extends Controller
{

  public static ?ModelUsuario $current_user = null;

  /**
   * Obtener parte del arreglo del sesión necesaria.
   *
   * @param object $user
   * @return array
   */
  public static function getSessionArrayElements(object $user): array
  {
    $session_details = $user->getParamValues();
    unset($session_details["password"]);

    return $session_details;
  }

  /**
   * Revisar si el usuario actual es administrador.
   *
   * @return boolean
   */
  public static function isAdmin()
  {
    return (Login::isUserLoggedIn()
      && Controller::isSessionActive()
      && isset($_SESSION["rol"])
      && ($_SESSION["rol"] === ModelUsuario::ROLES_ENUM_INDEX["administrador"]
        || $_SESSION["rol"] === "administrador"
      )
    );
  }

  public static function redirectIfNotAdmin($redirect_view = "index.php")
  {
    if (!self::isAdmin()) {
      self::redirectView(
        $redirect_view,
        message: "No tienes permisos de administrador."
      );
    }
  }

  /**
   * Obtenemos foto de perfil del usuario especificado.
   * 
   * Si no hay foto, es decir, que es null, regresar una genérica.
   * 
   * Esta función sirve para obtener la foto de perfil de cualquier usuario.
   *
   * @return void
   */
  public static function renderFotoPerfil(
    int $usuario_id,
    string $username,
    ?string $foto_perfil
  ) {
    $has_foto_perfil = false;
    $encoded_image = "";

    if (strlen($foto_perfil) > 0 && $foto_perfil !== null) {
      $encoded_image = Controller::getEncodedImage($foto_perfil);
      $has_foto_perfil = true;
    }

    $alt = "Foto de perfil de usuario - {$username}";
    $detalles_perfil_url =
      FOLDERS_WITH_LOCALHOST["VIEWS"]
      . "user/index.php?id={$usuario_id}";
    // . "user/index.php?id={$_SESSION["id"]}";

    if ($has_foto_perfil) {
?>
      <img src='data:image/jpeg; base64, <?php echo $encoded_image; ?>' alt='<?php echo $alt; ?>' class='circle-avatar profile-picture'>
    <?php
      return;
    }
    ?>

    <!-- 
    Font Awesome permite apilar elementos. Aquí ponemos un círculo detrás del 
    ícono para simular una foto de perfil circular con el ícono en medio.
    -->
    <span class="fa-stack circle-avatar profile-picture">
      <i class="fa-stack-2x fa-solid fa-circle circle-avatar profile-picture__circle"></i>
      <i class="fa-stack-1x fa-solid fa-user-astronaut fa-inverse profile-picture__icon"></i>
    </span>
  <?php
  }

  /**
   * Obtener arreglo con los datos del usuario actual.
   *
   * @param int $id
   * @return array
   */
  public static function getCurrentUserData(int $id): array
  {
    return Model::getRecords(
      table: ModelUsuario::TABLE_NAME,
      where_clause_names: ["id"],
      where_clause_values: [$id],
      pdo_params: ModelUsuario::PDO_PARAMS
    )[0];
  }

  /**
   * Verificar si el usuario especificó una contraseña por actualizar, además de
   * su contraseña actual.
   *
   * @param array $non_empty_fields Arreglo con campos no vacíos.
   * @return boolean
   */
  public static function isNewPasswordSpecified($non_empty_form_fields)
  {
    return
      isset($non_empty_form_fields["current_password"])
      && $non_empty_form_fields["current_password"]
      && isset($non_empty_form_fields["new_password"])
      && $non_empty_form_fields["new_password"];
  }

  /**
   * Verificar si se puede actualizar la contraseña o no.
   * 
   * Verificar si una contraseña existe para ser actualizada.
   *
   * @param string $current_password
   * @return bool
   */
  public static function canUpdatePassword(
    string $current_password
  ): bool {
    $login = ModelUsuario::isLoginDataCorrect(
      Usuario::getUsername(),
      $current_password
    );

    return !$login ? false : true;
  }

  /**
   * Actualizar los valores de la sesión que sean enviados en un arreglo
   * asociativo.
   *
   * @param array $session_values Arreglo asociativo con los valores a
   * actualizar de la sesión.
   * @return void
   */
  public static function updateSessionValues(array $session_values)
  {
    foreach ($session_values as $key => $value) {
      // Solo actualizar las llaves existentes.
      if (array_key_exists($key, $_SESSION)) {
        $_SESSION[$key] = $value;
      }
    }
  }

  /**
   * Revisar si los detalles del usuario son del que ha iniciado sesión.
   *
   * @param ModelUsuario $user Usuario de quien se han obtenido los detalles.
   * @return bool
   */
  public static function areDetailsFromLoggedUser(ModelUsuario $user): bool
  {
    if (Login::isUserLoggedIn()) {
      // Existe el ID del usuario que inició sesión y es el mismo de los
      // detalles.
      return
        Controller::idExists(false, $_SESSION)
        && $_SESSION["id"] === $user->_id;
    }
    return false;
  }

  public static function renderUserDetails(ModelUsuario $user)
  {
    // Ver si el perfil del usuario es el mismo que el del que tiene la sesión
    // iniciada. 
    //
    // De esta forma, le podemos mostrar los botones de edición de perfil.
    $are_details_from_logged_user = self::areDetailsFromLoggedUser($user);

    // Si el perfil de  usuario es el mismo que inició sesión y es
    // administrador. 
    $is_user_admin = false;

    // Ver si el usuario es admin.
    if (
      $are_details_from_logged_user
      && array_key_exists("rol", $_SESSION)
      && $_SESSION["rol"] === "administrador"
    ) {
      $is_user_admin = true;
    }

  ?>
    <main class="profile-details">
      <h1 class="profile-details__title">
        Detalles de perfil
      </h1>
      <figure class="profile-details__figure row">
        <!-- <figcaption>
          <h2 class="edit-profile__title">Nombre del usuario</h2>
        </figcaption> -->
        <!-- <img src="<?php echo IMG_FOLDER; ?>../avatar/1.jpg" alt="Username" class="circle-avatar"> -->


        <div class="col-12 col-sm-6 col-md-4">
          <?php
          Usuario::renderFotoPerfil(
            usuario_id: $user->_id,
            username: $user->_username,
            foto_perfil: $user->_foto_perfil
          );
          ?>
        </div>
        <figcaption class="profile-details__figcaption col-12 col-sm-6  col-md-8">
          <header class="profile-details__figcaption__header">
            <h2>
              <?php
              echo "{$user->_nombres} {$user->_apellidos}";
              ?>
            </h2>
            <h3 class="profile-details__username edit-profile__username">
              <?php echo $user->_username; ?>
            </h3>
            <?php
            // Si el usuario es administrador, mostrar su rol.
            if ($is_user_admin) {
            ?>
              <h4>Administrador</h4>
            <?php
            }
            ?>
          </header>

          <!-- Mostrar botones solo si se trata del dueño de la cuenta. -->
          <?php
          if ($are_details_from_logged_user) {
          ?>
            <form action="<?php echo CONTROLLERS_FOLDER . "usuario.php"; ?>" method="POST" enctype="multipart/form-data" class="">
              <!-- <input type="hidden" name="_method" value="DELETE"> -->
              <a href="<?php echo URL_PAGE["editar-perfil"]; ?>" class="btn btn-primary btn-fit-content">
                Editar perfil
              </a>
              <button name="_method" value="DELETE" title="Eliminar cuenta" class="btn btn-danger form__button" type="submit">
                Eliminar cuenta
              </button>
            </form>
          <?php
          }
          ?>
        </figcaption>
      </figure>

    </main>
<?php
  }

  public static function getUsername()
  {
    return $_SESSION["username"];
  }
  public static function getNombres()
  {
    return $_SESSION["nombres"];
  }
  public static function getApellidos()
  {
    return $_SESSION["apellidos"];
  }
  public static function getRol()
  {
    return $_SESSION["rol"];
  }
  public static function getId()
  {
    return $_SESSION["id"];
  }
}

Controller::startSession();
Model::initDbConnection();

$result = 0;
$message = "";


// Obtener los datos del usuario actual, ya que, estos pudieron haber sido
// actualizados.
if (Controller::isGet()) {
  if (
    str_contains(
      $_SERVER["SCRIPT_FILENAME"],
      "editar-perfil/index.php"
    )
    || str_contains(
      $_SERVER["SCRIPT_FILENAME"],
      "user/index.php"
    )
  ) {
    // No obtener datos de usuario y redirigir si no ha iniciado sesión.
    Login::redirectIfUserNotLoggedIn("login/index.php");
    Usuario::updateSessionValues(Usuario::getCurrentUserData($_SESSION["id"]));
  }

  // Mostrar detalles de usuario.
  if (
    str_contains(
      $_SERVER["SCRIPT_FILENAME"],
      "user/index.php"
    )
    && Controller::getKeyExist("id")
    && is_numeric($_GET["id"])
  ) {
    $db_user = ModelUsuario::getById($_GET["id"]);

    if ($db_user === null) {
      Controller::redirectView(
        view_path: "index.php",
        error: "No se encontró el usuario."
      );
      return;
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

    Usuario::renderUserDetails($user);
  }
  return;
}

if (
  Controller::isCurrentFileView()
  || Controller::isCurrentFileAnotherController("usuario")
) {
  return;
}

Controller::redirectIfNonExistentPostMethod("login/index.php");

// echo var_dump($_POST);

/* ------------------------- ELIMINACIÓN DE USUARIO ------------------------- */
if (Controller::isMethodDelete()) {
  $result = ModelUsuario::delete(Usuario::getId());

  if ($result === 1) {
    session_destroy();
  }
}

// Campos del formulario.
$form_fields = $_POST;
unset($form_fields["_method"]);

// Verificar si hay una foto. Si la hay, obtenerla.
if (Controller::fileExists("foto_perfil")) {
  $form_fields["foto_perfil"] = Controller::getFile("foto_perfil");
}

$non_empty_fields = Controller::getNonEmptyFormFields(
  $form_fields
);

/* ------------------------------ NUEVO USUARIO ----------------------------- */
if (Controller::isMethodPost()) {
  // Revisar que todos los campos menos "fecha_nacimiento" tienen datos.
  if (!Controller::areRequiredFieldsFilled(
    ModelUsuario::REQUIRED_FIELDS,
    $non_empty_fields
  )) {
    Controller::redirectView(
      view_path: "login/registro.php",
      error: "No se ingresaron los datos de todos los campos."
    );
    return;
  }

  $user = new ModelUsuario(
    nombres: $non_empty_fields["nombres"],
    apellidos: $non_empty_fields["apellidos"],
    username: $non_empty_fields["username"],
    password: $non_empty_fields["password"],
    rol: "normal",
    foto_perfil: $non_empty_fields["foto_perfil"],
  );

  $result = $user->insertUsuario();
}

// No se mandaron datos. Esto solo es posible en una actualización, ya que, no
// hay campos requeridos como en el registro.
if (count($non_empty_fields) === 0) {
  Controller::redirectView(
    "user/index.php",
    error: "No se enviaron datos en el formulario."
  );
  return;
}

/* ------------------------ ACTUALIZACIÓN DE USUARIO ------------------------ */
if (Controller::isMethodPut()) {
  if (
    Usuario::isNewPasswordSpecified($non_empty_fields)
    && Usuario::canUpdatePassword($non_empty_fields["current_password"])
  ) {
    // Establecemos la nueva contraseña.
    $non_empty_fields["password"] = $non_empty_fields["new_password"];

    // Quitamos los campos de la actual y nueva contraseña, ya que, no se
    // requieren para la actualización del usuario.
    unset($non_empty_fields["current_password"]);
    unset($non_empty_fields["new_password"]);
  }



  $result = Model::updateRecord(
    table: ModelUsuario::TABLE_NAME,
    param_values: $non_empty_fields,
    where_clause_names: [
      "id"
    ],
    where_clause_values: [
      Usuario::getId()
    ],
    unique_attributes: ModelUsuario::UNIQUE_ATTRIBUTES,
    pdo_params: ModelUsuario::PDO_PARAMS
  );

  if ($result === 1) {
    Usuario::updateSessionValues($non_empty_fields);
  }
}

$message = Model::OPERATION_INFO[$result];
// showElements(array($message));

// Al final de cualquiera de los procedimientos, redirigir a la pestaña
// principal.
if ($result === 1) {
  if (Controller::isMethodPut()) {
    // Redirigir a los detalles del usuario.
    Controller::redirectView(
      "user/index.php",
      message: $message
    );
    return;
  }

  Controller::redirectView(message: $message);
  return;
}

Controller::redirectView(error: $message);
