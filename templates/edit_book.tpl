{assign var="restricted"       value=""}
{assign var="restricted2"      value=""}
{if $user.role_name == "staff" OR  $user.role_name == "admin" OR  $work.mode == 'suggest' }
{else}  {assign var="restricted"       value="disabled=\"yes\""} 
{/if}
{if     ($book.doc_type_id == 4)}  {assign var="itemtxt" value="E-Book"}
{elseif ($book.doc_type_id == 3)}  {assign var="itemtxt" value="CD-ROM"}
{else}                         {assign var="itemtxt" value="Buch"}
{/if}
{if $work.mode == 'suggest' }
<h3 style="margin:10px; margin-bottom:0px; margin-top:0px; padding:10px; color: #FFF;" class="bgDef bg{$colData[$coll.title_short].coll_bib_id}">Erwerbungsvorschlag für: {$colData[$work.collection_id].title}<a style="float:right;" href="index.php?item=collection&action=show&collection_id={$coll.title_short}&r={$user.role|escape}"><img  class="icon" style="margin-top:-4px;" title="Zurück" src="img/svg/chevron-left_w.svg" /></a></h3>
<div style="margin:10px;  padding:10px; border:solid 1px black; ">
Wenn Sie ein Buch zur Anschaffung in der Bibliothek vorschlagen m&ouml;chten und dieses in  Ihren Semesterapparat aufgenommen werden soll, benutzen Sie bitte diese Formular.<br/><br/>Wir geben Ihnen eine Rückmeldung, ob wir Ihnen das Buch beschaffen k&ouml;nnen. 
</div>
{else} 
<h3 style="margin:10px; padding:10px; color: #FFF"  class="bgDef bg{$colData[$coll.title_short].coll_bib_id}" >{$colData[$work.collection_id].title} : {$itemtxt} bearbeiten<a style="float:right;" href="index.php?item=collection&action=show&collection_id={$coll.title_short}&r={$user.role|escape}"><img  class="icon" style="margin-top:-4px;" title="Zurück" src="img/svg/chevron-left_w.svg" /></a></h3> 
{/if}
<div style="margin:10px; margin-top:0px;  padding:10px; border:solid 1px black; ">
<form  action="index.php" method="get">
{if      ($book.doc_type_id == 4)}<input type="hidden" name="item"         value="ebook"    > <input type="hidden" name="doc_type_id" value="4"  >
{elseif  ($book.doc_type_id == 2)}<input type="hidden" name="item"         value="lh_book"  > <input type="hidden" name="doc_type_id" value="1"  >
{elseif  ($book.doc_type_id == 3)}<input type="hidden" name="item"         value="cd-rom"   > <input type="hidden" name="doc_type_id" value="1"  >
{elseif  ($book.doc_type_id == 1)}<input type="hidden" name="item"         value="book"     > <input type="hidden" name="doc_type_id" value="2"  >
{else}                                                                                        <input type="hidden" name="doc_type_id" value="{$work.doc_type_id}"  >
{/if}                                                          
{if     $work.mode == "new"      }<input type="hidden" name="action"        value="init"     >
{elseif $work.mode == "save"     }<input type="hidden" name="action"        value="save"     >
{elseif $work.mode == "edit"     }<input type="hidden" name="action"        value="save"     >
{elseif $work.mode == "suggest"  }<input type="hidden" name="action"        value="suggest"  >
{/if}                                             
<input type="hidden" name="document_id"   value="{$work.document_id|escape}"   >
<input type="hidden" name="collection_id" value="{$work.collection_id|escape}" >
<input type="hidden" name="ppn"           value="{$book.ppn|escape}"           >
<input type="hidden" name="redirect"      value="{$work.redirect|escape}"      >
<input type="hidden" name="physicaldesc"  value="{$book.physicaldesc|escape}"  >
<input type="hidden" name="r"             value="{$user.role|escape}"   >
{if $restricted}     
<input type="hidden" name="title"         value="{$book.title|escape}"         > 
<input type="hidden" name="author"        value="{$book.author|escape}"        >
<input type="hidden" name="publisher"     value="{$book.publisher|escape}"     >
<input type="hidden" name="year"          value="{$book.year|escape}"          >
<input type="hidden" name="volume"        value="{$book.volume|escape}"        >
<input type="hidden" name="edition"       value="{$book.edition|escape}"       >
<input type="hidden" name="signature"     value="{$book.signature|escape}"     >
<input type="hidden" name="ppn"           value="{$book.ppn|escape}"           >
{/if}               

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
{if ($book.doc_type_id == 1) && $work.mode != 'suggest'  }
<tr>
<td style="font-weight: bold;">Signatur:</td>
<td><input size="20" value="{$book.signature|escape}" {$restricted} name="signature"></td>
</tr>
{/if}
<tr>
<tr>
<td style="vertical-align: top; font-weight: bold;">(Optional) Bemerkungen <br>f&uuml;r die Stud:</td>
<td><textarea  cols="60" rows="5" name="notes_to_studies">{$book.notes_to_studies|escape}</textarea></td>
</tr>
{if (($book.doc_type_id == 1 OR $book.doc_type_id == 3 ) AND $work.mode == 'new')} {* doc_type 1 = Buch oder CD im SA *}
<tr >
<td style="vertical-align: top; font-weight: bold;">Als Literaturhinweis:</td>
<td> 
<div style="border:1px solid #AAA; height:50px; padding: 5px;">
<div style="width: calc(100% - 60px); float:left;">
Falls das bestellte Buch NICHT in Ihren Handapparat gestellt, sondern als reiner Literaturhinweis im Regal der Bibliothek verbleiben soll, setzen Sie bitte hier einen Haken.
</div>
{if $book.shelf_remain==1}
<input class="checkBox" type="checkbox"  value="1" name="shelf_remain" checked="yes"> 
{else}
<input class="checkBox" type="checkbox"  value="1" name="shelf_remain"> 
{/if}
</div>
</td></tr>
{/if}
{if ($book.doc_type_id == 1 OR $book.doc_type_id == 3 )} {* doc_type 1 = Buch oder CD im SA *}
<tr>
<td style="vertical-align: top; font-weight: bold;">(Optional) Bemerkungen <br>f&uuml;r die HIBS Mitarbeiter:</td>
<td><textarea cols="60" rows="5" name="notes_to_staff">{$book.notes_to_staff|escape}</textarea></td>
</tr>
{/if}
</tbody>
</table>
<br>
<input style="float: right;" name="b_ok" value="&nbsp;&nbsp;&nbsp;SPEICHERN&nbsp;&nbsp;&nbsp;" type="submit">
</form>
</div>