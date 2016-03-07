{if $work.mode != "print"}
{if !isset($footer_done)}
{assign var="footer_done" value="1"}
{/if}

{if $work.action == "show" OR $work.action == "showopen"}
  <script type='text/javascript' src='lib/jquery.modal.js'></script>
  <div class="overlay"></div>
  <div id="helpit" class="modal">
         <p class="closeBtn" style="float:right;" > <img  class="icon" title="close" src="img/svg/chevron-left.svg" /></p>
      <iframe style="width:100%; height:400px;" src="lib/hilfe.html"></iframe>
      
  </div>
    <script type='text/javascript' src='lib/modalhelp.js'></script>
{/if}
  
</body>
</html>
{/if}
