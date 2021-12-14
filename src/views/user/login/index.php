<?php

$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/directory-path.php";

include_once $path
  . DirectoryPath::LAYOUTS
  . "base-html-head.php";

$src_folder = DirectoryPath::getPathWithLocalhost(DirectoryPath::SRC);
$views_folder = DirectoryPath::getPathWithLocalhost(DirectoryPath::VIEWS);
$img_folder =
  DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS)
  . "img/";

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
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/config.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/components/components.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/utilities/utilities.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/form/form.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/login-register/login-register.css">

  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body class="body-container container-sm container-fluid">
  <header class="page-info">
    <a class="page-info__logo" rel="noopener noreferrer" href="<?php echo $views_folder; ?>index.php">
      <img class="" src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::PAGE_LOGO); ?>" alt="Colección de películas" srcset="">
    </a>
    <h1 class="page-info__title">Colección de películas</h1>
  </header>
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
  <a href="<?php echo $views_folder; ?>user/registro/index.php" class="form__link">
    Regístrate
  </a>

</html>
