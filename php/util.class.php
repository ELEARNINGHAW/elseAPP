<?php
#require_once('error.php');
#require_once 'const.php';
require_once('config.php');
#require_once('sql.php');

class Util   /// \brief check user input
{

var $sql;

function Util( $sql )
{
  $this->sql = $sql;
}


function expire ()
{
   /* ----------------------------------------------------------------------  */

    $input = array ( "item" => "collection" , "state" => "obsolete" ) ;
  
    $colls = $this -> sql -> getExpiredCollections() ;                          /*  ALLE Semesterapparate, die *aktiv* UND deren Zeit inzwischen abgelaufen sind */
    deb($colls,1);
    foreach ( $colls as $c ) {
      $input[ 'id' ] = $c[ 'id' ] ;                                                /*  werden auf neuen Status *obsolete* gesetzt  */
      $this -> set_item_state ( $input ) ;
    }
    /* ----------------------------------------------------------------------  */


    /* ----------------------------------------------------------------------  */
    $param = array (
        "tables" => "collection AS c,state AS s" ,
        "columns" => "c.id AS id" ,
        'cond' => "(c.state_id = s.id)      AND      (s.name = 'obsolete')     AND     DATE_ADD(c.expiry_date, INTERVAL 14 DAY) <= NOW()"
    ) ;

    $colls = $this -> sql -> sql_query ( 'select' , $param ) ;                          /*  ALLE Semesterapparate, die *obsolete* UND deren Zeit inzwischenn *SEIT  min. 14 TAGEN* abgelaufen sind */
    $input = array ( "item" => "collection" , "state" => "inactive" ) ;

    foreach ( $colls as $c ) /*  werden auf neuen Status *inactive* gesetzt  */ {
      $input[ 'id' ] = $c[ 'id' ] ;
      $this -> set_item_state ( $input ) ;
    }
    /* ----------------------------------------------------------------------  */
  }




function check_input( $user_input, $strict_mode = TRUE ) 
{
  global $CONST_validation_info;
  $debug_level =10;
  $err_info = array();
  foreach ($CONST_validation_info as $k ) 
  {   
    if (!isset($user_input[$k])) 
    {  $user_input[$k] = ""; 
    }
  }
                                                                                                                         if ($debug_level > 20)   { print "<hr><pre>";    print "util.php: check_input():\n\n"; }
  foreach ($user_input as $k => $v) 
  { 
    $type = gettype($v);
    switch($type) 
    { case "string":
        if (isset($CONST_validation_info[$k])) 
        {
          $ok = preg_match($CONST_validation_info[$k], $v);
        } else 
        { $ok = ($strict_mode) ? FALSE : TRUE;
        }
      break;

      case "array":
        $ok = TRUE;
      break;

      default:
        $ok = FALSE;
   }                                                                                                                     if ($debug_level > 20)   {  print "key: $k\n" ;    print "value: $v\n" ;    print "regexp: " . $CONST_validation_info[$k]. "\n";    print "ok: " ;print (($ok) ? "yes" : "no" ) . "\n\n"; }

  if (!$ok) 
  { $err_info[] = $k; 
  }
}
                                                                                                                    if ($debug_level > 20) { print "</pre></hr>";}
return $err_info;
}




function get_item_owner($item, $id  ) 
{
  $user = NULL;
   
  if($id)
  {  
    switch($item) 
    {
      case "user":
        $p = array ( 'tables' => "user,degree", 'columns' => "user.*,degree.description AS degree_description",	'cond' => "user.id = $id AND degree.id = user.degree_id");
        $ans = $this->sql->sql_query('select',$p);
        if (empty($ans)) {	user_error("database query failed" , E_USER_ERROR); }
	    $user = $ans[0];
      break;

      case "collection":
        $p = array ('tables' => "collection",'cond' => "id = $id");
        $ans = $this->sql->sql_query('select',$p );
        if (empty($ans)) { user_error("database query failed" ,	E_USER_ERROR ); }
        $user = $this->get_item_owner("user", $ans[0]['user_id'] );
      break;

      case "email":
        $p = array ('tables' => "email", 'cond' => "id = $id");
        $ans = $this->sql->sql_query('select',$p );
        $user = get_item_owner("document", $ans[0]['document_id'] );
      break;

      default:
        $p = array ('tables' => "document",'cond' => "id = $id");
        $ans = $this->sql->sql_query('select', $p );
        if (empty($ans))  {	user_error("database query failed" , E_USER_ERROR); }
        $user = $this->get_item_owner("collection",	$ans[0]['collection_id'] );
      break;
	}
  }
  return $user;
}

function send_email($smarty, $template, $kw, $email_to ) 
{
	global $_SESSION, $default_email_from, $default_email_subject;
	$email_from = $default_email_from;
	if (isset($_SESSION['user'])) 
    {
		$u = $_SESSION['user'];
		$email_from =   $u['forename'] . " " . $u['surname'] .  
        " <" . $u['email'] . ">";
		# cc-mail noch den eingeloggten user.. hwe, 23.04.2010
		$email_to .= "; <" . $u['email'] . ">";		
	}

	foreach ($kw as $k => $v)  
    {
		$smarty->assign($k, $v);
	}

	$email_txt = $smarty->fetch($template);

	$substFrom = array("&uuml;","&Uuml;","&ouml;","&Ouml;","&auml;","&Auml;");
	$substTo   = array("ü","Ü","ö","Ö","ä","Ä");

	$email_txt = str_replace($substFrom,$substTo,$email_txt);

	$email_txt = utf8_decode($email_txt);
	//$email_txt = @iconv('UTF-8', 'ISO-8859-1', $email_txt);

	foreach ($kw as $k => $v) 
    {
		$smarty->clearAssign($k, $v);
	}

    $headers = "From: ".utf8_decode($email_from)."\n"; //\r\n";
	$headers .= "Content-Type: text/plain; charset=iso-8859-1\n"; //\r\n";  	//$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"; //\r\n";

    echo "EMAIL:<br>";
    deb($email_to);
    deb($email_txt);
    die();

	#mail($email_to, $default_email_subject, $email_txt, $headers);
}

function get_new_expiry_date() {

	$t = getdate();

	$t['mday'] = 1; 

	if ($t['mon'] <= 2) { 
		$t['mon'] = 3; 
	} else if ($t['mon'] <= 7) { 
		$t['mon'] = 9; 
	} else {
		$t['mon'] = 3; 
		$t['year']++; 
	}

	$ans = sprintf("%04d%02d%02d", $t['year'] , $t['mon'] , $t['mday']);
	
	return $ans;

}

function check_acl($acl_list, $item, $id ) 
{  
  if      ( isset( $acl_list[ 'any' ] ) )   { $acl = $acl_list[ 'any' ] ; } 
  else if ( isset( $acl_list[ $item ] ) )   { $acl = $acl_list[ $item ] ; }
  else    { return FALSE;  }
    
  $u = $_SESSION['user'];
  
  $ok_ower = $ok_role = $ok_any = false;
      
  if ( isset ( $acl ) ) 
  {  
    foreach ( explode( ',', $acl ) as $a )      # $a = 'role=admin' OR 'owner=true'
    {
      list( $k, $v ) = explode( '=', $a );  # $k = 'role'/'owner' --  $v = 'admin' / 'edit' 
      if ( $k == 'owner' )
      {  
        $o        = $this->get_item_owner( $item, $id ); 
        $ok_ower  = ( $u[ 'id' ] == $o[ 'id' ]);
        if ($ok_ower) {break;}
      }
      
      if ( $k == 'role' )
      {    
        $ok_role  = ( $u[ 'role_name' ] == $v );
        if ($ok_role) {break;}
      }

      if ( $k == 'any' )
      {   
        $ok_any  = true;
        break;
      }
    }
  }
  #die( "<br>OWN:" .$ok_ower ." ROL:". $ok_role ." ANY:". $ok_any);
  
  return ( $ok_ower || $ok_role || $ok_any);
    
  /*
   * 
   *     $inverse = false;
    switch($k) 
    {
      case "!owner":
        $inverse = TRUE;
      case "owner":
        if ($id != "") 
        {
          $o = $this->get_item_owner($item, $id, $db); 
          $ok = ($u['id'] == $o['id']);
        }
      break;

      case "!role":
        $inverse = TRUE;
      case "role":
        $ok = ($u['role_name'] == $v);
      break;

      case "!any":
        $inverse = TRUE;
      case "any":
        $ok = TRUE;
      break;
 
      default:
         user_error("acl syntax error: $k" ,E_USER_ERROR); 
    }

	if ($inverse) 
    {
	  $ok = (!$ok);
    }

	if ($ok) 
    {
	  break;
	}	
     
    

  deb($u, 1);
  #  $u['forename'] $u['surname']    $u['role_name']


return $ok; 
    */
}

// set a random password 



    function resolveLocationID( $dep )
    {
      global $const_FAK;
      
      $fb_Design = $const_FAK[ 'DMI'];
      $fb_LS     = $const_FAK[ 'LS' ];
      $fb_TWI1   = $const_FAK[ 'WS' ];
      $fb_TWI2   = $const_FAK[ 'TI' ];
      
      if      ( in_array ( $dep , $fb_Design ) ) { $bibLoc = 1; }
      else if ( in_array ( $dep , $fb_LS     ) ) { $bibLoc = 2; }
      else if ( in_array ( $dep , $fb_TWI1   ) ) { $bibLoc = 3; }
      else if ( in_array ( $dep , $fb_TWI2   ) ) { $bibLoc = 4; }
      else                                       { $bibLoc = 5; }
      return $bibLoc;
    }





function getLetterOutput( $letter_header, $letter_exist )
{
#------------------------------------------------------------------------------------------------------------------- 
#----- ARRAY ALLER BUCHSTABEN ($letter_header: A-Z) # BUCHSTABEN die Anfangsbuchstaben von  Dozenten sind, die AKTIVE SA haben, haben Wert 1, sonst 0      = $letter_output  
$letter_activ  = "0" ;
$letter_output = array () ;

foreach ( $letter_header as $let ) {                                                                                      # aus const.php
  foreach ( $letter_exist as $val1 ) {
    if ( strtolower ( $let ) == strtolower ( $val1 ) )
    {
      $letter_activ = "1" ;
    }
  }
  $letter_output[ $let ] = $letter_activ ;
  $letter_activ          = "0" ;
   }
return $letter_output;
}


}
?>
