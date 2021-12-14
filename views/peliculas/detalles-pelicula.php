<?php
$path = "{$_SERVER["DOCUMENT_ROOT"]}/" . "fdw-2021-2022-a/proyecto-yeicobF/";

include_once $path . "src/config/directory-path.php";
include_once $path . DirectoryPath::LAYOUTS_URL . "base-html-head.php";

$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Detalles de película",
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
  <link rel="stylesheet" href="../css/config.css">
  <link rel="stylesheet" href="../css/components/components.css">
  <link rel="stylesheet" href="../css/menu/menu.css">
  <link rel="stylesheet" href="../css/utilities/utilities.css">
  <link rel="stylesheet" href="../css/transformations/rotate.css">
  <link rel="stylesheet" href="../css/movies/movies.css">
  <link rel="stylesheet" href="../css/footer/footer.css">

  <!-- SCRIPTS -->
  <script defer src="<?php echo $path . DirectoryPath::SRC_FOLDER_URL . "js/navbar.js"; ?>" type="module"></script>

  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body>
  <?php
  include $path . DirectoryPath::LAYOUTS_URL . "navbar.php";
  ?>
  <?php
  include $path . DirectoryPath::LAYOUTS_URL . "footer.php";
  ?>
</body>

</html>
