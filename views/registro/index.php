<?php

$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/directory-path.php";
include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/config.php";

include_once $path
  . LAYOUTS
  . "base-html-head.php";

$css_folder = DirectoryPath::getPathWithLocalhost(SRC);
$views_folder = DirectoryPath::getPathWithLocalhost(VIEWS);
$img_folder =
  DirectoryPath::getPathWithLocalhost(ASSETS)
  . "img/";
$css_folder = DirectoryPath::getPathWithLocalhost(CSS);

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

  <header class="page-info">
    <a class="page-info__logo" rel="noopener noreferrer" href="<?php echo $views_folder; ?>inicio/index.php">
      <img class="" src="<?php echo DirectoryPath::getPathWithLocalhost(PAGE_LOGO); ?>" alt="Colección de películas" srcset="">
    </a>
    <h1 class="page-info__title">Colección de películas</h1>
  </header>
  <form autocomplete="off" action="" method="get" class="form__container">
    <!-- Hay que definir el método a utilizar con un input hidden. -->
    <input type="hidden" name="_method" value="POST">

    <section class="form__input__container">
      <input autocomplete="off" type="text" name="username" id="username" placeholder="Ingresa un nombre de usuario">
      <i class="form__input__icon fas fa-user-alt"></i>
    </section>
    <section class="form__input__container">
      <input type="password" name="password" id="password" placeholder="Contraseña">
      <i class="form__input__icon fas fa-eye-slash"></i>
    </section>
    <section class="form__input__container form__birthday">
      <input type="date" name="fecha-nacimiento" id="fecha-nacimiento">
    </section>
    <input type="submit" class="btn form__button" id="register-form-button" value="Registrarse">
  </form>
  <a href="<?php echo $views_folder; ?>login/index.php" class="form__link">
    Iniciar sesión
  </a>

</html>
