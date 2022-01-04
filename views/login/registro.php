<?php

$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/config.php";

include_once $path
  . LAYOUTS
  . "base-html-head.php";

include_once
  FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
  . "usuario.php";

$registro_action = CONTROLLERS_FOLDER . "usuario.php";


$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Registro",
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
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>utilities/utilities.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>form/form.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>login-register/login-register.css">

  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body class="body-container register-container container-sm container-xl container-fluid">
  <?php
  require_once FOLDERS_WITH_DOCUMENT_ROOT["LAYOUTS"] . "login-header.php";
  ?>

  <form autocomplete="off" action="<?php echo $registro_action; ?>" method="POST" enctype="multipart/form-data" class="form__container">
    <!-- Hay que definir el método a utilizar con un input hidden. -->
    <input type="hidden" name="_method" value="POST">

    <div class="row">
      <section class="col-12 col-md-6">
        <label for="username">Nombre de usuario</label>
        <div class="form__input__container">
          <input required autocomplete="off" type="text" name="username" id="username" placeholder="Ingresa un nombre de usuario">
          <i class="form__input__icon fas fa-user-alt"></i>
        </div>
      </section>
      <section class="col-12 col-md-6">
        <label for="foto_perfil">Foto de perfil</label>
        <input class="form__input__picture" type="file" name="foto_perfil" class="form-control">
      </section>
    </div>
    <div class="row">

      <section class="col-12 col-lg-6">
        <label for="nombres">Nombre(s)</label>
        <div class="form__input__container">
          <input required autocomplete="off" type="text" name="nombres" id="nombres" placeholder="Ingresa tu nombre">
          <i class="form__input__icon fas fa-user-alt"></i>
        </div>
      </section>
      <section class="col-12 col-lg-6">
        <label for="apellidos">Apellido(s)</label>
        <div class="form__input__container">
          <input required autocomplete="off" type="text" name="apellidos" id="apellidos" placeholder="Ingresa tus apellidos">
          <i class="form__input__icon fas fa-user-alt"></i>
        </div>
      </section>
    </div>

    <section>
      <label for="password">Contraseña</label>
      <div class="form__input__container">
        <input required type="password" name="password" id="password" placeholder="Ingrese la contraseña">
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
