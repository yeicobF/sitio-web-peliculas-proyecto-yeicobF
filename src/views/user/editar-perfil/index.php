<?php

use Controllers\Login;
use Controllers\Usuario;

$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/config.php";

include_once
  FOLDERS_WITH_DOCUMENT_ROOT["LAYOUTS"]
  . "base-html-head.php";

include_once
  FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
  . "login.php";
include_once
  FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
  . "usuario.php";

Login::redirectIfUserNotLoggedIn("login/index.php");

$editar_perfil_action = CONTROLLERS_FOLDER . "usuario.php";
$username = $_SESSION["username"];

$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Editar perfil - {$username}",
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

  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>editar-perfil/editar-perfil.css">

  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body class="body-container">
  <?php
  include $path . LAYOUTS . "navbar.php";
  ?>
  <div class="fill-height-flex container-lg container-fluid">
    <div class="row edit-profile">

      <figure class="edit-profile__figure col-12 col-sm-4">
        <!-- <figcaption>
          <h2 class="edit-profile__title">Nombre del usuario</h2>
        </figcaption> -->
        <!-- <img src="<?php echo IMG_FOLDER; ?>../avatar/1.jpg" alt="Username" class="circle-avatar"> -->

        <?php
        Usuario::renderFotoPerfil(
          usuario_id: $_SESSION["id"],
          username: $_SESSION["username"],
          foto_perfil: $_SESSION["foto_perfil"]
        );
        ?>
      </figure>
      <form action="<?php echo $editar_perfil_action; ?>" method="POST" enctype="multipart/form-data" class="edit-profile__form col-12 col-sm-8">
        <!-- <input type="hidden" name="_method" value="PUT"> -->

        <hgroup>
          <h1>Editar perfil</h1>
          <h2 class="edit-profile__username"><?php echo $username; ?></h2>
        </hgroup>
        <section>
          <label for="foto_perfil">Foto de perfil</label>
          <input class="form__input__picture" type="file" name="foto_perfil" class="form-control">
        </section>
        <section class="">
          <label for="username">Nombre de usuario</label>
          <div class="form__input__container">
            <input autocomplete="off" type="text" name="username" id="username" placeholder="<?php echo Usuario::getUsername(); ?>">
            <i class="form__input__icon fas fa-user-alt"></i>
          </div>
        </section>
        <section class="">
          <label for="nombres">Nombre(s)</label>
          <div class="form__input__container">
            <input autocomplete="off" type="text" name="nombres" id="nombres" placeholder="<?php echo Usuario::getNombres(); ?>">
            <i class="form__input__icon fas fa-user-alt"></i>
          </div>
        </section>
        <section class="">
          <label for="apellidos">Apellido(s)</label>
          <div class="form__input__container">
            <input autocomplete="off" type="text" name="apellidos" id="apellidos" placeholder="<?php echo Usuario::getApellidos(); ?>">
            <i class="form__input__icon fas fa-user-alt"></i>
          </div>
        </section>

        <section class="">
          <label for="current_password">Contraseña actual</label>
          <div class="form__input__container">
            <input type="password" name="current_password" placeholder="Ingresa la contraseña actual">
            <i class="form__input__icon fas fa-eye-slash"></i>
          </div>
        </section>
        <section class="">
          <label for="new_password">Nueva contraseña</label>
          <div class="form__input__container">
            <input type="password" name="new_password" placeholder="Ingresa la nueva contraseña">
            <i class="form__input__icon fas fa-eye-slash"></i>
          </div>
        </section>

        <section class="form__buttons">
          <!-- DELETE -->
          <!-- <input type="hidden" name="_method" value="DELETE"> -->
          <button name="_method" value="DELETE" title="Eliminar cuenta" class="btn btn-danger form__button" type="submit">
            Eliminar cuenta
          </button>
          <div class="form__buttons form__buttons--safe">
            <a title="Cancelar" class="btn btn-warning form__button" href="<?php echo VIEWS_FOLDER . "index.php"; ?>">
              Cancelar
            </a>

            <button name="_method" value="PUT" title="Guardar cambios" class="btn btn-success form__button" type="submit">
              Guardar cambios
            </button>
          </div>

        </section>
      </form>
    </div>


  </div>

  <?php
  include $path . LAYOUTS . "footer.php";
  ?>
</body>

</html>
