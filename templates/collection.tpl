{assign var="edit_mode"   value="0"} 
{assign var="staff_mode"  value="0"}

{if $user.role_name == "edit"}   {assign var="edit_mode"  value="1"}                                      {/if}
{if $user.role_name == "staff"}  {assign var="edit_mode"  value="1"} {assign var="staff_mode" value="1"}  {/if}
{if $user.role_name == "admin"}  {assign var="edit_mode"  value="1"} {assign var="staff_mode" value="1"}  {/if}

{foreach key=cid item=ci from=$collection_info}
{if $work.collection_id != "" OR  $work.action == 'showopen'}
 <div class="SAMeta bg{$ci.categories_id}">
   <a href="action.php?item=collection&collection_id={$ci.id}&item=collection&action=b_coll_edit" style="text-decoration: none;"><div class="SAtitel" >{$ci.title}</div></a> 
   <div class="SAdozName">von:   {$ci.forename|escape} {$ci.surname|escape}  /  {$ci.department}  </div>
    <a target="_blank" href="action.php?item=collection&collection_id={$work.collection_id|escape:"url"|escape}&amp;action=show&todo=print&amp;state={$work.state|escape:"url"|escape}&amp;type={$work.type|escape:"url"|escape}&amp;sort_crit={$work.sort_crit|escape:"url"|escape}"><img src="img/svg/print_w.svg"    width="32"  height="32" style="position:relative; float:right; padding-right: 2px; margin:2px; margin-right:-1px; top:-40px;" title="Druckversion"   /></a>
      {if ($staff_mode and $edit_mode) }
        <!-- a href="#" title="Alle inaktiven Dokumente entfernen"               >                                                                                                                                                                                <img src="img/svg/flash_w.svg"     width="32"  height="32" style="position:relative; float:right;  margin:2px; top:-40px;" /></a -->
      {/if}
      {if ($edit_mode or $staff_mode)  }   
        <a href="action.php?item=collection&action=b_coll_meta_edit&collection_id={$ci.id}&redirect=SA" title="Bearbeiten der Allgemeinen Infos des Semesterapparats">                                                                           <img src="img/svg/settings_w.svg"  width="32"  height="32" style="position:relative; float:right;  margin:2px; top:-40px;" /></a>
      {/if}
      {if $edit_mode and $work.collection_id != "" }
        <a href="action.php?collection_id={$ci.id}&item=book&action=b_new&b_new=neu+anlegen"  title="Neues Buch dem Semesterapparat hinzufügen"  >                                                                                               <img src="img/svg/addBook_w.svg"   width="32"  height="32" style="position:relative; float:right;  margin:2px; top:-40px;" /></a>
      {/if}
  </div>
{/if}

{if isset($ci.notes_to_studies)  AND $ci.notes_to_studies != "" AND $work.action != 'showopen'}
  <div class="studihint">  <div style="color:red;" >Hinweise zur Vorlesung</div>   {$ci.notes_to_studies|replace:"\\":" "|nl2br} </div>
{/if}

{foreach from=$ci.document_info item=di} 
  {if $edit_mode == 0 AND  $di.state_id == 3  OR $edit_mode == 1 AND $di.state_id != 6 OR $work.action == 'showopen' }

  <div class="mediaInSA medium_{$di.doc_type_id}" > 
    <a title="Buch Im Onlinekatalog anzeigen" class="medLink .s_standard state_{$di.state_id}" href="{$di.url}" target="_blank" onclick="return -1"> 
      {if $di.title  != "" }     {$di.title}     {else}  ohne Titel  {/if}
      <div class="medAutor">Autor:{if $di.author != "" } {$di.author} {/if} </div>
    <div class="medVerlag2"> 
      {if $di.signature  != "" } <span class="medSignatur" >              {$di.signature|utf8_decode|escape}                             </span>{/if}
      {if $di.publisher  != "" } <span class="medVerlag"   >               {$di.publisher|escape|regex_replace:"/[,. ]*$/":""            }</span> {/if}
      {if $di.year       != "" } <span class="medJahrgang" >               {$di.year|escape|regex_replace:"/[,. ]*$/":""                 }</span> {/if}
      {if $di.volume     != "" } <span class="medBand"     >          Band:{$di.volume|utf8_decode|escape|regex_replace:"/[,. ]*$/":""   }</span>{/if}
      {if $di.journal    != "" } <span class="medAuflage"  >              {$di.journal|escape|regex_replace:"/[,. ]*$/":""              } </span>{/if}
    </div>
    </a>

    
    {if $di.notes_to_studies != "" }   <div class="medhint">Zur Beachtung: {$di.notes_to_studies|nl2br}  </div> {/if}

    <span class="prebib">  </span>
    
    <div class="bibStandort"> 
      {if $di.location_id != "" and $di.doc_type_id == 1 }
        {if $di.shelf_remain != 1}  {$fachbib[ $di.location_id ].description|escape},<br/> im Regal "Semesterapparate" 
        {else}                                    Im Buchbestand der Fachbibliothek<br/> (wie im Online-Katalog angegeben).
        {/if}
      {/if}
    </div>

    {if ($staff_mode or $edit_mode) and ($user.role_name != "print") }  
          <div class="status s_{$di.state_id}" />{$media_state[$di.state_id].description}</div>

    <div class="iconlist"> 
      {include file="action_button_bar.tpl" 
    mode        = $user.role_name 
    item        = $doc_type[$di.doc_type_id].name
    state       = $media_state[$di.state_id].name
    collection_id      = $work.collection_id 
    document_id = $di.id
    url         = $di.url
    protected   = $di.protected
     }
    </div> 

  {/if}
  {if ($staff_mode or $edit_mode) and ($di.state_id == 1 or $di.state_id == 2) } {* new or open  *}
    {if $di.notes_to_staff != "" }
      <div class="staffnote"> {$di.notes_to_staff|escape|nl2br} </div>
    {/if}
  {/if}
  {/if}
</div>
{/foreach}




{if ($staff_mode or $edit_mode) and (isset ($di.num_email)) }  
  Es 
  {if $di.num_email ==  1 }  ist eine E-Mail
  {else}                                   sind {$di.num_email|escape} E-Mails 
  {/if}                                    vorhanden.
  {include file="action_button.tpl" action="view_email" }
{/if}
<!---->

{foreachelse}              
  <div style="padding:10px; margin:10px; margin-top:0px; color: #000; font-size: 14px; border: solid #AAA 2px; background-color:#efe96d; ">
    Es ist kein  Dokument  vorhanden.
  </div>
{/foreach}
