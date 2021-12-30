    <!-- Inicio de sesión. -->
    <form class="session-buttons">
      <?php
        require_once __DIR__ . "/../libs/controller.php";
        use Libs\Controller;

        if (Controller::is)
      ?>

      <!-- button.btn.btn-light#login-button -->
      <a rel="noopener noreferrer" href="<?php echo $url_page["login"]; ?>" class="btn btn-light" id="login-button">
        Iniciar sesión
      </a>
      <!-- button.btn.btn-info#register-button{Registrarse} -->
      <a rel="noopener noreferrer" href="<?php echo $url_page["registro"]; ?>" class="btn btn-info" id="register-button">
        Registrarse
      </a>
    </form>
