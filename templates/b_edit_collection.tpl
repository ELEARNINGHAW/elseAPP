{assign var="restricted_edit" value=""}
{assign var="restricted_staff" value=""}

{if $work.mode == "edit"}   {assign var="restricted_edit"  value="disabled=\"yes\""}{/if}
{if $work.mode == "staff"}  {assign var="restricted_staff" value="disabled=\"yes\""}{/if}

 
{if    $work.mode == "new"}   <h3 style="margin:20px; padding:10px; color: #FFF; background-color: #800000;">Neuen Semesterapparat anlegen für: {$colData[$work.collection_id].title}</h3>
{else}                        <h3 style="margin:20px; padding:10px; color: #FFF; background-color: #800000;">Semesterapparat - {$colData[$work.collection_id].title}- bearbeiten</h3>
{/if}


<div style="margin:20px; margin-top:0px;  padding:10px; border:solid 1px black; ">
<form action="action.php" method="post">

  <input type="hidden" value="collection"  name="item">

{if $work.action == "b_new"}
<input type="hidden" value="{$colData[$work.collection_id].expiry_date|escape}"  name="expiry_date">
<input type="hidden" value="b_new"  name="action">
<input type="hidden" value="init"   name="todo">
{else}
<input type="hidden" value="b_coll_meta_edit"  name="action">
<input type="hidden" value="{$colData[$work.collection_id].id|escape}"  name="id">
<input type="hidden" value="save"   name="todo">
{/if}

{if $restricted_staff}
<input type="hidden" value="{$colData[$work.collection_id].title|escape}" name="title">
<input type="hidden" value="{$colData[$work.collection_id].collection_no|escape}"  name="collection_no">
{/if}


<table style="text-align: left; width: 100%;" border="0"
  <tr>
    <td width="30%" ><span style="font-weight: bold;">Titel der Vorlesung: </span></td>
    <td><input value="{$colData[$work.collection_id].title|escape}" {$restricted_staff} size="80" name="title"></td>
  </tr>
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
    <td style="vertical-align: top;"><span style="font-weight: bold;">Leihfrist:</span></td>
    <td> {$colData[$work.collection_id].expiry_date|regex_replace:"/(\d\d\d\d)(\d\d)(\d\d)/i":"\\1-\\2-\\3" }<a target="help_win" href="help.php?topic=leihfrist"><img border="0" src="img/help.gif" alt="?"></a>
    </td>
  </tr>

  <tr>
    <td style="vertical-align: top;"><span style="font-weight: bold;">Bemerkungen für die Studierende:</span></td>
    <td> <textarea cols="60" rows="10" name="notes_to_studies">{$colData[$work.collection_id].notes_to_studies|escape}</textarea> </td>
  </tr>
 </tbody>
</table>
</div>


  <div style="margin:20px; margin-top:0px;  padding:10px; border:solid 1px black; ">
  <b>Hinweis:</b>
<ul>
<li> <b>Nach Ablauf der Leihfrist</b> werden die im Semesterapparat
aufgestellten B&uuml;cher zur&uuml;ck an ihren Platz gestellt. Sie erhalten 
vorher eine Mitteilung per Mail, und haben die Gelegenheit, die
Leihfrist zu verl&auml;ngern.<p></p>

</ul>
  <input style="float: right;" name="b_ok" value="&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;" type="submit">
  <input style="float: right;" name="b_cancel" value="Abbrechen" type="submit">


</form>
</div>
