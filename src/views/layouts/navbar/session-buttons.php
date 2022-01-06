      <?php
      require_once __DIR__ . "/../../../config/config.php";
      require_once FOLDERS_WITH_DOCUMENT_ROOT["LIBS"] . "controller.php";
      require_once FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"] . "login.php";
      require_once FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"] . "usuario.php";

      use Libs\Controller;
      use Controllers\Login;
      use Controllers\Usuario;

      $action = CONTROLLERS_FOLDER . "login.php";
      ?>

      <!-- Inicio de sesión. -->
      <form class="session-buttons" action="<?php echo $action; ?>" method="POST" autocomplete="off">
        <?php

        if (Login::isUserLoggedIn()) {
          $avatar_url = URL_PAGE["detalles-perfil"];
          // $avatar_url = "{URL_PAGE["editar-perfil"]}?id={$_SESSION["id"]}";

          $username_alt = $_SESSION["username"];
        ?>
          <input type='hidden' name='_method' value='DELETE'>
          <!-- button.btn.btn-light#login-button -->
          <button type="submit" class="btn btn-danger" value="Cerrar sesión">
            Cerrar sesión
          </button>
          <!-- button.btn.btn-info#register-button{Registrarse} -->
          <a rel="noopener noreferrer" href="<?php echo $avatar_url; ?>" class="session-buttons__user-info" id="avatar" alt="<?php echo $username_alt; ?>">
            <!-- <img src="http://localhost:8012/fdw-2021-2022-a/proyecto-yeicobF/src/public/assets/img/../avatar/1.jpg" alt="<?php echo $username_alt; ?>" class="circle-avatar"> -->

            <?php
            Usuario::renderFotoPerfil(
              usuario_id: $_SESSION["id"],
              username: $_SESSION["username"],
              foto_perfil: $_SESSION["foto_perfil"]
            );
            ?>
            <h2 class="session-buttons__user-info__username">
              <?php
              echo Usuario::getUsername();
              ?>
            </h2>
          </a>

        <?php
        } else {
        ?>
          <!-- button.btn.btn-light#login-button -->
          <a rel="noopener noreferrer" href="<?php echo URL_PAGE["login"]; ?>" class="btn btn-light" id="login-button">
            Iniciar sesión
          </a>
          <!-- button.btn.btn-info#register-button{Registrarse} -->
          <a rel="noopener noreferrer" href="<?php echo URL_PAGE["registro"]; ?>" class="btn btn-info" id="register-button">
            Registrarse
          </a>
        <?php
        }
        ?>


      </form>
