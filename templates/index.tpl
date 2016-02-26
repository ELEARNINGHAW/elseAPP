{if $work.mode != "edit"} {* <!-- HEADLINE: DEPARTMENT -->*}
{if $work.categories == 1}                                                                                      <div class="depName bg{$work.categories}" > Semesterapparate der gesamten HAW</div> 
{elseif   $work.categories == 20 or $work.categories == 30 or $work.categories == 50 or $work.categories == 60 }<div class="depName bg{$work.categories}" > Semesterapparate der Fakultät:   {$html_options.fak[$work.categories].FakAbk|escape|utf8_encode}   </div>
{else}                                                                                                          <div class="depName bg{$work.categories}" > Semesterapparate des Department: {$html_options.dep[$work.categories].DepName|escape|utf8_encode}   </div>
{/if}{else}                                                                                                     <div class="depName bg{$work.categories}" > Semesterapparate von:  </div>
{/if}

{foreach key=key item=c name=collection from=$collection }
  {if ((isset($c[0].bib_id)))}  {*  <!-- HEADLINE:  DOZENT  -->   *}

    <div class="dozentName bg{$work.categories}">
    <a  class="dozentLink" href="#">{* <!-- Doz.ID --> *} {$c[0].forename} {$c[0].surname}   </a>   {if not isset($c[0].bib_id)}  {$c[0].bib_id = 'HAW'}  {/if}
    <span style="float:right">{$html_options['dep'][$c[0].department]['DepName']}</span>  {*  <!-- Department -->  <!-- Fak.ID, Dozent Titel,  Vorname, Nachname -->   *}
    </div>  

    {section name=j loop=$c} 
    {if ( ( $work.mode == "edit"  AND $c[j].state_name != 'delete') OR  ($work.mode == "admin" or $work.mode == "staff") ) AND $c[0].bib_id != 0  }  {*  <!-- HEADLINE:  SEMAPP -- EDITMODE  --> *}
      <a class="name lb{$c[j].bib_id|escape} semapNameListe  " href="index.php?item=collection&collection_id={$c[j].id}&item=collection&action=b_coll_edit">{$c[j].title|escape} 
      {if     $c[j].state_name == 'active'  }<br><span class='colList' style="background-color: #009966;">&nbsp; <b> Leihfrist: bis {$c[j].expiry_date|regex_replace:"/^(....)-(..)-(..)$/":"\\3. \\2. \\1"|escape}                                                </b> &nbsp; </span> 
      {elseif $c[j].state_name == 'obsolete'}<br><span class='colList' style="background-color: #FF0000;">&nbsp; <b>Die Leihfrist endet am {$c[j].expiry_date|regex_replace:"/^(....)-(..)-(..)$/":"\\3. \\2. \\1"|escape} und  wird demn&auml;chst aufgel&ouml;st!</b> &nbsp; </span> 
      {elseif $c[j].state_name == 'inactive'}<br><span class='colList' style="background-color: #B0B0B0;">&nbsp; <b>IST AUFGEL&Ouml;ST                                                                                                                            </b> &nbsp; </span>
      {elseif $c[j].state_name == 'delete'  }<br><span class='colList' style="background-color: #000000;">&nbsp; <b>IST GEL&Ouml;SCHT                                                                                                                            </b> &nbsp; </span>
      {/if}
      </a>
      {if $work.mode == "edit" or $work.mode == "staff" or $work.mode == "admin"}
        <div  class="semapIconListe">
        {include file="action_button_bar.tpl" mode=$work.mode item="collection" state=$c[j].state_name collection_id=$c[j].id  document_id=0 }
        </div>  
      {/if}  
      
    {elseif $c[j].state_name != 'delete'} {* HEADLINE:  SEMAPP -- USER-MODE  *}
      <div class='SAHeadline' style="display: block;" >
        <a class="name2 semapNameListe"  href="index.php?item=collection&action=show&collection_id={$c[j].id|escape}">{$c[j].title|escape}<span style="float: right; padding-right:5px;">{$c[j].bib_id}</span></a>
      </div>
    {/if}

    {/section}
  {/if}
{/foreach}

    


