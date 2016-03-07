<?php
class CONSTANT
{
var $default_role_id;
var $debug_level  = 1;
var $bib_id ;
var $CONST_letter_header = array ( 'A' , 'B' , 'C' , 'D' , 'E' , 'F' , 'G' , 'H' , 'I' , 'J' , 'K' , 'L' , 'M' , 'N' , 'O' , 'P' , 'Q' , 'R' , 'S' , 'T' , 'U' , 'V' , 'W' , 'X' , 'Y' , 'Z' ) ;
var $CONST_actions_info ; 

function CONSTANT()
{
if   ( !isset ($default_role_id)) $default_role_id = 3;

if   ( isset ( $_POST[ 'bib_id' ] ) )  { $bib_id = $_POST[ 'bib_id' ] ; }
else                                   { $bib_id = 'HAW' ;              }

## actions info
$this->CONST_actions_info = array
(    'b_new'                 => array
    (                                                                           /* Neues Medium anlegen (suchen, annotieren, in SA speichern) ## Buch (- -> 1): [Neu bestellt] ## E-Book (- -> 3): [Ist aktiv]     /   Neuer SA anlegen, annotieren, speichern,  SA ## (- -> 3): [ist aktiv] */
        'button'            => 'b_new' ,
        'button_label'      => 'neu anlegen' ,
        'input'             => array ( "mode" => "new" ) ,
        'acl'               => array 
        (
            "user"       => "any" ,
            "collection" => "role=admin,role=edit,role=staff" ,
            "book"       => "role=admin,role=edit,role=staff" ,
            "ebook"      => "role=admin,role=edit,role=staff" ,
            "lh_book"    => "role=admin,role=edit,role=staff" ,
          ) ,
    ) ,

    'b_coll_meta_edit'      => array 
    (                                                                           #  Metadaten des SA bearbeiten              (3 -> 3): [ist aktiv]  #
        'button'            => 'b_coll_meta_edit' ,
        'button_label'      => 'Bearbeiten' ,
        'input'             => array ( "mode" => "edit" ) ,
        'acl'               => array (  "collection" => "role=admin,owner=true,role=staff",  ) ,
        'button_visible_if' => array 
        (
            "item"       => array ( "collection" ) ,
            "state"      => array ( "active" ) ,
            "mode"       => array ( "edit" , "admin" , 'staff') ,
        ) ,
    ) ,

        
    
   'b_coll_edit'            => array (                                          /*  Inhalt des  SA (=Medien) wird angezeigt zum bearbeiten              (3 -> 3): [ist aktiv]   */                                              
        'button'            => 'b_coll_edit' ,
        'button_label'      => 'Bearbeiten' ,
        'input'             => array () ,
        'acl'               => array (  "collection" => "role=admin,owner=true,role=staff" ,  ) ,
    ) ,
    
  'b_delete'             => array 
      (                                                                         /*  Medium (E-Book, Buch) oder SA  wird gelöscht  (5 -> 6): Erscheint nicht mehr   */
        'button'            => 'b_delete' ,
        'button_label'      => 'Löschen' ,
        'input'             => array () ,
        'button_visible_if' => array
        (
            "state"    => array ( "inactive" ) ,
            "mode"     => array ( "edit" , "admin" , "staff" ) ,
            "item"     => array ( "book",  "ebook" ,"lh_book"      ) ,
        ) ,
        'acl' => array
        (
            "book"       => "role=admin,owner=true,role=staff" ,
            "ebook"      => "role=none,owner=none" ,
            "lh_book"      => "role=none,owner=none" ,
        ) ,
    ) ,
    
    'b_accept'                => array
    (                                                                           /*  Bestellwunsch (oder Kaufvorschlag) wurde akzeptiert und wir von HIBS bearbeitet (1 -> 2): [Wird bearbeitet]   */
        'button'            => 'b_accept' ,
        'button_label'      => 'Bestellung annehmen' ,
        'input'             => array ( "state" => "open" ) ,
        'acl' => array (  "book" => "role=admin,role=staff" ,  ) ,
        'button_visible_if' => array 
        (
            "item"     => array ( "book" ) ,
            "state"    => array ( "new", "suggest" ) ,
            "mode"     => array ( "staff" , "admin" ) ,
        ) ,
    ) ,
    
    'b_cancel_order'        => array
    (                                                                           /*  Bestellwunsch wurde von HIBS (oder owner) abgelehnt/storniert   (1 -> 5): [Ist inaktiv] */
        'button'            => 'b_cancel_order' ,
        'button_label'      => 'Bestellung stornieren' ,
        'input'             => array ( "state" => "inactive" ) ,
        'acl' => array ( "book" => "role=admin,owner=true,role=staff" ,  ) ,
        'button_visible_if' => array 
        (
            "state"    => array ( "new" ) ,
            "item"     => array ( "book" ) ,
            "mode"     => array ( "edit" , "staff" , "admin" ) ,
        ) ,
    ) ,
    
    'b_finished'            => array
    (                                                                           /*  Bestellwunsch wurde erledigt und steht nun (im Bücherregal SA) zur Verfügung  (2 -> 3): [Ist aktiv]   */
        'button'            => 'b_finished' ,
        'button_label'      => 'Bestellung erledigt' ,
        'input'             => array ( "state" => "active" ) ,
        'button_visible_if' => array 
        (
            "state" => array ( "open" ) ,
            "mode"  => array ( "staff" , "admin" ) ,
        ) ,
        'acl'               => array ( "book" => "role=admin,role=staff" ,   ) ,
    ) ,
    
    'b_release'             => array
    (                                                                           /* Buch kann zurückgegeben werden  (3 -> 4): [Wird entfernt] */
        'button'            => 'b_release' ,
        'button_label'      => 'Rückgabe' ,
        'input'             => array ( "state" => "obsolete" ) ,
        'acl'               => array ( "book" => "role=admin,owner=true" , ) ,
        'button_visible_if' => array
        (
            "item" => array ( "book" ) ,
            "state" => array ( "active" ) ,
            "mode" => array ( "edit" , "admin" ,"staff" ) ,
        ) ,
    ) ,
    
    'b_return'                => array 
    (                                                                           /* Buch ist zurückgegeben worden   (4 -> 5): [Inaktiv]   */
        'button'            => 'b_return' ,
        'button_label'      => 'Rückgabe erledigt' ,
        'input'             => array ( "state" => "inactive" ) ,
        'acl'               => array ( "book" => "role=admin,role=staff" , ) ,
        'button_visible_if' => array 
        (
            "item"      => array (  "book"  ) ,
            "state"     => array ( "obsolete" , "delete") ,
            "mode"      => array ( "staff" , "admin" ) ,
        ) ,
    ) ,
    
    'b_cancel_release'        => array 
    (                                                                           /* Buch wird doch nicht zurückgegeben (sondern sogar verlängert)  (4 -> 2): [Wird bearbeitet]   */
        'button'            => 'b_cancel_release' ,
        'button_label'      => 'Bestellung verlängern' ,
        'input'             => array ( "state" => "open" ) ,
        'button_visible_if' => array 
        (
            "item"      => array ( 'book' ) ,
            "state"     => array ( 'obsolete' ) ,
            "mode"      => array ( 'edit', 'staff' , 'admin' ) ,
        ) ,
        'acl' => array 
        (
            "book"      => "role=admin, owner=true, role=staff" ,
        ) ,
    ) ,

    'b_revive'              => array 
    (                                                                           /*  Bestellwunsch wird erneuert   (5 -> 1): [Neu bestellt]   */
        'button'            => 'b_revive' ,
        'button_label'      => 'Bestellung erneuern' ,
        'input'             => array ( "state" => "new" ) ,
        'button_visible_if' => array 
        (
            "item"       => array ( "book" , "article" ) ,
            "state"      => array ( "inactive" ) ,
            "mode"       => array (  "admin", 'staff' ) ,
        ) ,
        'acl' => array 
        (
            "article" => "role=admin,owner=true,role=staff" ,
            "book"    => "role=admin,owner=true,role=staff" ,
        ) ,
    ) ,
    
    'b_deactivate'            => array 
    (                                                                           /*  E-Book wird deaktiviert            (3 -> 5): [Ist inaktiv]     */
        'button'            => 'b_deactivate' ,
        'button_label'      => 'Deaktivieren' ,
        'input'             => array ( "state" => "inactive" ) ,
        'button_visible_if' => array
        (
            "state"  => array ( "active" ) ,
            "item"   => array ( "ebook", "lh_book" ) ,
            "mode"   => array ( "edit" , "admin" ,'staff' ) ,
        ) ,
        'acl' => array 
        (
            "ebook"  => "owner=true,role=admin, role=staff",
            "lh_book"  => "owner=true,role=admin, role=staff",
        ) ,
    ) ,
    
    
    'b_activate'              => array
    (                                                                           /*  E-Book wird wieder aktiviert         (5 -> 3): [Ist aktiv]       */
        'button'            => 'b_activate' ,
        'button_label'      => 'Aktivieren' ,
        'input'             => array ( "state" => "active" ) ,
        'button_visible_if' => array
         (

            "state"   => array ( "inactive" ,"delete" ) ,
            "item"    => array ("lh_book", "ebook" ) ,
            "mode"   => array ( "edit" , "admin" ,'staff' ) ,
        ) ,
        'acl' => array 
        (
            "ebook"  => "owner=true,role=admin, role=staff",
            "lh_book"  => "owner=true,role=admin, role=staff",
        ) ,
    ) ,
    
    'b_edit'                => array 
    (                                                                           /* Metadaten des Mediums (Buch, E-Book) wird bearbeiten    (a -> a )State ändert sich nicht */
        'button'            => 'b_edit' ,
        'button_label'      => 'Bearbeiten' ,
        'input'             => array ( "mode" => "edit" ) ,
        'button_visible_if' => array 
        (
            "item"     => array ( "book" , "ebook"  , "lh_book"  ) ,
            "mode"     => array ( "edit" , "staff" , "admin" ) ,
        ) ,
        'acl'               => array 
        (
            "book"    => "role=admin,role=staff,owner=true" ,
            "ebook"   => "role=admin,role=staff,owner=true" ,
            "lh_book"   => "role=admin,role=staff,owner=true" ,
        ) ,
    ) ,
    
   'new_email'              => array
   (
        'button'            => 'b_new_email' ,
        'button_label'      => 'E-Mail senden' ,
        'input'             => array ( "mode" => "new" , "item" => "email" ) ,
        'acl'               => array ( "any" => "role=admin,role=staff" , ) ,
        'button_visible_if' => array 
        (
            "item" => array (  "book" , "ebook", "lh_book" ) ,
            "mode" => array ( "staff" , "admin" ) ,
        ) ,
    ) ,
    
   'kill'              => array
   (
        'button'            => 'b_kill' ,
        'button_label'      => 'ENDGÜLTIG LÖSCHEN!' ,
        'input'             => array ( "state" => "delete" ) ,
        'acl'               => array ( "any" => "role=admin,role=staff" , ) ,
        'button_visible_if' => array 
        (
            "state"  => array ( "delete" ) ,
            "item"   => array (  "book" , "ebook", "lh_book" ) ,
            "mode"   => array ( "staff" , "admin" ) ,
        ) ,
    ) ,
    
) ;

}

function getDocType($book)
{
  if (isset ($book['physicaldesc']) AND !isset ($book['state_id'] )  ) 
  {                                                                                               $book['state_id'   ]  =  1 ;
    if(      stristr(  $book['physicaldesc']  , 'Online') == TRUE ) { $book['doc_type_id']  = 4;  $book['state_id'   ]  =  3;}  
    else if( stristr(  $book['physicaldesc']  , 'CD-ROM') == TRUE ) { $book['doc_type_id']  = 3;  $book['state_id'   ]  =  1;}
  } 

  if (!isset ($book['doc_type_id'])) 
  {
    $book['doc_type_id'] = 1;
  }
  
  if( $book['doc_type_id']  == 4 ) 
  { 
    $book['doc_type'   ]  = "electronic";   #  E-BOOK            
    $book['item'       ]  = 'ebook'; 
  }
  else if( $book['doc_type_id']  == 3 )     # CD-ROM
  {
    $book['doc_type'   ]  = "cd-rom";
    $book['item'       ]  = 'book'; 
   /* Status: NEU BESTELLT  */
  }
  else if( $book['doc_type_id']  == 2 )      # BUCH als Literaturhinweis
  {
    $book['doc_type'   ]  = "print";
    $book['item'       ]  = 'lh_book'; 
  }
  else                                       # BUCH im Semesterapparat
  {
    $book['doc_type'   ]  = "print";
    $book['item'       ]  = 'book'; 
    $book['doc_type_id']  =  1;               
  }
  return $book;
}

function deb($obj, $kill=false) {   echo "<pre>";  print_r ($obj);  echo "<pre>";  if($kill){die();} }

}
?>
