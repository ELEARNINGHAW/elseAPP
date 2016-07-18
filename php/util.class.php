<?php

class Util   /// \brief check user input
{
  var  $sql ;
  var  $CFG ;

  function Util ( $CFG, $SQL )
  {
    $this->sql        = $SQL;
    $this->CFG        = $CFG;
  }

function getInput ( )
{

if ( !isset ( $_SESSION[ 'DEP2BIB'] ) )                                     # Ermittelt die zuständige FachBib zum jeweiligen Department 
{
  $this->HAWdb          = new HAW_DB();                                     # Aus der SQLite DB
  $_SESSION['DEP2BIB' ] =  $this->HAWdb->getDEP2BIB();
  $_SESSION['FAK'     ] =  $this->HAWdb->getAllFak();
  $_SESSION['FACHBIB' ] =  $this->HAWdb->getAllFachBib();
}   
  
if ( isset ( $_GET[ 'uid' ] ) )    ##  Initiale Parameterübergabe über  Moodle ## // Kurskurzname 
{  
  $this -> getGET_EMIL_Values () ; /* Paramterübergabe von EMIL  */
}
#print_r($_SESSION['user']);
if ( ! isset($_SESSION['user']['role'] ) ) { die('NO ROLE'); } 

if (isset( $_SESSION[ 'work' ][ 'document_id' ] ) ) { $INPUT[ 'work' ][ 'document_id'      ] = $_SESSION[ 'work' ][ 'document_id' ]; } else {  $INPUT[ 'work' ][ 'document_id'    ] = 0; } /* Standard 'document_id' des zuletzt bearbeiteten Mediums, wird *immer* in SESSION übernommen*/
if(isset( $_GET[ 'document_id'      ] ) )           { $INPUT[ 'work' ][ 'document_id'      ] = $_GET[  'document_id'   ]; }
                                                      $INPUT[ 'work' ][ 'collection_id'    ] = $_SESSION[ 'coll' ][ 'collection_id' ];     ## Standard work.collection_id = Kurzname aus EMIL-RAUM   #POST, GET
if(isset( $_GET[ 'collection_id'    ] ) )           { $INPUT[ 'work' ][ 'collection_id'    ] = $_GET[ 'collection_id'     ]; }
if(  isset( $_GET[ 'collection_64'  ] ) AND  $_GET[ 'collection_64'  ] !='undefined' )       { $INPUT[ 'work' ][ 'collection_id'    ] = $this->dc($_GET[ 'collection_64'     ]); }  ## collectionID aus jquery document-resort
if(isset( $_GET[ 'item'             ] ) )           { $INPUT[ 'work' ][ 'item'             ] = $_GET[ 'item'              ]; } else { $INPUT[ 'work' ][ 'item'             ] = '' ;}
if(isset( $_GET[ 'action'           ] ) )           { $INPUT[ 'work' ][ 'action'           ] = $_GET[ 'action'            ]; } else { $INPUT[ 'work' ][ 'action'           ] = '' ;}
if(isset( $_GET[ 'todo'             ] ) )           { $INPUT[ 'work' ][ 'todo'             ] = $_GET[ 'todo'              ]; } else { $INPUT[ 'work' ][ 'todo'             ] = '' ;}
if(isset( $_GET[ 'mode'             ] ) )           { $INPUT[ 'work' ][ 'mode'             ] = $_GET[ 'mode'              ]; } else { $INPUT[ 'work' ][ 'mode'             ] = '' ;}
if(isset( $_GET[ 'ppn'              ] ) )           { $INPUT[ 'work' ][ 'ppn'              ] = $_GET[ 'ppn'               ]; } else { $INPUT[ 'work' ][ 'ppn'              ] = '' ;}
if(isset( $_GET[ 'doc_type_id'      ] ) )           { $INPUT[ 'work' ][ 'doc_type_id'      ] = $_GET[ 'doc_type_id'       ]; } else { $INPUT[ 'work' ][ 'doc_type_id'      ] = '' ;}                                                                                
if(isset( $_GET[ 'redirect'         ] ) )           { $INPUT[ 'work' ][ 'redirect'         ] = $_GET[ 'redirect'          ]; } else { $INPUT[ 'work' ][ 'redirect'         ] = '' ;}                                                                                                
if(isset( $_GET[ 'physicaldesc'     ] ) )           { $INPUT[ 'work' ][ 'physicaldesc'     ] = $_GET[ 'physicaldesc'      ]; } else { $INPUT[ 'work' ][ 'physicaldesc'     ] = ' ' ;}                                                                                                
if(isset( $_GET[ 'title'            ] ) )           { $INPUT[ 'work' ][ 'title'            ] = $_GET[ 'title'             ]; } else { $INPUT[ 'work' ][ 'title'            ] = '' ;}                                                                                                
if(isset( $_GET[ 'author'           ] ) )           { $INPUT[ 'work' ][ 'author'           ] = $_GET[ 'author'            ]; } else { $INPUT[ 'work' ][ 'author'           ] = '' ;}                                                                                                
if(isset( $_GET[ 'publisher'        ] ) )           { $INPUT[ 'work' ][ 'publisher'        ] = $_GET[ 'publisher'         ]; } else { $INPUT[ 'work' ][ 'publisher'        ] = '' ;}                                                                                                
if(isset( $_GET[ 'year'             ] ) )           { $INPUT[ 'work' ][ 'year'             ] = $_GET[ 'year'              ]; } else { $INPUT[ 'work' ][ 'year'             ] = '' ;}                                                                                                
if(isset( $_GET[ 'volume'           ] ) )           { $INPUT[ 'work' ][ 'volume'           ] = $_GET[ 'volume'            ]; } else { $INPUT[ 'work' ][ 'volume'           ] = '' ;}                                                                                                
if(isset( $_GET[ 'edition'          ] ) )           { $INPUT[ 'work' ][ 'edition'          ] = $_GET[ 'edition'           ]; } else { $INPUT[ 'work' ][ 'edition'          ] = '' ;}                                                                                                
if(isset( $_GET[ 'signature'        ] ) )           { $INPUT[ 'work' ][ 'signature'        ] = $_GET[ 'signature'         ]; } else { $INPUT[ 'work' ][ 'signature'        ] = '' ;}                                                                                                
if(isset( $_GET[ 'notes_to_studies' ] ) )           { $INPUT[ 'work' ][ 'notes_to_studies' ] = $_GET[ 'notes_to_studies'  ]; } else { $INPUT[ 'work' ][ 'notes_to_studies' ] = '' ;}                                                                                                
if(isset( $_GET[ 'shelf_remain'     ] ) )           { $INPUT[ 'work' ][ 'shelf_remain'     ] = $_GET[ 'shelf_remain'      ]; } else { $INPUT[ 'work' ][ 'shelf_remain'     ] = '' ;}                                                                                                
if(isset( $_GET[ 'notes_to_staff'   ] ) )           { $INPUT[ 'work' ][ 'notes_to_staff'   ] = $_GET[ 'notes_to_staff'    ]; } else { $INPUT[ 'work' ][ 'notes_to_staff'   ] = '' ;} 
if(isset( $_GET[ 'user_id'          ] ) )           { $INPUT[ 'work' ][ 'user_id'          ] = $_GET[ 'user_id'           ]; } else { $INPUT[ 'work' ][ 'user_id'          ] = '' ;} 
if(isset( $_GET[ 'bib_id'           ] ) )           { $INPUT[ 'work' ][ 'bib_id'           ] = $_GET[ 'bib_id'            ]; } else { $INPUT[ 'work' ][ 'bib_id'           ] = '' ;} 
if(isset( $_GET[ 'department_id'    ] ) )           { $INPUT[ 'work' ][ 'department_id'    ] = $_GET[ 'department_id'     ]; } else { $INPUT[ 'work' ][ 'department_id'    ] = '' ;} 
if(isset( $_GET[ 'sortorder'        ] ) )           { $INPUT[ 'work' ][ 'sortorder'        ] = $_GET[ 'sortorder'         ]; } else { $INPUT[ 'work' ][ 'sortorder'        ] = '' ;} 
if(isset( $_GET[ 'expiry_date'      ] ) )           { $INPUT[ 'work' ][ 'expiry_date'      ] = $_GET[ 'expiry_date'       ]; } else { $INPUT[ 'work' ][ 'expiry_date'      ] = '' ;} 
if(isset( $_GET[ 'ro'               ] ) )           { $INPUT[ 'work' ][ 'ro'               ] = $_GET[ 'ro'                ]; } else { $INPUT[ 'work' ][ 'ro'               ] = '' ;}
if(isset( $_GET[ 'categories'       ] ) )           { $INPUT[ 'work' ][ 'categories'       ] = $_GET[ 'categories'        ]; } else { $INPUT[ 'work' ][ 'categories'       ] = '' ;} 
if(isset( $_GET[ 'letter'           ] ) )           { $INPUT[ 'work' ][ 'letter'           ] = $_GET[ 'letter'            ]; } else { $INPUT[ 'work' ][ 'letter'           ] = '' ;} 
if(isset( $_GET[ 'to'               ] ) )           { $INPUT[ 'work' ][ 'to'               ] = $_GET[ 'to'                ]; } else { $INPUT[ 'work' ][ 'to'               ] = '' ;} 
if(isset( $_GET[ 'txt'              ] ) )           { $INPUT[ 'work' ][ 'txt'              ] = $_GET[ 'txt'               ]; } else { $INPUT[ 'work' ][ 'txt'              ] = '' ;} 
if(isset( $_GET[ 'from'             ] ) )           { $INPUT[ 'work' ][ 'from'             ] = $_GET[ 'from'              ]; } else { $INPUT[ 'work' ][ 'from'             ] = '' ;} 
    
if ( isset( $_SESSION[ 'work' ][ 'last_page'   ] ) ) { $INPUT[ 'work'    ][ 'last_page'   ] = $_SESSION[ 'work' ][ 'last_page'   ]; } /* 'Lastpage' wird *immer* in SESSION übernommen*/

if ( isset ( $INPUT[ 'work' ][ 'action' ] ) AND isset ( $this->CFG->C->CONST_actions_info[ $INPUT[ 'work' ][ 'action' ] ] [ 'input' ] ) )
{  $INPUT[ 'work' ] = array_merge ( $INPUT[ 'work' ] ,  $this->CFG->C->CONST_actions_info[ $INPUT[ 'work' ][ 'action' ] ] [ 'input' ] ) ;   /* get mode */
}

if ( isset( $_SESSION[ 'user' ]['tmpcat']) AND ( $INPUT[ 'work' ]['action'] != 'b_coll_delete' ) )   #  Hook, wenn Staff ELSE betritt, wird sofort die LR Übersicht angezeigt (anstelle des SA des LR)
{  $INPUT[ 'work' ][ 'categories'    ] = 1;    # Globale Liste wird angezeigt 
   $INPUT[ 'work' ][ 'action'        ] = null; # anstelle des Semesterapparates
   $INPUT[ 'work' ][ 'collection_id' ] = null; # des aktuellen EMIL-Raums
  
   unset($_SESSION[ 'user' ]['tmpcat'] );
} 

# $INPUT[ 'work' ][ 'mode'          ] = '' ; ## z.B printversion
  
$_SESSION[ 'work' ] = $INPUT[ 'work' ] ;

if (isset($INPUT[ 'work' ][ 'collection_id'   ]))
$_SESSION[ 'coll' ] = $this->sql->getCollectionData( $INPUT[ 'work' ][ 'collection_id'   ] );  /* Wenn coll_id übergegen wird, wird dieser der aktive SA  */

return $INPUT ;
}

function dc( $str )  # decode GET input
{
  return  rawurldecode ( base64_decode( $str ) );
}

function getGET_EMIL_Values ( )
{ $dm = false;
    if ( isset ( $_GET[ 'cid'] ) )  { $Course[ 'id'          ] = $this->dc ( $_GET[ 'cid']  ) ; }  else  {                                if($dm) echo "<br>ERROR: no 'course ID'        " ;  }
    if ( isset ( $_GET[ 'sn' ] ) )  { $Course[ 'shortname'   ] = $this->dc ( $_GET[ 'sn' ]  ) ; }  else  { $Course[ 'shortname'   ] = "";  if($dm) echo "<br>ERROR: no 'course shortname' " ;  }
    if ( isset ( $_GET[ 'cn' ] ) )  { $Course[ 'fullname'    ] = $this->dc ( $_GET[ 'cn' ]  ) ; }  else  {                                if($dm) echo "<br>ERROR: no 'course fullname'  " ;  }
    if ( isset ( $_GET[ 'uid'] ) )  { $IDMuser[ 'id'         ] = $this->dc ( $_GET[ 'uid']  ) ; }  else  { $IDMuser[ 'id'         ] = ""; if($dm) echo "<br>ERROR: no 'user ID'          " ;  }
    if ( isset ( $_GET[ 'm'  ] ) )  { $IDMuser[ 'mail'       ] = $this->dc ( $_GET[ 'm'  ]  ) ; }  else  { $IDMuser[ 'mail'       ] = ""; if($dm) echo "<br>ERROR: no 'mail'             " ;  }
    if ( isset ( $_GET[ 'fn' ] ) )  { $IDMuser[ 'vorname'    ] = $this->dc ( $_GET[ 'fn' ]  ) ; }  else  { $IDMuser[ 'vorname'    ] = ""; if($dm) echo "<br>ERROR: no 'vorname'          " ;  }
    if ( isset ( $_GET[ 'ln' ] ) )  { $IDMuser[ 'nachname'   ] = $this->dc ( $_GET[ 'ln' ]  ) ; }  else  { $IDMuser[ 'nachname'   ] = ""; if($dm) echo "<br>ERROR: no 'nachname'         " ;  }
    if ( isset ( $_GET[ 'u'  ] ) )  { $IDMuser[ 'hawaccount' ] = $this->dc ( $_GET[ 'u'  ]  ) ; }  else  { $IDMuser[ 'akennung'   ] = ""; if($dm) echo "<br>ERROR: no 'hawaccount'       " ;  }
    if ( isset ( $_GET[ 'id' ] ) )  { $IDMuser[ 'matrikelnr' ] = $this->dc ( $_GET[ 'id' ]  ) ; }  else  { $IDMuser[ 'matrikelnr' ] = ""; if($dm) echo "<br>ERROR: no 'matrikelnr'       " ;  }
    if ( isset ( $_GET[ 'fa' ] ) )  { $IDMuser[ 'fakultaet'  ] = $this->dc ( $_GET[ 'fa' ]  ) ; }  else  { $IDMuser[ 'fakultaet'  ] = ""; if($dm) echo "<br>ERROR: no 'studiengang'      " ;  }
    if ( isset ( $_GET[ 'dp' ] ) )  { $IDMuser[ 'department' ] = $this->dc ( $_GET[ 'dp' ]  ) ; }  else  { $IDMuser[ 'department' ] = ""; if($dm) echo "<br>ERROR: no 'department'       " ;  }
    if ( isset ( $_GET[ 'sx' ] ) )  { $IDMuser[ 'sex'        ] = $this->dc ( $_GET[ 'sx' ]  ) ; }  else  { $IDMuser[ 'sex'        ] = ""; if($dm) echo "<br>ERROR: no 'sex'              " ;  }
    if ( isset ( $_GET[ 'ro' ] ) )  { $IDMuser[ 'role'       ] = $this->dc ( $_GET[ 'ro' ]  ) ;              
                                      $IDMuser[ 'role_encode'] = $_GET[ 'ro' ] ;                                    }  else  { $IDMuser[ 'role'       ] = ""; $IDMuser[ 'role_encode'] = "";  echo "<br>ERROR: no 'role'  " ;  }

    #-------------------------------------------------------------------------# Alle Nuter aus Department 102 = HIBS sind Bib-Mitarbeiter mit Editrechten in ELSE 
    if ( $IDMuser[ 'department' ] == 102 )                                    
    {                                 $IDMuser[ 'role'   ] = 2; 
                                      $IDMuser[ 'tmpcat' ] = 1; 
    }   
                                      $IDMuser[ 'role_name' ] = $this->sql -> getRoleName ( $IDMuser[ 'role' ] ) ;   ## echo "--Transformiert EMIL-Rechte zu ELSE-Rechte/Rollennamen --";  

    #-------------------------------------------------------------------------
                                      
    if ( $IDMuser[ 'sex' ] == 2 )   { $IDMuser[ 'sex' ] = 'w' ;  }
    else                            { $IDMuser[ 'sex' ] = 'm' ;  }
                                  
    
    
    #-------------------------------------------------------------------------
    if ( ! isset( $_SESSION[ 'DEP2BIB' ][ $IDMuser[ 'department' ]  ] ) )
    {                                 $IDMuser[ 'bib'] = $_SESSION['DEP2BIB' ][ 101  ];                       // Preset auf FachBib = HAW, falls dem User kein Department zugeordnet ist.
    } 
    else
    {                                 $IDMuser[ 'bib'] = $_SESSION['DEP2BIB' ][ $IDMuser[ 'department' ]  ];
    }

    if ( $IDMuser[ 'role' ] == 1 OR $IDMuser[ 'role' ] == 2 OR $IDMuser[ 'role' ] == 3 )
    { $this -> initUpdateUser ( $IDMuser ) ;                                 
      $this -> initUpdateCollection ( $Course , $IDMuser ) ;
    }
    
    #-------------------------------------------------------------------------# echo "--Semesterapparat existiert bereits--";  
    if ( $this->sql -> getCollectionData ( $Course[ 'shortname' ] ) )
    {                                                                             
      $IC[ 'categories_id' ] = $IDMuser[ 'department' ] ;
      $IC[ 'title'         ] = $Course[  'fullname'   ] ;
      $IC[ 'title_short'   ] = $Course[  'shortname'  ] ;
      $IC[ 'collection_id' ] = $Course[  'shortname'  ] ;
      $IC[ 'bib_id'        ] = $_SESSION[ 'DEP2BIB'   ][ $IDMuser[ 'department' ] ] [ 'BibID' ];
    
      $_SESSION[ 'coll' ] = $IC ;
    }
    #-----------------------------------------------------------------------------

    $_SESSION[ 'user' ] = $IDMuser ;

} 


function initUpdateUser ( $IDMuser )    # EMIL Nutzer ist schon ELSE Nutzer 
{
  if    ( $this->sql -> checkUserExistence ( $IDMuser[ 'hawaccount' ] ) ) { $this->sql -> updateUser ( $IDMuser ) ; }  # echo "- Bestehender USER (UPDATE DB )-";
  else                                                                    { $this->sql -> initUser   ( $IDMuser ) ; }  # echo "- NEUER USER (INIT DB )-";

  $ans = $this->sql -> getUserHSK ( $IDMuser[ 'hawaccount' ] ) ;

  $_SESSION[ 'user' ] = $ans[ 0 ] ;

  switch ( $IDMuser[ 'role' ] )
  {
    case 1:  $_SESSION[ 'work' ][ 'mode' ] = 'admin' ;  break ;
    case 2:  $_SESSION[ 'work' ][ 'mode' ] = 'staff' ;  break ;
    case 3:  $_SESSION[ 'work' ][ 'mode' ] = 'edit'  ;  break ;
    default: $_SESSION[ 'work' ][ 'mode' ] = 'guest' ;
  }
}

function initUpdateCollection ( $Course , $IDMuser )
{
  $collection = $this->sql -> getCollectionData ( $Course[ 'shortname' ] ) ;

  if ( isset ( $collection[ 'id' ] ) )                                          #  echo "Semesterapparat existiert schon"; 
  {                                                                           
    $this->sql -> updateColMetaData ( $Course , $IDMuser) ;                     #  echo "- Bestehender Semesterapparat (UPDATE DB )-";
  }
  else                                                                          #  echo "Semesterapparat existiert NOCH NICHT "; 
  {
    $Course['expiry_date'] = $this -> get_new_expiry_date () ; 

    $this->sql -> initCollection ( $Course , $IDMuser ) ;                       #  echo "- NEUER Semesterapparat (INIT DB )-";
  }
}

function sendBIB_APmails()
{
  $conf = $this->CFG->getConf();
  $BIB_Anrede  = $conf[ 'BIB_Anrede' ]; #= "Liebe ELSE/HIBS Mitarbeiterin  \r\n\r\n";
  $BIB_Gruss   = $conf[ 'BIB_Gruss'  ]; #= "\r\n\r\nIhr ELSE Server \r\n\r\n http://www.elearning.haw-hamburg.de/mod/else/view.php?id=443297  \r\n\r\n";

  $mailInfos =     $this->sql -> getAdminEmailInfos ( ) ;

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
  $conf = $this->CFG->getConf();
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

  $sendok = mail ( $to , $subject , $message , $header ) ;

  if ( $sendok )
  { $linkTxt = $subject ;
  }
  else
  { $linkTxt = "ERROR: Mail nicht versendet! ". $subject ;
  }
  echo date("d.m.Y")." ".$linkTxt."\r\n";
}

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

####################### --- TOOLS --- #######################

function check_permission ( $INPUT )
{
  #--------------------------------------------------------------------------------------------------------------------
  if ( ! $util -> check_acl ( $this->CFG->C->CONST_actions_info[ $INPUT[ 'work' ][ 'action' ] ][ 'acl' ] , $INPUT[ 'work' ][ 'item' ] , $INPUT[ 'work' ][ 'id' ] ) )
  {
    user_error ( "Permission denied: action " . $INPUT[ 'work' ][ 'action' ] . " on item type " . $INPUT[ 'work' ][ 'item' ] . " for: " . $_SESSION[ 'user' ][ 'role_name' ] . " / " . $_SESSION[ 'user' ][ 'surname' ] . " " , E_USER_ERROR ) ;
  }
}


  function check_host()
  {
    /* (Assuming session already started) */
    if(isset($_SESSION['referrer'])){
    // Get existing referrer
    $referrer   = $_SESSION['referrer'];

    } elseif(isset($_SERVER['HTTP_REFERER'])){
    // Use given referrer
    $referrer   = $_SERVER['HTTP_REFERER'];

    } else {
    // No referrer
    }

    // Save current page as next page's referrer
    $_SESSION['referrer']   = $this->current_page_url();
   
    
    $conf = $this->CFG->getConf();
	  if  ( isset ($_SERVER['HTTP_REFERER' ] ) )                
    {   $host1 = explode('/', $_SERVER['HTTP_REFERER']);  
         print_r($_SERVER);die();    
    if ( ! in_array( $host1[2], $conf['ok_host'] ) )  {  die("<div style='text-align:center;'><h1>ACCESS ERROR<h1><h3>Unzul&auml;ssiger Zugriff!</h3><a href=\"javascript:window.back()\">Zur&uuml;ck</a></div>"); }
    }
    else
    {  if( $_SERVER['SERVER_NAME' ] != 'localhost' )
      {      
         header("Location:index.html");
         die("<div style='text-align:center;'><h1>ACCESS ERROR<h1><h3>Unzul&auml;ssiger Zugriff!</h3><a href=\"javascript:window.back()\">Zur&uuml;ck</a></div>"); 
      }
    }
  }
  
  // Get the full URL of the current page
function current_page_url()
{
    $page_url   = 'http';
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
        $page_url .= 's';
    }
    return $page_url.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
}


}

?>
