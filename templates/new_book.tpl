{config_load file='site.conf'}
{config_load file='site.conf' section='new_book'}

<a style="text-decoration: none; " href="action.php?item=collection&mode=edit&state=&collection_id={$colData[$work.collection_id].id}&document_id={$colData[$work.collection_id].id}">
  <h3 style="margin:10px; padding:10px; margin-bottom: 0px;  margin-top: 0px; color: #FFF; background-color: #990000;">{$colData[$work.collection_id].title}</h3></a>

{if $page == "1"}                      {* ------- Eingabefelder Titel/Autor/Signatur für die Buch-Suchmaske ----------- *}

  {if $searchHits == 1}
   <h3 style="margin:10px;  margin-bottom: 0px;  margin-top: 0px; padding:10px; color: #FFF; background-color: #600000;">Katalogsuche </h3>
  {elseif $searchHits < 1}
  <h3 style="margin:10px; margin-bottom: 0px; margin-top: 0px; padding:10px; color: #FFF; background-color: #600000;">Suchergebniss: {$searchHits} Treffer</h3>
  <div style="margin:10px;  padding:10px; border:solid 1px black;"><span style= "color: red;">
      <b>Es wurde kein Eintrag im Katalog mit dem Suchbegriff [{$book.title} {$book.author} {$book.signature} ] gefunden</b></span>
    <p>Sie k&ouml;nnen nun: 
    <ol type="A">
      <li>die Suche erneut nutzen, oder </li> 
      <li>einen Bestellwunsch über einen Erwerbungsvorschlag vornehmen.<br/> Die Bearbeitung kann 2-3 Wochen dauern.
    <br />
    <br />
    <a  style =" text-decoration: none ; font:700 14px; color:#000; background-color: #EFEFEF; padding:3px; "  href="http://localhost/ELSE/htdocs/action.php?item=book&action=purchase_suggestion&collection_id={$colData[$work.collection_id].id}">Erwerbungsvorschlag</a>
      </li>
    </ol>
    
  </div>
  {/if}
  
  <div style="margin:10px; margin-bottom: 0px; padding:10px; border:solid 1px black; ">
  Bitte geben Sie in dieser Suchmaske <b>Titel</b> und / oder <b>Autor</b> und / oder <b>Signatur</b> ein.<br><br>
  Das Buch wird dann im HIBS Online-Katalog gesucht.<br><br>
  Bei mehreren Treffern erscheint eine Auswahlliste. Es werden maximal 50 Treffer angezeigt.<br><br>
  Ihre Auswahl wird  &uuml;bernommen und erscheint in Ihrer Literaturliste. <br><br>
  </div>

  <div style="margin:10px; margin-top:0px;  padding:10px; border:solid 1px black; ">
  <form action="action.php" method="post">
  <input type="hidden" name="action" value="search" >
  <input type="hidden" name="item" value="book">
  <input type="hidden" name="collection_id" value="{$colData[$work.collection_id].id|escape}">

  <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
   <tbody>
    <tr><td class="head1">Titel:                                               </td><td><input class="txtin"  size="80" value="{$book.title|escape}"     name="title"></td></tr>
    <tr><td class="head1">Autor (Nachname):                                    </td><td><input class="txtin"  size="80" value="{$book.author|escape}"    name="author"></td></tr>
    <tr><td class="head1">Signatur:                                            </td><td><input class="txtin"  size="80" value="{$book.signature|escape}" name="signature"></td></tr>
   </tbody>
  </table>
  <input style="float: right;" name="b_ok" value="&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;" type="submit">
  <input style="float: right;" name="b_cancel" value="Abbrechen" type="submit">
  </form>
{/if}






{if $page == "2"}                       {* ------- Tefferliste der Suche   ----------- *}
  <h3 style="margin:10px; margin-bottom: 0px; margin-top: 0px; padding:10px; color: #FFF; background-color: #600000;">Suchergebniss: {$searchHits} Treffer</h3>
  <div style="margin:0px; margin-left:10px; margin-right:10px; padding:10px; font-weight: bold; color:#990000; border: 1px solid #444;">Bitte w&auml;hlen Sie das gew&uuml;nschte Buch aus der Liste aus </div>
  <table style="margin-left:10px; text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
     <tbody>

      {foreach from=$books_info item=b}        
        {assign var="item"  value=""}
        {if (isset ( $b.physicaldesc ))}  
          {if ($b.physicaldesc == 'print'      )} {assign var="item"  value="book" } {/if}
          {if ($b.physicaldesc == 'electronic' )} {assign var="item"  value="ebook"} {/if}
        {/if}

      {if (isset ( $b.title  ) AND $b.title != "" )}
          <tr valign="top"><td  class="border2top"  colspan="3"></td></tr>
          <tr valign="top">
           <td class="mediaListHeader" style="padding: 10px;padding-left:3px;">Titel:</td>
           <td>      <a class="hitlink_{$b.physicaldesc}" href="action.php?ppn={$b.ppn}&item={$item}&action=annoteNewMedia&collection_id={$colData[$work.collection_id].id} ">{$b.title|escape} <span class="mediaListHeader">({$b.physicaldesc|escape})</span></a></td>
        </tr>
      {/if}
      {if (isset ( $b.author       ) AND $b.author       != "" )} <tr valign="top"><td class="mediaListHeader">Autor:    </td><td>{$b.author|escape}      </td></tr>{/if}
      {if (isset ( $b.signature    ) AND $b.signature    != "" )} <tr valign="top"><td class="mediaListHeader">Signatur: </td><td>{$b.signature|escape}   </td></tr>{/if}
      {if (isset ( $b.physicaldesc ) AND $b.physicaldesc != "" )} <tr valign="top"><td class="mediaListHeader">Medienart:</td><td>{$b.physicaldesc|escape}</td></tr>{/if}
      {if (isset ( $b.edition      ) AND $b.edition      != "" )} <tr valign="top"><td class="mediaListHeader">Edition:  </td><td>{$b.edition|escape}     </td></tr>{/if}
      {if (isset ( $b.publisher    ) AND $b.publisher    != "" )} <tr valign="top"><td class="mediaListHeader">Verlag:   </td><td>{$b.publisher|escape}   </td></tr>{/if}
      {if (isset ( $b.year         ) AND $b.year         != "" )} <tr valign="top"><td class="mediaListHeader">:         </td><td>{$b.year|escape}        </td></tr>{/if}
      {if (isset ( $b.volume       ) AND $b.volume       != "" )} <tr valign="top"><td class="mediaListHeader">:         </td><td>{$b.volume|escape}      </td></tr>{/if}
      </a>

      {foreachelse}                                               <tr><td> <b> Keine B&uuml;cher mit Signatur  gefunden.</b> <br> Bitte versuchen Sie es noch einmal.</td></tr>

      {/foreach}

     </tbody>
    </table>

{/if}




</div>