<?php
session_start();
#$_SESSION = "";
## --- TODO ---  --- CHECK PERMISSIONS ---
# check_permission($INPUT)
require_once( '../php/const.class.php'             );
require_once( '../php/config.class.php'            );
require_once( '../php/sql.class.php'         );
require_once( '../php/util.class.php'        );
require_once( '../php/collection.class.php'  );
require_once( '../php/media.class.php'       );
require_once( '../php/renderer.class.php'    ); 

$CST        = new CONSTANT();
$CFG        = new Config( $CST );
$SQL        = new SQL( $CFG );
$util       = new Util( $CFG, $SQL  );
$renderer   = new Renderer( $CFG );
$collection = new Collection( $CFG, $SQL, $renderer );
$media      = new Media( $CFG, $SQL, $renderer );

#$util->  check_host();

$INPUT = $util->getInput();                                #--- GET ALL INPUT (GET) ---

if ( isset ( $_SESSION[ 'work'  ] ) ) { $IW = $_SESSION[ 'work' ]; }  # Übergebene Werte der einzelnen Services
if ( isset ( $_SESSION[ 'user'  ] ) ) { $IU = $_SESSION[ 'user' ]; }  # Alle Userdaten
if ( isset ( $_SESSION[ 'coll'  ] ) ) { $IC = $_SESSION[ 'coll' ]; }  # Alle Semesterapparatdaten

#$CFG->C->deb( $_GET);
#$CFG->C->deb( $_SESSION['user']['role'] );

if ( $IW[ 'categories'] != '' OR $IW[ 'letter'] != '' )  
{ $media ->  renderDozSort ();  # Dozenten sortiert
}

if(( $_SESSION['user']['role'] == 3 OR $_SESSION['user']['role'] == 2 ))  ## Folgende Aktionen nur für Dozenten oder Staff
{
  if ( $IW['item'] == 'collection'  )
{ 
  if      ( $IW['action'] == 'b_coll_edit'         )  { $collection->editCollection            ( $IW, $IU,$IC  ) ;   } /*  SAs wird angezeigt (deren Editierbarkeit ist abhängig von der Rolle des Nuters)   */
  else if ( $IW['action'] == 'print'               )  { $collection->showCollectionPrintversion( $IW, $IU      ) ;   } /* Printversion des SAs wird angezeigt (nur aktive Medien)    */
  else if ( $IW['action'] == 'show'                )  { $collection->showCollection            ( $IW, $IU      ) ;   } /*  SAs wird angezeigt (deren Editierbarkeit ist abhängig von der Rolle des Nuters)   */
  else if ( $IW['action'] == 'showopen'            )  { $collection->showCollectionLists       ( $IW, $IU      ) ;   } /* Zeigt die Liste der SAs, gefiltert nach deren Zustand      */

  else if ( $IW['action'] == 'b_coll_release'      )  { $collection->setCollectionState_5      ( $IW           );    } /* Zustand 5 = 'AUFGELÖST'                                    */
  else if ( $IW['action'] == 'b_coll_revive'       )  { $collection->setCollectionState_3      ( $IW           );    } /* Zustand 3 = 'AKTIV'                                        */
  else if ( $IW['action'] == 'b_delete'            )  { $collection->setCollectionState_6      ( $IW           );    } /* Zustand 6 = 'GELÖSCHT'/Mülleimer                           */
  else if ( $IW['action'] == 'b_coll_meta_edit'    )  { $collection->editColMetaData           ( $IW, $IU      );    } /* Anzeigen des Formulars um Metadaten des SA zu bearbeiten   */
  else if ( $IW['action'] == 'b_coll_meta_save'    )  { $collection->updateColMetaData         ( $IW, $IU      );    } /* Metadaten des SA updaten                                   */
  else if ( $IW['action'] == 'b_new_init'          )  { $collection->saveNewCollection         ( $IW, $IU      ) ;   } /* Metadaten des NEUEN SA speichern / SA anlegen              */
  else if ( $IW['action'] == 'b_new'               )  { $collection->newCollection             ( $IW           ) ;   } /* Metadaten des NEUEN SA ermitteln/eingeben                  */
  else if ( $IW['action'] == 'b_coll_delete'       )  { $collection->deleteCollection          ( $IW, $IU, $IC ) ;   } /*  SAs wird angezeigt (deren Editierbarkeit ist abhängig von der Rolle des Nuters)   */
  else if ( $IW['action'] == 'b_kill'              )  { $collection->ereaseCollection          ( $IW, $IU      ) ;   } /* Löscht SA endgültig                                        */
  else if ( $IW['action'] == 'resort'              )  { $collection->resortCollection          ( $IW, $IC      ) ;   } /* Setzt neue Reihenfolge der Dokumente im SA                 */
}

else if ( $IW['item'] == 'book' OR $IW['item'] == 'cd-rom' )
{      if ( $IW['action'] == 'b_new'               )  {  $media->showNewBookForm    ( $IW              ); } /* Eingabemaske für Mediensuche anzeigen                           */
  else if ( $IW['action'] == 'search'              )  {  $media->searchMedia        ( $IW              ); } /* Suchprozess des Mediums wird gestartet                          */
  else if ( $IW['action'] == 'annoteNewMedia'      )  {  $media->annoteNewMediaForm ( $IW, $IU         ); } /* Eingabemaske Metadaten für Buch Annotation anzeigen             */
  else if ( $IW['action'] == 'init'                )  {  $media->saveNewMedia       ( $IW, $IC, $IU    ); } /* Metadaten eines neues Buch speichern                            */
  else if ( $IW['action'] == 'suggest'             )  {  $media->saveNewMediaSuggest( $IW, $IU         ); } /* Metadaten eines Literaturvoschlag speichern                     */
  else if ( $IW['action'] == 'b_edit'              )  {  $media->editMediaMetaData  ( $IW, $IC         ); } /* Formular zur Bearbeitung der Metadaten des Buchs wird gezeigt   */
  else if ( $IW['action'] == 'save'                )  {  $media->updateMediaMetaData( $IW, $IU         ); } /* Update der Metadaten des Buchs                                  */
  else if ( $IW['action'] == 'b_accept'            )  {  $media->acceptMedia        ( $IW              ); } /* angefordertes Buch wird akzeptiert zur Bearbeitung              */
  else if ( $IW['action'] == 'b_finished'          )  {  $media->doneMedia          ( $IW              ); } /* angefordertes Buch steht für die Studies bereit                 */
  else if ( $IW['action'] == 'b_cancel_order'      )  {  $media->cancelMedia        ( $IW              ); } /* Buchbestellung wird storiert                                    */
  else if ( $IW['action'] == 'b_release'           )  {  $media->releaseMedia       ( $IW              ); } /* Buch wird zurückgegeben                                         */
  else if ( $IW['action'] == 'b_revive'            )  {  $media->reviveMedia        ( $IW              ); } /* storierte Buchbestellung wird erneuert                          */
  else if ( $IW['action'] == 'b_delete'            )  {  $media->deleteMedia        ( $IW              ); } /* Buch wird aus SA gelöscht                                       */
  else if ( $IW['action'] == 'b_kill'              )  {  $media->ereaseMedia        ( $IW, $IU         ); } /* Buch wird endgültig aus SA gelöscht                             */
  else if ( $IW['action'] == 'b_return'            )  {  $media->returnDoneMedia    ( $IW              ); } /* Buchrückgabe ist erledigt                                       */
  else if ( $IW['action'] == 'b_cancel_release'    )  {  $media->cancel_release     ( $IW              ); } /* Buch verlängern / Buchrückgabe abbrechen                        */
  else if ( $IW['action'] == 'purchase_suggestion' )  {  $media->purchase_suggestion( $IW, $IU         ); } /* Erwebungsvorschlag (nach 0 Suchtreffern)                        */
  else if ( $IW['action'] == 'b_new_email'         )  {  $media->showMailForm       ( $IW, $IU, $IC    ); } /* Mailformular für Infomail an Nutzer                             */
}

else if ( $IW['item'] == 'ebook' OR $IW['item'] == 'lh_book' )
{ if      ( $IW['action'] == 'b_edit'              )  {  $media->editMediaMetaData  ( $IW, $IC       ); } /* Metadaten des SA bearbeiten                                     */
  else if ( $IW['action'] == 'annoteNewMedia'      )  {  $media->annoteNewMediaForm ( $IW, $IU       ); } /* Eingabemaske Metadaten für Buch Annotation anzeigen             */
  else if ( $IW['action'] == 'save'                )  {  $media->updateMediaMetaData( $IW, $IU       ); } /* Update der Metadaten des Buchs                                  */
  else if ( $IW['action'] == 'init'                )  {  $media->saveNewMedia       ( $IW, $IC, $IU  ); } /* Update der Metadaten des Buchs                                  */
  else if ( $IW['action'] == 'b_deactivate'        )  {  $media->deactivateMedia    ( $IW            ); } /* Medium Deaktivieren                                             */
  else if ( $IW['action'] == 'b_activate'          )  {  $media->activateMedia      ( $IW            ); } /* Medium Aktivieren                                               */
  else if ( $IW['action'] == 'b_delete_ebook'      )  {  $media->deleteMedia        ( $IW            ); } /* Medium wird aus SA gelöscht                                     */
  else if ( $IW['action'] == 'b_delete'            )  {  $media->deleteMedia        ( $IW            ); } /* Medium wird aus SA gelöscht                                     */
  else if ( $IW['action'] == 'b_new_email'         )  {  $media->showMailForm       ( $IW, $IU, $IC  ); } /* Erwebungsvorschlag (nach 0 Suchtreffern)                        */
}
}

if(( $_SESSION['user']['role'] == 3 OR $_SESSION['user']['role'] == 2 OR  $_SESSION['user']['role'] == 5))  ## Folgende Aktionen nur für Dozenten oder Staff oder Mailuser
{
 if ( $IW['item'] == 'email' )
{ 
   
   if      ( $IW['action'] == 'sendmail'            )  {  $media->send_email(  $IW, $IU, $IC );   }           /* Email wird verschickt                                         */
  if      ( $IW['action'] == 'HIBSAPmail'          )  {  $util->sendBIB_APmails();               }           /* Cronjob: HIBS Ansprechpartner Infomail                                         */
}

}
else     ## Folgende Aktionen für Studis erlaubt
{      if ( $IW['item'] == 'collection' )
{ 
       if ( $IW['action'] == 'print'               )  { $collection->showCollectionPrintversion( $IW, $IU      ) ;   } /* Printversion des SAs wird angezeigt (nur aktive Medien)    */
  else if ( $IW['action'] == 'show'                )  { $collection->showCollection            ( $IW, $IU      ) ;   } /*  SAs wird angezeigt (deren Editierbarkeit ist abhängig von der Rolle des Nuters)   */
  else if ( $IW['action'] == 'showopen'            )  { $collection->showCollectionLists       ( $IW, $IU      ) ;   } /* Zeigt die Liste der SAs, gefiltert nach deren Zustand      */
  else if ( $IW['action'] == 'b_coll_edit'         )  { $collection->editCollection            ( $IW, $IU,$IC  ) ;   } /*  SAs wird angezeigt (deren Editierbarkeit ist abhängig von der Rolle des Nuters)   */

  }  
}
?>
  