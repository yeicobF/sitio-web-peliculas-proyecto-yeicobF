    <!-- Inicio de sesión. -->
    <form class="session-buttons">
      <?php
      require_once __DIR__ . "/../../../config/config.php";
      require_once "{$libs_folder}controller.php";

      use Libs\Controller;

      if (Controller::isSessionActive()) {
        echo "
            <!-- button.btn.btn-light#login-button -->
            <a 
              rel='noopener noreferrer' 
              href=" . $url_page["login"] . " 
              class='btn btn-danger' 
              id='cerrar-sesion'
            >
              Cerrar sesión
            </a>
            <!-- button.btn.btn-info#register-button{Registrarse} -->
            <a 
              rel='noopener noreferrer' 
              href=" . $url_page["editar-perfil"] . " 
              class='btn btn-info' 
              id='register-button'
            >
              Registrarse
            </a>
            ";
      }
      echo "
              <!-- button.btn.btn-light#login-button -->
              <a 
                rel='noopener noreferrer' 
                href=" . $url_page["login"] . " 
                class='btn btn-light' 
                id='login-button'
              >
                Iniciar sesión
              </a>
              <!-- button.btn.btn-info#register-button{Registrarse} -->
              <a 
                rel='noopener noreferrer' 
                href=" . $url_page["registro"] . " 
                class='btn btn-info' 
                id='register-button'
              >
                Registrarse
              </a>
              ";
      ?>


    </form>
