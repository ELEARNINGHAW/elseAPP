{if $work.mode != "edit"} {* <!-- HEADLINE: DEPARTMENT -->*}
{if      $work.categories == 1}                                                                                 <div class="depName bg{$work.categories}" > Semesterapparate der gesamten HAW</div> 
{elseif  $work.categories == 0}                                                                                 <div class="depName bgDef" > Semesterapparate ohne Fakultät</div> 
{elseif  $work.categories == 20 or $work.categories == 30 or $work.categories == 50 or $work.categories == 60 } <div class="depName bg{$work.categories}" > Semesterapparate der Fakultät:   {$html_options.fak[$work.categories].FakAbk|escape|utf8_encode}   </div>
{else}                                                                                                          <div class="depName bg{$work.categories}" > Semesterapparate des Department: {$html_options.dep[$work.categories].DepName|escape|utf8_encode}   </div>
{/if}
{else}                                                                                                           <div class="depName bg{$work.categories}" > Semesterapparate von:  </div>
{/if}

{foreach key=key item=c name=collection from=$collection }
{if ((isset($c[0].bib_id)))}  {*  <!-- HEADLINE:  DOZENT  -->   *}
<div class="dozentName bgDef bg{$c[0].bib_id}">
<a  class="dozentLink" href="#">{* <!-- Doz.ID --> *} {$c[0].forename} {$c[0].surname}   </a>   {if not isset($c[0].bib_id)}  {$c[0].bib_id = 'HAW'}  {/if}
<span style="float:right">{$html_options['dep'][$c[0].department]['DepName']}</span>  {*  <!-- Department -->  <!-- Fak.ID, Dozent Titel,  Vorname, Nachname -->   *}
</div>  
{section name=j loop=$c} 
{if ( ( $work.mode == "edit"  AND $c[j].state_name != 'delete') OR  ($work.mode == "admin" or $work.mode == "staff") ) AND $c[0].bib_id != 0  }  {*  <!-- HEADLINE:  SEMAPP -- EDITMODE  --> *}
<a class="name lb{$c[j].bib_id|escape} semapNameListe  " href="index.php?item=collection&collection_id={$c[j].id}&item=collection&action=b_coll_edit&amp;r={$user.role}">{$c[j].title|escape} 
</a>
{if $work.mode == "edit" or $work.mode == "staff" or $work.mode == "admin"}
<div  class="semapIconListe">
{include file="action_button_bar.tpl" mode=$work.mode item="collection" state=$c[j].state_name collection_id=$c[j].id  document_id=0 }
</div>  
{/if}  
{elseif $c[j].state_name != 'delete'} {* HEADLINE:  SEMAPP -- USER-MODE  *}
<div class='SAHeadline' style="display: block;" >
<a class="name2 semapNameListe"  href="index.php?item=collection&action=show&collection_id={$c[j].id|escape}&amp;r={$user.role}">{$c[j].title|escape}<span style="float: right; padding-right:5px;">{$c[j].bib_id}</span></a>
</div>
{/if}
{/section}
{/if}
{/foreach}

    


