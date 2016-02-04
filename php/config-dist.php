<?php
session_start();

require_once '../php/const.php' ;

function getConf()
{
$serverName = $_SERVER[ 'SERVER_NAME' ];

if ($serverName == 'lernserver.el.haw-hamburg.de' )
{
  $conf['db_host']               = "";		                      ## MySQL Database parametes
  $conf['db_name']               = "";	
  $conf['db_user']               = "";
  $conf['db_pass']               = "";
  $conf['templates_compile_dir'] = "";                          ## Directory for storing compiled HTML teplates
  $conf['canYAZ']                = true;
}
  
else if ($serverName == 'localhost' OR $serverName == '127.0.0.1' )
{  
  $conf['db_host']               = "";		                      ## MySQL Database parametes
  $conf['db_name']               = "";	
  $conf['db_user']               = "";
  $conf['db_pass']               = "";
  $conf['templates_compile_dir'] = "";                          ## Directory for storing compiled HTML teplates -- /home/semapp/
}

$conf['opac_url']                = "https://kataloge.uni-hamburg.de/CHARSET=ISO-8859-1/DB=2/LNG=DU/CMD?ACT=SRCHA&IKT=12&SRT=YOP&TRM=";  ## URL of the online library catalogue
$conf['default_email_from']      = "Semesterapparate HIBS HAW Hamburg <hibs.mailservice@haw-hamburg.de>";
$conf['default_email_subject']   = "[HIBS] Ihr Semesterapparat";

$conf['debug_level']             = 0;  ## Debugging level (0 .. 99)
$conf['default_role_id']         = 3;  ## Default Role id for new users. 
$conf['default_location_id']     = 1;  # Default Location id for new document collections

#$opac_url = "https://kataloge.uni-hamburg.de/CHARSET=ISO-8859-1/DB=2/LNG=DU/CMD?ACT=SRCHA&IKT=12&SRT=YOP&TRM=";
#$opac_url = "https://hhas21.rrz.uni-hamburg.de/DB=2/LNG=DU/CMD?ACT=SRCHA&IKT=12&SRT=YOP&TRM=";
#$opac_url = "http://kataloge.uni-hamburg.de/DB=1/";


#getAllDocTypes()  


return $conf;
}

function getDocType($book)
{
 
  if (isset ($book['physicaldesc']) AND !isset ($book['state_id'] )  ) 
  {                                                                                               $book['state_id'   ]  =  1 ;
    if(      stristr(  $book['physicaldesc']  , 'Online') == TRUE ) { $book['doc_type_id']  = 4;  $book['state_id'   ]  =  3;}  
    else if( stristr(  $book['physicaldesc']  , 'CD-ROM') == TRUE ) { $book['doc_type_id']  = 3;  $book['state_id'   ]  =  1;}
  } 

  if (!isset ($book['doc_type_id'])) 
  {
    $book['doc_type_id'] = 1;
  }
  
  if( $book['doc_type_id']  == 4 ) 
  { 
    $book['doc_type'   ]  = "electronic";   #  E-BOOK            
    $book['item'       ]  = 'ebook'; 
  }
  else if( $book['doc_type_id']  == 3 )     # CD-ROM
  {
    $book['doc_type'   ]  = "cd-rom";
    $book['item'       ]  = 'book'; 
   /* Status: NEU BESTELLT  */
  }
  else if( $book['doc_type_id']  == 2 )      # BUCH als Literaturhinweis
  {
    $book['doc_type'   ]  = "print";
    $book['item'       ]  = 'lh_book'; 
  }
  else                                       # BUCH im Semesterapparat
  {
    $book['doc_type'   ]  = "print";
    $book['item'       ]  = 'book'; 
    $book['doc_type_id']  =  1;               
  }
 
  return $book;
}




function deb($obj, $kill=false) {   echo "<pre>";  print_r ($obj);  echo "<pre>";  if($kill){die();} }

?>
