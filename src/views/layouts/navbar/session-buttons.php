      <?php
      require_once __DIR__ . "/../../../config/config.php";
      require_once FOLDERS_WITH_DOCUMENT_ROOT["LIBS"] . "controller.php";
      require_once FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"] . "login.php";

      use Libs\Controller;
      use Controllers\Login;

      $action = "{$controllers_folder}login.php";
      ?>

      <!-- Inicio de sesión. -->
      <form class="session-buttons" action="<?php echo $action; ?>" method="POST">
        <?php

        if (Login::isUserLoggedIn()) {
        ?>
          <input type='hidden' name='_method' value='DELETE'>

          <!-- button.btn.btn-light#login-button -->
          <input type="submit" class="btn btn-danger" value="Cerrar sesión">
          <!-- button.btn.btn-info#register-button{Registrarse} -->
          <a rel="noopener noreferrer" href="<?php echo "{$url_page["editar-perfil"]}?id={$_SESSION["id"]}"; ?>" class="" id="avatar" alt="<?php echo $_SESSION["username"]; ?>">
            <img src="http://localhost:8012/fdw-2021-2022-a/proyecto-yeicobF/src/public/assets/img/../avatar/1.jpg" alt="<?php echo $_SESSION["username"]; ?>" class="circle-avatar">
          </a>
        <?php
        } else {
        ?>
          <!-- button.btn.btn-light#login-button -->
          <a rel="noopener noreferrer" href="<?php echo $url_page["login"]; ?>" class="btn btn-light" id="login-button">
            Iniciar sesión
          </a>
          <!-- button.btn.btn-info#register-button{Registrarse} -->
          <a rel="noopener noreferrer" href="<?php echo $url_page["registro"]; ?>" class="btn btn-info" id="register-button">
            Registrarse
          </a>
        <?php
        }
        ?>


      </form>
