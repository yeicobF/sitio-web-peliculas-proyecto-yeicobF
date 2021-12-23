<?php
$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path . "fdw-2021-2022-a/proyecto-yeicobF/src/config/config.php";

/**
 * Obtener la base que viene en el head del HTML en general y no ponerla de
 * forma manual.
 * 
 * Se envían banderas como parámetros para indicar qué agregar y qué no.
 */
class BaseHtmlHead
{
  private $pageName = " | Colección de películas";
  private $includeOwnFramework = true;
  private $includeFontAwesome = true;
  private $path;


  /* ------------------------------ CONSTANTES ------------------------------ */

  /**
   * HTML del framework propio tanto de CSS como de JS.
   */
  const OWN_FRAMEWORK = [
    "css" => "
      <!-- Framework que hicimos obtenido de la GitHub Page que publicamos. -->
      <link rel='stylesheet' href='https://fundamentos2122.github.io/framework-css-yeicobF/css/framework.css'>
    ",
    "js" => "
      <!-- FRAMEWORK -->
      <script defer src='https://fundamentos2122.github.io/framework-css-yeicobF/js/framework.js'>
      </script>
    "
  ];

  /**
   * Código necesario para hacer uso de FontAwesome.
   */
  const FONT_AWESOME = "
    <!-- 
      FONT AWESOME 6 (BETA) -> Agregué el defer.

      En un artículo de FontAwesome se recomienda. Además, así se descarga
      asíncronamente y se ejecuta cuando termina de hacerse el parse del HTML. 

      https://fontawesome.com/v5.15/how-to-use/on-the-web/advanced/svg-asynchronous-loading
    -->
    <script defer src='https://kit.fontawesome.com/db6a4307fa.js' crossorigin='anonymous'></script>
  ";

  /**
   * HTML necesario para agregar Google Fonts.
   *
   * @var string
   */
  const GOOGLE_FONTS = "
    <!-- GOOGLE FONTS -->

    <!-- Quicksand -->
    <!-- font-family: 'Quicksand', sans-serif; -->
    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap' rel='stylesheet'>

    <!-- Roboto -->
    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap' rel='stylesheet'>

    <!-- Single Day -->
    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Single+Day&display=swap' rel='stylesheet'>

    <!-- Noto Sans -->
    <!-- https://fonts.google.com/noto/specimen/Noto+Sans -->
    <!-- font-family: 'Noto Sans', sans-serif; -->

    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap' rel='stylesheet'>
  ";

  public function __construct(
    $_pageName = 'Inicio',
    $_includeOwnFramework = true,
    $_includeFontAwesome = true
  ) {
    $this->pageName = $_pageName . $this->pageName;
    $this->includeOwnFramework = $_includeOwnFramework;
    $this->includeFontAwesome = $_includeFontAwesome;
    $this->path = "{$_SERVER["DOCUMENT_ROOT"]}/" . BARE_PROJECT_FOLDER_DIR;
  }
  public function getHtmlBaseHead()
  {
    $htmlBaseHead = "
      <meta charset='UTF-8'>
      <meta http-equiv='X-UA-Compatible' content='IE=edge'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <!-- favicon -->
      <link 
        rel='shortcut icon' 
        href='" . FOLDERS_WITH_LOCALHOST["PAGE_LOGO"] . "' 
        type='image/x-icon'
      >

      <script 
        defer 
        src='" . FOLDERS_WITH_LOCALHOST["JS"] . "navbar.js' 
        type='module'
      ></script>
    ";

    $htmlBaseHead .=
      $this->includeOwnFramework
      ? self::OWN_FRAMEWORK["css"]
      : "";

    $htmlBaseHead .=
      $this->includeOwnFramework
      ? self::OWN_FRAMEWORK["js"]
      : "";

    $htmlBaseHead .=
      $this->includeFontAwesome
      ? self::FONT_AWESOME
      : "";

    $htmlBaseHead .= self::GOOGLE_FONTS;

    return $htmlBaseHead;
  }

  /**
   * Obtener título del HTML.
   *
   * @return string
   */
  public function getTitle()
  {
    return "<title>{$this->pageName}</title>";
  }
}
