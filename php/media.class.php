<?php

require_once('../php/sql.class.php'         );
require_once('../php/renderer.class.php'    );

class Media
{

var $renderer;   
var $sql;
  
function Media()
{
   $this->sql        = new SQL();
   $this->renderer   = new Renderer();
}  
  
####################### --- MEDIA  --- #######################

function updateMediaMetaData( $IW,  $IU )
{  
  $this->sql-> updateMediaMetaData($IW);                                                                   /* Metadaten des neuen Mediums speichern */
  $url = "index.php?item=collection&collection_id=".$IW['collection_id']."&ro=".$IU['role_encode']."&action=b_coll_edit";
  $this->renderer-> doRedirect( $url);
  exit(0);
}

function editMediaMetaData( $IW,  $IC )
{
   $book =  $this->sql->getMediaMetaData($IW['document_id']);
   
   $tpl_vars['book']                      = $book;    
   $tpl_vars['work']                      = $IW;    
   $tpl_vars['coll']                      = $IC;    
   $tpl_vars['colData']                   = $this->sql->getCollectionInfos ( $IW['collection_id'] );
   $tpl_vars['work']['notes_to_studies']  = "";    
   $tpl_vars['work']['notes_to_staff']    = "";    
   $tpl_vars['user']                      = $_SESSION['user'];
   
   if   ( $book['state_id'] == 9 )       { $tpl_vars['work']['todo']  = "suggest";   } # 9 = Suggest Mode / Kaufvorschlag
   
   if (  isset( $book[ 'notes_to_studies' ] ) ) {$tpl_vars['work']['notes_to_studies'] =  $book[ 'notes_to_studies' ]; }
   if (  isset( $book[ 'notes_to_staff'   ] ) ) {$tpl_vars['work']['notes_to_staff'  ] =  $book[ 'notes_to_staff'   ]; }
   if (! isset( $book[ 'signature'        ] ) ) {$tpl_vars['work']['signature'       ] =  getSignature( $book[ 'ppn' ] ); }
 
   #deb($tpl_vars,1);
   
   $this->renderer->do_template ( 'edit_book.tpl' , $tpl_vars ) ;
   exit(0);
}

function purchase_suggestion( $IW,  $IU )
{
    $book['ppn']              = 0 ;
    $book['title']            = "" ;
    $book['author']           = "" ;
    $book['publisher']        = "" ;
    $book['signature']        = "" ;
    $book['shelf_remain']     = "" ;
    $book['notes_to_staff  '] = "" ;
    $book['notes_to_studies'] = "" ;
    $book['notes_to_staff']   = "" ;
    $book['doc_type_id']      = 1 ; 
   
    $tpl_vars['user']                          = $_SESSION['user'];
    $tpl_vars['coll']                          = $_SESSION['coll'];
    $tpl_vars['book']                          = $book;    
    $tpl_vars['work']                          = $IW;    
    $tpl_vars['colData']                       = $this->sql->getCollectionInfos ( $IW['collection_id'] );
    $tpl_vars['work']['mode']                  = "suggest";    
    $tpl_vars['work']['document_id']           = 0 ; 
 
    $this->renderer->do_template ( 'edit_book.tpl' , $tpl_vars ) ;
    exit(0);
}

function  activateMedia(   $IW )
{
  $this->sql->setMediaState(  $IW['document_id'], 3 );
  $this->renderer->doRedirect( $IW['last_page'] );
}

function deactivateMedia(   $IW )
{  
  $this->sql->setMediaState(  $IW['document_id'], 5 );
  $this->renderer->doRedirect( $IW['last_page'] );
}

function cancel_release(   $IW )
{
  $this->sql->setMediaState(  $IW['document_id'], 2 );
  $this->renderer->doRedirect( $IW['last_page'] );
}

function returnDoneMedia(   $IW )
{
  $this->sql->setMediaState(  $IW['document_id'], 5 );
  $this->renderer->doRedirect( $IW['last_page'] );
}

function reviveMedia(   $IW )
{
  $this->sql->setMediaState(  $IW['document_id'], 1 );
  $this->renderer->doRedirect( $IW['last_page'] );
}

function acceptMedia(  $IW )  
{
  $this->sql->setMediaState(  $IW['document_id'], 2 );
  $this->renderer->doRedirect( $IW['last_page'] );
}

function doneMedia(  $IW )  
{
  $this->sql->setMediaState(  $IW['document_id'], 3 );
  $this->renderer->doRedirect( $IW['last_page'] );
}

function releaseMedia( $IW )
{
  $this->sql->setMediaState(  $IW['document_id'], 4 );
  $this->renderer->doRedirect( $IW['last_page'] );
}

function cancelMedia( $IW )
{
  $this->sql->setMediaState(  $IW['document_id'], 5 );
  $this->renderer->doRedirect( $IW['last_page'] );
}

function deleteMedia(  $IW )  
{
  $this->sql->setMediaState(  $IW['document_id'], 6 );
  $this->renderer->doRedirect( $IW['last_page'] );
}

function ereaseMedia( $IW, $IU)
{
    $this->sql->deleteMedia($IW, $IU);
    $url       = "index.php?collection=0&ro=".$IU['role_encode']."";
    $this->renderer->doRedirect( $url );
}        

function saveNewMedia(  $IW , $IC, $IU)
{
  $bookSE                   =   $_SESSION['books'][$IW['ppn']];
  $book['edition'         ] = "";
  $book['year'            ] = "";  
  $book['journal'         ] = "";  
  $book['volume'          ] = ""; 
  $book['pages'           ] = ""; 
  $book['publisher'       ] = ""; 
  $book['ppn'             ] = "";  
  $book['url'             ] = "";  
  $book['state_id'        ] = ""; 
  $book['relevance'       ] = ""; 
  $book['shelf_remain'    ] = "";
  $book['collection_id'   ] = $IW['collection_id'];
  $book = array_merge($book,  $bookSE );                                        /* Metadaten des ausgwählten Mediums, übernommen aus dem Katalog */
  $book['title']            = $IW['title'];                                     /* Änderungen des Benutzers, aus dem Forumlar übernommen         */
  $book['author']           = $IW['author']; 
  $book['publisher']        = $IW['publisher']; 
  $book['signature']        = $IW['signature']; 
  $book['notes_to_staff'  ] = $IW['notes_to_staff']; 
  $book['notes_to_studies'] = $IW['notes_to_studies']; 
 
  if (isset ($IW['shelf_remain']) AND  $IW['shelf_remain']!= '')                ## Wenn Checkbox 'Bleibt im Regal' aktiv ist, dann wird das Medium zum Literaturhinweis
  {
     $book['shelf_remain'  ] = $IW['shelf_remain'];
     $book['state_id'      ] = "3";                                             # Staus = aktiv
     $book['doc_type_id'   ] = 2;                                               # doc_typ = Buch Literaturhinweis
  } 
  
  $_SESSION[ 'work' ][ 'document_id' ] =   $this->sql->initMedia($book);                                                                   /* Metadaten des neuen Mediums speichern */

  $url = "index.php?item=collection&collection_id=".$IW['collection_id']."&ro=".$IU['role_encode']."&item=collection&action=b_coll_edit";
  
  $this->renderer->doRedirect( $url );
 }       

function saveNewMediaSuggest (  $IW, $IU )
{
  #deb($IW,1);

  $book['edition'         ] = "";
  $book['year'            ] = "";  
  $book['journal'         ] = "";  
  $book['volume'          ] = ""; 
  $book['pages'           ] = ""; 
  $book['publisher'       ] = ""; 
  $book['ppn'             ] = "";  
  $book['url'             ] = "";  
  $book['relevance'       ] = ""; 
  $book['shelf_remain'    ] = "";
  $book['doc_type_id'     ] = 1;  # BUCH      
  $book['state_id'        ] = 9;  # Bestellvorschlag 
  $book['notes_to_staff'  ] = "Erwerbungsvorschlag\n".$IW['notes_to_staff'];  
   
  if (isset ( $IW['collection_id'   ] )) { $book['collection_id'   ]   = $IW['collection_id'];   } 
  if (isset ( $IW['title'           ] )) { $book['title'           ]   = $IW['title'];           } 
  if (isset ( $IW['author'          ] )) { $book['author'          ]   = $IW['author'];          } 
  if (isset ( $IW['publisher'       ] )) { $book['publisher'       ]   = $IW['publisher'];       } 
  if (isset ( $IW['signature'       ] )) { $book['signature'       ]   = $IW['signature'];       } 
  if (isset ( $IW['notes_to_studies'] )) { $book['notes_to_studies']   = $IW['notes_to_studies'];} 

  $_SESSION[ 'work' ][ 'document_id' ] =   $this->sql->initMedia($book);                                                                   /* Metadaten des neuen Mediums speichern */
  
  $url = "index.php?item=collection&collection_id=".$IW['collection_id']."&ro=".$IU['role_encode']."&item=collection&action=b_coll_edit";
  
  $this->renderer->doRedirect( $url );
}       

function annoteNewMediaForm(  $IW, $IU)
{   
   $book =   $_SESSION['books'][$IW['ppn']];
   $tpl_vars['user']                     = $_SESSION['user'];
   $tpl_vars['coll']                     = $_SESSION['coll'];
   
   $tpl_vars['colData']                  = $this->sql->getCollectionInfos ( $IW['collection_id'] );
   $tpl_vars['work']                     = $IW;    
   $tpl_vars['work']['mode']             = "new";    
   $tpl_vars['work']['document_id']      = "0";    
   $tpl_vars['work']['redirect']         = "0";    
   $tpl_vars['book']                     = $book;    
   $tpl_vars['book']['shelf_remain']     = 0;
   $tpl_vars['book']['notes_to_studies'] = "";    
   $tpl_vars['book']['notes_to_staff']   = "";    
    
   $this->renderer->do_template ( 'edit_book.tpl' , $tpl_vars ) ;
   exit(0);
}

function searchMedia( $IW )
{   
    $toSearch['title']          = $IW['title'];
    $toSearch['author']         = $IW['author'];
    $toSearch['signature']      = $IW['signature'];
    $books = $_SESSION['books'] = $this->getBooks ( $toSearch ) ;    

    if ( isset($books['hits']) AND $books['hits'] < 1 )                                              /*  -- Suche ergab keinen Treffer */
    {
       $this->showNewBookForm( $IW, $toSearch, $books['hits'] );
    }
    else
    {
     $this->showHitList( $IW, $books);
    }
}

function showHitList( $IW, $books)
{
  $tpl_vars['page']               = 2;                                                                  /* Seite 2 = Anzeige der Trefferliste nach der Suche */
  $tpl_vars['user']               = $_SESSION['user'];
  $tpl_vars['coll']               = $_SESSION['coll'];
  $tpl_vars['work']               = $IW;
  $tpl_vars['colData']            = $this->sql->getCollectionInfos ( $IW['collection_id'] );
  $tpl_vars['searchHits']         = $books['hits'];                                                        
  $tpl_vars['books_info']         = $books;                                                          
  $tpl_vars['coll']['title_short']  = $IW['collection_id'];  

  $this->renderer->do_template ( 'new_book.tpl' , $tpl_vars ) ;
  exit(0);
}

function showNewBookForm( $IW, $toSearch = NULL, $searchHits = 1 )
{ 
  $tpl_vars['page']                 = 1;                                                                  /* Seite 1 = Eingabemaske für die Suchbegriffe bei der Mediensuche */
  $tpl_vars['user']                 = $_SESSION['user'];
  $tpl_vars['coll']                 = $_SESSION['coll'];  
  $tpl_vars['work']                 = $IW;
  $tpl_vars['colData']              = $this->sql->getCollectionInfos ( $IW['collection_id'] );
  $tpl_vars['searchHits']           = $searchHits;                                                          
  $tpl_vars['book']['title']        = $toSearch['title'];                                                                 /*   */
  $tpl_vars['book']['author']       = $toSearch['author'];                                                                 /*   */
  $tpl_vars['book']['signature']    = $toSearch['signature'];                                                                 /*   */
  $tpl_vars['coll']['title_short']  = $IW['collection_id'];  
#deb( $tpl_vars['coll']    );
  $this->renderer->do_template ( 'new_book.tpl' , $tpl_vars ) ;
  exit(0);
}

/*
     Anzeige Forumulare:
 * - Suche im Katalog
 * - Anzeige der Trefferliste 
 * - Anzeige des ausgwählten Mediums + eingabe der Metadaten 
 * - Speichern des Mediums in den SA
 */

function getBooks( $searchQuery )
{

#--------------------------------
$conf           = getConf();
$cat            = $conf['cat'         ]; #'opac-de-18-302';  # HIBS 
$recordSchema   = $conf['recordSchema']; #'turbomarc';       # turbomarc / mods
$maxRecords     = $conf['maxRecords'  ]; # 50;
$catURL         = $conf['catURL'      ]; #'http://sru.gbv.de/';
#--------------------------------

$query       =  $this->build_sru_query( $searchQuery ) ; 

$datasource  = $catURL.$cat.'?version=1.2&operation=searchRetrieve&query='.$query.'+sortby+year%2Fdescending&maximumRecords='.$maxRecords.'&recordSchema='.$recordSchema;
$page        = file_get_contents($datasource);
$sxm         = simplexml_load_string( str_replace( 'zs:', '' , $page ) );

$hits        = $sxm->numberOfRecords;  # Anzahl Treffer

if (isset ($sxm->records->record) )
foreach ( $sxm->records->record as $rec )
{  
  ## ------------- TURBOMARC ---------------
  if      ( $recordSchema   == 'turbomarc' )
  {
     $r = $rec->recordData->r;   
     #turbomarc
     $book[ 'title'            ]  =   $r->d245->sa.'';
     $book[ 'subTitle'         ]  =   $r->d245->sb.'';
     $book[ 'author'           ]  =   $r->d100->sa.'';   if ($book[ 'author' ]  == '' ) { $book[ 'author'  ]  = $this->getPersons( $r->d700);} # Wenn im Datensatz kein Autor vorhanden ist, wird dafür 'Peronen' genommen 
     $book[ 'publisher'        ]  =   $r->d260->sb .' '. $r->d260->sa .' '. $r->d260->sc.'';
     $book[ 'edition'          ]  =   $r->d250->sa .''; 
     $book[ 'signature'        ]  =   $r->d954->sd .'';
     $book[ 'ppn'              ]  =   $r->c001 .'';
     $book[ 'directory'        ]  =   $r->d856->su .'';
     $book[ 'physicaldesc'     ]  =   $r->d300->sa .''; 

     $bookX                        =   array_merge ( $book, getDocType($book) );
     
     
     $ret[ $book[ 'ppn' ] ]= $bookX;     
  } 
  
  ## ------------- MODS ---------------
  else if ( $recordSchema   == 'mods' )                                           
  {
    $r = $rec->recordData->mods; 
    $authors = '';
    foreach( $r->name as $names => $name ) 
    { 
     $link = false;
     $n =  $name->namePart[0]. ", " .$name->namePart[1];                                                                                    # author 
     foreach($name->attributes() as $a => $b) {  if ($a == 'valueURI')  { $link= true; break; } }                                          # autor has descr.link?      if ($link == true ) { $authors .= '<a target="_blank" class="author" href="'.$b.'">'.$n.'</a>, '; }  else {$authors .= $n.", "; }     # list of autors with links 
     $authors .= $n.'; ';
   }  
   if( isset( $r->relatedItem[ 0 ]->location->url ) )
     $directory  =  ( ( string )  $r->relatedItem[ 0 ]->location->url ); 
   else {
     $directory = ''; }# Inhaltsverzeichnis als PDF / oder Coverpic als jpg
     $book[ 'titleNonSortPart' ]  =  ( ( string ) $r->titleInfo->nonSort) ;              # Buchtitel (unsortierter Teil) 
     $book[ 'title'            ]  =  ( ( string ) $r->titleInfo->title);                 # Buchtitel
     $book[ 'subTitle'         ]  =  ( ( string ) $r->titleInfo->subTitle);              # Subtitel
     $book[ 'author'           ]  =    $authors;                                         # list of autors with links 
     $book[ 'publisher'        ]  =  ( ( string ) $r->originInfo->publisher );           # Verlag
     $book[ 'edition'          ]  =  ( ( string ) $r->originInfo->edition );             # Edition
     $book[ 'dateissued'       ]  =  ( ( string ) $r->originInfo->dateIssued );          # Jahr
     $book[ 'signature'        ]  =    ( string ) ' ';                                   # Signature
     $book[ 'ppn'              ]  =  ( ( string ) $r->recordInfo->recordIdentifier );    # PPN
     $book[ 'physicaldesc'     ]  =  ( ( string ) $r->physicalDescription->form[ 0 ] );  # Form: electronic /  print  
     $book[ 'extend'           ]  =  ( ( string ) $r->physicalDescription->extent);      # Anzahl Seiten, Speicherplatz (Höhe in cm / kB)
     $book[ 'directory'        ]  =    $directory ;                                      # Inhaltsverzeichnis als PDF
     
     $ret[ $book[ 'ppn' ] ]= $book;
  }
}
 
 $ret[ 'hits'       ]  = ( string )$hits;  ## Erster Datensatz enthalt: Die Anzahl der gefundenen Medien
 $ret[ 'maxRecords' ]  = $maxRecords;      #  Anzahl der gespeicherten Datensätze
 
 return $ret;
}

function getSignature($ppn = NULL)
{
  #--------------------------------
  $conf           = getConf();
  $cat            = $conf['cat'         ]; #'opac-de-18-302';  # HIBS 
  $catURL         = $conf['catURL'      ]; #'http://sru.gbv.de/';
  #--------------------------------
  
  $datasource = $catURL.$cat.'?version=1.1&operation=searchRetrieve&query=pica.ppn='.$ppn.'&maximumRecords=1&recordSchema=turbomarc';
  $page        = file_get_contents($datasource);
  $sxm         = simplexml_load_string( str_replace( 'zs:', '' , $page ) );
  $book        = $sxm->records->record->recordData->r ;
  return $book->d954->sd .""; /* Signatur */
}

function getPPNBySignature( $signature )
{
  #--------------------------------
  $conf           = getConf();
  $cat            = $conf['cat'         ]; #'opac-de-18-302';  # HIBS 
  $catURL         = $conf['catURL'      ]; #'http://sru.gbv.de/';
  #--------------------------------
  
  $datasource = $catURL.$cat.'?version=1.1&operation=searchRetrieve&query=pica.sgn='.$signature.'&maximumRecords=1&recordSchema=turbomarc';
  $page        = file_get_contents($datasource);
  $sxm         = simplexml_load_string( str_replace( 'zs:', '' , $page ) );
  $book        = $sxm->records->record->recordData->r ;
  return $book->c001."";  /* PPN  */
}

function build_sru_query($search) 
{
  $query = array();
  if ( ( isset( $search[ 'signature' ] ) AND ( $search[ 'signature' ] != '' ) ) ) {  $search[ 'signature' ] = str_replace( '.','?',  $search[ 'signature' ]);
                                                                                     $query[] = 'pica.sgb="'.$search[ 'signature' ].'"' ;  }
  if ( ( isset( $search[ 'author'    ] ) AND ( $search[ 'author'    ] != '' ) ) ) {  $query[] = 'pica.per="'.$search[ 'author'    ].'"' ;  }
  if ( ( isset( $search[ 'title'     ] ) AND ( $search[ 'title'     ] != '' ) ) ) {  $query[] = 'pica.tit="'.$search[ 'title'     ].'"' ;  }  

  $listSize = sizeof($query);
  if      ( $listSize == 0 ) { $ret = '';  }
  else if ( $listSize >= 1 ) { $ret = $query[0];  }
  if      ( $listSize >= 2 ) {  for ( $i = 1; $i < $listSize; $i++ )   {  $ret .=  ' AND '.$query[ $i ];  }  }
        
  return  urlencode( $ret );
}

/* 
 * - Liste aller SAs
 * - Admin sieht alle SAs         (und kann alle SA bearbeiten)
 * - Dozent sieht nur eigene SAs  (und kann seine SA bearbeiten)
 * - Studi sieht (noch) alle SAs  (und kann NICHTS bearbeiten)
 * index.tpl
 *  */

function getFilterHeader()
{ 

global $CONST_actions_info ;  
global $CONST_letter_header;

$letter_exist = array () ;
$letter_eq    = array () ;

#$util -> expire () ;  /* pseudo cron: abgelaufene SA bekommen neuen status*/

/* ----------------- LISTE DER INPUTPARAMETER  ------------------ */
$default = array
(   "todo"       => "view" ,
    "mode"       => "view" ,
    "categories" => "1" , /* Departments,  1 = ALLE */
    "letter"     => "" ,
    "item"       => "collectionList" ,
) ;

$INPUT['work']  = array_merge($default, $_GET, $_POST   ); 

if ( isset ( $_SESSION['work'][ 'mode'   ] ) )   {  $INPUT['work'][ 'mode'   ] = $_SESSION['work'][ 'mode'   ] ;   } 
if ( isset ( $_SESSION['work'][ 'letter' ] ) )   {  $INPUT['work'][ 'letter' ] = $_SESSION['work'][ 'letter' ] ;   } # Sortierbuchstabe

$tpl_var = $INPUT ;
$tpl_var[ 'html_options' ]['dep'] = $_SESSION['DEP2BIB']; #$this->sql -> getAllDepartments() ;                                               ## Liste aller Departments (Categories)
$tpl_var[ 'html_options' ]['fak'] = $_SESSION['FAK'];          ## Liste aller Fakultäten
$tpl_var[ 'collection' ] = array () ;

$userlist = $this->sql->getUser( $INPUT['work']['mode']);                                                                                    ## LISTE mit N Einträgen mit Stammdaten aller registrieter Nutzer 

foreach ( $userlist as $u )                                                                                                                  ## Liste wird mit entsprechenden SAs erweitert 
{
  #$tpl_var[ 'html_options' ][ 'user' ][ $u[ 'hawaccount' ] ] =  $this-> getFullUserName($u);                                                ## LISTE DER ELSE USER  = $tpl_var[ 'html_options' ][ 'user' ]   
  
  $SAlistTMP                                            =  $this->sql->getSAlist( $u, $INPUT['work']['mode'], $INPUT['work']['categories'] );
   
  $SAlist = ""; 
  if ($SAlistTMP)
  foreach ( $SAlistTMP as $SAL)   /* SA die nicht angzeigt werden sollen, werden aus der Liste entfernt */
  {
    if (      $SAL[ 'id' ] !=  'ELSE-ADMIN' 
           && $SAL[ 'id' ] !=  'HIBS ELSE' 
          # && $SAL[ 'id' ] !=  'LS01' 
           && $SAL[ 'id' ] !=  'Lebens Stiel2' 
                    
	     )
	     {
         $SAlist[] = $SAL; 
       }
 
  }
 
  if ( isset ( $SAlist[ 0 ][ 'surname' ] ) && $letter_eq != substr ( $SAlist[ 0 ][ 'surname' ] , 0 , 1 ) )                    ## Wenn User mindestens ein Semesterapparat hat
  { $letter = substr ( $SAlist[ 0 ][ 'surname' ] , 0 , 1 );
    $letter_exist[$letter] = $letter;                                                                                         ## Wird sein Anfangsbuchstabe gespeichert
  }

  $key  = $this->getKey( $u );
  $tpl_var[ 'collection' ][ $key ] = NULL ;  
  
  $substring = strtolower ( $INPUT['work'][ 'letter' ] ) ;
  
  if ( $substring )                                                                                                             /* FILTER AUF Anfangsbuchstaben von Dozenten gewählt */
  { if ( strtolower ( substr ( $u[ 'surname' ] , 0 , 1 ) ) == ( $substring ) )
    {  if ( ! empty ( $SAlist ) )  {  $tpl_var[ 'collection' ][ $key ] = array_merge ( (array) $tpl_var[ 'collection' ][ $key ] , $SAlist ) ;  }
    }
  }

  else                                                                                                                          /* KEIN FILTER auf Anfangsbuchstabe  -- ALLE Dozenten gewählt */
  { if ( ! empty ( $SAlist ) )    {  $tpl_var[ 'collection' ][ $key ] = array_merge ( (array) $tpl_var[ 'collection' ][ $key ] , $SAlist ) ;   }
  }
  #------------------------------------------------------------------------------------------------------------------- 
}


#------------------------------------------------------------------------------------------------------------------- 
if ( !isset( $_SESSION[ 'user' ] ) ) { $_SESSION['user'] = NULL; }
#------------------------------------------------------------------------------------------------------------------- 
$tpl_var[ 'user' ]           = $_SESSION[ 'user' ] ;
$tpl_var[ 'actions_info' ]   = $CONST_actions_info ;                                                                           # aus const.php ##===================== ACTION INFO BESSER HIER IM PHP AUSWERTEN, NICHT IM TEMPLATE  
$tpl_var[ 'letter_output' ]  = $this->getLetterOutput( $CONST_letter_header, $letter_exist ) ;                                                          /* Liste mit allen Anfangsbuchstaben aller Nutzer */
$tpl_var[ 'source' ]         = 'index.php' ;
##------------------------------------------------------------------------------------------------------------------- 
# deb($_SESSION);
 #deb($tpl_var,1);
$this->renderer -> do_template ( 'index.tpl' , $tpl_var , TRUE ) ;
}

function getFullUserName($u)
{
  return $u[ 'forename' ] . " " . $u[ 'surname' ] ; /*  Vorname, Nachname */
}

function getKey( $u )
{
  return trim ( $u[ 'hawaccount' ] ).'::'.  trim ( $u[ 'surname' ] ) .'::'. trim ( $u[ 'forename' ] ) ;
}

function cmp_coll ( $a , $b )                          # callback function for usort() 
{
  $key_a = $a[ 'title' ] . $a[ 'collection_no' ] ;
  $key_b = $b[ 'title' ] . $b[ 'collection_no' ] ;
  return strcmp ( $key_a , $key_b ) ;
}


function getLetterOutput ( $letter_header , $letter_exist )
{
#------------------------------------------------------------------------------------------------------------------- 
#----- ARRAY ALLER BUCHSTABEN ($letter_header: A-Z) # BUCHSTABEN die Anfangsbuchstaben von  Dozenten sind, die AKTIVE SA haben, haben Wert 1, sonst 0      = $letter_output  
    $letter_activ  = "0" ;
    $letter_output = array () ;

    foreach ( $letter_header as $let ) {                                                                                      # aus const.php
      foreach ( $letter_exist as $val1 ) {
        if ( strtolower ( $let ) == strtolower ( $val1 ) )
        {
          $letter_activ = "1" ;
        }
      }
      $letter_output[ $let ] = $letter_activ ;
      $letter_activ          = "0" ;
    }
    return $letter_output ;
  }

  
  
  function showMailForm ( $IW , $IU, $IC )
  {
    $doc_info = $this->sql -> getDocumentInfos ( $IW[ 'document_id' ] ) ;
    $CI       = $this->sql -> getCollectionInfos ( $doc_info[ 'collection_id' ] ) ;

    
    $col_info  = $CI[ $doc_info[ 'collection_id' ] ] ;
    
   # deb($col_info,1);
    $user_info = $col_info [ 'user_info' ] ;

    if ( $user_info[ 'sex' ] == 'w' )
    {
      $salutaton = 'Sehr geehrte Frau ' . $user_info[ 'surname' ] ;
    }
    else
    {
      $salutaton = 'Sehr geehrter Herr ' . $user_info[ 'surname' ] ;
    }

    $tpl_vars['coll']             = $IC;
    
    $tpl_vars[ 'fromFirstName'  ] = $IU[ 'vorname' ] ;
    $tpl_vars[ 'fromName'       ] = $IU[ 'nachname' ] ;
    $tpl_vars[ 'fromEmail'      ] = $IU[ 'mail' ] ;

    $tpl_vars[ 'salutaton'      ] = $salutaton ;
    $tpl_vars[ 'toFirstName'    ] = $user_info[ 'forename' ] ;
    $tpl_vars[ 'toName'         ] = $user_info[ 'surname' ] ;
    $tpl_vars[ 'toEmail'        ] = $user_info[ 'email' ] ;

    $tpl_vars[ 'collectionName' ] = $col_info[ 'title' ] ;
    $tpl_vars[ 'collection_id'  ] = $col_info[ 'id' ] ;

    $tpl_vars[ 'documentName'   ] = $doc_info[ 'title' ] ;
    $tpl_vars[ 'doc_info'  ]    = $doc_info ;
  
    
    $tpl_vars[ 'linkTxt'        ] = '' ;
    $tpl_vars[ 'url'            ]     = '' ;

    $tpl_vars[ 'work'           ] = $IW ;
    $tpl_vars[ 'user'           ] = $IU ;
    $tpl_vars[ 'ci'             ] = $col_info ;
    
    $this->renderer -> do_template ( 'email.tpl' , $tpl_vars ) ;
  }
  
  
  function send_email ( $IW , $IU , $IC)
  {
    $doc_info = $this->sql -> getDocumentInfos ( $IW[ 'document_id' ] ) ;
    $CI       = $this->sql -> getCollectionInfos ( $doc_info[ 'collection_id' ] ) ;

    
  	#$IW[ 'to' ]   = 'werner.welte@haw-hamburg.de' ;
    $IW[ 'bcc2' ] = 'Daniela.Mayer@haw-hamburg.de' ;
	  $to       = $IW[ 'to' ] ;

    $CI       = $this->sql -> getCollectionInfos ( $doc_info[ 'collection_id' ] ) ;
    $col_info  = $CI[ $doc_info[ 'collection_id' ] ] ;
    
    $url      = "index.php?item=collection&collection_id=" . $IW[ 'collection_id' ] . "&ro=".$IU['role_encode']."&action=b_coll_edit" ;
    $subject  = 'Ihr ELSE Semesterapparat' ;
    $message  = $IW[ 'txt' ] ;

    $header  = 'From: '         . $IW[ 'from' ] . "\r\n" ;
  # $header .= 'Reply-To: '     . $IW[ 'dm'   ] . "\r\n" ;
    $header .= 'Bcc: '          . $IW[ 'bcc2' ] . "\r\n" ;
    $header .= "Mime-Version: 1.0\r\n" ;
    $header .= "Content-type: text/plain; charset=iso-8859-1" ;
    $header .= 'X-Mailer: Greetings from ELSE - PHP/' . phpversion () ;
	
    if ( $IU[ 'role_name' ] == 'staff' OR $IU[ 'role_name' ] == 'admin' OR $IU[ 'role_name' ] == 'edit' )
    {  
      $sendok = mail ( $to , $subject , $message , $header ) ;
    }
    
    if ( $sendok )
    {
      $linkTxt = "Mail gesendet <br><br> weiter " ;
    }
    else
    {
      $linkTxt = "ERROR: Mail nicht versendet!" ;
    }

    $doc_info = $this->sql -> getDocumentInfos ( $IW[ 'document_id' ] ) ;
    $tpl_vars['coll']             = $IC;
    $tpl_vars[ 'documentName'   ] = $doc_info[ 'title' ] ;
    $tpl_vars[ 'linkTxt' ] = $linkTxt ;
    $tpl_vars[ 'url' ]     = $url ;
    $tpl_vars[ 'work' ]    = $IW ;
    $tpl_vars[ 'user' ]    = $IU ;
    $tpl_vars[ 'ci' ]      = $col_info ;

    $this->renderer -> do_template ( 'email.tpl' , $tpl_vars ) ;
  }
  
  function getPersons( $pers )
  {
    $ret = '';
    foreach ($pers as $p)
    {
      $ret .=   $p->sa.' '. $p->sb.'; '; 
    }
    $ret = substr($ret, 0, -2);
    return $ret;   
  }
}
