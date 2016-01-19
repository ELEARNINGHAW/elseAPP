{if $work.mode == "edit"}   {assign var="restricted_edit"  value="disabled=\"yes\""} {else} {assign var="restricted_edit" value=""} {/if}
{if $work.mode == "staff"}  {assign var="restricted_staff" value="disabled=\"yes\""} {else} {assign var="restricted_staff" value=""}{/if}

<h3 style="margin:20px; padding:10px; color: #FFF; background-color: #800000;">
{if    $work.mode == "new"}   Neuen Semesterapparat anlegen für: {$colData[$work.collection_id].title}
{else}                        Semesterapparat bearbeiten : {$colData[$work.collection_id].title}
{/if}
<a style="float:right;" href="index.php?item=collection&action=show&collection_id={$coll.title_short}">
  <img  class="icon" style="margin-top:-4px;" title="Zurück" src="img/svg/chevron-left_w.svg" />
</a>
</h3>

<div style="margin:20px; margin-top:0px;  padding:10px; border:solid 1px black; ">
<form action="index.php" method="post">

<input type="hidden" name="item"           value="collection"  >

{if $work.action == "b_new"}
<input type="hidden" name="expiry_date"    value="{$colData[$coll.title_short].expiry_date|escape}"  >
<input type="hidden" name="action"         value="b_new">
<input type="hidden" name="todo"           value="init"  >
{else}
<input type="hidden" name="action"         value="b_coll_meta_edit">
<input type="hidden" name="id"             value="{$colData[$coll.title_short].id|escape}" >
<input type="hidden" name="todo"           value="save"  >
{/if}

{if $restricted_staff}
<input type="hidden" name="title"           value="{$colData[$coll.title_short].title|escape}" >
<input type="hidden"  name="collection_no"  value="{$colData[$coll.title_short].collection_no|escape}">
{/if}

<table style="text-align: left; width: 100%;" border="0"
  {* 
   <tr>
    <td width="30%" ><span style="font-weight: bold;">Titel der Vorlesung: </span></td>
    <td><input value="{$colData[$coll.title_short].title|escape}" {$restricted_staff} size="80" name="title"></td>
  </tr>
  *}     
  <tr>
    <td  style="vertical-align: top;"><span style="font-weight: bold;">Department:</span></td>
    <td> {html_options name="categories_id" options=$tpl.departments selected=$colData[$work.collection_id].categories_id }</td>
  </tr>
{if $work.mode == "staff" or $work.mode == "admin" or 1==1 }
  <tr>
    <td style="vertical-align: top;"><span style="font-weight: bold;">Standort des Semesterapparats:</span></td>
    <td>{html_options name="location_id" options=$tpl.bib_info selected=$colData[$work.collection_id].location_id } im Regal &quot;Semesterapparate&quot;</td>
  </tr>
{/if}

  <tr>
    <td style="vertical-align: top;"><span style="font-weight: bold;">(Optional)<br/>Bemerkungen für die Studierende zum Semesterapparat:</span></td>
    <td> <textarea cols="60" rows="5" name="notes_to_studies">{$colData[$work.collection_id].notes_to_studies|escape}</textarea> </td>
  </tr>
 </tbody>
</table>

  <input style="float: right;" name="b_ok" value="&nbsp;&nbsp;&nbsp;SPEICHERN&nbsp;&nbsp;&nbsp;" type="submit">

</div>

