<?php

require_once('config.php') ;
require_once('../php/sql.class.php'         );

class Util   /// \brief check user input
{
  var  $sql ;

  function Util ( )
  {
    $this->sql        = new SQL();
     
    if ( !isset ( $_SESSION[ 'DEP2BIB'] ) )                                     # Ermittelt die zuständige FachBib zum jeweiligen Department 
    {
        $this->HAWdb      = new HAW_DB();                                       # Aus der SQLite DB
       $_SESSION['DEP2BIB' ] =  $this->HAWdb->getDEP2BIB();
       $_SESSION['FAK'     ] =  $this->HAWdb->getAllFak();
       $_SESSION['FACHBIB' ] =  $this->HAWdb->getAllFachBib();
    } 
  }
/*
  function check_input ( $user_input , $strict_mode = TRUE )
  {
    global $CONST_validation_info ;
    $debug_level = 10 ;
    $err_info    = array () ;
    foreach ( $CONST_validation_info as $k ) 
    {
      if ( ! isset ( $user_input[ $k ] ) )
      {
        $user_input[ $k ] = "" ;
      }
    }
    if ( $debug_level > 20 )
    {
      print "<hr><pre>" ;
      print "util.php: check_input():\n\n" ;
    }
    foreach ( $user_input as $k => $v ) {
      $type = gettype ( $v ) ;
      switch ( $type ) {
        case "string":
          if ( isset ( $CONST_validation_info[ $k ] ) )
          {
            $ok = preg_match ( $CONST_validation_info[ $k ] , $v ) ;
          }
          else
          {
            $ok = ($strict_mode) ? FALSE : TRUE ;
          }
          break ;

        case "array":
          $ok = TRUE ;
          break ;

        default:
          $ok = FALSE ;
      } if ( $debug_level > 20 )
      {
        print "key: $k\n" ;
        print "value: $v\n" ;
        print "regexp: " . $CONST_validation_info[ $k ] . "\n" ;
        print "ok: " ;
        print (($ok) ? "yes" : "no" ) . "\n\n" ;
      }

      if ( ! $ok )
      {
        $err_info[] = $k ;
      }
    }
    if ( $debug_level > 20 )
    {
      print "</pre></hr>" ;
    }
    return $err_info ;
  }
*/
  
  function get_item_owner ( $item , $id )
  {
    $user = NULL ;

    if ( $id )
    {
      switch ( $item ) {
        case "user":
          $p   = array ( 'tables' => "user,degree" , 'columns' => "user.*,degree.description AS degree_description" , 'cond' => "user.id = $id AND degree.id = user.degree_id" ) ;
          $ans = $this -> sql -> sql_query ( 'select' , $p ) ;
          if ( empty ( $ans ) )
          {
            user_error ( "database query failed" , E_USER_ERROR ) ;
          }
          $user = $ans[ 0 ] ;
          break ;

        case "collection":
          $p   = array ( 'tables' => "collection" , 'cond' => "id = $id" ) ;
          $ans = $this -> sql -> sql_query ( 'select' , $p ) ;
          if ( empty ( $ans ) )
          {
            user_error ( "database query failed" , E_USER_ERROR ) ;
          }
          $user = $this -> get_item_owner ( "user" , $ans[ 0 ][ 'user_id' ] ) ;
          break ;

        case "email":
          $p    = array ( 'tables' => "email" , 'cond' => "id = $id" ) ;
          $ans  = $this -> sql -> sql_query ( 'select' , $p ) ;
          $user = get_item_owner ( "document" , $ans[ 0 ][ 'document_id' ] ) ;
          break ;

        default:
          $p   = array ( 'tables' => "document" , 'cond' => "id = $id" ) ;
          $ans = $this -> sql -> sql_query ( 'select' , $p ) ;
          if ( empty ( $ans ) )
          {
            user_error ( "database query failed" , E_USER_ERROR ) ;
          }
          $user = $this -> get_item_owner ( "collection" , $ans[ 0 ][ 'collection_id' ] ) ;
          break ;
      }
    }
    return $user ;
  }

  function get_new_expiry_date ()
  {
    $t = getdate () ;

    $t[ 'mday' ] = 1 ;

    if      ( $t[ 'mon' ] <= 2 )  { $t[ 'mon' ] = 3 ;  }
    else if ( $t[ 'mon' ] <= 7 )  { $t[ 'mon' ] = 9 ;  }
    else                          { $t[ 'mon' ] = 3 ;  $t[ 'year' ] ++ ;  }

    $ans = sprintf ( "%04d%02d%02d" , $t[ 'year' ] , $t[ 'mon' ] , $t[ 'mday' ] ) ;

    return $ans ;
  }

  function check_acl ( $acl_list , $item , $id )
  {
    if ( isset ( $acl_list[ 'any' ] ) )
    {
      $acl = $acl_list[ 'any' ] ;
    }
    else if ( isset ( $acl_list[ $item ] ) )
    {
      $acl = $acl_list[ $item ] ;
    }
    else
    {
      return FALSE ;
    }

    $u = $_SESSION[ 'user' ] ;

    $ok_ower = $ok_role = $ok_any  = false ;

    if ( isset ( $acl ) )
    {
      foreach ( explode ( ',' , $acl ) as $a ) {      # $a = 'role=admin' OR 'owner=true'
        list( $k , $v ) = explode ( '=' , $a ) ;  # $k = 'role'/'owner' --  $v = 'admin' / 'edit' 
        if ( $k == 'owner' )
        {
          $o       = $this -> get_item_owner ( $item , $id ) ;
          $ok_ower = ( $u[ 'id' ] == $o[ 'id' ]) ;
          if ( $ok_ower )
          {
            break ;
          }
        }

        if ( $k == 'role' )
        {
          $ok_role = ( $u[ 'role_name' ] == $v ) ;
          if ( $ok_role )
          {
            break ;
          }
        }

        if ( $k == 'any' )
        {
          $ok_any = true ;
          break ;
        }
      }
    }
    return ( $ok_ower || $ok_role || $ok_any ) ;
  }

// set a random password 

  /*
  function resolveLocationID ( $dep )
  {
    global $const_BIB ;
  
    if      ( in_array ( $dep , $const_BIB['DMI'   ]  ) )  {  $bibLoc = 'DMI' ;  }
    else if ( in_array ( $dep , $const_BIB['LS'    ]  ) )  {  $bibLoc = 'LS' ;  }
    else if ( in_array ( $dep , $const_BIB['TWI1'  ]  ) )  {  $bibLoc = 'TWI1' ;  }
    else if ( in_array ( $dep , $const_BIB['TWI2'  ]  ) )  {  $bibLoc = 'TWI2' ;  }
    else if ( in_array ( $dep , $const_BIB['SP'    ]  ) )  {  $bibLoc = 'TWI2' ;  }
    else                                                   {  $bibLoc = 'XX' ;  }
    return $bibLoc ;
  }
*/
  
  
####################### --- MAIL --- #######################

####################### --- TOOLS --- #######################

  function check_permission ( $INPUT )
  {
    global $CONST_actions_info ;
    #--------------------------------------------------------------------------------------------------------------------
    if ( ! $util -> check_acl ( $CONST_actions_info[ $INPUT[ 'work' ][ 'action' ] ][ 'acl' ] , $INPUT[ 'work' ][ 'item' ] , $INPUT[ 'work' ][ 'id' ] ) )
    {
      user_error ( "Permission denied: action " . $INPUT[ 'work' ][ 'action' ] . " on item type " . $INPUT[ 'work' ][ 'item' ] . " for: " . $_SESSION[ 'user' ][ 'role_name' ] . " / " . $_SESSION[ 'user' ][ 'surname' ] . " " , E_USER_ERROR ) ;
    }
  }

  function getInput ( )
  {
    global $CONST_actions_info ;

    $this -> getGET_EMIL_Values () ; /* Paramterübergabe von EMIL  */
    
    $INPUT[ 'work' ][ 'item'          ] = '' ;
    $INPUT[ 'work' ][ 'action'        ] = '' ;
    $INPUT[ 'work' ][ 'todo'          ] = '' ;
    $INPUT[ 'work' ][ 'item'          ] = '' ;
    $INPUT[ 'work' ][ 'collection_id' ] = '' ;
    $INPUT[ 'work' ][ 'mode'          ] = '' ;
    $INPUT[ 'work' ][ 'shelf_remain'  ] = '' ;
    
    $INPUT[ 'work' ] = array_merge ( $INPUT[ 'work' ] , $_GET , $_POST ) ;
    
    if ( isset( $_SESSION[ 'user' ]['tmpcat']) )  #  Hook, wenn Staff ELSE betritt, wird sofort die LR Übersicht angezeigt (anstelle des SA des LR)
    {
      $INPUT[ 'work' ]['categories'] = 1;
      unset($_SESSION[ 'user' ]['tmpcat'] );
    } 

    if ( isset ( $INPUT[ 'work' ][ 'action' ] ) AND isset ( $CONST_actions_info[ $INPUT[ 'work' ][ 'action' ] ] [ 'input' ] ) )
    {
      $INPUT[ 'work' ] = array_merge ( $INPUT[ 'work' ] , $CONST_actions_info[ $INPUT[ 'work' ][ 'action' ] ] [ 'input' ] ) ;   /* get mode */
    }

   #  deb( $INPUT[ 'work' ][ 'collection_id' ]);
    
    
    if (            $INPUT[ 'work' ][ 'item'          ] == '' ) { $INPUT[ 'work' ][ 'item'        ] = 'collection'  ;   }                     ## Standard work.item ist 'collection'
    if (    !isset( $INPUT[ 'work' ][ 'action'        ]   )   ) { $INPUT[ 'work' ][ 'action'      ] = 'b_coll_edit' ;   }                     ## Standard work.action ist 'b_coll_edit'
    if ( isset(  $_SESSION[ 'work' ][ 'last_page'     ]   )   ) { $INPUT[ 'work' ][ 'last_page'   ] = $_SESSION[ 'work' ][ 'last_page'   ]; } /* 'Lastpage' wird *immer* in SESSION übernommen*/
    if (!isset(     $INPUT[ 'work' ][ 'document_id'   ]   )   ) { $INPUT[ 'work' ][ 'document_id' ] = $_SESSION[ 'work' ][ 'document_id' ]; } /* 'document_id' wird *immer* in SESSION übernommen*/
    if (            $INPUT[ 'work' ][ 'collection_id' ] != '' ) { $_SESSION[ 'coll' ] = $this->sql->doCollectionExist(  $INPUT[ 'work' ][ 'collection_id' ] ); } /* Wenn coll_id übergegen wird, wird dieser der aktive SA  */
    
    $_SESSION[ 'work' ] = $INPUT[ 'work' ] ;
    return $INPUT ;
  }

  function getGET_EMIL_Values ( )
  {
    if ( isset ( $_GET[ 'sn' ] ) )    ##  Initiale Parameterübergabe über  Moodle ##
    {
      if ( isset ( $_GET[ 'sn' ] ) )  { $Course[ 'shortname'   ] = rawurldecode ( base64_decode ( $_GET[ 'sn' ] ) ) ;  }  else   { echo "<br>ERROR: no 'course shortname' " ;  }
      if ( isset ( $_GET[ 'cn' ] ) )  { $Course[ 'fullname'    ] = rawurldecode ( base64_decode ( $_GET[ 'cn' ] ) ) ;  }  else   { echo "<br>ERROR: no 'course fullname'  " ;  }
      if ( isset ( $_GET[ 'm'  ] ) )  { $IDMuser[ 'mail'       ] = rawurldecode ( base64_decode ( $_GET[ 'm'  ] ) ) ;  }  else   { echo "<br>ERROR: no 'mail'             " ;  }
      if ( isset ( $_GET[ 'fn' ] ) )  { $IDMuser[ 'vorname'    ] = rawurldecode ( base64_decode ( $_GET[ 'fn' ] ) );   }  else   { echo "<br>ERROR: no 'vorname'          " ;  }
      if ( isset ( $_GET[ 'ln' ] ) )  { $IDMuser[ 'nachname'   ] = rawurldecode ( base64_decode ( $_GET[ 'ln' ] ) ) ;  }  else   { echo "<br>ERROR: no 'nachname'         " ;  }
      if ( isset ( $_GET[ 'u'  ] ) )  { $IDMuser[ 'akennung'   ] = rawurldecode ( base64_decode ( $_GET[ 'u'  ] ) ) ;  }  else   { echo "<br>ERROR: no 'akennung'         " ;  }
      if ( isset ( $_GET[ 'id' ] ) )  { $IDMuser[ 'matrikelnr' ] = rawurldecode ( base64_decode ( $_GET[ 'id' ] ) ) ;  }  else   { echo "<br>ERROR: no 'matrikelnr'       " ;  }
      if ( isset ( $_GET[ 'fa' ] ) )  { $IDMuser[ 'fakultaet'  ] = rawurldecode ( base64_decode ( $_GET[ 'fa' ] ) ) ;  }  else   { echo "<br>ERROR: no 'studiengang'      " ;  }
      if ( isset ( $_GET[ 'dp' ] ) )  { $IDMuser[ 'department' ] = rawurldecode ( base64_decode ( $_GET[ 'dp' ] ) ) ;  }  else   { echo "<br>ERROR: no 'department'       " ;  }
      if ( isset ( $_GET[ 'sx' ] ) )  { $IDMuser[ 'sex'        ] = rawurldecode ( base64_decode ( $_GET[ 'sx' ] ) ) ;  }  else   { echo "<br>ERROR: no 'sex'              " ;  }
      if ( isset ( $_GET[ 'ro' ] ) )  { $IDMuser[ 'role'       ] = rawurldecode ( base64_decode ( $_GET[ 'ro' ] ) ) ; 
                                        $IDMuser[ 'role_encode'] = $_GET[ 'ro' ] ;                                     }  else   { echo "<br>ERROR: no 'role'             " ;  }

 	    if ( $IDMuser[ 'sex' ] == 2 )   { $IDMuser[ 'sex' ] = 'w' ;  }
      else                            { $IDMuser[ 'sex' ] = 'm' ;  }
 
      
      #-------------------------------------------------------------------------# Alle Nuter aus Department 106 = HIBS sind Bib-Mitarbeiter mit Editrechten in ELSE 
      if ( $IDMuser[ 'department' ] == 106 )                                    
      { $IDMuser[ 'role'   ] = 2; 
        $IDMuser[ 'tmpcat' ] = 1; 
      }         

      #-----------------------------------------------------------------------------
      if ( $IDMuser[ 'role' ] == 1 OR $IDMuser[ 'role' ] == 2 OR $IDMuser[ 'role' ] == 3 )
      { $this -> initUpdateUser ( $IDMuser ) ;                                 
        $this -> initUpdateCollection ( $Course , $IDMuser ) ;
      }

      #-------------------------------------------------------------------------# echo "--Semesterapparat existiert bereits--";  
      if ( $this->sql -> doCollectionExist ( $Course[ 'shortname' ] ) )
      {                                                                             
        $IC[ 'title'         ] = $Course[ 'fullname' ] ;
        $IC[ 'location_id'   ] = $_SESSION[ 'DEP2BIB' ][ $IDMuser[ 'department' ] ] [ 'BibID' ];
        $IC[ 'categories_id' ] = $IDMuser[ 'department' ] ;
        $IC[ 'title_short'   ] = $Course[ 'shortname'   ] ;
      }
      
      #-----------------------------------------------------------------------------
      $IDMuser[ 'role_name' ] = $this->sql -> getRoleName ( $IDMuser[ 'role' ] ) ;                ## echo "--Transformiert EMIL-Rechte zu ELSE Rollennamen --";  

      $_SESSION[ 'user' ] = $IDMuser ;
      $_SESSION[ 'coll' ] = $IC ;
    }
  }
/*
  function getLTIValues ( $I )
  {
    if ( isset ( $IW[ 'work' ][ 'lti_version' ] ) )
    {
      if      ( stristr ( $IW[ 'work' ][ 'roles' ] , 'Instructor' ) )  {  $IW[ 'user' ][ 'role_id' ] = 3 ;   }
      else if ( stristr ( $IW[ 'work' ][ 'roles' ] , 'Learner'    ) )  {  $IW[ 'user' ][ 'role_id' ] = 4 ;   }

      $IW[ 'work' ][ 'roles'                              ] ; # => Instructor,urn:lti:sysrole:ims/lis/Administrator,urn:lti:instrole:ims/lis/Administrator
      $IW[ 'work' ][ 'context_id'                         ] ; # => 2
      $IW[ 'work' ][ 'context_label'                      ] ; # => LS01
      $IW[ 'work' ][ 'context_title'                      ] ; # => Lebens Stiel
      $IW[ 'work' ][ 'lis_person_name_given'              ] ; # => Admin
      $IW[ 'work' ][ 'lis_person_name_family'             ] ; # => User
      $IW[ 'work' ][ 'lis_person_name_full'               ] ; # => Admin User
      $IW[ 'work' ][ 'ext_user_username'                  ] ; # => admin
      $IW[ 'work' ][ 'lis_person_contact_email_primary'   ] ; # => werner.welte@haw-hamburg.de
    }
  }
*/
  function initUpdateUser ( $IDMuser )    # EMIL Nutzer ist schon ELSE Nutzer 
  {
    if    ( $this->sql -> checkUserExistence ( $IDMuser[ 'akennung' ] ) ) { $this->sql -> updateUser ( $IDMuser ) ; }  # echo "- Bestehender USER (UPDATE DB )-";
    else                                                                  { $this->sql -> initUser   ( $IDMuser ) ; }  # echo "- NEUER USER (INIT DB )-";

    $ans = $this->sql -> getUserHSK ( $IDMuser[ 'akennung' ] ) ;

    $_SESSION[ 'user' ] = $ans[ 0 ] ;

    switch ( $IDMuser[ 'role' ] ) {
      case 1:  $_SESSION[ 'work' ][ 'mode' ] = 'admin' ;  break ;
      case 2:  $_SESSION[ 'work' ][ 'mode' ] = 'staff' ;  break ;
      case 3:  $_SESSION[ 'work' ][ 'mode' ] = 'edit'  ;  break ;
      default: $_SESSION[ 'work' ][ 'mode' ] = 'guest' ;
    }
  }

  function initUpdateCollection ( $Course , $IDMuser )
  {
    $collection = $this->sql -> doCollectionExist ( $Course[ 'shortname' ] ) ;

    if ( isset ( $collection[ 'id' ] ) )                                        #  echo "Semesterapparat existiert schon"; 
    {                                                                           
      $IC[ 'title' ] = $Course[ 'fullname' ] ;
      $IC[ 'location_id' ] = $_SESSION[ 'DEP2BIB' ][ $IDMuser[ 'department' ] ] [ 'BibID' ] ;
      $IC[ 'title_short' ] = $Course[ 'shortname' ] ;

      $this->sql -> updateColMetaData ( $IC) ;                                 #  echo "- Bestehender Semesterapparat (UPDATE DB )-";
    }
    else                                                                        #  echo "Semesterapparat existiert NOCH NICHT "; 
    {
      $IC[ 'title' ] = $Course[ 'fullname' ] ;
      $IC[ 'title_short' ] = $Course[ 'shortname' ] ;
      $IC[ 'location_id' ] = $_SESSION[ 'DEP2BIB' ][ $IDMuser[ 'department' ] ] [ 'BibID' ]  ;
      $IC[ 'expiry_date' ] = $this -> get_new_expiry_date () ;
      $IC[ 'notes_to_studies' ] = '' ;
      $IC[ 'categories_id' ] = $IDMuser[ 'department' ] ;

      $this->sql -> initCollection ( $IC ) ;                                    #  echo "- NEUER Semesterapparat (INIT DB )-";
    }
  }
 
  
  function sendBIB_APmails()
  {
    $conf = getConf();
    $BIB_Anrede  = $conf[ 'BIB_Anrede' ]; #= "Liebe ELSE/HIBS Mitarbeiterin  \r\n\r\n";
    $BIB_Gruss   = $conf[ 'BIB_Gruss'  ]; #= "\r\n\r\nIhr ELSE Server \r\n\r\n http://www.elearning.haw-hamburg.de/mod/else/view.php?id=443297  \r\n\r\n";
    
    $mailInfos =     $this->sql -> getAdminEmailInfos ( ) ;
   
    #deb($mailInfos,1);
    foreach ($mailInfos as $mi)
    { 
      $message ="";
      $message .= $BIB_Anrede;
      if ( $mi[9] > 0 OR $mi[1] > 0 )
      { 
        {                    $subject  = 'ELSE: Statusbericht -- '.$mi['BibID'] . " -- [ N:".$mi[1]." ] [ K:".$mi[9]." ]";
		                         $message .= "ELSE Statusbericht: \r\n\r\n";
          if(  $mi[1] > 0 ) {$message .= " Neu bestellt: ".$mi[1]. "\r\n"; }
          if(  $mi[9] > 0 ) {$message .= " Kaufvorschlag: ".$mi[9]. "\r\n"; }
        }

        $message .= $BIB_Gruss;

        $to =  $mi[ 'BibAPMail' ];
        
	    	$this->sendAMail($to, $subject, $message);
      }
    }  
  }
    
  function sendAMail($to, $subject, $message)
  {
    $conf = getConf();
    $BIB_BCC  = $conf['BIB_BCC'];
    $BIB_FROM = $conf['BIB_FROM'];
    $BIB_RPTO = $conf['BIB_RPTO'];
    
    $bcc      = $BIB_BCC ;  # 'daniela.mayer@haw-hamburg.de, werner.welte@haw-hamburg.de' ;
    $from     = $BIB_FROM;  # 'ELSE-noreply@haw-hamburg.de' ;
    $rpto     = $BIB_RPTO;  # 'werner.welte@haw-hamburg.de';

    $header  = 'From: '         . $from . "\r\n" ;
    $header .= 'Reply-To: '     . $rpto . "\r\n" ;
    $header .= 'Bcc: '          . $bcc  . "\r\n" ;
    $header .= "Mime-Version: 1.0\r\n" ;
    $header .= "Content-type: text/plain; charset=iso-8859-1" ;
    $header .= 'X-Mailer: PHP/' . phpversion () ;
	
    deb ( $to . $subject . $message . $header,1 ) ;

    $sendok = mail ( $to , $subject , $message , $header ) ;
    
    if ( $sendok )
    { $linkTxt = $subject ;
    }
    else
    { $linkTxt = "ERROR: Mail nicht versendet! ". $subject ;
    }
	  echo date("d.m.Y")." ".$linkTxt."\r\n";
  }
  
  /*
    function expire ()
    {

    $input = array ( "item" => "collection" , "state" => "obsolete" ) ;

    $colls = $this -> sql -> getExpiredCollections () ;                         #  ALLE Semesterapparate, die *aktiv* UND deren Zeit inzwischen abgelaufen sind #
    #deb ( $colls , 1 ) ;
    foreach ( $colls as $c ) {
    $input[ 'id' ] = $c[ 'id' ] ;                                               #  werden auf neuen Status *obsolete* gesetzt  #
    $this -> set_item_state ( $input ) ;
    }

    $param = array (
    "tables" => "collection AS c,state AS s" ,
    "columns" => "c.id AS id" ,
    'cond' => "(c.state_id = s.id)      AND      (s.name = 'obsolete')     AND     DATE_ADD(c.expiry_date, INTERVAL 14 DAY) <= NOW()"
    ) ;

    $colls = $this -> sql -> sql_query ( 'select' , $param ) ;                   #  ALLE Semesterapparate, die *obsolete* UND deren Zeit inzwischenn *SEIT  min. 14 TAGEN* abgelaufen sind#
    $input = array ( "item" => "collection" , "state" => "inactive" ) ;

    foreach ( $colls as $c )                                                    # werden auf neuen Status *inactive* gesetzt  #
    {


    $input[ 'id' ] = $c[ 'id' ] ;
    $this -> set_item_state ( $input ) ;
    }

    }
   */
  
  
}

?>
