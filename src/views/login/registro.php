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

<body class="body-container container-sm container-fluid">
  <?php
  require_once FOLDERS_WITH_DOCUMENT_ROOT["LAYOUTS"] . "login-header.php";
  ?>

  <form autocomplete="off" action="" method="get" class="form__container">
    <!-- Hay que definir el método a utilizar con un input hidden. -->
    <input type="hidden" name="_method" value="POST">

    <label for="username">Username</label>
    <section class="form__input__container">
      <input autocomplete="off" type="text" name="username" id="username" placeholder="Ingresa un nombre de usuario">
      <i class="form__input__icon fas fa-user-alt"></i>
    </section>
    <label for="password">Contraseña</label>
    <section class="form__input__container">

      <input type="password" name="password" id="password" placeholder="Contraseña">
      <i class="form__input__icon fas fa-eye-slash"></i>
    </section>
    <label for="fecha-nacimiento">Fecha de nacimiento</label>
    <section class="form__input__container form__birthday">

      <input type="date" name="fecha-nacimiento" id="fecha-nacimiento">
    </section>
    <section>
      <label for="upload-picture">Foto de perfil</label>
      <input class="form__input__picture" type="file" name="upload-picture" class="form-control">
    </section>
    <input type="submit" class="btn form__button" id="register-form-button" value="Registrarse">
  </form>
  <a href="index.php" class="form__link">
    Iniciar sesión
  </a>

</html>
