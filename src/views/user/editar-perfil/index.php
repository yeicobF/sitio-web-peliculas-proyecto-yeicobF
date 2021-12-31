<?php

use Controllers\Login;

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

$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Editar perfil",
  _includeOwnFramework: true,
  _includeFontAwesome: true
);

Login::redirectIfUserNotLoggedIn("login/index.php");
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  echo $baseHtmlHead->getHtmlBaseHead();
  ?>

  <!-- CSS -->
  <!-- CSS Propios -->
  <link rel="stylesheet" href="<?php echo $css_folder; ?>config.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>components/components.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>menu/menu.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>utilities/utilities.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>transformations/rotate.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>movies/movies.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>movies/movie-details.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>movies/comments.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>footer/footer.css">

  <link rel="stylesheet" href="<?php echo $css_folder; ?>form/form.css">

  <link rel="stylesheet" href="<?php echo $css_folder; ?>editar-perfil/editar-perfil.css">

  <!-- SCRIPTS -->
  <script defer src="<?php echo FOLDERS_WITH_LOCALHOST["SRC"] . "js/navbar.js"; ?>" type="module"></script>


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
        <img src="<?php echo $img_folder; ?>../avatar/1.jpg" alt="Username" class="circle-avatar">

      </figure>
      <form action="" class="edit-profile__form col-12 col-sm-8" method="POST">
        <input type="hidden" name="_method" value="PUT">

        <hgroup>
          <h1>Editar perfil</h1>
          <h2>Nombre de usuario</h2>
        </hgroup>
        <section>
          <label for="upload-picture">Foto de perfil</label>
          <input class="form__input__picture" type="file" name="upload-picture" class="form-control">
        </section>
        <section class="">
          <label for="username">Nombre de usuario</label>
          <div class="form__input__container">
            <input autocomplete="off" type="text" name="username" id="username" placeholder="Correo electrónico o usuario">
            <i class="form__input__icon fas fa-user-alt"></i>
          </div>
        </section>

        <section class="">
          <label for="current-password">Contraseña actual</label>
          <div class="form__input__container">
            <input type="password" name="current-password" placeholder="Ingresa la contraseña actual">
            <i class="form__input__icon fas fa-eye-slash"></i>
          </div>
        </section>
        <section class="">
          <label for="new-password">Nueva contraseña</label>
          <div class="form__input__container">
            <input type="password" name="new-password" placeholder="Ingresa la nueva contraseña">
            <i class="form__input__icon fas fa-eye-slash"></i>
          </div>
        </section>

        <section class="form__buttons">
          <button title="Eliminar cuenta" class="btn btn-danger form__button" type="submit">
            Eliminar cuenta
          </button>
          <div class="form__buttons form__buttons--safe">
            <a title="Cancelar" class="btn btn-warning form__button" href="<?php echo "{$views_folder}index.php"; ?>">
              Cancelar
            </a>
            <button title="Guardar cambios" class="btn btn-success form__button" type="submit">
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
