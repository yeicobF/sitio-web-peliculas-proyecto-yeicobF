<?php

require_once FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"] . "login.php";

use Controllers\Login;


Login::redirectIfUserLoggedIn();
?>

<header class="page-info">
  <a class="page-info__logo" rel="noopener noreferrer" href="<?php echo $views_folder; ?>index.php">
    <img class="" src="<?php echo FOLDERS_WITH_LOCALHOST["PAGE_LOGO"]; ?>" alt="Colección de películas" srcset="">
  </a>
  <h1 class="page-info__title">Colección de películas</h1>
</header>
