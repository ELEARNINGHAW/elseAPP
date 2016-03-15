<h3 style="margin:10px; padding:10px; color: #FFF;" class="bgDef bg{$colData[$coll.title_short].coll_bib_id}" >
{$colData[$coll.title_short].title} : Katalogsuche <a style="float:right;" href="index.php?item=collection&action=show&collection_id={$coll.title_short}&r={$user.role}"><img  class="icon" style="margin-top:-4px;" title="Zurück" src="img/svg/chevron-left_w.svg" /></a>
</h3>

<div id='basic-modal'>
{if $page == "1"}                      
{* ------- Eingabefelder Titel/Autor/Signatur für die Buch-Suchmaske ----------- *}
{if $searchHits < 1}
<h3 style="margin:10px; margin-bottom: 0px; margin-top: 0px; padding:10px; color: #FFF; background-color: #600000;">Suchergebniss: {$searchHits} Treffer  für {$book.title}{$book.author}{$book.signature}</h3>
<div style="margin:10px;  padding:0px;">Sie k&ouml;nnen nun:</div>
<div style="margin:10px; margin-bottom: 10px; padding:10px;  padding-bottom:20px;  text-height: 150%; border:solid 1px black; ">
<div style="font-size:35px; float:left; padding:10px; margin:5px; display:block;   background-color:#EFEFEF">A</div> Einen Bestellwunsch über einen Erwerbungsvorschlag vornehmen.<br/><br> Die Bearbeitung kann 2-3 Wochen dauern.<br><br>
<div style="display:block; padding:4px; margin-left:55px; width:450px;" >
<a style ="text-decoration: none;"  href="index.php?item=book&action=purchase_suggestion&collection_id={$colData[$coll.title_short].collID|escape}&r={$user.role}"><div style="border:1px solid black; font:700 14px; color:#000; background-color: #EFEFEF; padding:3px; " >Zum Erwerbungsvorschlag</div></a>
</div>
</div> 
{/if}

<div style="margin:10px; margin-bottom: 0px; padding:10px; border:solid 1px black; ">
{if $searchHits < 1}
 <div style="font-size:35px; float:left; padding:10px; margin:5px; margin-bottom:100px;display:block;   background-color:#EFEFEF">B</div>Eine neue Suche starten:<br><br>
{/if}
Bitte geben Sie in dieser Suchmaske <b>Titel</b> und / oder <b>Autor</b> und / oder <b>Signatur</b> ein.<br><br>
Das Buch wird dann im HIBS Online-Katalog gesucht.<br><br>
Bei mehreren Treffern erscheint eine Auswahlliste. Es werden maximal 50 Treffer angezeigt.<br><br>
Ihre Auswahl wird  &uuml;bernommen und erscheint in Ihrer Literaturliste. <br><br>
<form action="index.php" method="get">
<input type="hidden" name="action"        value="search" >
<input type="hidden" name="item"          value="book">
<input type="hidden" name="r"             value="{$user.role}">
<input type="hidden" name="collection_id" value="{$colData[$coll.title_short].collID|escape}">
<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
<tbody>
<tr><td class="head1">Titel:               </td><td><input class="txtin"  size="80" value="{$book.title|escape}"     name="title"></td></tr>
<tr><td class="head1">Autor (Nachname):    </td><td><input class="txtin"  size="80" value="{$book.author|escape}"    name="author"></td></tr>
<tr><td class="head1">Signatur:            </td><td><input class="txtin"  size="80" value="{$book.signature|escape}" name="signature"></td></tr>
</tbody>
</table>
<input style="float: right;" name="basic"  class="basic" value="&nbsp;&nbsp;&nbsp;SUCHE&nbsp;&nbsp;&nbsp;" type="submit">
</form>
{/if}


{if $page == "2"}                       {* ------- Tefferliste der Suche   ----------- *}
<h3 style="margin:10px; margin-bottom: 0px; margin-top: 0px; padding:10px; color: #FFF; background-color: #600000; font-size: 11px; ">Suchergebniss: {$searchHits} Treffer</h3>
<div style="margin:0px; margin-left:10px; margin-right:10px; padding:10px; font-weight: bold; color:#990000; border: 1px solid #444; font-size: 11px; ">Bitte w&auml;hlen Sie das gew&uuml;nschte Medium aus der Liste aus </div>
{foreach from=$books_info item=b}        
{assign var="item"  value=""}
{if (isset ( $b.physicaldesc ))}  
{if ($b.physicaldesc == 'print'      )} {assign var="item"  value="book" } {/if}
{if ($b.physicaldesc == 'electronic' )} {assign var="item"  value="ebook"} {/if}
{/if}
<a class="hitlink_{$b.doc_type}" href="index.php?ppn={$b.ppn}&item={$b.item}&action=annoteNewMedia&collection_id={$colData[$coll.title_short].collID}&mode=new&r={$user.role}">
<table>
{if (isset ( $b.title         ) AND $b.title        != "" )}<tr><td><div class="mediaListHeader">Titel:    </div></td><td><span class="mediaTxt">{$b.title|escape}                           </span></td></tr>{/if}
{if (isset ( $b.author       ) AND $b.author       != "" )}<tr><td><div class="mediaListHeader">Autor:    </div></td><td><span class="mediaTxt">{$b.author|escape}                          </span></td></tr>{/if}
{if (isset ( $b.signature    ) AND $b.signature    != "" )}<tr><td><div class="mediaListHeader">Signatur: </div></td><td><span class="mediaTxt">{$b.signature|escape}                       </span></td></tr>{/if}
{if (isset ( $b.doc_type     ) AND $b.doc_type     != "" )}<tr><td><div class="mediaListHeader">Art:      </div></td><td><span class="mediaTxt">{$b.doc_type|escape}                        </span></td></tr>{/if}
{if (isset ( $b.physicaldesc ) AND $b.physicaldesc != "" )}<tr><td><div class="mediaListHeader">Format:   </div></td><td><span class="mediaTxt">{$b.physicaldesc|escape}                    </span></td></tr>{/if}
{if (isset ( $b.edition      ) AND $b.edition      != "" )}<tr><td><div class="mediaListHeader">Edition:  </div></td><td><span class="mediaTxt">{$b.edition|escape}                         </span></td></tr>{/if}
{if (isset ( $b.publisher    ) AND $b.publisher    != "" )}<tr><td><div class="mediaListHeader">Verlag:   </div></td><td><span class="mediaTxt">{$b.publisher|escape}                       </span></td></tr>{/if}
{if (isset ( $b.year         ) AND $b.year         != "" )}<tr><td><div class="mediaListHeader">:         </div></td><td><span class="mediaTxt">{$b.year|escape}                            </span></td></tr>{/if}
{if (isset ( $b.volume       ) AND $b.volume       != "" )}<tr><td><div class="mediaListHeader">:         </div></td><td><span class="mediaTxt">{$b.volume|escape}                          </span></td></tr>{/if}
</table>
</a>
{foreachelse}   <h3 style="color:red; text-align:center; width:100%; height:50px;  vertical-align: middle; border: 2px solid red;">ERROR: Zur Zeit keine Verbindung zum Bibliotheksserver möglich.<br>(OPAC)</h3>

{/foreach}
<div class="text">
Haben Sie nicht das Gewünschte gefunden?<br/> Machen Sie doch einen 
<a  class="erwebungsvorschlag"  href="index.php?item=book&action=purchase_suggestion&collection_id={$colData[$coll.title_short].collID}&r={$user.role}">Erwerbungsvorschlag</a>
</div>
{/if}
</div>
</div>

<div id="basic-modal-content">
		

<img src="img/loader.gif" style="width:80px; heigth:80px" /></div>
<script type='text/javascript' src='lib/jquery.simplemodal.js'></script>
<script type='text/javascript' src='lib/basic.js'></script>

