{if $work.mode != "print"}
{if !isset($footer_done)}
{assign var="footer_done" value="1"}
 

<div class="overlay"></div>
  <div id="helpit" class="modal">
      <p class="closeBtn">Close</p>
      <iframe style="width:100%; height:600px;" src="help.php"></iframe>
      <!--iframe style="width:100%; height:600px;" src="email.php"></iframe-->  
  </div>
    
  <script type='text/javascript' src="lib/jquery-1.10.0.min.js"></script>
  <script type='text/javascript' src='lib/jquery.modal.js'></script>
  <script type="text/javascript" src="lib/else.js"></script>
  <script type='text/javascript' src='lib/site.js'></script>    
{/if}



</body>
</html>
{/if}
