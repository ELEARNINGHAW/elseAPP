{assign var="edit_mode"   value="0"} 
{assign var="staff_mode"  value="0"}
{if $user.role_name == "admin"  OR  $user.role_name == "staff" OR  $user.role_name == "edit"  } {assign var="edit_mode"  value="1"} {/if}
{if $user.role_name == "admin"  OR  $user.role_name == "staff"                                } {assign var="staff_mode" value="1"} {/if}
<div class="column">
{foreach key=cid item=ci from=$collection_info}
{if $ci.title_short != "" OR  $work.action == 'showopen'}
<div class="SAMeta bgDef bg{$ci.coll_bib_id}">
{if ( $staff_mode )}   
<div style="width:630px; display: inline-block; font-weight: 700; font-size: 14px; color: #FFF; padding-top:2px; "> 
<div style="float:left;">{$ci.title|truncate:70:"...":true} </div> 
<div style="float:right;"> FB:{$ci.bib_id}</div><br/>
<div style="float:left;">von: {$ci.user_info.vorname|escape} {$ci.user_info.nachname|escape}</div> 
<div style="float:right;">Dep:{$department[$ci.user_info.department_id].DepName}</div> 
</div>
{else}
<div class="SAdozName">ELSE<br />Der elektronische Semesterapparat </div>
{/if}
{if ($work.todo != "print" AND ($edit_mode OR $staff_mode)) }
<a target="help_win" class="modalLink" href="#helpit" title="Weitere Informationen über ELSE"                  ><img src="img/svg/help.svg"        width="32"  height="32" style="position:relative; float:right; padding-right: 2px; margin:2px; margin-right:-1px;"  /></a>
<a target="_blank" href="index.php?item=collection&amp;collection_id={$ci.title_short|escape:"url"|escape}&amp;action=show&amp;todo=print&amp;r={$user.role}">
<img src="img/svg/print_w.svg"    width="32"  height="32" style="position:relative; float:right; padding-right: 2px; margin:2px; margin-right:-1px;" title="Druckversion"   /></a>
{if ($edit_mode OR $staff_mode)}   
<a href="index.php?item=collection&amp;action=b_coll_meta_edit&amp;collection_id={$ci.title_short}&amp;redirect=SA&amp;r={$user.role}" title="Bearbeiten der allgemeinen Infos des Semesterapparats">
<img src="img/svg/settings_w.svg"  width="32"  height="32" style="position:relative; float:right;  margin:2px; " /></a>
{/if}
{if $edit_mode AND $ci.title_short != "" }
<a href="index.php?collection_id={$ci.title_short}&amp;item=book&amp;action=b_new&amp;b_new=neu+anlegen&amp;r={$user.role}"  title="Neues Medium (Buch, E-Book,...) dem Semesterapparat hinzufügen"  >
<img src="img/svg/addBook_w.svg"   width="32"  height="32" style="position:relative; float:right;  margin:2px; " /></a>
{/if}
{else}
<a target="_blank" href="#"  onclick="window.print(); return false;">
<img src="img/svg/print_w.svg"    width="32"  height="32" style="position:relative; float:right; padding-right: 2px; margin:2px; margin-right:-1px;" title="Zum Drucker senden"   /></a>
{/if}
 </div>
{/if}

{if isset($ci.notes_to_studies)  AND $ci.notes_to_studies != "" AND $work.action != 'showopen'}
<div class="studihint">  <div style="color:red;" >Hinweise zur Vorlesung</div>   {$ci.notes_to_studies|replace:"\\":" "|nl2br} </div>
{/if}

{if isset($ci.document_info)}
{foreach from=$ci.document_info item=di} 
  {if $edit_mode == 0 AND  $di.state_id == 3  OR $edit_mode == 1 AND $di.state_id != 6 OR $work.action == 'showopen' }
   {if $work.document_id == $di.id} {assign var="current" value="currentDoc"} {else}  {assign var="current" value="XXX"} {/if}

<div  id="{$di.id}" class="mediaInSA medium_{$di.doc_type_id} {$current} " >  
<a name="{$di.id}" style="position:relative; top:-220px;"></a>
<a title="Buch Im Onlinekatalog anzeigen" class="medLink medi_{$di.doc_type_id} .s_standard state_{$di.state_id}" href="{$work.catURLlnk}{$di.ppn}" target="_blank" onclick="return -1"> 
<table>
{if $di.title          != "" } <tr><td><div class="medHead">Titel:  </div></td><td> <div class="medTitle"  >{$di.title}                        </div>{if $di.year   != "" } <div class="medJahrgang" >{$di.year|escape|regex_replace:"/[,. ]*$/":""}</div> </td></tr> {/if}  {else}  ohne Titel</td></tr> </div> {/if}
{if $di.physicaldesc   != "" } <tr><td><div class="medHead">Format: </div></td><td><div class="medTyp"    >{$di.doc_type|escape} / {$di.physicaldesc|escape}         </div></td></tr>{/if}
{if $di.author         != "" } <tr><td><div class="medHead">Autor:  </div></td><td><div class="medAutor"  >{$di.author}                       </div></td></tr>{/if}
{*if $di.signature      != "" } <br /><span class="medHead">Signatur: </span><span class="medSignatur" >{$di.signature|escape}</span>{/if*}
</table>
</a>
{if $di.notes_to_studies != "" }   <div class="medhint">Zur Beachtung: {$di.notes_to_studies|nl2br}  </div> {/if}
<div class="bibStandort"> 
{if $ci.bib_id != "" }
{if $di.doc_type_id == 1 OR $di.doc_type_id == 3}{* Buch im SA  / LitHinweis Buch / CD Rom *}
{if $di.shelf_remain != 1}  {$fachbib[ $ci.coll_bib_id ].BibName|escape},<br/> im Regal "Semesterapparate"   {else} Im Buchbestand der Fachbibliothek<br/> (wie im Online-Katalog angegeben). {/if}
{/if}
{if  $di.doc_type_id == 2 }{* LitHinweis Buch  *}
Im Buchbestand der Fachbibliothek<br/> (wie im Online-Katalog angegeben).  
{/if}
{if $di.doc_type_id == 4 }{* PDF *}
Im Online-Katalog,<br/>  erreichbar nur aus dem HAW-Netz (oder VPN).
{/if}
{/if}
</div>
{if ($staff_mode or $edit_mode) and ($work.todo != "print") }  
<div class="status s_{$di.state_id}" />{$media_state[$di.state_id].description}</div>
<div class="iconlist"> 
{include file="action_button_bar.tpl" 
state          = $media_state[$di.state_id].name
role_encode    = $user.role_encode 
mode           = $user.role_name 
collection_id  = $ci.title_short 
item           = $di.item
document_id    = $di.id
url            = $di.url
protected      = $di.protected
ppn            = $di.ppn
}
</div> 
{/if}
{if ($staff_mode or $edit_mode) AND ($di.notes_to_staff != "") and  ($di.state_id == 1 or $di.state_id == 2 or $di.state_id == 9) } {* new or open  *}
<div class="staffnote"> {$di.notes_to_staff|escape|nl2br} </div>
{/if}
</div>
{/if}
{/foreach}
{/if}

{foreachelse}              
<div style="padding:10px; margin:10px; margin-top:0px; color: #000; font-size: 14px; border: solid #AAA 2px; background-color:#efe96d; ">
Es ist kein  Dokument  vorhanden.
</div>
{/foreach}
</div> 