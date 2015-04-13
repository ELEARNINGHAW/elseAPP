<?php
session_start();
#set_include_path ( '../php' . PATH_SEPARATOR . get_include_path () ) ;
#set_include_path ( '../smarty/libs' . PATH_SEPARATOR . get_include_path () ) ;

require_once '../php/const.php' ;
$serverName = $_SERVER[ 'SERVER_NAME' ];


if ($serverName == 'lernserver.el.haw-hamburg.de' )
{
  ## MySQL Database parametes
  $db_host = "localhost";		
  $db_name = "semapp";	
  $db_user = "semapp";
  $db_pass = "semapp";
 
  #$sitebase = "http://semapp.bbt.haw-hamburg.de";

  ## Directory for storing uploaded documents 
  $upload_base_dir="/home/ELSE/upload/";
  
  ## Directory for storing compiled HTML teplates
  $templates_compile_dir = "/home/ELSE/template/";

  $canYAZ = true;
}
  
if ($serverName == 'localhost' || $serverName == '127.0.0.1' )
{ 
  ## MySQL Database parametes
  $db_host = "localhost";		
  $db_name = "semapp";	
  $db_user = "semapp";
  $db_pass = "semapp";

  #$sitebase = "http://semapp.bbt.haw-hamburg.de";
  
  ## Directory for storing uploaded documents 
  $templates_compile_dir ="C:/Users/semapp/upload/";  
  
  ## Directory for storing compiled HTML teplates
  $templates_compile_dir ="C:/Users/semapp/templates/";
}


## URL of the online library catalogue
#$opac_url = "https://kataloge.uni-hamburg.de/CHARSET=ISO-8859-1/DB=2/LNG=DU/CMD?ACT=SRCHA&IKT=12&SRT=YOP&TRM=";
#$opac_url = "https://hhas21.rrz.uni-hamburg.de/DB=2/LNG=DU/CMD?ACT=SRCHA&IKT=12&SRT=YOP&TRM=";
#$opac_url = "http://kataloge.uni-hamburg.de/DB=1/";
$opac_url = "https://kataloge.uni-hamburg.de/CHARSET=ISO-8859-1/DB=2/LNG=DU/CMD?ACT=SRCHA&IKT=12&SRT=YOP&TRM=";




## The web server needs read and write permission on these 
## directories.  For security reasons, the directories should 
## be located *outside* the webserver's document root.

# E-Mail parameters

$default_email_from 	= "Semesterapparate HIBS HAW Hamburg <hibs.mailservice@haw-hamburg.de>";
$default_email_subject  = "[HIBS] Ihr Semesterapparat";


## Z39.50 configuration (library catalogue search)
# Enable the search function for the library catalogue.
# The catalogue must have a z39.50 interface for this to work.

#$use_z3950 = FALSE;
#$use_z3950 = TRUE;

#$z_host = "z3950.gbv.de";
#$z_port = "20010"; #ISO-8859-1 search
#$z_port = "20012"; #UTF-8 search
#$z_user = "999";
#$z_pass = "abc";
#$z_db   = "hh_haw";

#####  these settings do not need to be changed

## Debugging level (0 .. 99)
$debug_level = 0;
#$debug_level = 200;

## Default Role id for new users.  
$default_role_id = 3;

# Default Location id for new document collections
$default_location_id = 1;

 

function deb($obj, $kill=false) {   echo "<pre>";  print_r ($obj);  echo "<pre>";  if($kill){die();} }

?>
