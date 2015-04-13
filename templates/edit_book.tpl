{assign var="restricted"       value=""}
{assign var="restricted_staff" value=""}
{assign var="restricted2"      value=""}

{if $work.mode == "edit" AND ! $work.todo == "suggest"}  	    {assign var="restricted"       value="disabled=\"yes\""} {/if}
{if $work.mode == "staff"}      {assign var="restricted_staff" value="disabled=\"yes\""} {/if}

{if ($book.doc_type_id == 4)}  {assign var="itemtxt" value="E-Book"}
{else}                         {assign var="itemtxt" value="Buch"}
{/if}

<h3 style="margin:10px; margin-bottom:0px; margin-top:0px; padding:10px; color: #FFF; background-color: #600000;">{$itemtxt} bearbeiten für: {$colData[$work.collection_id].title}</h3>

{if $work.mode == 'suggest' }
  <div style="margin:10px;  padding:10px; border:solid 1px black; ">
    <h3>Erwerbungsvorschlag</h3>  
Wenn Sie ein Buch zur Anschaffung in der Bibliothek vorschlagen m&ouml;chten und dieses in 
Ihren Semesterapparat aufgenommen werden soll, benutzen Sie bitte diese Formular.<br><br>
Wir geben Ihnen eine Rückmeldung, ob wir Ihnen das Buch beschaffen k&ouml;nnen. 
</div>
{/if}

<div style="margin:10px; margin-top:0px;  padding:10px; border:solid 1px black; ">
<form  action="action.php" method="post">

{if      ($book.doc_type_id == 4)} <input type="hidden" value="ebook" name="item" > <input type="hidden" value="4"                    name="doc_type_id">
{elseif  ($book.doc_type_id == 1)} <input type="hidden" value="book"  name="item" > <input type="hidden" value="1"                    name="doc_type_id">
{else}                                                                              <input type="hidden" value="{$work.doc_type_id}"  name="doc_type_id">
{/if}

{if     $work.mode == "new"    } <input type="hidden" value="init"     name="action">
{elseif $work.mode == "save"   } <input type="hidden" value="save"     name="action">
{elseif $work.mode == "edit"   } <input type="hidden" value="save"     name="action">
{elseif $work.mode == "suggest"} <input type="hidden" value="suggest"  name="action">
{/if}

<input type="hidden" value="{$work.document_id|escape}"  name="document_id">
<input type="hidden" value="{$work.collection_id|escape}"name="collection_id">
<input type="hidden" value="{$book.ppn|escape}"          name="ppn">
<input type="hidden" value="{$work.redirect|escape}"     name="redirect">

{if $restricted}
	<input type="hidden" value="{$book.title|escape}"      name="title">
	<input type="hidden" value="{$book.author|escape}"     name="author">
	<input type="hidden" value="{$book.publisher|escape}"  name="publisher">
	<input type="hidden" value="{$book.year|escape}"       name="year">
  <input type="hidden" value="{$book.volume|escape}"     name="volume">
  <input type="hidden" value="{$book.edition|escape}"    name="edition">
  <input type="hidden" value="{$book.signature|escape}"  name="signature">
  <input type="hidden" value="{$book.ppn|escape}"        name="ppn">
{/if}

{assign var="restricted_staff" value=""}
{if $work.mode == "staff"}    {assign var="restricted_staff" value="disabled=\"yes\""} {/if}

<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
<tbody>
 <tr>
  <td style="font-weight: bold;">Titel: </td>
	<td><input size="80" value="{$book.title|escape}" {$restricted} name="title"></td>
 </tr>
 <tr>
   <td style="font-weight: bold;">Autor:</td>
 	<td><input size="80" value="{$book.author|escape}" {$restricted} name="author"></td>
 </tr>
 <tr>
   <td style="font-weight: bold;">Verlag:</td>
  <td><input size="80" value="{$book.publisher|escape}" {$restricted} name="publisher"></td>
 </tr>
 {if ($book.doc_type_id == 1)}
 <tr>
   <td style="font-weight: bold;">Signatur:</td>
   <td><input size="20" value="{$book.signature|escape}" {$restricted} name="signature"></td>
 </tr>
 {/if}
 <tr>
 <tr>
   <td style="vertical-align: top; font-weight: bold;">Bemerkungen <br>f&uuml;r die Studenten:</td>
   <td><textarea {$restricted_staff} cols="60" rows="10" name="notes_to_studies">{$book.notes_to_studies|escape}</textarea></td>
</tr>

{if ($book.doc_type_id == 1)} {* doc_type 1 = Buch *}
<tr colspan="2" >
 <td colspan="2"> 
 

<div style="border:1px solid #AAA; height:35px; padding: 5px;">
<div style="width: calc(100% - 60px); float:left;">
Falls das bestellte Buch NICHT in Ihren Handapparat gestellt, sondern als reiner Literaturhinweis im Regal der Bibliothek verbleiben soll, setzen Sie bitte hier einen Haken.
</div>
{if $book.shelf_remain==1} 	<input class="checkBox" type="checkbox"  value="1" name="shelf_remain" checked="yes"> 
{else}                   		<input class="checkBox" type="checkbox"  value="1" name="shelf_remain"> 
{/if}
</div
 </td></tr>

<tr>
 <td style="vertical-align: top; font-weight: bold;">Bemerkungen <br>f&uuml;r die HIBS Mitarbeiter:</td>
  <td><textarea cols="60" rows="10" name="notes_to_staff">{$book.notes_to_staff|escape}</textarea></td>
 </tr>

{/if}
 </tbody>
</table>

<br>
  <input style="float: right;" name="b_ok" value="&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;" type="submit">
  <input style="float: right;" name="b_cancel" value="Abbrechen" type="submit">

</div>