<div class="SAMeta bg{$ci.categories_id}">
   <a href="action.php?item=collection&collection_id={$ci.id}&item=collection&action=b_coll_edit" style="text-decoration: none;"><div class="SAtitel" >{$ci.title}</div></a> 
</div>


{if $link == ''}  
  <br />
  <div  style="margin:10px; font-family:Arial, Helvetica, sans-serif;" >An: {$toFirstName} {$toName} &lt;{$toEmail}&gt; </div>
<div  style="margin:10px; font-family:Arial, Helvetica, sans-serif;" >Betreff: Ihr ELSE Semesterapparat </div>
<form  action="action.php" >

<input  name="to"              type="hidden"  value="{$toEmail}"/>
<input  name="from"            type="hidden"  value="{$fromEmail}"/>
<input  name="collection_id"   type="hidden"  value="{$collection_id}"/>
<input  name="action"          type="hidden"  value="sendmail"/>
<input  name="item"            type="hidden"  value="email"/>
<br />
<textarea  style="margin:10px; width:95%" name="txt" cols="60" rows="18">{$salutaton}

Ihr Semesterapparat: {$collectionName }

Ihr Dokument: {$documentName }


 

Mit freundlichen Gr&uuml;&szlig;en

{$fromFirstName} {$fromName}
HIBS-Serviceteam 

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
  
  <h1 style="text-align: center ">{$link}</h1>
  
  
{/if}


</body>
</html>
