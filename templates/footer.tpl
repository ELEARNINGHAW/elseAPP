{if $work.mode != "print"}
{if !isset($footer_done)}
{assign var="footer_done" value="1"}
{/if}

  <div class="overlay"></div>
  <div id="helpit" class="modal">
         <p class="closeBtn" style="float:right;" > <img  class="icon" title="close" src="img/svg/chevron-left.svg" /></p>
      <iframe style="width:100%; height:400px;" src="hilfe.html"></iframe>
  </div>
  
</body>
</html>
{/if}
