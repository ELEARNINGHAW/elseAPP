function mainmenu(){
$(" #nav ul ").css({display: "none"}); // Opera Fix
$(" #nav li").hover(function(){
		$(this).find('ul:first').css({visibility: "visible",display: "none"}).show(400);
		},function(){
		$(this).find('ul:first').css({visibility: "hidden"});
		});
}
function statemenu(){
$(" #nav2 ul ").css({display: "none"}); // Opera Fix
$(" #nav2 li").hover(function(){
		$(this).find('ul:first').css({visibility: "visible",display: "none"}).show(400);
		},function(){
		$(this).find('ul:first').css({visibility: "hidden"});
		});
}

 $(document).ready(function(){					
	mainmenu();
	statemenu();
  
   $('.modalLink').modal({
        trigger: '.modalLink',          // id or class of link or button to trigger modal
        olay:'div.overlay',             // id or class of overlay
        modals:'div.modal',             // id or class of modal
        animationEffect: 'fadeIn',      // overlay effect | slideDown or fadeIn | default=fadeIn
        animationSpeed: 600,            // speed of overlay in milliseconds | default=400
        moveModalSpeed: 'slow',         // speed of modal movement when window is resized | slow or fast | default=false
        background: 'eeeeee',           // hexidecimal color code - DONT USE #
        opacity: 0.7,                   // opacity of modal |  0 - 1 | default = 0.8
        openOnLoad: false,              // open modal on page load | true or false | default=false
        docClose: true,                 // click document to close | true or false | default=true    
        closeByEscape: true,            // close modal by escape key | true or false | default=true
        moveOnScroll: true,             // move modal when window is scrolled | true or false | default=false
        resizeWindow: true,             // move modal when window is resized | true or false | default=false
        video: 'http://player.vimeo.com/video/2355334?color=eb5a3d',    // enter the url of the video
        videoClass:'video',             // class of video element(s)
        close:'.closeBtn'               // id or class of close button
    });
  
    $(function() {
    $( ".column" ).sortable({
      placeholder: "mediaInSA-placeholder",
      items: ".mediaInSA",
      axis: "y"
    });
  });
  
   $( "#column li" ).disableSelection();

   $( ".column" ).on( "sortstop", function( event, ui ) { 
       sortedIDs = $( ".column" ).sortable( "toArray" );

      $.post("index.php",
      {
      item: 'collection',  
      action: 'resort',
      sortoder: sortedIDs
      },
      
      function(data, status){
      }
    ) ;
  
  } );
  
  /* Sortierfunktion der Dokumente innerhalb des Semesterapparats wird deaktiviert */
 
   
  /* Bei: Rolle != Admin/Staff/Editor  */ 
  ro = GET('ro').substring(0,2);
  if ( ro == 'Mw' || ro == 'Mg' || ro == 'MQ' )
  { 
  }
  else
  {
      $( ".column" ).sortable( "destroy" );
  }  

  /* Bei: Dokumentenlisten mit spezifischen Zuständen (aktiv/wird bearbeitet/... */
  if (GET('action') == 'showopen')
  { 
   $( ".column" ).sortable( "destroy" );
  }
});


function GET(v)
{
  if(!HTTP_GET_VARS[v]){return 'undefined';}
  return HTTP_GET_VARS[v];
}

 
HTTP_GET_VARS=new Array();
strGET = document.location.search.substr( 1,document.location.search.length );
if( strGET!='' )
{
  gArr = strGET.split( '&' );
  for( i = 0 ; i < gArr.length; ++i )
  {
    v= '' ; vArr=gArr[i].split( '=' );
    if( vArr.length > 1 ){ v = vArr[ 1 ]; }
    HTTP_GET_VARS[ unescape( vArr[ 0 ] ) ] = unescape( v );
  }
}
 


