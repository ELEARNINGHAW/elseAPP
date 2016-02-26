<?php

require_once '../smarty/libs/Smarty.class.php';

class Renderer
{

var $smarty;  

function   Renderer( $CONFIG )
{
  $this->smarty = new Smarty;

  $conf = $CONFIG->getConf();
  $this->smarty->compile_dir  = $conf['templates_compile_dir'];
  $this->smarty->template_dir = "../templates";
  $this->smarty->config_dir   = "../configs";
  $this->smarty->addTemplateDir('./templates');
# $this->smarty->compile_check = true;
}

function smarty_init() 
{
}

function doRedirect( $url = "index.php?categories=1" ) 
{
  $this->smarty->assign("url", $url);
  header("Location: $url"); 	
	$this->smarty->display('header.tpl');
	$this->smarty->display('redirect.tpl');
	$this->smarty->display('footer.tpl');
	exit(0);	
}

function do_template( $template, $kw, $HuF = true ) 
{   
	$this->smarty->compile_check = TRUE;

	foreach ($kw as $k => $v)  
  {  $this->smarty->assign($k, $v);
	}
    
  if ($HuF) $this->smarty->display('header.tpl');
	{ $this->smarty->display($template);
  }
  
  if ($HuF) $this->smarty->display('footer.tpl');
  {
	  foreach ($kw as $k => $v) 
    {  #$smarty->clear_assign( array($k, $v));
    }
  }
  {  $_SESSION['work']['last_page'] = $_SERVER['REQUEST_URI'];
	}
}


}

?>