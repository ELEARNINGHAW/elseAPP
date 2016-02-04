<?php

$debug_level  = 1;

$CONST_letter_header = array ( 'A' , 'B' , 'C' , 'D' , 'E' , 'F' , 'G' , 'H' , 'I' , 'J' , 'K' , 'L' , 'M' , 'N' , 'O' , 'P' , 'Q' , 'R' , 'S' , 'T' , 'U' , 'V' , 'W' , 'X' , 'Y' , 'Z' ) ;

$const_FAK['DMI'] = array (20, 21, 22, 23); 
$const_FAK['LS' ] = array (30,31,32,33,34,35,36,37,39, 430);
$const_FAK['TI' ] = array (50,51,52,53,54,55);
$const_FAK['WS' ] = array (60,61,62,63,64);

$const_BIB['DMI'   ] = array (21, 22, 23); 
$const_BIB['LS'    ] = array (31,32,33,34,35,36,37,39 ,430); 
$const_BIB['TWI2'  ] = array (61,62,63,64,65); 
$const_BIB['TWI1'  ] = array (61,62); 
$const_BIB['SP'    ] = array (63,64); 

if (!isset ($default_role_id)) $default_role_id = 3;

if   ( isset ( $_POST[ 'location_id' ] ) )  { $location_id = $_POST[ 'location_id' ] ; }
else                                        { $location_id = 1 ;                       }

## actions info
$CONST_actions_info = array
(   'b_new'                 => array
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
            "lh_book"      => "role=admin,role=edit,role=staff" ,
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

# end of actions info


## validation info
$CONST_validation_info = array (
    "action" => "/^[a-z_]*$/" ,
    "alias_degree_id" => "/^[0-9]*$/" ,
    "alias_surname" => "/^.*$/" ,
    "alias_forename" => "/^.*$/" ,
    "alias_sex" => "/^[mf]?$/" ,
    "author" => "/^.*$/" ,
    "b_cancel" => "/^.*$/" ,
    "b_ok" => "/^.*$/" ,
    "b_ok_x" => "/^.*$/" ,
    "b_ok_y" => "/^.*$/" ,
    "b_prio_down" => "/^.*$/" ,
    "b_prio_up" => "/^.*$/" ,
    "b_report" => "/^.*$/" ,
    "b_report_admin" => "/^.*$/" ,
    "b_report_dozent" => "/^.*$/" ,
    "b_del_inact" => "/^.*$/" ,
    'b_accept' => "//" ,
    'b_activate' => "//" ,
    'b_cancel' => "//" ,
    'b_cancel_order' => "//" ,
    'b_cancel_order_2' => "//" ,
    'b_cancel_release' => "//" ,
    'b_coll_edit' => "//" ,
    'b_coll_release' => "//" ,
    'b_coll_revive' => "//" ,
    'b_deactivate' => "//" ,
    'b_delete' => "//" ,
    'b_delete_url' => "//" ,
    'b_delete_ebook' => "//" ,
    'b_directory' => "//" ,
    'b_edit' => "//" ,
    'b_extend_loan' => "//" ,
    'b_finished' => "//" ,
    'b_new' => "//" ,
    'b_new_email' => "//" ,
    'b_opac' => "//" ,
    'b_reject' => "//" ,
    'b_release' => "//" ,
    'b_return' => "//" ,
    'b_revive' => "//" ,
    'b_setpw' => "//" ,
    'b_user_accept' => "//" ,
    'b_user_reject' => "//" ,
    'b_user_disable' => "//" ,
    'b_user_enable' => "//" ,
    'b_view' => "//" ,
    'b_view_email' => "//" ,
    "c_dontask" => "/^.*$/" ,
    "c_order_toc" => "/^.*$/" ,
    "degree_id" => "/^[0-9]*$/" ,
    "document_id" => "/^[0-9]*$/" ,
    "edition" => "/^.*$/" ,
    "expiry_date_Day" => "/^[0-9]*$/" ,
    "expiry_date_Month" => "/^[0-9]*$/" ,
    "expiry_date_Year" => "/^[0-9]*$/" ,
    "email" => "/^.*$/" ,
    #"email"    => "[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)\b",
    #"email"    => "/[\.a-z0-9_-]+@[a-z0-9-]{2,}\.[a-z]{2,4}$/i", 
    "file" => "/^[^\/]*$/" ,
    "forename" => "/^.*$/" ,
    "id" => "/^[0-9]*$/" ,
    "item" => "/^[a-z]*$/" ,
    "journal" => "/^.*$/" ,
    "collection_id" => "/^[0-9]*$/" ,
    #"collection_no"  => "/^.*$/",
    "collection_no" => "//" ,
    "lecture_no" => "/^.*$/" ,
    "doc_type_id" => "/^[0-9]*$/" ,
    "letter" => "/^.*$/" ,
    "location_id" => "/^[0-9]*$/" ,
    "login" => "/^.*$/" ,
    "mode" => "/^(new|edit|staff|admin|view|opac|file|print|)$/" ,
    "moodle_url" => "/^.*$/" ,
    "notes_to_staff" => "//" ,
    "notes_to_staff" => "//" ,
    "page" => "/^[0-9]*$/" ,
    "pages" => "/^.*$/" ,
    "password" => "/^.*$/" ,
    "pw1" => "/^.*$/" ,
    "pw2" => "/^.*$/" ,
    "userfile1" => "/^.*$/" ,
    "userfile2" => "/^.*$/" ,
    "userfile3" => "/^.*$/" ,
    "phone" => "/^.*$/" ,
    "protected" => "/^.*$/" ,
    "publisher" => "/^.*$/" ,
    "ppn" => "//" ,
    "redirect" => "/^.*$/" ,
    "relevance" => "/^[0-5]?$/" ,
    "role_id" => "/^[0-9]*$/" ,
    "sex" => "/^[mf]?$/" ,
    "shelf_remain" => "/^.*$/" ,
    "signature" => "/^.*$/" ,
    "state" => "/^[a-z]*$/" ,
    "sort_crit" => "/^(author asc|title asc|relevance desc|signature asc|surname asc|)$/" ,
    "surname" => "/^.*$/" ,
    "text" => "//" ,
    "title" => "/^.*$/" ,
    "type" => "/^[a-z]*$/" ,
    "user_id" => "/^[0-9]*$/" ,
    "url" => "/^.*$/" ,
    "url_type_id" => "/^[0-9]*$/" ,
    "use_alias" => "//" ,
    "volume" => "/^.*$/" ,
    "year" => "/^.*$/" ,
    "categories_id" => "/^[0-9]*$/" ,
    "categories" => "/^[0-9]*$/" ,
    "b_cat_ok" => "/^.*$/" ,
) ;


?>
