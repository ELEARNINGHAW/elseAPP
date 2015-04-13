{if ( not isset( $header_done ) ) } {assign var=header_done value="1"}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
  <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
  <link rel="stylesheet" href="lib/style.css" type="text/css" media="screen" />
  <title>Semesterapparate</title>
</head>
<body style="margin:0px; padding:0px;">
 
{if not isset($user.sex)}                {$user.sex                 = ''}  {/if}
{if not isset($user.degree_description)} {$user.degree_description  = ''}  {/if}
{if not isset($user.forename)}           {$user.forename            = ''}  {/if}
{if not isset($user.surname)}            {$user.surname             = ''}  {/if}
{if not isset($user.id)}                 {$user.id                  = ''}  {/if}
        
{if $work.todo != "print"  }
  
<div style="position:relative; height: 50px;  left:0px; top:0px; margin:0px; background-color: #FFF;  " >
<div> 
  <a href="index.php" title="ELSE - Der Elektronische Semesterapparat der HAW-Hamburg"><img src="img/svg/ELSE.logo.svg"    width="150"  height="38" style="position:absolute; left:5px;  top:5px; float:left" /></a> 
</div> 
  
 
{if $user.id != "" }
  <div style="position:absolute; left:180px; top:0px; padding:12px; padding-left:10px; height:10px; margin:0px;">
    <div class="{$work.mode}_head"><a title="{$work.mode}">[{$work.mode}:] {$user.forename|escape}  {$user.surname}</a></div>
  </div>
{/if}
  <span style="position:absolute; right:140px; top:7px; color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px; text-align:right;" ><a href="login.php" style="text-decoration: none; color: #000;">E</a>-Semesterapparat<br /> der HAW-Hamburg</span> 
  <a href="logout.php" title="HIBS - Der Hochschul Informations- und Bibliothekarsservice der HAW-Hamburg" ><img src="img/svg/HIBS.logo.svg" width="128" height="32" style="position:absolute; right:5px; top:10px;"  /></a> 
</div>
  
  

<div style="position:relative;   padding:0px; height:38px; margin:0px;  background-color: #234A89;  margin-left:10px; margin-right: 10px;">
  {if $work.item == "collection" AND  $work.mode != "admin" AND  $work.mode != "staff"  } <a href="index.php"  style="text-decoration: none;"><div style="color: #FFF; padding:10px; padding-left: 30px;" >Semesterapparat:</div></a>{/if}

<div style="position: relative; left:0px; top:0px; height:40px;"   >  
{if  $work.mode == "admin" ||  $work.mode == "staff"} 
 <ul id="nav" style="position:absolute; left:5px;   top:6px; margin-right:60px;"   >
     <li><a title="Semesterapparate der HAW" style="background-color:#234A89;"  href="index.php?categories=1"> ELSE </a>
     </li>
     <li><a title="Semesterapparate der Fakultät DMI" style="background-color:#008B95;" href="index.php?categories=20"> DMI </a>
         <ul style="display: block; visibility: hidden;">
             <li><a title="Semesterapparate des Department Design"                                          style="background-color:#008B95;" href="index.php?categories=21">Design</a></li>
             <li><a title="Semesterapparate des Department Information"                                     style="background-color:#008B95;" href="index.php?categories=22">Information</a></li>
             <li><a title="Semesterapparate des Department Technik"                                         style="background-color:#008B95;" href="index.php?categories=23">Technik</a></li>
         </ul>
     </li>
     <li><a title="Semesterapparate der Fakultät LS" style="background-color:#E98300;"  href="index.php?categories=30"> LS </a>
         <ul style="display: block; visibility: hidden;">
             <li><a title="Semesterapparate des Department Biotechnologie"                                   style="background-color:#E98300;" href="index.php?categories=31">Biotechnologie </a></li>
             <li><a title="Semesterapparate des Department Gesundheits-issenschaften"                        style="background-color:#E98300;" href="index.php?categories=32">Gesundheits-wissenschaften</a></li>
             <li><a title="Semesterapparate des Department Medizintechnik"                                   style="background-color:#E98300;" href="index.php?categories=33">Medizintechnik</a></li>
             <li><a title="Semesterapparate des Department Ökotrophologie"                                   style="background-color:#E98300;" href="index.php?categories=34">Ökotrophologie</a></li>
             <li><a title="Semesterapparate des Department Umwelttechnik"                                    style="background-color:#E98300;" href="index.php?categories=35">Umwelttechnik</a></li>
             <li><a title="Semesterapparate des Department Verfahrenstechnik"                                style="background-color:#E98300;" href="index.php?categories=36">Verfahrenstechnik</a></li>
             <li><a title="Semesterapparate des Department Wirtschaftsingenieurwesen"                        style="background-color:#E98300;" href="index.php?categories=37">Wirtschafts-ingenieurwesen</a></li>
         </ul>
     </li>
     <li><a title="Semesterapparate der Fakultät TI"  style="background-color:#0E905A;"   href="index.php?categories=50"> TI </a>
         <ul style="display: block; visibility: hidden;">
             <li><a title="Semesterapparate des Department Fahrzeugtechnik und Flugzeugbau"                  style="background-color:#0E905A;" href="index.php?categories=51">Fahrzeugtechnik und Flugzeugbau </a></li>
             <li><a title="Semesterapparate des Department Informatik"                                       style="background-color:#0E905A;" href="index.php?categories=52">Informatik</a></li>
             <li><a title="Semesterapparate des Department Information und Elektrotechnik"                   style="background-color:#0E905A;" href="index.php?categories=53">Information und Elektrotechnik</a></li>
             <li><a title="Semesterapparate des Department Maschinenbau und Produktion"                      style="background-color:#0E905A;" href="index.php?categories=54">Maschinenbau und Produktion</a></li>
             <li><a title="Semesterapparate des Department Mechatronik"                                      style="background-color:#0E905A;" href="index.php?categories=55">Mechatronik</a></li>
         </ul>
     </li>
     <li><a title="Semesterapparate der Fakultät W&amp;S" style="background-color:#C60C30;"  href="index.php?categories=60">  W&amp;S </a>
         <ul style="display: block; visibility: hidden;">
             <li><a title="Semesterapparate des Department Public Management"                                style="background-color:#C60C30;" href="index.php?categories=61">Public Management</a></li>
             <li><a title="Semesterapparate des Department Wirtschaft"                                       style="background-color:#C60C30;" href="index.php?categories=62">Wirtschaft</a></li>
             <li><a title="Semesterapparate des Department Pflege und Management"                            style="background-color:#C60C30;" href="index.php?categories=63">Pflege und Management</a></li>
             <li><a title="Semesterapparate des Department Soziale Arbeit"                                   style="background-color:#C60C30;" href="index.php?categories=64">Soziale Arbeit</a></li>
         </ul>
     </li> 

 {if isset($letter_output)}
   {foreach from=$letter_output key=letter_k item=letter_i}
     {if $letter_i == 1}
       <li><a class="en" title="Alle Dozenten, beginned mit {$letter_k}" href="{$source}?letter={$letter_k}">{$letter_k}</a></li> 
     {/if}
   {/foreach}
 {/if}
 {/if}
</div>   

<a target="help_win" class="modalLink" href="#helpit" title="Weitere Informationen über ELSE"                  ><img src="img/svg/help.svg"        width="32"  height="32" style="position:absolute; right:2px; top:3px;" /></a>
            
    
{if $work.mode == "edit" AND $work.item == "collectionList"}              
  <a href="action.php?item=collection&action=b_new&user_id={$user.id}" title="Neuer Semesterapparat anlegen" ><img src="img/svg/bookmark-o.svg"  width="32"  height="32" style="position:absolute;right:39px;  top:3px;" /></a>
{/if}

{if $work.mode == "staff" or $work.mode == "admin"}              
  <a href="action.php?item=collection&action=showopen&todo=6" title="Gelöschte"           ><img src="img/svg/G.svg"        width="32"  height="32" style="position:absolute;right:39px;  top:3px;" /></a>
  <a href="action.php?item=collection&action=showopen&todo=3" title="Aktiv"               ><img src="img/svg/A.svg"        width="32"  height="32" style="position:absolute;right:110px;  top:3px;" /></a>
  <a href="action.php?item=collection&action=showopen&todo=5" title="Inaktiv"             ><img src="img/svg/I.svg"        width="32"  height="32" style="position:absolute;right:145px;  top:3px;" /></a>
  <a href="action.php?item=collection&action=showopen&todo=9" title="Kaufvorschlag"       ><img src="img/svg/K.svg"        width="32"  height="32" style="position:absolute;right:180px;  top:3px;" /></a>
  <a href="action.php?item=collection&action=showopen&todo=4" title="Wird Entfernt"       ><img src="img/svg/E.svg"        width="32"  height="32" style="position:absolute;right:215px;  top:3px;" /></a>
  <a href="action.php?item=collection&action=showopen&todo=2" title="Wird Bearbeitet"     ><img src="img/svg/B.svg"        width="32"  height="32" style="position:absolute;right:250px;  top:3px;" /></a>
  <a href="action.php?item=collection&action=showopen&todo=1" title="Neu Eingestellt"     ><img src="img/svg/N.svg"        width="32"  height="32" style="position:absolute;right:285px;  top:3px;" /></a>

{/if}

{if  $work.mode == "admin"}
<!--
  <a href="#" title="Benutzerverwaltung"                                                                           ><img src="img/svg/users.svg"      width="32"  height="32" style="position:absolute;right:109px;  top:3px;" /></a>
  <a href="#" title="Statusreport an BIB-Mitabeiter schicken"                                                      ><img src="img/svg/bell.svg"       width="32"  height="32" style="position:absolute;right:144px;  top:3px;" /></a>
  <a href="#" title="Statusreport an Dozenten schicken"                                                            ><img src="img/svg/bell2.svg"      width="32"  height="32" style="position:absolute;right:213px;  top:58px;" /></a>-->
{/if}

 </div>

  

<div style=" padding:2px; padding-left:30px;   margin:0px;  background-color: #FFF;  margin-left:10px; margin-right: 10px;">
  {if  $work.todo == 1}<h3>Gesamtliste: Neu Eingestellt </h3>{/if}
  {if  $work.todo == 2}<h3>Gesamtliste: Wird Bearbeitet </h3>{/if}
  {if  $work.todo == 3}<h3>Gesamtliste: Aktiv  </h3>{/if}
  {if  $work.todo == 4}<h3>Gesamtliste: Wird Entfernt </h3>{/if}
  {if  $work.todo == 5}<h3>Gesamtliste: Inaktiv </h3>{/if}
  {if  $work.todo == 6}<h3>Gesamtliste: Gelöscht </h3>{/if}
  {if  $work.todo == 9}<h3>Gesamtliste: Kaufvorschlag </h3>{/if}
  
 
</div>
{/if}
{/if}

