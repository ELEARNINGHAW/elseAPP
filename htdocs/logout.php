<?php
require_once('../php/config.php');
require_once '../php/renderer.class.php';

$logout = new Logout();
$logout-> doLogout();

class Logout
{
 var $renderer;
 
 function Logout()
 {
   $this->renderer  = new Renderer();
 }

 function doLogout()
 {
  unset($_SESSION);
  #print_r($_SESSION);
  session_destroy();
  $this->renderer->doRedirect('index.php');
 }
}
?>
