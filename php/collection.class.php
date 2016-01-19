<?php

require_once('../php/sql.class.php'         );
require_once('../php/renderer.class.php'    );

class Collection
{

var $renderer;   
var $sql;
  
function Collection()
{
   $this->sql        = new SQL();
   $this->renderer   = new Renderer();
}  
  
####################### --- COLLECTION --- #######################

function ereaseCollection( $IW, $IU)
{
  $this->sql->deleteCollection($IW, $IU);
  $url       = "index.php?collection=0";
  $this->renderer->doRedirect( $url );
}        
        
function saveNewCollection(  $IW, $IU)
{
    $this->sql->initCollection($IW, $IU);
    $url       = "index.php?collection=0";
    $this->renderer->doRedirect( $url );
}        

function editColMetaData(  $IW )
{
  $tpl_vars['user']               = $_SESSION['user'];
  $tpl_vars['coll']               = $_SESSION['coll'];
  $tpl_vars['work']               = $IW;
  $tpl_vars['colData']            = $this->sql->getCollectionInfos( $IW[ 'collection_id' ] );
  $tpl_vars['tpl']['departments'] = $this->sql->getAllDepartments();
  $tpl_vars['tpl']['bib_info']    = $this->sql->getBibInfos('name');
  $tpl_vars['tpl']['role_info']   = $this->sql->getRoleInfos('name');
  # deb($tpl_vars,1);
  $this->renderer->do_template ( 'b_edit_collection.tpl' , $tpl_vars ) ;
  exit(0);
}

function newCollection( $util,   $IW )
{
  $departments = $this->sql->getAllDepartments();
  $bib_info    = $this->sql->getBibInfos('name');
  $role_info   = $this->sql->getRoleInfos('name');

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
  
  $this->renderer->do_template ( 'b_edit_collection.tpl' , $tpl_vars ) ;
  exit(0);
}

function  showCollectionLists(  $IW , $IU)
{ 
  global  $CONST_actions_info;

  $docType  =  $this->sql->getAllDocTypes();
 
/* getAllMediaFromCollection - Liefert alle Medien Daten: 
$colID:       0 = ALLE, 
$state_id:    1 = neu bestellt, 2 wird bearbeitet, 3 aktiv, 4 wird entfernt, 5 inaktiv, 6 gelöscht, 9 Erwerbvorschlag 
$doc_type_id: 1 = Buch, 3, = CD, 4 = E-Book, 
*/ 
 
  $IW['mode'] = $IU['role_name'];
  /*
            [$]['item']  
            []['description''] 
            []['for_loan'] 
            []['doc_type_id'']  
            []['doc_type'] 
  */
  $tpl_vars[ 'work'            ] = $IW; 
  $tpl_vars[ 'user'            ] = $IU;                                          
  $tpl_vars[ 'collection_info' ] = $this->sql->getCollectionInfos( null, 1, $IW['todo'] , true ); # ($colID , $doc_type_id, $doc_state_id, $short )
  if( $IW['todo']  == 6 )
  {
  $ebooks                        = $this->sql->getCollectionInfos( null, 4, $IW['todo'] , true );                      /* In der Liste 'Gelöscht' werden auch E-Books angezeigt */
  $lh_books                      = $this->sql->getCollectionInfos( null, 2, $IW['todo'] , true );                      /* In der Liste 'Gelöscht' werden auch LH-Books angezeigt */
  $tpl_vars[ 'collection_info' ] = array_merge($tpl_vars[ 'collection_info' ], $ebooks, $lh_books   );
  }
  $tpl_vars[ 'media_state'     ] = $this->sql->getAllMedStates();
  $tpl_vars[ 'fachbib'         ] = $this->sql->getBibInfos();
  $tpl_vars[ 'location'        ] = $this->sql->getAllDepartments();
  $tpl_vars[ 'actions_info'    ] = $CONST_actions_info;
   
 #deb( $tpl_vars,1 );
  
  $this->renderer->do_template( 'collection.tpl', $tpl_vars );
 }
         

function editCollection(   $IW , $IU)
{ 
  global  $CONST_actions_info;

  $tpl_vars =  $this->sql->getAllDocTypes();

  $IC = $_SESSION['coll'];

  $tpl_vars[ 'collection'      ]         = $IC;
  $tpl_vars[ 'user'            ]         = $IU; 
  $tpl_vars[ 'work'            ]         = $IW; 
  $tpl_vars[ 'collection_info' ]         = $this->sql->getCollectionInfos( $IC['title_short'] );
  $tpl_vars[ 'media_state'     ]         = $this->sql->getAllMedStates();
  $tpl_vars[ 'fachbib'         ]         = $this->sql->getBibInfos();
  $tpl_vars[ 'location'        ]         = $this->sql->getAllDepartments();
  $tpl_vars[ 'errors_info'     ][]       = '';
  $tpl_vars[ 'actions_info'    ]         = $CONST_actions_info;
 
 # deb( $tpl_vars,1 );
  
  $this->renderer->do_template( 'collection.tpl', $tpl_vars );
 }

 
function showCollection( $IW , $IU)
{ 
  global  $CONST_actions_info;
  $tpl_vars[ 'work'            ] = $IW; 
  $tpl_vars[ 'user'            ] = $IU;                                          
  $tpl_vars[ 'collection_info' ] = $this->sql->getCollectionInfos( $IW['collection_id'] );
  $tpl_vars[ 'collection'      ] = $tpl_vars[ 'collection_info' ][$IW['collection_id']];

  # $tpl_vars[ 'collection_info' ][0][ 'document_info' ] = $this->sql->getDokumentList( $IW['collection_id'] );
  $tpl_vars[ 'doc_type'        ] = $this->sql->getAllDocTypes();
  $tpl_vars[ 'media_state'     ] = $this->sql->getAllMedStates();
  $tpl_vars[ 'fachbib'         ] = $this->sql->getBibInfos();
  $tpl_vars[ 'location'        ] = $this->sql->getAllDepartments();
  $tpl_vars[ 'errors_info'     ][] = '';
  $tpl_vars[ 'actions_info'    ] = $CONST_actions_info;
   #  deb($tpl_vars,1);
  $this->renderer->do_template( 'collection.tpl', $tpl_vars, ( $IW[ 'action' ] != 'print' ) );
 }
 
function setCollectionState_6($IW)
{
  $colInfo = $this->sql->getCollectionInfos ($IW['collection_id']);
  foreach ( $colInfo[$IW['collection_id']]['document_info'] as $di )
  {
    if ( $di['doc_type_id'] == 1  )   # Medium ist Buch
    {   
       if ($di['state_id'] ==  1   ) {  $this->sql->setMediaState( $IW['document_id'], 6 ); }  #  'Neu Bestellt'    -> 'Gelöscht'  
       if ($di['state_id'] ==  2   ) {  $this->sql->setMediaState( $IW['document_id'], 4 ); }  #  'Wird bearbeitet' -> 'Wird entfernt'  
       if ($di['state_id'] ==  3   ) {  $this->sql->setMediaState( $IW['document_id'], 4 ); }  #  'aktiv'           -> 'Wird entfernt'  
    }
    else if ( $di['doc_type_id'] == 4  )   # Medium ist Ebook
    {
       $this->sql->setMediaState(  $IW['document_id'], 6 );
    }
  }
  $this->sql->setCollectionState( $IW['collection_id'], 6 );
  $this->renderer->doRedirect( $IW['last_page'] );
}        
function setCollectionState_3(  $IW)
{
  $this->sql->setCollectionState( $IW['collection_id'], 3 );
  $this->renderer->doRedirect( $IW['last_page'] );
}        
function setCollectionState_5(  $IW)
{
  $this->sql->setCollectionState( $IW['collection_id'], 5 );
  $this->renderer->doRedirect( $IW['last_page'] );
}        


function  saveColMetaData(   $IW, $IU )
{
  #$this->sql->saveColMetaData  ($renderer, $sql, $IW);    
  $url = "index.php?item=collection&collection_id=".$IW['collection_id']."&ro=".$IU['role_encode']."&item=collection&action=b_coll_edit";
  $this->renderer->doRedirect( $url );
  
}

function updateColMetaData(  $IW, $IU)
{
  #deb($IU,1);
  $this->sql-> updateColMetaData($IW);
  $url = "index.php?item=collection&collection_id=".$IW['collection_id']."&ro=".$IU['role_encode']."&item=collection&action=b_coll_edit";
#  $url = "index.php?item=collection&collection_id=".$IW['collection_id']."&item=collection&action=b_coll_edit";  
  #  $url = "index.php?collection=0";
  $this->renderer->doRedirect( $url );
}

function redirToCollection( $IW )
{
    $this->renderer->doRedirect( $IW['last_page'] );
    exit(0);
}

function resortCollection( $IW, $IC )
{
    $this->sql-> updateCollectionSortOrder( $IC['id'], $IW['sortoder']  );
    exit(0);
}


}
