<?php
/* --- TODO --- */
#  --- CHECK PERMISSIONS ---
# check_permission($INPUT)
error_reporting ( E_ALL );
require_once('../php/config.php'            );
require_once('../php/util.class.php'        );

$util       = new Util( );

$util ->  sendAdminEmails ();
?>
