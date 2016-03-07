<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8" />
  <link rel="stylesheet" href="lib/style.css" type="text/css" media="screen" />
  <title>Semesterapparate</title>
  <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
  <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script type="text/javascript" src="lib/else.js"></script>
</head>
<body style='margin:0px; padding:0px;'>
{if not isset($user.sex)}                {$user.sex                 = ''}  {/if}
{if not isset($user.degree_description)} {$user.degree_description  = ''}  {/if}
{if not isset($user.forename)}           {$user.forename            = ''}  {/if}
{if not isset($user.surname)}            {$user.surname             = ''}  {/if}
{if not isset($user.id)}                 {$user.id                  = ''}  {/if}
{if not isset($user.role_name)}          {$user.role_name           = ''}  {/if}
{if $work.todo != "print"  }
{if  $user.role_name == "admin" ||  $user.role_name == "staff"} 
<div style="position:relative;   padding:0px; height:38px; margin:0px;  background-color: #234A89; margin-bottom:20px;  margin-left:0px; margin-right: 0px;">
<div style="position: relative; left:0px; top:0px; height:40px;"   >  
<ul id="nav" style="position:absolute; left:5px;   top:6px; margin-right:60px;"   >
   
{if isset($work.categories)}<li class="en">   <a class="en" title="Alle Dozenten, über Anfangsbuchstabe" href="#">A</a> 
{if isset($letter_output)}
    <ul style="width:20px;"> 
{foreach from=$letter_output key=letter_k item=letter_i}
{if $letter_i == 1}<li><a class="en" title="Alle Dozenten, beginned mit {$letter_k}" href="{$source}?letter={$letter_k}&amp;action=sort&amp;r={$user.role}">{$letter_k}</a></li> {/if}
{/foreach}
</ul> 
{/if}
</li> 
{/if}
<li><a title="Semesterapparate der HAW" style="background-color:#234A89;"  href="index.php?categories=1&amp;action=sort&amp;r={$user.role}"> HAW </a>   </li>
<li><a title="Semesterapparate der Fakultät DMI" style="background-color:#008B95;" href="index.php?categories=20&amp;action=sort&amp;r={$user.role}"> DMI </a>
  <ul style="display: block; visibility: hidden;">
    <li><a title="Semesterapparate des Department Design"                                          style="background-color:#008B95;" href="index.php?categories=21&amp;action=sort&amp;r={$user.role}">Design</a></li>
    <li><a title="Semesterapparate des Department Information"                                     style="background-color:#008B95;" href="index.php?categories=22&amp;action=sort&amp;r={$user.role}">Information</a></li>
    <li><a title="Semesterapparate des Department Technik"                                         style="background-color:#008B95;" href="index.php?categories=23&amp;action=sort&amp;r={$user.role}">Technik</a></li>
  </ul>
 </li>
 <li><a title="Semesterapparate der Fakultät LS" style="background-color:#E98300;"  href="index.php?categories=30&amp;action=sort&amp;r={$user.role}"> LS </a>
     <ul style="display: block; visibility: hidden;">
         <li><a title="Semesterapparate des Department Biotechnologie"                                   style="background-color:#E98300;" href="index.php?categories=31&amp;action=sort&amp;r={$user.role}">Biotechnologie </a></li>
         <li><a title="Semesterapparate des Department Gesundheits-issenschaften"                        style="background-color:#E98300;" href="index.php?categories=32&amp;action=sort&amp;r={$user.role}">Gesundheits-wissenschaften</a></li>
         <li><a title="Semesterapparate des Department Medizintechnik"                                   style="background-color:#E98300;" href="index.php?categories=33&amp;action=sort&amp;r={$user.role}">Medizintechnik</a></li>
         <li><a title="Semesterapparate des Department Ökotrophologie"                                   style="background-color:#E98300;" href="index.php?categories=34&amp;action=sort&amp;r={$user.role}">Ökotrophologie</a></li>
         <li><a title="Semesterapparate des Department Umwelttechnik"                                    style="background-color:#E98300;" href="index.php?categories=35&amp;action=sort&amp;r={$user.role}">Umwelttechnik</a></li>
         <li><a title="Semesterapparate des Department Verfahrenstechnik"                                style="background-color:#E98300;" href="index.php?categories=36&amp;action=sort&amp;r={$user.role}">Verfahrenstechnik</a></li>
         <li><a title="Semesterapparate des Department Wirtschaftsingenieurwesen"                        style="background-color:#E98300;" href="index.php?categories=37&amp;action=sort&amp;r={$user.role}">Wirtschafts-ingenieurwesen</a></li>
     </ul>
 </li>
 <li><a title="Semesterapparate der Fakultät TI"  style="background-color:#0E905A;"   href="index.php?categories=50&amp;action=sort&amp;r={$user.role}"> TI </a>
     <ul style="display: block; visibility: hidden;">
         <li><a title="Semesterapparate des Department Fahrzeugtechnik und Flugzeugbau"                  style="background-color:#0E905A;" href="index.php?categories=51&amp;action=sort&amp;r={$user.role}">Fahrzeugtechnik und Flugzeugbau </a></li>
         <li><a title="Semesterapparate des Department Informatik"                                       style="background-color:#0E905A;" href="index.php?categories=52&amp;action=sort&amp;r={$user.role}">Informatik</a></li>
         <li><a title="Semesterapparate des Department Information und Elektrotechnik"                   style="background-color:#0E905A;" href="index.php?categories=53&amp;action=sort&amp;r={$user.role}">Information und Elektrotechnik</a></li>
         <li><a title="Semesterapparate des Department Maschinenbau und Produktion"                      style="background-color:#0E905A;" href="index.php?categories=54&amp;action=sort&amp;r={$user.role}">Maschinenbau und Produktion</a></li>
         <li><a title="Semesterapparate des Department Mechatronik"                                      style="background-color:#0E905A;" href="index.php?categories=55&amp;action=sort&amp;r={$user.role}">Mechatronik</a></li>
     </ul>
 </li>
 <li><a title="Semesterapparate der Fakultät W&amp;S" style="background-color:#C60C30;"  href="index.php?categories=60&amp;action=sort&amp;r={$user.role}">  W&amp;S </a>
     <ul style="display: block; visibility: hidden;">
         <li><a title="Semesterapparate des Department Public Management"                                style="background-color:#C60C30;" href="index.php?categories=61&amp;action=sort&amp;r={$user.role}">Public Management</a></li>
         <li><a title="Semesterapparate des Department Wirtschaft"                                       style="background-color:#C60C30;" href="index.php?categories=62&amp;action=sort&amp;r={$user.role}">Wirtschaft</a></li>
         <li><a title="Semesterapparate des Department Pflege und Management"                            style="background-color:#C60C30;" href="index.php?categories=63&amp;action=sort&amp;r={$user.role}">Pflege und Management</a></li>
         <li><a title="Semesterapparate des Department Soziale Arbeit"                                   style="background-color:#C60C30;" href="index.php?categories=64&amp;action=sort&amp;r={$user.role}">Soziale Arbeit</a></li>
     </ul>
 </li> 
  <li><a title="Semesterapparate ohne Fakultät" style="background-color:#234A89;"  href="index.php?categories=0&amp;action=sort&amp;r={$user.role}"> 0 </a></li>
 </ul>
</div>   
 <!-- 
  <div  style="position:absolute; right:190px;   top:10px;   font-weight: 700;">
    <a class='fachbib'  title="Semesterapparate der HAW" style="background-color:#234A89;"  href="index.php?categories=1"> DMI </a>
    <a class='fachbib'  title="Semesterapparate der HAW" style="background-color:#234A89;"  href="index.php?categories=1"> LS </a>
    <a class='fachbib'  title="Semesterapparate der HAW" style="background-color:#234A89;"  href="index.php?categories=1"> TWI1 </a>
    <a class='fachbib'  title="Semesterapparate der HAW" style="background-color:#234A89;"  href="index.php?categories=1"> TWI2 </a>
    <a class='fachbib'  title="Semesterapparate der HAW" style="background-color:#234A89;"  href="index.php?categories=1"> SP </a>
    <a class='fachbib'  title="Semesterapparate der HAW" style="background-color:#234A89;"  href="index.php?categories=1"> HAW </a>
  </div>
-->
 
  <div  style="position:absolute; right:39px;   top:2px;">
  <a href='index.php?item=collection&amp;action=showopen&amp;todo=1' title="Neu Eingestellt"><img src="img/svg/N.svg" width="32" height="32"/></a>
  <a href='index.php?item=collection&amp;action=showopen&amp;todo=2' title="Wird Bearbeitet"><img src="img/svg/B.svg" width="32" height="32"/></a> 
  <a href='index.php?item=collection&amp;action=showopen&amp;todo=9' title="Kaufvorschlag"  ><img src="img/svg/K.svg" width="32" height="32"/></a> 
  </div>
  
  <ul id="nav2" style="position:absolute; right:1px;   top:2px;">
  {if      $work.todo == 3}<li><a href="index.php?item=collection&amp;action=showopen&amp;todo=3" title="Aktiv"          ><img src="img/svg/A.svg" width="32" height="32"/></a> 
  {elseif  $work.todo == 5}<li><a href="index.php?item=collection&amp;action=showopen&amp;todo=5" title="Inaktiv"        ><img src="img/svg/I.svg" width="32" height="32"/></a> 
  {elseif  $work.todo == 6}<li><a href="index.php?item=collection&amp;action=showopen&amp;todo=6" title="Gelöschte"      ><img src="img/svg/G.svg" width="32" height="32"/></a> 
  {else}                   <li><a href="index.php?item=collection&amp;action=showopen&amp;todo=4" title="Wird Entfernt"  ><img src="img/svg/E.svg" width="32" height="32"/></a> 
  {/if}
   
  <ul>
   <li><a href="index.php?item=collection&amp;action=showopen&amp;todo=4" title="Wird Entfernt"   ><img src="img/svg/E.svg" width="20" height="20"/>Zum Entfernen</a></li>
    <li><a href="index.php?item=collection&amp;action=showopen&amp;todo=3" title="Aktiv"          ><img src="img/svg/A.svg" width="20" height="20"/>Aktive</a></li>
    <li><a href="index.php?item=collection&amp;action=showopen&amp;todo=5" title="Inaktiv"        ><img src="img/svg/I.svg" width="20" height="20"/>Inaktive</a></li>
    <li><a href="index.php?item=collection&amp;action=showopen&amp;todo=6" title="Gelöschte"      ><img src="img/svg/G.svg" width="20" height="20"/>Gelöschte</a></li>
  </ul>
  </li>
  </ul>
 
</div>

  {if  $work.todo == 1}<div class="todoHeader bgTD{$work.todo}">Gesamtliste: Neu Eingestellt </div>{/if}
  {if  $work.todo == 2}<div class="todoHeader bgTD{$work.todo}">Gesamtliste: Wird Bearbeitet</div>{/if}
  {if  $work.todo == 3}<div class="todoHeader bgTD{$work.todo}">Gesamtliste: Aktiv  </div>{/if}
  {if  $work.todo == 4}<div class="todoHeader bgTD{$work.todo}">Gesamtliste: Wird Entfernt </div>{/if}
  {if  $work.todo == 5}<div class="todoHeader bgTD{$work.todo}">Gesamtliste: Inaktiv </div>{/if}
  {if  $work.todo == 6}<div class="todoHeader bgTD{$work.todo}">Gesamtliste: Gelöscht </div>{/if}
  {if  $work.todo == 9}<div class="todoHeader bgTD{$work.todo}">Gesamtliste: Kaufvorschlag </div>{/if}
{/if}
 {/if}