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
    <input type="hidden" name="_method" value="POST">

    <section class="form__input__container">
      <input autocomplete="off" type="text" name="username" id="username" placeholder="Correo electrónico o usuario">
      <i class="form__input__icon fas fa-user-alt"></i>
    </section>
    <section class="form__input__container">
      <input type="password" name="password" id="password" placeholder="Contraseña">
      <i class="form__input__icon fas fa-eye-slash"></i>
    </section>
    <input type="submit" class="btn form__button" id="login-form-button" value="Iniciar sesión">
  </form>
  <a href="<?php echo $views_folder; ?>registro/index.php" class="form__link">
    Regístrate
  </a>

</html>
