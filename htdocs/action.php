<?php
/* --- TODO */
#  --- CHECK PERMISSIONS ---

require_once('../php/config.php');
require_once('../php/renderer.class.php');
require_once('../php/util.class.php');
require_once('../php/sql.class.php');

require_once '../php/book.php' ;

$sql        = new SQL();
$renderer   = new Renderer();
$util       = new Util( $sql );

                                                                                 # syntax    # action.php?action=xxx&item=yyy&id=zz
#--------------------------------------------------------------------------------------------------------------------
#--- GET ALL INPUT (POST/GET) ---
#--------------------------------------------------------------------------------------------------------------------
$INPUT = getInput( $util, $renderer  ) ; 
if (isset ($INPUT['work'])) $IW = $INPUT['work'];
if (isset ($INPUT['user'])) $IU = $INPUT['user'];
#deb($_SESSION);

#deb($INPUT,1);
#--------------------------------------------------------------------------------------------------------------------
#--- CHECK PERMISSIONS ---
#--------------------------------------------------------------------------------------------------------------------
#  check_permission($INPUT)

if ( isset ( $IW['b_cancel'] ) )
{  $IW['last_page'] = 'index.php';
   $renderer->doRedirect( $IW['last_page'] );
}

else if ( $IW['item'] == 'collection' )
{
  if      ( $IW['action'] == 'b_coll_release'      )  { setCollectionState_5(       $renderer, $sql, $IW);                } /* Zustand 5 = 'AUFGELÖST'                                    */
  else if ( $IW['action'] == 'b_coll_revive'       )  { setCollectionState_3(       $renderer, $sql, $IW);                } /* Zustand 3 = 'AKTIV'                                        */
  else if ( $IW['action'] == 'b_delete'            )  { setCollectionState_6(       $renderer, $sql, $IW);                } /* Zustand 6 = 'GELÖSCHT'/Mülleimer                           */
  else if ( $IW['action'] == 'b_coll_meta_edit' 
         && $IW['todo'  ] == 'save'                )  { updateColMetaData(          $renderer, $sql, $IW);                 } /* Metadaten des SA updaten                                  */
  else if ( $IW['action'] == 'b_coll_meta_edit'    )  { editColMetaData(            $renderer, $sql, $IW);                 } /* Anzeigen des Formulars um Metadaten des SA zu bearbeiten  */
  else if ( $IW['action'] == 'b_new'   
         && $IW['todo'  ] == 'init'                )  { saveNewCollection(          $renderer,         $sql, $IW , $IU) ;   } /* Metadaten des NEUEN SA speichern / SA anlegen            */
  else if ( $IW['action'] == 'b_new'               )  { newCollection(              $renderer, $util , $sql, $IW);          } /* Metadaten des NEUEN SA ermitteln/eingeben                */
  else if ( $IW['action'] == 'show'                )  { showCollection(             $renderer, $util , $sql, $IW , $IU) ;   } /*  SAs wird angezeigt (deren Editierbarkeit ist abhängig von der Rolle des Nuters)   */
  else if ( $IW['action'] == 'b_coll_edit'         )  { editCollection(             $renderer, $util , $sql, $IW , $IU) ;   } /*  SAs wird angezeigt (deren Editierbarkeit ist abhängig von der Rolle des Nuters)   */
  else if ( $IW['action'] == 'print'               )  { showCollectionPrintversion( $renderer, $util , $sql, $IW , $IU) ;   } /* Printversion des SAs wird angezeigt (nur aktive Medien)   */
  else if ( $IW['action'] == 'showopen'            )  { showCollectionLists(        $renderer, $util , $sql, $IW , $IU) ;   } /* Zeigt die Liste der SAs, gefiltert nach deren Zustand     */
  else if ( $IW['action'] == 'b_kill'              )  { ereaseCollection(           $renderer,         $sql, $IW , $IU) ;   } /* Löscht SA endgültig                                       */
}

else if ( $IW['item'] == 'book' )
{
       if ( $IW['action'] == 'b_new'               )  {  showNewBookForm(     $renderer, $sql , $IW );         } /* Eingabemaske für Mediensuche anzeigen                           */
  else if ( $IW['action'] == 'search'              )  {  searchMedia(         $renderer, $sql,  $IW );         } /* Suchprozess des Mediums wird gestartet                          */
  else if ( $IW['action'] == 'annoteNewMedia'      )  {  annoteNewMediaForm(  $renderer, $sql , $IW, $IU );    } /* Eingabemaske Metadaten für Buch Annotation anzeigen             */
  else if ( $IW['action'] == 'init'                )  {  saveNewMedia(        $renderer, $sql , $IW );         } /* Metadaten eines neues Buch speichern                            */
  else if ( $IW['action'] == 'suggest'             )  {  saveNewMediaSuggest( $renderer, $sql , $IW );         } /* Metadaten eines Literaturvoschlag speichern                     */
  else if ( $IW['action'] == 'b_edit'              )  {  editMediaMetaData(   $renderer, $sql , $IW, $IU );    } /* Formular zur Bearbeitung der Metadaten des Buchs wird gezeigt   */
  else if ( $IW['action'] == 'save'                )  {  updateMediaMetaData( $renderer, $sql , $IW, $IU );    } /* Update der Metadaten des Buchs                                  */
  else if ( $IW['action'] == 'b_accept'            )  {  acceptMedia(         $renderer, $sql , $IW );         } /* angefordertes Buch wird akzeptiert zur Bearbeitung              */
  else if ( $IW['action'] == 'b_finished'          )  {  doneMedia(           $renderer, $sql , $IW );         } /* angefordertes Buch steht für die Studies bereit                 */
  else if ( $IW['action'] == 'b_cancel_order'      )  {  cancelMedia(         $renderer, $sql , $IW );         } /* Buchbestellung wird storiert                                    */
  else if ( $IW['action'] == 'b_release'           )  {  releaseMedia(        $renderer, $sql , $IW );         } /* Buch wird zurückgegeben                                         */
  else if ( $IW['action'] == 'b_revive'            )  {  reviveMedia(         $renderer, $sql , $IW );         } /* storierte Buchbestellung wird erneuert                          */
  else if ( $IW['action'] == 'b_delete'            )  {  deleteMedia(         $renderer, $sql , $IW );         } /* Buch wird aus SA gelöscht                                       */
  else if ( $IW['action'] == 'b_kill'              )  {  ereaseMedia(         $renderer, $sql , $IW, $IU);     } /* Buch wird endgültig aus SA gelöscht                             */
  else if ( $IW['action'] == 'b_return'            )  {  returnDoneMedia(     $renderer, $sql , $IW );         } /* Buchrückgabe ist erledigt                                       */
  else if ( $IW['action'] == 'b_cancel_release'    )  {  cancel_release(      $renderer, $sql , $IW );         } /* Buch verlängern / Buchrückgabe abbrechen                        */
  else if ( $IW['action'] == 'purchase_suggestion' )  {  purchase_suggestion( $renderer, $sql , $IW, $IU );    } /* Erwebungsvorschlag (nach 0 Suchtreffern)                        */
  else if ( $IW['action'] == 'b_new_email'         )  {  showMailForm       ( $renderer, $sql , $IW, $IU );    } /* Mailformular für Infomail an Nutzer                        */
}

else if ( $IW['item'] == 'ebook' )
{
  if      ( $IW['action'] == 'b_edit'              )  {  editMediaMetaData(   $renderer, $sql , $IW, $IU  );   } /* Metadaten des SA bearbeiten                                     */
  else if ( $IW['action'] == 'annoteNewMedia'      )  {  annoteNewMediaForm(  $renderer, $sql , $IW, $IU  );   } /* Eingabemaske Metadaten für Buch Annotation anzeigen             */
  else if ( $IW['action'] == 'save'                )  {  updateMediaMetaData( $renderer, $sql , $IW, $IU  );   } /* Update der Metadaten des Buchs                                  */
  else if ( $IW['action'] == 'init'                )  {  saveNewMedia(        $renderer, $sql , $IW );         } /* Update der Metadaten des Buchs                                  */
  else if ( $IW['action'] == 'b_deactivate'        )  {  deactivateMedia(     $renderer, $sql , $IW );         } /* Medium Deaktivieren                                             */
  else if ( $IW['action'] == 'b_activate'          )  {  activateMedia(       $renderer, $sql , $IW );         } /* Medium Aktivieren                                               */
  else if ( $IW['action'] == 'b_activate'          )  {  activateMedia(       $renderer, $sql , $IW );         } /* Medium Aktivieren                                               */
  else if ( $IW['action'] == 'b_delete_ebook'      )  {  deleteMedia(         $renderer, $sql , $IW );         } /* Medium wird aus SA gelöscht                                     */
  else if ( $IW['action'] == 'b_delete'            )  {  deleteMedia(         $renderer, $sql , $IW );         } /* Medium wird aus SA gelöscht                                     */
  else if ( $IW['action'] == 'b_new_email'         )  {  showMailForm       ( $renderer, $sql , $IW, $IU );    } /* Erwebungsvorschlag (nach 0 Suchtreffern)                        */
}

else if ( $IW['item'] == 'email' )
{
  if      ( $IW['action'] == 'sendmail'              )  {  send_email(   $renderer, $sql , $IW, $IU  );   } /* Metadaten des SA bearbeiten                                          */
}

####################### --- MAIL --- #######################
function send_email(   $renderer, $sql , $IW, $IU  )
{
     #deb($IU,1);
     $IW['to'] = 'werner.welte@haw-hamburg.de';
     $to        = $IW['to'];
   
     $col_info  = $sql->getCollection($IW['collection_id']);
     $url       = "action.php?item=collection&collection_id=".$IW['collection_id']."&item=collection&action=b_coll_edit";
     $subject   = 'Ihr ELSE Semesterapparat';
     $message   = $IW['txt'];

     $header    = 'From: '         .$IW['from']. "\r\n" ;
     $header   .= 'Reply-To: '     .$IW['from']. "\r\n";
     $header   .= 'Bcc: '          .$IW['from']. "\r\n";
     $header   .= "Mime-Version: 1.0\r\n";
     $header   .= "Content-type: text/plain; charset=iso-8859-1";
     $header   .= 'X-Mailer: PHP/' . phpversion();
     
     if ($IU['role_name'] == 'staff' OR $IU['role_name'] == 'admin' OR $IU['role_name'] == 'edit'  )
     $sendok =  mail ( $to , $subject , $message , $header  );

     if ($sendok) { $link = "<a style=\"text-decoration: none;\" href=\"$url\">Mail erfolgreich gesendet</a>"; }
     else         { $link = "<a style=\"text-decoration: none;\" href=\"$url\">ERROR: Mail nicht versendet!</a>"; }
     
	 $tpl_vars['link' ] = $link;
	 $tpl_vars[ 'work'            ] = $IW; 
     $tpl_vars[ 'user'            ] = $IU; 
     $tpl_vars[ 'ci'              ] = $col_info; 
     
     
     $renderer->do_template( 'email.tpl', $tpl_vars );
}

function showMailForm( $renderer, $sql , $IW, $IU )
{

 $doc_info     = $sql->getDocumentInfos ( $IW['document_id'] );
 $col_info     = $sql->getCollection($doc_info['collection_id']);
 $user_info    = $sql->getUserData( $col_info['user_id']  );

 #deb($user_info); 
 #deb($col_info);  
 #deb($doc_info);  

 if ($user_info['sex'] == 'f' ) {$salutaton = 'Sehr geehrte Frau ' .$user_info['surname']; }
 else                           {$salutaton = 'Sehr geehrter Herr '.$user_info['surname']; }

$tpl_vars['fromFirstName'  ]  = $IU['forename'];
$tpl_vars['fromName'       ]  = $IU['surname'];
$tpl_vars['fromEmail'      ]  = $IU['email'];

$tpl_vars['salutaton'      ]  = $salutaton;
$tpl_vars['toFirstName'    ]  = $user_info['forename'];
$tpl_vars['toName'         ]  = $user_info['surname'];
$tpl_vars['toEmail'        ]  = $user_info['email'];

$tpl_vars['collectionName' ]  = $col_info['title'];
$tpl_vars['documentName'   ]  = $doc_info['title'];

$tpl_vars['collection_id'  ]  = $doc_info['collection_id'];

$tpl_vars['link' ] = '';

$tpl_vars[ 'work'            ] = $IW; 
$tpl_vars[ 'user'            ] = $IU; 
$tpl_vars[ 'ci'              ] = $col_info; 

        
$renderer->do_template( 'email.tpl', $tpl_vars );
  
}



####################### --- COLLECTION --- #######################


function ereaseCollection( $renderer, $sql, $IW, $IU)
{
  $sql->deleteCollection($IW, $IU);
  $url       = "index.php";
  $renderer->doRedirect( $url );
}        
        
        
function saveNewCollection($renderer, $sql, $IW, $IU)
{
    $sql->initCollection($IW, $IU);
    $url       = "index.php";
    $renderer->doRedirect( $url );
}        

function editColMetaData($renderer , $sql,  $IW )
{
  $tpl_vars['user']               = $_SESSION['user'];
  $tpl_vars['work']               = $IW;
  $tpl_vars['colData']            = $sql->getCollectionInfos( $IW[ 'collection_id' ] );
  $tpl_vars['tpl']['departments'] = $sql->getAllDepartments();
  $tpl_vars['tpl']['bib_info']    = $sql->getBibInfos('name');
  $tpl_vars['tpl']['role_info']   = $sql->getRoleInfos('name');
 
  $renderer->do_template ( 'b_edit_collection.tpl' , $tpl_vars ) ;
  exit(0);
}

function newCollection($renderer , $util,  $sql,  $IW )
{
  $departments = $sql->getAllDepartments();
  $bib_info    = $sql->getBibInfos('name');
  $role_info   = $sql->getRoleInfos('name');

  $colData = array();
  $colData['categories_id']          = $util->resolveLocationID( $_SESSION['user']['department'] );
  $colData['title']                  = "";
  $colData['notes_to_studies']       = "";
  $colData['expiry_date']            = $util->get_new_expiry_date();        
  $colData['location_id']            = 2;         /* TODO Standard Vorgabenwert ermitteln */
  $colData['state_id'   ]            = 3;         /* Standard Vorgabenwert ermitteln */
  
  $tpl_vars['user']                  = $_SESSION['user'];
  $tpl_vars['work']                  = $IW;
  $tpl_vars['work']['collection_id'] = 0;
  $tpl_vars['work']['id']            = 0;
  $tpl_vars['colData'][0]            = $colData ;
  $tpl_vars['tpl']['departments']    = $departments;
  $tpl_vars['tpl']['bib_info']       = $bib_info;
  $tpl_vars['tpl']['role_info']      = $role_info ;
  
  $renderer->do_template ( 'b_edit_collection.tpl' , $tpl_vars ) ;
  exit(0);
}



function  showCollectionLists( $renderer, $util , $sql, $IW , $IU)
{ 
  global  $CONST_actions_info;

/* getAllMediaFromCollection - Liefert alle Medien Daten: 
$colID:       0 = ALLE, 
$state_id:    1 = neu bestellt, 2 wird bearbeitet, 3 aktiv, 4 wird entfernt, 5 inaktiv, 6 gelöscht, 9 Erwerbvorschlag 
$doc_type_id: 1 = Buch, 2 = E-Book
*/ 

 
  $IW['mode'] = $IU['role_name'];
  $tpl_vars[ 'work'            ] = $IW; 
  $tpl_vars[ 'user'            ] = $IU;                                          
  $tpl_vars[ 'collection_info' ] = $sql->getCollectionInfos( null, 1, $IW['todo'] , true ); # ($colID , $doc_type_id, $doc_state_id, $short )
  if( $IW['todo']  == 6 ){
   $ebooks = $sql->getCollectionInfos( null, 4, $IW['todo'] , true );                      /* In der Liste 'Gelöscht' werden auch E-Books angezeigt */
   $tpl_vars[ 'collection_info' ] = array_merge($tpl_vars[ 'collection_info' ], $ebooks );
  }
  $tpl_vars[ 'doc_type'        ] = $sql->getAllDocTypes();
  $tpl_vars[ 'media_state'     ] = $sql->getAllMedStates();
  $tpl_vars[ 'fachbib'         ] = $sql->getBibInfos();
  $tpl_vars[ 'location'        ] = $sql->getAllDepartments();
  $tpl_vars[ 'actions_info'    ] = $CONST_actions_info;
  #deb($tpl_vars);
  $renderer->do_template( 'collection.tpl', $tpl_vars );
 }

         

function editCollection( $renderer, $util , $sql, $IW , $IU)
{ 
  global  $CONST_actions_info;

  $tpl_vars[ 'user'            ] = $IU; 
  $tpl_vars[ 'work'            ] = $IW; 
  $tpl_vars[ 'work'            ]['mode'] = $IU['role_name'];                                      
  $tpl_vars[ 'collection_info' ] = $sql->getCollectionInfos( $IW['collection_id'] );
  $tpl_vars[ 'doc_type'        ] = $sql->getAllDocTypes();
  $tpl_vars[ 'media_state'     ] = $sql->getAllMedStates();
  $tpl_vars[ 'fachbib'         ] = $sql->getBibInfos();
  $tpl_vars[ 'location'        ] = $sql->getAllDepartments();
  $tpl_vars[ 'errors_info'     ][] = '';
  $tpl_vars[ 'actions_info'    ] = $CONST_actions_info;
  
  #deb($tpl_vars );
  $renderer->do_template( 'collection.tpl', $tpl_vars );
 }



function showCollection( $renderer, $util , $sql, $IW , $IU)
{ 
  global  $CONST_actions_info;
  $tpl_vars[ 'work'            ] = $IW; 
  $tpl_vars[ 'user'            ] = $IU;                                          
  $tpl_vars[ 'collection_info' ] = $sql->getCollectionInfos( $IW['collection_id'] );
 # $tpl_vars[ 'collection_info' ][0][ 'document_info' ] = $sql->getDokumentList( $IW['collection_id'] );
  $tpl_vars[ 'doc_type'        ] = $sql->getAllDocTypes();
  $tpl_vars[ 'media_state'     ] = $sql->getAllMedStates();
  $tpl_vars[ 'fachbib'         ] = $sql->getBibInfos();
  $tpl_vars[ 'location'        ] = $sql->getAllDepartments();
  $tpl_vars[ 'errors_info'     ][] = '';
  $tpl_vars[ 'actions_info'    ] = $CONST_actions_info;
  #deb($tpl_vars);
  $renderer->do_template( 'collection.tpl', $tpl_vars, ( $IW[ 'action' ] != 'print' ) );
 }

 
function setCollectionState_6($renderer,  $sql, $IW)
{
  $colInfo = $sql->getCollectionInfos ($IW['collection_id']);
  foreach ( $colInfo[$IW['collection_id']]['document_info'] as $di )
  {
    if ( $di['doc_type_id'] == 1  )   # Medium ist Buch
    {   
       if ($di['state_id'] ==  1   ) {  $sql->setMediaState( $IW['document_id'], 6 ); }  #  'Neu Bestellt'    -> 'Gelöscht'  
       if ($di['state_id'] ==  2   ) {  $sql->setMediaState( $IW['document_id'], 4 ); }  #  'Wird bearbeitet' -> 'Wird entfernt'  
       if ($di['state_id'] ==  3   ) {  $sql->setMediaState( $IW['document_id'], 4 ); }  #  'aktiv'           -> 'Wird entfernt'  
    }
    else if ( $di['doc_type_id'] == 4  )   # Medium ist Ebook
    {
       $sql->setMediaState(  $IW['document_id'], 6 );
    }
  }
  $sql->setCollectionState( $IW['collection_id'], 6 );
  $renderer->doRedirect( $IW['last_page'] );
}        
function setCollectionState_3($renderer,  $sql, $IW)
{
  $sql->setCollectionState( $IW['collection_id'], 3 );
  $renderer->doRedirect( $IW['last_page'] );
}        
function setCollectionState_5($renderer,  $sql, $IW)
{
  $sql->setCollectionState( $IW['collection_id'], 5 );
  $renderer->doRedirect( $IW['last_page'] );
}        


function  saveColMetaData($renderer, $sql ,   $IW )
{
  $sql->saveColMetaData  ($renderer, $sql, $IW);    
  $url = "action.php?item=collection&collection_id=".$IW['collection_id']."&item=collection&action=b_coll_edit";
  $renderer->doRedirect( $url );
  
}
 

 
function updateColMetaData($renderer,  $sql, $IW)
{
  $sql-> updateColMetaData($IW);
#  $url = "action.php?item=collection&collection_id=".$IW['collection_id']."&item=collection&action=b_coll_edit";
  $url = "index.php";
  $renderer->doRedirect( $url );
}

function redirToCollection($renderer, $IW )
{
    $renderer->doRedirect( $IW['last_page'] );
    exit(0);
}


####################### --- MEDIA  --- #######################
function updateMediaMetaData($renderer, $sql , $IW,  $IU )
{
  $sql-> updateMediaMetaData($IW);                                                                   /* Metadaten des neuen Mediums speichern */
  $url = "action.php?item=collection&collection_id=".$IW['collection_id']."&item=collection&action=b_coll_edit";
  $renderer->doRedirect( $url);
  exit(0);
}

function editMediaMetaData($renderer, $sql , $IW,  $IU )
{
   $book =  $sql->getMediaMetaData($IW['document_id']);
   
   $tpl_vars['book']                      = $book;    
   $tpl_vars['work']                      = $IW;    
   $tpl_vars['colData']                   = $sql->getCollectionInfos ( $IW['collection_id'] );
   $tpl_vars['work']['notes_to_studies']  = "";    
   $tpl_vars['work']['notes_to_staff']    = "";    

   if   ( $book['state_id'] == 9 )       { $tpl_vars['work']['todo']  = "suggest";   } # 9 = Suggest Mode / Kaufvorschlag
   
   if ( isset( $book[ 'notes_to_studies' ] )  ) {$tpl_vars['work']['notes_to_studies'] =  $book[ 'notes_to_studies' ]; }
   if ( isset( $book[ 'notes_to_staff'   ] )  ) {$tpl_vars['work']['notes_to_staff'  ] =  $book[ 'notes_to_staff'   ]; }
   if (! isset( $book[ 'signature'        ] ) ) {$tpl_vars['work']['signature'       ] =  getSignature( $book[ 'ppn' ] ); }
  # deb($tpl_vars,1);
   $renderer->do_template ( 'edit_book.tpl' , $tpl_vars ) ;
    exit(0);
}

function purchase_suggestion($renderer, $sql , $IW,  $IU )
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
   
    $tpl_vars['book']                          = $book;    
    $tpl_vars['work']                          = $IW;    
    $tpl_vars['colData']                       = $sql->getCollectionInfos ( $IW['collection_id'] );
    $tpl_vars['work']['mode']                  = "suggest";    
    $tpl_vars['work']['document_id']           = 0 ; 

   #deb($tpl_vars,1);
    $renderer->do_template ( 'edit_book.tpl' , $tpl_vars ) ;
    exit(0);
}



function  activateMedia($renderer, $sql ,   $IW )
{
  $sql->setMediaState(  $IW['document_id'], 3 );
  $renderer->doRedirect( $IW['last_page'] );
}

function deactivateMedia($renderer, $sql ,   $IW )
{
  $sql->setMediaState(  $IW['document_id'], 5 );
  $renderer->doRedirect( $IW['last_page'] );
}


function cancel_release($renderer, $sql ,   $IW )
{
  $sql->setMediaState(  $IW['document_id'], 2 );
  $renderer->doRedirect( $IW['last_page'] );
}


function returnDoneMedia($renderer, $sql ,   $IW )
{
  $sql->setMediaState(  $IW['document_id'], 5 );
  $renderer->doRedirect( $IW['last_page'] );
}

function reviveMedia($renderer, $sql ,   $IW )
{
  $sql->setMediaState(  $IW['document_id'], 1 );
   $renderer->doRedirect( $IW['last_page'] );
}

function acceptMedia($renderer, $sql ,  $IW )  
{
  $sql->setMediaState(  $IW['document_id'], 2 );
  $renderer->doRedirect( $IW['last_page'] );
}

function doneMedia($renderer, $sql ,  $IW )  
{
  $sql->setMediaState(  $IW['document_id'], 3 );
  $renderer->doRedirect( $IW['last_page'] );
}

function releaseMedia($renderer, $sql , $IW )
{
  $sql->setMediaState(  $IW['document_id'], 4 );
  $renderer->doRedirect( $IW['last_page'] );
}

function cancelMedia($renderer, $sql , $IW )
{
  $sql->setMediaState(  $IW['document_id'], 5 );
  $renderer->doRedirect( $IW['last_page'] );
}

function deleteMedia($renderer, $sql ,  $IW )  
{
  $sql->setMediaState(  $IW['document_id'], 6 );
  $renderer->doRedirect( $IW['last_page'] );
}

function ereaseMedia( $renderer, $sql, $IW, $IU)
{
    $sql->deleteMedia($IW, $IU);
    $url       = "index.php";
    $renderer->doRedirect( $url );
}        
    



function saveNewMedia($renderer, $sql ,  $IW )
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
  $book = array_merge($book,  $bookSE );                                        /* Metadaten des ausgwählten Mediums, übernommen aus dem Katalog*/
  $book['title']            = $IW['title'];                                                        /* Änderungen des Benutzers, aus dem Forumlar übernommen*/
  $book['author']           = $IW['author']; 
  $book['publisher']        = $IW['publisher']; 
  $book['signature']        = $IW['signature']; 
  $book['notes_to_staff'  ] = $IW['notes_to_staff']; 
  $book['notes_to_studies'] = $IW['notes_to_studies']; 
  if (isset ($IW['shelf_remain'])) {
  $book['shelf_remain']     = $IW['shelf_remain'];} 

  if ($book['physicaldesc']  == 'electronic')
  { 
   $book['doc_type_id'] = 4; /*  E-BOOK               */
   $book['state_id'   ] = 3; /* Status: AKTIV         */
  }  
  else 
 { 
   $book['doc_type_id'] = 1; /* BUCH                  */
   $book['state_id'   ] = 1 ;/* Status: NEU BESTELLT  */
 }  
  
  
  $sql->initMedia($book);                                                                   /* Metadaten des neuen Mediums speichern */
  $url = "action.php?item=collection&collection_id=".$IW['collection_id']."&item=collection&action=b_coll_edit";
  $renderer->doRedirect( $url );
 }       

function saveNewMediaSuggest ($renderer, $sql ,  $IW )
{
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

  if (isset ( $IW['collection_id'   ] )) { $book['collection_id'   ]   = $IW['collection_id'];   } 
  if (isset ( $IW['title'           ] )) { $book['title'           ]   = $IW['title'];           } 
  if (isset ( $IW['author'          ] )) { $book['author'          ]   = $IW['author'];          } 
  if (isset ( $IW['publisher'       ] )) { $book['publisher'       ]   = $IW['publisher'];       } 
  if (isset ( $IW['signature'       ] )) { $book['signature'       ]   = $IW['signature'];       } 
  if (isset ( $IW['notes_to_staff'  ] )) { $book['notes_to_staff'  ]   = $IW['notes_to_staff'];  } 
  if (isset ( $IW['notes_to_studies'] )) { $book['notes_to_studies']   = $IW['notes_to_studies'];} 

  $sql->initMedia($book);                                                                   /* Metadaten des neuen Mediums speichern */
  
  $renderer->doRedirect( $IW['last_page'] );
 }       

 
function getDocTypeID($book)
{ 
  $doc_type_id = 1;
  if ($book['physicaldesc'] == "print"      ) { $doc_type_id = 1; }
  if ($book['physicaldesc'] == "electronic" ) { $doc_type_id = 4; }
  return $doc_type_id;
}

function annoteNewMediaForm($renderer, $sql, $IW, $IU)
{   
  
    $book =   $_SESSION['books'][$IW['ppn']];
    $tpl_vars['colData']                  = $sql->getCollectionInfos ( $IW['collection_id'] );
    $tpl_vars['work']                     = $IW;    
    $tpl_vars['work']['mode']             = "new";    
    $tpl_vars['work']['dockument_id']     = "";    
    $tpl_vars['book']                     = $book;    
    $tpl_vars['book']['doc_type_id']      = getDocTypeID($book);
    $tpl_vars['book']['shelf_remain']     = 0;
    $tpl_vars['book']['notes_to_studies'] = "";    
    $tpl_vars['book']['notes_to_staff']   = "";    
    # $tpl_vars['book']['signature'       ] =  getSignature( $book[ 'ppn' ] ); 
    # deb($tpl_vars,1);

    $renderer->do_template ( 'edit_book.tpl' , $tpl_vars ) ;
    exit(0);
}

function searchMedia($renderer, $sql, $IW)
{
    $toSearch['title']          = $IW['title'];
    $toSearch['author']         = $IW['author'];
    $toSearch['signature']      = $IW['signature'];
    $books = $_SESSION['books'] = getBooks ( $toSearch ) ;    
   
    if ( isset($books['hits']) AND $books['hits'] < 1 )                                              /*  -- Suche ergab keinen Treffer */
    {
       showNewBookForm($renderer, $sql, $IW, $toSearch, $books['hits'] );
    }
    else
    {
     showHitList($renderer, $sql, $IW, $books);
    }
}


function showHitList($renderer, $sql, $IW, $books)
{
  $tpl_vars['page']               = 2;                                                                  /* Seite 2 = Anzeige der Trefferliste nach der Suche */
  $tpl_vars['user']               = $_SESSION['user'];
  $tpl_vars['work']               = $IW;
  $tpl_vars['colData']            = $sql->getCollectionInfos ( $IW['collection_id'] );
  $tpl_vars['searchHits']         = $books['hits'];                                                        
  $tpl_vars['books_info']         = $books;                                                          
  $renderer->do_template ( 'new_book.tpl' , $tpl_vars ) ;
  exit(0);
}



function showNewBookForm($renderer, $sql, $IW, $toSearch = NULL, $searchHits = 1 )
{ 
  $tpl_vars['page']               = 1;                                                                  /* Seite 1 = Eingabemaske für die Suchbegriffe bei der Mediensuche */
  $tpl_vars['user']               = $_SESSION['user'];
  $tpl_vars['work']               = $IW;
  $tpl_vars['colData']            = $sql->getCollectionInfos ( $IW['collection_id'] );
  $tpl_vars['searchHits']         = $searchHits;                                                          
  $tpl_vars['book']['title']      = $toSearch['title'];                                                                 /*   */
  $tpl_vars['book']['author']     = $toSearch['author'];                                                                 /*   */
  $tpl_vars['book']['signature']  = $toSearch['signature'];                                                                 /*   */
  $renderer->do_template ( 'new_book.tpl' , $tpl_vars ) ;
  exit(0);
}




####################### --- TOOLS --- #######################

function check_permission($INPUT)
{ 
  global $CONST_actions_info;
  #--------------------------------------------------------------------------------------------------------------------
  if ( !$util->check_acl( $CONST_actions_info[ $INPUT['work'][ 'action' ] ][ 'acl' ], $INPUT['work'][ 'item' ], $INPUT['work'][ 'id' ] ))            
  {
    user_error( "Permission denied: action ".$INPUT['work'][ 'action' ]." on item type ".$INPUT['work'][ 'item' ]." for: ".$_SESSION['user']['role_name']." / ".$_SESSION['user']['surname']." " ,  E_USER_ERROR ); 
  }
}


function getInput( $util, $redirect  )
{
  global $CONST_actions_info;
  
  $INPUT                       = $_SESSION;
  $INPUT['work'][ 'todo'     ] = '';
  $INPUT['work'][ 'item'     ] = '';
  $INPUT['work'][ 'collection_id'   ] = '';
  $INPUT['work'][ 'file'     ] = '';
  $INPUT['work'][ 'redirect' ] = '';
  $INPUT['work'][ 'state'    ] = '';
  
  $INPUT['work']   = array_merge( $INPUT['work'], $_GET, $_POST  );                                      
  
  $INPUT['work'][ 'mode'     ] = '';

  if( isset ($INPUT['work' ][ 'action' ]) AND  isset ( $CONST_actions_info[ $INPUT['work' ][ 'action' ] ] [ 'input' ] )  )   
  { 
    $INPUT[ 'work' ] = array_merge( $INPUT[ 'work' ],  $CONST_actions_info[ $INPUT['work' ][ 'action' ] ] [ 'input' ] );   /* get mode */
  }
  else 
  {
    #$INPUT[ 'work' ][ 'action' ] = '';
  } 

  return $INPUT;

}      



?>
