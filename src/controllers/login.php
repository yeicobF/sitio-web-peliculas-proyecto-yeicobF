<?php
class Login extends Controller
{
  public function __construct()
  {
    parent::__construct();
    // error_log("Login::__construct() -> Inicio de Login.");
  }

  public function render()
  {
    $this->view->render("login/index");
  }
}
