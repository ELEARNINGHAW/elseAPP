<?php
require_once('../php/config.php');

/*
     Anzeige Forumulare:
 * - Suche im Katalog
 * - Anzeige der Trefferliste 
 * - Anzeige des ausgwählten Mediums + eingabe der Metadaten 
 * - Speichern des Mediums in den SA
 */

#require_once('error.php');

function getBooks( $searchQuery )
{
#--------------------------------
$cat            = 'opac-de-18-302';  # HIBS 
$recordSchema   = 'turbomarc';       # turbomarc / mods
$maxRecords     = 50;
#--------------------------------

$query       =  build_sru_query( $searchQuery ) ; 
$datasource  = 'http://sru.gbv.de/'.$cat.'?version=1.2&operation=searchRetrieve&query='.$query.'+sortby+year%2Fdescending&maximumRecords='.$maxRecords.'&recordSchema='.$recordSchema;
#die($datasource);
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
     $book[ 'author'           ]  =   $r->d100->sa.''; 
     $book[ 'publisher'        ]  =   $r->d260->sb .' '. $r->d260->sa .' '. $r->d260->sc.'';
     $book[ 'edition'          ]  =   $r->d250->sa .''; 
     $book[ 'signature'        ]  =   $r->d954->sd .'';
     $book[ 'ppn'              ]  =   $r->c001 .'';
     $book[ 'directory'        ]  =   $r->d856->su .'';

     if (isset ( $r->d337->sa) ) /* nur bei E-Books steht hier 'eBook' */
     {
       $book[ 'physicaldesc'     ]  =   'electronic'; 
     } 
     else 
     {
       if (isset($book[ 'signature'  ]) ) /* Signatur ist nur bei Büchern vorhanden */
       {
         $book[ 'physicaldesc' ] = 'print';
       } 
     }
     $ret[ $book[ 'ppn' ] ]= $book;     
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
     $book[ 'signature'        ]  =    ( string ) ' ';                                    # Signature
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
  $datasource = 'http://sru.gbv.de/opac-de-18-302?version=1.1&operation=searchRetrieve&query=pica.ppn='.$ppn.'&maximumRecords=1&recordSchema=turbomarc';
  $page        = file_get_contents($datasource);
  $sxm         = simplexml_load_string( str_replace( 'zs:', '' , $page ) );
  $book        = $sxm->records->record->recordData->r ;
  return $book->d954->sd .""; /* Signatur */
}

function getPPNBySignature( $signature )
{
  $datasource = 'http://sru.gbv.de/opac-de-18-302?version=1.1&operation=searchRetrieve&query=pica.sgn='.$signature.'&maximumRecords=1&recordSchema=turbomarc';
  $page        = file_get_contents($datasource);
  $sxm         = simplexml_load_string( str_replace( 'zs:', '' , $page ) );
  $book        = $sxm->records->record->recordData->r ;
  return $book->c001."";  /* PPN  */
}




function build_sru_query($search) 
{
  $query = array();
  if ( ( isset( $search[ 'signature' ] ) AND ( $search[ 'signature' ] != '' ) ) ) {  $query[] = 'pica.sgb='.$search[ 'signature' ] ;  }
  if ( ( isset( $search[ 'author'    ] ) AND ( $search[ 'author'    ] != '' ) ) ) {  $query[] = 'pica.per='.$search[ 'author'    ] ;  }
  if ( ( isset( $search[ 'title'     ] ) AND ( $search[ 'title'     ] != '' ) ) ) {  $query[] = 'pica.tit='.$search[ 'title'     ] ;  }  

  $listSize = sizeof($query);
  if      ( $listSize == 0 ) { $ret = '';  }
  else if ( $listSize >= 1 ) { $ret = $query[0];  }
  if      ( $listSize >= 2 ) {  for ( $i = 1; $i < $listSize; $i++ )   {  $ret .=  ' AND '.$query[ $i ];  }  }
        
  return  urlencode( $ret );
}
?>
