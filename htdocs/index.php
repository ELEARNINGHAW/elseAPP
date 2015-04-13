<?php
/* index.php
 * - Liste aller SAs
 * - Admin sieht alle SAs         (und kann alle SA bearbeiten)
 * - Dozent sieht nur eigene SAs  (und kann seine SA bearbeiten)
 * - Studi sieht (noch) alle SAs  (und kann NICHTS bearbeiten)
 * 
 * index.tpl
 *  */
require_once '../php/config.php' ;

require_once '../php/sql.class.php' ;
require_once '../php/util.class.php' ;
require_once '../php/renderer.class.php' ;

#session_unset();
 
#require_once '../php/error.php' ;

$sql      = new SQL() ;
$util     = new Util($sql) ;
$renderer = new Renderer();


$letter_exist = array () ;
$letter_eq    = array () ;

#$util -> expire () ;  /* pseudo cron: abgelaufene SA bekommen neuen status*/

/* ----------------- LISTE DER INPUTPARAMETER  ------------------ */
$default = array
(   "todo" => "view" ,
    "mode" => "view" ,
    "categories" => "1" , /* Departments,  1 = ALLE */
    "letter" => "" ,
     "item" => "collectionList" ,
) ;

$INPUT['work']  = array_merge($default, $_GET, $_POST   ); 

if ( isset ( $_SESSION['work'][ 'mode'   ] ) )   {  $INPUT['work'][ 'mode'   ] = $_SESSION['work'][ 'mode'   ] ;   } 
if ( isset ( $_SESSION['work'][ 'letter' ] ) )   {  $INPUT['work'][ 'letter' ] = $_SESSION['work'][ 'letter' ] ;   } # Sortierbuchstabe

$tpl_var = $INPUT ;
$tpl_var[ 'html_options' ]['categories'] = $sql -> getAllDepartments() ;                                                     ## Liste aller Departments (Categories)
$tpl_var[ 'collection' ] = array () ;



$user = $sql->getUser( $INPUT['work']['mode']);                                                                              ## LISTE mit N Einträgen mit Stammdaten aller registrieter Nutzer 

foreach ( $user as $u )                                                                                                      ## Liste wird mit entsprechenden SAs erweitert 
{
  $tpl_var[ 'html_options' ][ 'user' ][ $u[ 'id' ] ] =  getFullUserName($u);                                                 #----- LISTE DER ELSE USER  = $tpl_var[ 'html_options' ][ 'user' ]   
  $SAlist                                            =  $sql->getSAlist( $u, $INPUT['work']['mode'], $INPUT['work']['categories'] );
  if ( isset ( $SAlist[ 0 ][ 'surname' ] ) && $letter_eq != substr ( $SAlist[ 0 ][ 'surname' ] , 0 , 1 ) )                    ## Wenn User mindestens ein Semesterapparat hat
  { $letter = substr ( $SAlist[ 0 ][ 'surname' ] , 0 , 1 );
    $letter_exist[$letter] = $letter;                                                                                         ## Wird sein Anfangsbuchstabe gespeichert
  }

 $key  = getKey( $u );
  $tpl_var[ 'collection' ][ $key ] = NULL ;  
  
  $substring = strtolower ( $INPUT['work'][ 'letter' ] ) ;
  
  if ( $substring )                                                                                                             /* FILTER AUF Anfangsbuchstaben von Dozenten gewählt */
  {
    if ( strtolower ( substr ( $u[ 'surname' ] , 0 , 1 ) ) == ( $substring ) )
    {  if ( ! empty ( $SAlist ) )  {  $tpl_var[ 'collection' ][ $key ] = array_merge ( (array) $tpl_var[ 'collection' ][ $key ] , $SAlist ) ;  }
    }
  }
  else                                                                                                                          /* KEIN FILTER auf Anfangsbuchstabe  -- ALLE Dozenten gewählt */
  { if ( ! empty ( $SAlist ) )    {  $tpl_var[ 'collection' ][ $key ] = array_merge ( (array) $tpl_var[ 'collection' ][ $key ] , $SAlist ) ;   }
  }
  #------------------------------------------------------------------------------------------------------------------- 
}

#------------------------------------------------------------------------------------------------------------------- 
/*
  foreach ( $tpl_var[ 'collection' ] as $k => $v )  # sort collections
  {
  usort( $v, "cmp_coll" );
  $tpl_var[ 'collection' ][ $k ] = $v;
  }
 */

#------------------------------------------------------------------------------------------------------------------- 
if ( !isset( $_SESSION[ 'user' ] ) ) { $_SESSION['user'] = NULL; }
#------------------------------------------------------------------------------------------------------------------- 
$tpl_var[ 'user' ]           = $_SESSION[ 'user' ] ;
$tpl_var[ 'actions_info' ]   = $CONST_actions_info ;                                                                           # aus const.php ##===================== ACTION INFO BESSER HIER IM PHP AUSWERTEN, NICHT IM TEMPLATE  
$tpl_var[ 'letter_output' ]  = $util->getLetterOutput( $CONST_letter_header, $letter_exist ) ;                                                          /* Liste mit allen Anfangsbuchstaben aller Nutzer */
$tpl_var[ 'source' ]         = 'index.php' ;
##------------------------------------------------------------------------------------------------------------------- 
#deb($_SESSION);
#deb($tpl_var);
$renderer -> do_template ( 'index.tpl' , $tpl_var , TRUE ) ;

function getFullUserName($u)
{
  return $u[ 'forename' ] . " " . $u[ 'surname' ] ; /*  Vorname, Nachname */
}

function getKey( $u )
{
return trim ( $u[ 'haw-account' ] ).'::'.  trim ( $u[ 'surname' ] ) .'::'. trim ( $u[ 'forename' ] ) ;
}


function cmp_coll ( $a , $b )                          # callback function for usort() 
{
  $key_a = $a[ 'title' ] . $a[ 'collection_no' ] ;
  $key_b = $b[ 'title' ] . $b[ 'collection_no' ] ;
  return strcmp ( $key_a , $key_b ) ;
}


?>