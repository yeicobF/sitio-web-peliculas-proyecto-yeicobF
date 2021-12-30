<?php

$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/config.php";

include_once $path
  . LAYOUTS
  . "base-html-head.php";

$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Iniciar sesión",
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
  <link rel="stylesheet" href="<?php echo $css_folder; ?>config.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>components/components.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>utilities/utilities.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>form/form.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>login-register/login-register.css">

  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body class="body-container register-container container-sm container-xl container-fluid">
  <?php
  require_once FOLDERS_WITH_DOCUMENT_ROOT["LAYOUTS"] . "login-header.php";
  ?>

  <form autocomplete="off" action="" method="get" class="form__container">
    <!-- Hay que definir el método a utilizar con un input hidden. -->
    <input type="hidden" name="_method" value="POST">

    <div class="row">
      <section class="col-12 col-md-6">
        <label for="username">Username</label>
        <div class="form__input__container">
          <input autocomplete="off" type="text" name="username" id="username" placeholder="Ingresa un nombre de usuario">
          <i class="form__input__icon fas fa-user-alt"></i>
        </div>
      </section>
      <section class="col-12 col-md-6">
        <label for="upload-picture">Foto de perfil</label>
        <input class="form__input__picture" type="file" name="upload-picture" class="form-control">
      </section>
    </div>
    <div class="row">

      <section class="col-12 col-lg-6">
        <label for="name">Nombre(s)</label>
        <div class="form__input__container">
          <input autocomplete="off" type="text" name="name" id="name" placeholder="Ingresa tu nombre">
          <i class="form__input__icon fas fa-user-alt"></i>
        </div>
      </section>
      <section class="col-12 col-lg-6">
        <label for="last-name">Apellido(s)</label>
        <div class="form__input__container">
          <input autocomplete="off" type="text" name="last-name" id="last-name" placeholder="Ingresa tus apellidos">
          <i class="form__input__icon fas fa-user-alt"></i>
        </div>
      </section>
    </div>

    <section>
      <label for="password">Contraseña</label>
      <div class="form__input__container">
        <input type="password" name="password" id="password" placeholder="Ingrese la contraseña">
        <i class="form__input__icon fas fa-eye-slash"></i>
      </div>
    </section>
    <section>
      <label for="fecha-nacimiento">Fecha de nacimiento</label>
      <div class="form__input__container form__birthday">
        <input type="date" name="fecha-nacimiento" id="fecha-nacimiento">
      </div>
    </section>

    <input type="submit" class="btn form__button" id="register-form-button" value="Registrarse">
  </form>
  <a href="index.php" class="form__link">
    Iniciar sesión
  </a>

</html>
