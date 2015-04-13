<?php
/* login.php
 * - Login für Admins und Dozenten
 * 
 * login.tpl
 *   */

require_once '../php/config.php' ;
require_once '../php/renderer.class.php' ;
require_once '../php/sql.class.php' ;
require_once '../php/util.class.php' ;

#require_once 'error.php';

$login = new Login() ;
$login -> doLogin () ;

class Login
{ 
  var     $util ;
  var     $renderer ;
  var     $INPUT ;
  var     $valid_input = array ( ## parameters accepted by this php script
      "login" => "/^.+$/" ,
      "password" => "/^.+$/" ,
  ) ;

  function Login ()
  {
    $this->sql      = new SQL() ;
    $this->renderer = new Renderer() ;
    $this->util     = new Util ( $this->sql ) ;
  }

  function doLogin ()
  {
    $INPUT['work'] = array_merge ( $_GET , $_POST ) ;                                                                                    ## check user input 

    
    if ( ! isset ( $INPUT['work'][ 'mode' ] ) )    {     $INPUT['work'][ 'mode' ] = 'view' ;   }                                  # wenn kein mode vorhanden, default mode = view
    $INPUT['work'][ 'todo' ] = 'view' ;
    $INPUT['work']['item'] = 'login';
    # $errors = $util->check_input($INPUT, $validation_info); ** TODO --- ERROR CHECK ---

    $display_html_form = (isset ( $INPUT['work'][ 'b_ok' ] )) ? FALSE : TRUE ;
/*
    if ( ! $display_html_form )                                                                                                # Login Forumular abgeschickt
    {
      $errors = $this -> util -> check_input ( $INPUT , $this -> valid_input , FALSE ) ;
      if ( ! empty ( $errors ) )                                                                                                # Login und PW werden auf erlaubte Zeichen geprüft
      {
        $display_html_form = TRUE ;
      }
    }
*/
    if ( $display_html_form )                                                                                                 ## (re-)display the LOGIN input form
    {
      $tpl_vars = $INPUT ;
      $tpl_vars['work'][ 'item' ] = 'login' ;

      $this -> renderer -> do_template ( 'login.tpl' , $tpl_vars ) ;
          
    }
    else                                                                                                                    # Erlaubte Userkennung und erlaubtes PW wurden übergeben  
    {
      ## -----------------------------------------------------------------------------------------------------------------

      $INPUT['work'][ 'password' ] = "{SHA1}" . sha1 ( $INPUT['work'][ 'password' ] ) ;                                                          # encrypt password
          

      
     # $ans               = $this->sql->sql_query ( 'check_pw' , $INPUT['work'] ) ;                                                                          # DB Check auf PW und Userkennung
      $ans               = $this->sql->checkPW ( $INPUT['work'] ) ;                                                                          # DB Check auf PW und Userkennung
     
      ## -----------------------------------------------------------------------------------------------------------------
      unset ( $INPUT['work'][ 'password' ] ) ;
      unset ( $INPUT['work'][ 'username' ] ) ;
      
     

      if ( empty ( $ans[0] ) )                                                                                                     # wrong userkennung / password
      {
        $tpl_vars                  = $INPUT ;
        $tpl_vars[ 'errors_info' ][] = 'password' ;
        $this -> renderer -> do_template ( 'login.tpl' , $tpl_vars ) ;
          
        }
      else                                                                                                                      # Login korrekt, Nutzerdaten werden übergeben
      {
        $_SESSION[ 'user' ] = $ans[ 0 ] ;
        switch ( $ans[ 0 ][ 'role_id' ] ) 
        {
          case 1:  $_SESSION['work'][ 'mode' ] = 'admin' ;  break ;
          case 2:  $_SESSION['work'][ 'mode' ] = 'staff' ;  break ;
          default: $_SESSION['work'][ 'mode' ] = 'edit' ;
        }


        $this -> renderer -> doRedirect ( 'index.php' ) ;
      }
      ## -----------------------------------------------------------------------------------------------------------------
    }
  }

}

?>
