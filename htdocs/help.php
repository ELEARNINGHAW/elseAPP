<?php
require_once('../php/config.php');
require_once('../php/renderer.class.php');

$topic =""; 
if (isset( $_GET['topic'] ) ) { $topic = $_GET['topic']; } 

#require_once('error.php');

$renderer = new Renderer();

$renderer->smarty->assign( 'topic', $topic );
 
#$smarty->display('header_help.tpl');
#$smarty->display('header.tpl');

$renderer->smarty->display('help.tpl');
#$smarty->display('footer.tpl');

?>
