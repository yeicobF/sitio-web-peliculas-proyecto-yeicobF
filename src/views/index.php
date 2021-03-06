<?php
// Obtener la raíz del proyecto en el disco duro y no en el URL como en __DIR__.
// https://stackoverflow.com/questions/13369529/full-url-not-working-with-php-include
// $path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once __DIR__ . "/../config/config.php";

include_once FOLDERS_WITH_DOCUMENT_ROOT["VIEWS"]
  . "layouts/base-html-head.php";

$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Inicio",
  _includeOwnFramework: true,
  _includeFontAwesome: true
);

// error_log("prueba log");

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
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>footer/footer.css">

  <!-- SCRIPTS -->



  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body class="body-container">

  <?php
  include DOCUMENT_ROOT . LAYOUTS . "navbar.php";
  ?>

  <div class="container-fluid container-lg fill-height-flex">

  </div>

  <?php
  include DOCUMENT_ROOT . LAYOUTS . "footer.php";
  ?>
</body>

</html>
