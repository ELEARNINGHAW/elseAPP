<?php
class Config
{

var $CONST;  
  
function Config( $CONSTANT )
{  
  $this->C = $CONSTANT;

}
function getConf()
{
$serverName = $_SERVER[ 'SERVER_NAME' ];

if ($serverName == 'lernserver.el.haw-hamburg.de' )
{
$conf[ 'db_host'               ] = "localhost";		                     ## MySQL Database parametes
$conf[ 'db_name'               ] = "semapp";	
$conf[ 'db_user'               ] = "semapp";
$conf[ 'db_pass'               ] = "semapp";
$conf[ 'templates_compile_dir' ] = "/home/ELSE/template/";             ## Directory for storing compiled HTML teplates
}
  
else if ($serverName == 'localhost' OR $serverName == '127.0.0.1' )
{ 
$conf[ 'db_host'               ] = "localhost";		                     ## MySQL Database parametes
$conf[ 'db_name'               ] = "semapp";	
$conf[ 'db_user'               ] = "semapp";
$conf[ 'db_pass'               ] = "semapp";
$conf[ 'templates_compile_dir' ] = "C:/X/home/ELSE/";                    ## Directory for storing compiled HTML teplates
}

$conf[ 'catURL'                ] = 'http://sru.gbv.de/';
$conf[ 'cat'                   ] = 'opac-de-18-302';  # HIBS 
$conf[ 'recordSchema'          ] = 'turbomarc';       # turbomarc / mods
$conf[ 'maxRecords'            ] = 50; 

## --- Mail an die HIBS/ELSE - Ansprechpartner ---
$conf[ 'BIB_BCC'               ] =  'daniela.mayer@haw-hamburg.de, werner.welte@haw-hamburg.de' ;
$conf[ 'BIB_FROM'              ] =  'ELSE-noreply@haw-hamburg.de' ;
$conf[ 'BIB_RPTO'              ] =  'werner.welte@haw-hamburg.de';

$conf[ 'BIB_Anrede'            ] = "Liebe ELSE/HIBS Mitarbeiterin  \r\n\r\n";
$conf[ 'BIB_Gruss'             ] = "\r\n\r\nIhr ELSE Server \r\n\r\n http://www.elearning.haw-hamburg.de/mod/else/view.php?id=443297  \r\n\r\n";
## --------------------------------------------------

$conf[ 'debug_level'           ] = 0;  ## Debugging level (0 .. 99)
$conf[ 'default_role_id'       ] = 3;  ## Default Role id for new users. 
$conf[ 'default_bib_id'        ] = 'HAW';  ## Default Location id for new document collections
$conf[ 'canYAZ'                ] = true;

return $conf;
}
}
?>
