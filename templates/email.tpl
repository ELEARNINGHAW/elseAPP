   
 <h3 style="margin:10px; padding:10px; color: #FFF; background-color: #800000;">
{$ci.title} : {$documentName } : Email
   <a style="float:right;" href="index.php?item=collection&action=show&collection_id={$ci.title_short}">
  <img  class="icon" style="margin-top:-4px;" title="Zurück" src="img/svg/chevron-left_w.svg" />
</a>
</h3> 


{if $linkTxt == ''}  
  <br />
  <div  style="margin:10px; font-family:Arial, Helvetica, sans-serif;" >An: {$toFirstName} {$toName} &lt;{$toEmail}&gt; </div>
<div  style="margin:10px; font-family:Arial, Helvetica, sans-serif;" >Betreff: Ihr ELSE Semesterapparat </div>
<form  action="index.php" >

<input  name="to"              type="hidden"  value="{$toEmail}"/>
<input  name="from"            type="hidden"  value="{$fromEmail}"/>
<input  name="collection_id"   type="hidden"  value="{$collection_id}"/>
<input  name="action"          type="hidden"  value="sendmail"/>
<input  name="item"            type="hidden"  value="email"/>
<br />
<textarea  style="margin:10px; width:95%" name="txt" cols="60" rows="18">{$salutaton}

Ihr Semesterapparat: {$collectionName }

Ihr Dokument: {$documentName }


{$doc_info.notes_to_staff}
 

Mit freundlichen Gr&uuml;&szlig;en
{$fromFirstName} {$fromName}

HIBS-Serviceteam 
{$fromEmail}



</textarea>
<br />
<input  style="float:right; margin:10px;" name="send"  value="Senden" type="submit" />
</form>

{else}
  <br />
  <br />
  <br />
  <br />
  <br />
  
  <h1 style="text-align: center"><a style="text-decoration: none;" href="{$url}">{$linkTxt}</a></h1>
  
  
{/if}


</body>
</html>
