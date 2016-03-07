<?php

class SQL
{
var $DB;
var $conf; 
var $CFG; 
function SQL ( $CFG )
{
  $this->CFG  = $CFG;
  $this->conf = $CFG->getConf();
 
#  $CFG->C->deb( );
  
  $this->DB = new mysqli( $this->conf['db_host'], $this->conf['db_user'],  $this->conf['db_pass'],  $this->conf['db_name']);         
  if (mysqli_connect_errno()) 
  {   printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());   exit();
  }
}
function es( $str )
{
  return $this->DB->real_escape_string( $str);
}

function initMedia($book)
{
 $SQL = '
 INSERT INTO `document` 
 SET
 collection_id      = "' . $this->es($book['collection_id'   ]). '",
 title              = "' . $this->es($book['title'           ]). '", 
 author             = "' . $this->es($book['author'          ]). '", 
 edition            = "' . $this->es($book['edition'         ]). '", 
 year               = "' . $this->es($book['year'            ]). '", 
 journal            = "' . $this->es($book['journal'         ]). '", 
 volume             = "' . $this->es($book['volume'          ]). '",
 pages              = "' . $this->es($book['pages'           ]). '",
 publisher          = "' . $this->es($book['publisher'       ]). '",
 signature          = "' . $this->es($book['signature'       ]). '",
 ppn                = "' . $this->es($book['ppn'             ]). '", 
 doc_type_id        = "' . $this->es($book['doc_type_id'     ]). '", 
 url                = "' . $this->es($book['url'             ]). '", 
 physicaldesc       = "' . $this->es($book['physicaldesc'    ]). '", 
 state_id           = "' . $this->es($book['state_id'        ]). '", 
 relevance          = "' . $this->es($book['relevance'       ]). '",
 notes_to_staff     = "' . $this->es($book['notes_to_staff'  ]). '",
 shelf_remain       = "' . $this->es($book['shelf_remain'    ]). '",    
 notes_to_studies   = "' . $this->es($book['notes_to_studies']). '", 
 created            = NOW() ,
 last_modified      = NOW() ,
 last_state_change  = NOW()';  
 
 $res =  mysqli_query ( $this->DB, $SQL);
 $res = $this->getDocumentID ( $book );
 return $res;
}


function getDokumentList( $colID, $doc_type_id = null , $state_id = null  )
{
  $ret = NULL;
  
  $SQL = " 
  SELECT * 
  FROM `document` 
  WHERE `collection_id` = \"" . $colID  . "\"";  
  
  if ( isset($doc_type_id) ) { $SQL .= " AND `doc_type_id` = ". $this->es( $doc_type_id ); }
  if ( isset($state_id   ) ) { $SQL .= " AND `state_id`    = ". $this->es( $state_id    ); }
   
  $res =  mysqli_query ( $this->DB, $SQL);

  if ($res)
  while ($row = mysqli_fetch_assoc($res)) 
  { $ret[ $row['id'] ] = $row;
    $ret[ $row['id'] ]['document_id'] = $row['id']; 
  }     
 # $this->CFG->C->deb($ret,1);
  return $ret; 
}


function getAdminEmailInfos (   )
{
  foreach ($_SESSION['FACHBIB'] as $HIBS_loc)
  { $ret[$HIBS_loc['BibID']] = $HIBS_loc;
    $SQL2 = "
    SELECT COUNT(*)
    FROM document 
    INNER JOIN collection ON document.collection_id = collection.id 
    WHERE document.state_id = '1' AND collection.bib_id = '". $this->es($HIBS_loc['BibID'] )."'";    /* Status 1 = Neu Angefordert */
  
    $res =  mysqli_query ( $this->DB, $SQL2);
    $tmp  = mysqli_fetch_assoc($res);
    $ret[$HIBS_loc['BibID']][1] = $tmp['COUNT(*)']; 
  
    $SQL2 = "                                                    
    SELECT COUNT(*)
    FROM document 
    INNER JOIN collection ON document.collection_id = collection.id 
    WHERE document.state_id = '9' AND collection.bib_id = '". $this->es($HIBS_loc['BibID'])."'";   /* Status 9 = Kaufvorschlag  */
  
    $res =  mysqli_query ( $this->DB, $SQL2);
    $tmp  = mysqli_fetch_assoc($res);
    $ret[$HIBS_loc['BibID']][9] = $tmp['COUNT(*)'];  
  }
  return $ret;   
}


function getUserHSK ( $hawAccount )
{
  
  $SQL = "
  SELECT 
  user.id            as id, 
  user.role_id       as role,
  user.forename      as vorname, 
  user.surname       as nachname, 
  user.sex           as sex, 
  user.email         as mail, 
  user.bib_id        as user_bib_id, 
  user.department    as department_id,
  user.hawaccount    as hawaccount
  
  FROM user,state,role
  WHERE state.name='active' 
  AND user.state_id = state.id 
  AND user.hawaccount = \"". $this->es($hawAccount)."\" 
  AND user.role_id = role.id LIMIT 1";

  $res =  mysqli_query ( $this->DB, $SQL);
  $ret[] = mysqli_fetch_assoc($res);

   #$this->CFG->C->deb($ret,1);
  
  return $ret;  
  
  
}


function checkUserExistence( $hawacc )
{
  $SQL = "
  SELECT * 
  FROM user 
  WHERE `hawaccount` = \"". $this->es( $hawacc )."\"" ;
  $res =  mysqli_query ( $this->DB, $SQL);
  $ret = mysqli_fetch_assoc($res);
  return $ret;  
}


function getCollectionData( $title_short )
{
  if ( $title_short != '' )
  {$SQL = "
  SELECT * 
  FROM collection 
  WHERE `title_short` = \"".$this->es( $title_short )."\"" ;
  $res =  mysqli_query ( $this->DB, $SQL);
  $ret = mysqli_fetch_assoc($res);
  $ret['collection_id'] = $ret['id'] ;
  }
  else
  {
    $ret = 0;
  }  
  
  #$this->CFG->C->deb($ret,1);
  return $ret;  
}

function getAllMedStates()
{
  $SQL = "
  SELECT * 
  FROM `state` 
  ORDER BY `id` ASC";
  
  $res =  mysqli_query ( $this->DB, $SQL);
  while ($row = mysqli_fetch_assoc($res)) 
  {
    $row['description'] = str_replace('##' , '<br />',  $row['description']);                   ## Parst nach '##' und ersetzt durch '<br>'
    $ret[$row['id']] = $row;
  }
  return $ret;  
}

function getRoleInfos( $style = NULL )
{
  $SQL = "
  SELECT * 
  FROM `role` 
  ORDER BY `id` ASC";
  
  $res =  mysqli_query ( $this->DB, $SQL);
  while ($row = mysqli_fetch_assoc($res)) 
  { if ( $style == 'name')
    { $ret[$row['id']] = $row['description'];
    }       
    else
    { $ret[$row['id']] = $row;
    }
  }
  return $ret;  
}


function deleteCollection($IW, $IU)
{
  if( $IU['role_name'] == 'staff' || $IU['role_name'] == 'admin'  )
  { $SQL = "
    DELETE 
    FROM  `collection` 
    WHERE `collection`.`id` = ". $this->es( $IW['collection_id'] );     

    $res =  mysqli_query ( $this->DB, $SQL);
    return  mysqli_fetch_assoc( $res );
  }
}



function deleteMedia($IW, $IU)
{
  if( $IU['role_name'] == 'staff' || $IU['role_name'] == 'admin'  )
  { $SQL = "
    DELETE
    FROM `document` 
    WHERE `document`.`id` = ". $this->es($IW['document'] );     
    $res =  mysqli_query ( $this->DB, $SQL);
    return  mysqli_fetch_assoc( $res );
  }
}


/* Gibt alle Medien Daten zurück: 
$colID:       0 = ALLE, 
$state_id:    1 = neu bestellt, 2 wird bearbeitet, 3 aktiv, 4 wird entfernt, 5 inaktiv, 6 gelöscht, 9 Erwerbvorschlag 
$doc_type_id: 1 = Buch, 2 = E-Book
*/ 

function getMediaMetaData($mediaID) /* Gibt alle Medien Daten zurück */
{
  $SQL = "
  SELECT * 
  FROM  `document` 
  WHERE `id` = ". $this->es( $mediaID )." 
  ORDER BY `doc_type_id`
  ASC  
  "; 

  $res =  mysqli_query ( $this->DB, $SQL);
  return  mysqli_fetch_assoc( $res );
}


function setCollectionState( $colID, $state )
{
  $SQL = "
  UPDATE collection 
  SET `state_id` = '". $this->es( $state )."' WHERE `collection`.`id` = ". $this->es( $colID );
  
  $res =  mysqli_query ( $this->DB, $SQL);
  return $res;
}

function setMediaState( $mediaID, $state )
{
  $SQL = "
  UPDATE document 
  SET `state_id` = '". $this->es( $state ) ."' WHERE `document`.`id` = ". $this->es( $mediaID );
 
  $res =  mysqli_query ( $this->DB, $SQL);
  return $res;
}


function initUser($IU)
{
  $SQL = "
  INSERT INTO user SET
    id                = \"" . $this->es( $IU['id'           ] ). "\"  ,
    role_id           = \"" . $this->es( $IU['role'         ] ). "\"  ,
    surname           = \"" . $this->es( $IU['nachname'     ] ). "\"  ,
    forename          = \"" . $this->es( $IU['vorname'      ] ). "\"  ,
    sex               = \"" . $this->es( $IU['sex'          ] ). "\"  ,
    email             = \"" . $this->es( $IU['mail'         ] ). "\"  ,
    state_id          = 3                                             ,
    created           = NOW()                                         , 
    last_modified     = NOW()                                         , 
    last_state_change = NOW()                                         , 
    bib_id            = \"" . $this->es( $IU['bib']['BibID' ] ) . "\" ,
    department        = \"" . $this->es( $IU['department'   ] ) . "\" ,
    hawaccount        = \"" . $this->es( $IU['hawaccount'   ] ) . "\"";
 
  $res =  mysqli_query ( $this->DB, $SQL );
  return $res;
}


function updateUser( $IU,  $IW = null )
{
 #$this->CFG->C->deb($IU);
  /* if ($IW['set_standard_BIB'])
  {  
     $IW[ 'bib_id' ] =  $_SESSION['DEP2BIB'][ $IW[ 'department' ] ] ['Dep2BIB'] ;  # Standard-Bib des entprechenden Deparmtents
  }
*/ 
                                         $SQL = "UPDATE `user` SET";
if( isset( $IU[ 'role'          ] ) )  { $SQL .= " role_id               = \"" .$this->es($IU[ 'role'          ]). "\" ,";  }	
if( isset( $IU[ 'nachname'      ] ) )  { $SQL .= " surname               = \"" .$this->es($IU[ 'nachname'      ]). "\" ,";  }	
if( isset( $IU[ 'vorname'       ] ) )  { $SQL .= " forename              = \"" .$this->es($IU[ 'vorname'       ]). "\" ,";  }	
if( isset( $IU[ 'sex'           ] ) )  { $SQL .= " sex                   = \"" .$this->es($IU[ 'sex'           ]). "\" ,";  }	
if( isset( $IU[ 'mail'          ] ) )  { $SQL .= " email                 = \"" .$this->es($IU[ 'mail'          ]). "\" ,";  }	
if( isset( $IW[ 'bib_id'        ] ) )  { $SQL .= " bib_id                = \"" .$this->es($IW[ 'bib_id'        ]). "\" ,";  }	
if( isset( $IW[ 'department_id' ] ) )  { $SQL .= " department            = \"" .$this->es($IW[ 'department_id' ]). "\" ,";  }	
                                         $SQL .= " last_modified         =      NOW()                                   "; 
                                         $SQL .= " WHERE `hawaccount`    = \"" .$this->es($IU[ 'hawaccount'    ]) .  "\"";
 
 #$this->CFG->C->deb($SQL,1);
                                        
  $res =  mysqli_query ( $this->DB, $SQL );
  
  return $res;
}


function initCollection( $Course, $IDMuser )
{
  $SQL = "
  INSERT INTO collection SET
  state_id         =      3                                                      ,
  created          =      NOW()                                                  , 
  last_modified    =      NOW()                                                  , 
  last_state_change=      NOW()                                                  , 
  notes_to_studies =   ''                                                        , 
  expiry_date      =  \"" . $this->es( $Course[ 'expiry_date'     ]      ) . "\" ,
  id               =  \"" . $this->es( $Course[ 'shortname'       ]      ) . "\" ,
  title            =  \"" . $this->es( $Course[ 'fullname'        ]      ) . "\" ,
  title_short      =  \"" . $this->es( $Course[ 'shortname'       ]      ) . "\" ,
  course_id        =  \"" . $this->es( $Course[ 'id'              ]      ) . "\" , 
  bib_id           =  \"" . $this->es( $IDMuser[ 'bib' ][ 'BibID' ]      ) . "\" ,  
  user_id          =  \"" . $this->es( $_SESSION[ 'user' ]['hawaccount'] ) . "\"";
 
  $res =  mysqli_query ( $this->DB, $SQL );
  return $res;
}


function updateColMetaData( $Course, $IDMuser )
{
  $SQL = "
  UPDATE `collection` 
  SET";  
  if( isset( $Course[ 'bib_id'            ] ) )  { $SQL .= " bib_id               = \"" .$this->es($Course[ 'bib_id'            ]). "\" ,";  }
  if( isset( $Course[ 'fullname'          ] ) )  { $SQL .= " title                = \"" .$this->es($Course[ 'fullname'          ]). "\" ,";  }
  if( isset( $Course[ 'notes_to_studies'  ] ) )  { $SQL .= " notes_to_studies     = \"" .$this->es($Course[ 'notes_to_studies'  ]). "\" ,";  }
                                                   $SQL .= " last_modified        = NOW()                                                "; 
  if( isset( $Course[ 'shortname'         ] ) )  { $SQL .= " WHERE title_short    = \"" .$this->es($Course[ 'shortname'         ]). "\"  ";  }
  else                                           { $SQL .= " WHERE id             = \"" .$this->es($Course[ 'collection_id'     ]). "\"  ";  }
 
  $res =  mysqli_query ( $this->DB, $SQL);
  return $res;
}


function updateMediaMetaData($w)
{  
  if( !isset( $w[ 'shelf_remain' ] ) ) { $w[ 'shelf_remain' ] = 0; }
  
                                        $SQL  = " UPDATE document SET ";
  if (isset( $w['title'           ])) { $SQL .= " title            = \"" .$this->es($w['title'             ]). "\" ,";}
  if (isset( $w['signature'       ])) { $SQL .= " signature        = \"" .$this->es($w['signature'         ]). "\" ,";}
  if (isset( $w['notes_to_studies'])) { $SQL .= " notes_to_studies = \"" .$this->es($w['notes_to_studies'  ]). "\" ,";}
  if (isset( $w['notes_to_staff'  ])) { $SQL .= " notes_to_staff   = \"" .$this->es($w['notes_to_staff'    ]). "\" ,";}
  if (isset( $w['shelf_remain'    ])) { $SQL .= " shelf_remain     = \"" .$this->es($w['shelf_remain'      ]). "\"  ";}
                                        $SQL .= " WHERE id         = \"" .$this->es($w['document_id'       ]). "\"  "; 
  $res =  mysqli_query ( $this->DB, $SQL);
  return $res;
}

function updateCollectionSortOrder( $collection_id, $sortorder )
{  
  $SQL = " UPDATE collection SET ";
  $SQL .= " sortorder         = \"" . $this->es( $sortorder ) . "\"";
  $SQL .= " WHERE id         = \"" . $this->es( $collection_id ). "\"  "; 

  #$this->CFG->C->deb( $SQL,1 );
  
  $res =  mysqli_query ( $this->DB, $SQL );
  return $res;
}

function getCollectionInfos ( $colID = null, $doc_type_id = null , $doc_state_id = null, $short = null  )
{
  $SQL = "SELECT * , user.bib_id as user_bib_id , collection.bib_id as coll_bib_id,   collection.id  as collID , user.id as uID FROM `collection` , `user`";
  $SQL .= " WHERE  user.hawaccount = collection.user_id  ";

  if ( $colID )
  {
    $SQL .= " AND collection.id = \"" . $this->es( $colID ) ."\"  ";  
  } 
  $SQL .= " ORDER BY collection.id ";
  /* ALLE Medieninfo zu dem entsprechenden SA werden ermittelt */
  $ret = false;
  
 # $this->CFG->C->deb( $SQL,1 );  
   
  $res =  mysqli_query ( $this->DB, $SQL );
    
  if ( $res )
  {  
   while ( $row = mysqli_fetch_assoc( $res ) ) 
   { 
     $sortorder = explode ( ',' , $row['sortorder'] );

     $userInfo = $this-> getUserHSK(  $row['user_id'] );
    
      $ret[ $row[ 'collID' ] ] = $row;
      $ret[ $row[ 'collID' ]][ 'user_info' ] = $userInfo[0] ; 
      
      $dl = $this->getDokumentList( $row[ 'collID' ], $doc_type_id,  $doc_state_id );  /*  ( $doc_ID, $doc_type_id = null , $doc_state_id = null  ) */
      
      if( $dl )
      {  unset($withoutSortOrder);
         unset($withSortOrder);
      
        foreach($dl as $d)
        {  
          $withoutSortOrder[ $d[ 'id' ] ] = array_merge( $d, $this->CFG->C->getDocType( $d ) ); ## --- Attribute hinzufügen 'doc_type', 'item', 'doc_type_id', 'state_id'   
        } 

        if(  $sortorder[ 0 ] != '' AND $doc_state_id == '' )
        {	
          foreach( $sortorder as $so )
          {
             $withSortOrder[] =  $withoutSortOrder[ $so ];
             unset( $withoutSortOrder[ $so ] );
          }
         $withSortOrder = array_merge( $withSortOrder, $withoutSortOrder);
        }
		    else
		    {
           $withSortOrder =  $withoutSortOrder;
	     	}
        
        $ret[ $row[ 'collID' ] ][ 'document_info' ] = $withSortOrder ;
      }
      elseif( $short )  /* Wenn SA keine Medien beinhaltet, wird er wieder entfernt */
      {
        unset ($ret[$row['collID']]);
      }
      elseif($doc_state_id != null )
      {
        unset ($ret[$row['collID']]);
      #  $ret[$row['id']][ 'document_info' ] = null ;
      }
    }
  }
  

  return $ret ;
}


function getDocumentInfos ( $docID ) /* Kartesisches Produkt aller Dokumenten mit allen dazugehörigen Infos */
{ 
  $SQL = "SELECT * FROM `document` WHERE `id`  = ". $this->es( $docID );
  $res =  mysqli_query ( $this->DB, $SQL);
  $ans =  mysqli_fetch_assoc( $res );
  return $ans ;
}

function getDocumentID ( $book ) /* Kartesisches Produkt aller Dokumenten mit allen dazugehörigen Infos */
{ 
  $SQL = " SELECT id FROM `document` WHERE";
  if( $book['collection_id'   ] ) $SQL .="     `collection_id`      = \"" . $this->es( $book['collection_id'   ] ) ."\""; 
  if( $book['title'           ] ) $SQL .=" AND `title`              = \"" . $this->es( $book['title'           ] ) ."\""; 
  if( $book['author'          ] ) $SQL .=" AND `author`             = \"" . $this->es( $book['author'          ] ) ."\""; 
  if( $book['edition'         ] ) $SQL .=" AND `edition`            = \"" . $this->es( $book['edition'         ] ) ."\""; 
  if( $book['year'            ] ) $SQL .=" AND `year`               = \"" . $this->es( $book['year'            ] ) ."\""; 
  if( $book['journal'         ] ) $SQL .=" AND `journal`            = \"" . $this->es( $book['journal'         ] ) ."\""; 
  if( $book['volume'          ] ) $SQL .=" AND `volume`             = \"" . $this->es( $book['volume'          ] ) ."\""; 
  if( $book['pages'           ] ) $SQL .=" AND `pages`              = \"" . $this->es( $book['pages'           ] ) ."\""; 
  if( $book['publisher'       ] ) $SQL .=" AND `publisher`          = \"" . $this->es( $book['publisher'       ] ) ."\""; 
  if( $book['signature'       ] ) $SQL .=" AND `signature`          = \"" . $this->es( $book['signature'       ] ) ."\""; 
  if( $book['ppn'             ] ) $SQL .=" AND `ppn`                = \"" . $this->es( $book['ppn'             ] ) ."\"";  
  if( $book['doc_type_id'     ] ) $SQL .=" AND `doc_type_id`        = \"" . $this->es( $book['doc_type_id'     ] ) ."\""; 
  if( $book['url'             ] ) $SQL .=" AND `url`                = \"" . $this->es( $book['url'             ] ) ."\""; 
  if( $book['physicaldesc'    ] ) $SQL .=" AND `physicaldesc`       = \"" . $this->es( $book['physicaldesc'    ] ) ."\""; 
  if( $book['state_id'        ] ) $SQL .=" AND `state_id`           = \"" . $this->es( $book['state_id'        ] ) ."\""; 
  if( $book['relevance'       ] ) $SQL .=" AND `relevance`          = \"" . $this->es( $book['relevance'       ] ) ."\"";  
  if( $book['notes_to_staff'  ] ) $SQL .=" AND `notes_to_staff`     = \"" . $this->es( $book['notes_to_staff'  ] ) ."\"";  
  if( $book['shelf_remain'    ] ) $SQL .=" AND `shelf_remain`       = \"" . $this->es( $book['shelf_remain'    ] ) ."\""; 
  if( $book['notes_to_studies'] ) $SQL .=" AND `notes_to_studies`   = \"" . $this->es( $book['notes_to_studies'] ) ."\""; 
  $SQL .= " ORDER BY `id` DESC";
  
  $res =  mysqli_query ( $this->DB, $SQL);
   #$this->CFG->C->deb( $SQL,1 );
  while ($ret = $res->fetch_array(MYSQLI_ASSOC))
  {  
    $ans = $ret['id'];
  }

  return $ans ;
}


function getAllDocTypes()  
{
  $SQL = "SELECT * FROM `doc_type` ORDER BY doc_type_id asc" ;
  
  $res =  mysqli_query ( $this->DB, $SQL );
  while ( $row = mysqli_fetch_assoc( $res ) ) 
  {
    $ret[ $row[ 'id' ] ] = $row;
  }
 return $ret;  
}

function getRoleName( $roleNr )  
{
  $SQL = "SELECT name FROM `role` WHERE id = ". $this->es( $roleNr) ;
  
  $res =  mysqli_query ( $this->DB, $SQL );
  while ( $row = mysqli_fetch_assoc( $res ) ) 
  {
    $ret = $row[ 'name' ];
  }

 return $ret;  
}


function getUser( $mode )
{
   $param = array 
   (
     "tables" => "user,role,state" ,
     "cond"   => "user.state_id = state.id  
     AND user.role_id = role.id
     AND (role.name != 'edit' OR role.name != 'staff' )", 

     "columns" => "user.*" ,
     "order"   => "surname,forename,sex"
    ) ;

    if ( $mode == "edit" and isset ( $_SESSION[ 'user' ] ) )
    {
      $param[ 'cond' ] .= " AND user.id=" . $this->es( $_SESSION[ 'user' ][ 'id' ] );
    }
    if ( $mode == "view" )
    {
      $param[ 'cond' ] .= " AND state.name = 'active'" ;
    }
    else
    {
      $param[ 'cond' ] .= " AND state.name != 'new'" ;
    }
   
   $SQL = "SELECT " . $param['columns']. " FROM " .$param['tables']. "  WHERE  ".$param['cond'] ." ORDER BY user.surname";

  
  $res =  mysqli_query ( $this->DB, $SQL);
#  $this->CFG->C->deb( $SQL );
  if (isset ($res))
  while ($row = mysqli_fetch_assoc($res)) 
  {
    $ret[] = $row;
  }
 
  return $ret; 
}

function getSAlist( $user, $mode, $categories )
{
 if(     $categories == 20) { $cat = "u.department = 21 OR u.department = 22 OR u.department = 23 OR u.department = 24"; }  
 else if($categories == 30) { $cat = "u.department = 31 OR u.department = 32 OR u.department = 33 OR u.department = 34 OR u.department = 35 OR u.department = 36 OR u.department = 37 OR u.department = 430"; }  
 else if($categories == 50) { $cat = "u.department = 51 OR u.department = 52 OR u.department = 53 OR u.department = 54 OR u.department = 55"; }  
 else if($categories == 60) { $cat = "u.department = 61 OR u.department = 62 OR u.department = 63 OR u.department = 64 OR u.department = 65"; }  
 else if($categories == 0 ) { $cat = "u.department !=21 AND u.department != 22 AND u.department != 23 AND u.department != 24 AND u.department != 31 AND u.department != 32 AND u.department != 33 AND u.department != 34 AND u.department != 35 AND u.department != 36 AND u.department != 37  AND u.department != 51 AND u.department != 52 AND u.department != 53 AND u.department != 54 AND u.department != 55 AND u.department != 61 AND u.department != 62 AND u.department != 63 AND u.department != 64 AND u.department != 65 "; } 
 else if($categories == 1 ) { $cat = "u.department != 0"; } 
 
 else                       { $cat = "u.department = ". $categories;   }  
 $SQL  =  " SELECT c.*, u.department,  u.surname, u.forename, s.name AS state_name, s.description AS state_description"; 
 $SQL .=  " FROM collection c "; 
 $SQL .=  " LEFT JOIN  user u"; 
 $SQL .=  " ON u.hawaccount = c.user_id "; 
 $SQL .=  " LEFT JOIN  state s"; 
 $SQL .=  "  ON s.id = c.state_id"; 
 $SQL .=  " WHERE user_id = \"" . $this->es($user[ 'hawaccount' ] )."\""; 
 if ( $mode       == "view" )  {  $SQL .= " AND c.state_id = 3";                    }                  /* Zustand 3 = aktiv */  
 $SQL .= " AND (  ". $cat .")" ;   //Filter for category/department
 $SQL .=  " ORDER BY `id` "; 
 $SQL .=  " DESC "; 
 
 #$this->CFG->C->deb( $SQL );
 
 $res =  mysqli_query ( $this->DB, $SQL);
 $ret = NULL;
 if ($res)
 while ($row = mysqli_fetch_assoc($res)) 
 {
    $ret[] = $row;
  }
  return $ret; 
 }

 }
 
class HAW_DB
{
	var $db;
  var $log;
  var $status;
	function HAW_DB( )
	{
		{	$this->db = new SQLite3( '../DB/HAW-Fak-Dep-SG.s3db' );		
			if( $this->db )
			{  $this->log = fopen("../log/HAW-FAK-DEP.log", "a");
			}
			else
			{	die( "<b>KEINE Verbindung zur HAW FAK-DB Datenbank möglich</b>" );
			}
		}
  }
  
  function getDEP2BIB()
	{ 
		$r = NULL;
		$SQL =  "SELECT * FROM Department, Fakultaet , HIBS WHERE Department.Dep2Fak = Fakultaet.FakID AND Department.Dep2BIB = HIBS.BibID;";

		$result =  $this->db->query( $SQL );
		 
		while ( $tmp = $result->fetchArray() )										// Daten zeilenweise in Array speichern  
		{	 
			$r[$tmp['DepID']][ 'DepID'     ] = $tmp[ 'DepID'     ];
   	  $r[$tmp['DepID']][ 'DepName'   ] = $tmp[ 'DepName'   ];
  		$r[$tmp['DepID']][ 'Dep2Fak'   ] = $tmp[ 'Dep2Fak'   ];
  		$r[$tmp['DepID']][ 'Dep2BIB'   ] = $tmp[ 'Dep2BIB'   ];
    	$r[$tmp['DepID']][ 'FakID'     ] = $tmp[ 'FakID'     ];
	 		$r[$tmp['DepID']][ 'FakName'   ] = $tmp[ 'FakName'   ];
	 		$r[$tmp['DepID']][ 'FakAbk'    ] = $tmp[ 'FakAbk'    ];
  #	  $r[$tmp['DepID']][ 'FakFarbe'  ] = $tmp[ 'FakFarbe'  ];
			$r[$tmp['DepID']][ 'BibID'     ] = $tmp[ 'BibID'     ];
			$r[$tmp['DepID']][ 'BibName'   ] = $tmp[ 'BibName'   ];
			$r[$tmp['DepID']][ 'BibAPName' ] = $tmp[ 'BibAPName' ];
			$r[$tmp['DepID']][ 'BibAPMail' ] = $tmp[ 'BibAPMail' ];
		}
		return $r;
  }
  
  function getAllFak()
  {
		$r = NULL;
		$SQL =  "   SELECT * FROM  Fakultaet;";

		$result =  $this->db->query( $SQL );
		 
		while ( $tmp = $result->fetchArray() )										// Daten zeilenweise in Array speichern  
		{	 
			$r[$tmp['FakID']][ 'FakID'    ] = $tmp[ 'FakID'    ];
			$r[$tmp['FakID']][ 'FakName'  ] = $tmp[ 'FakName'  ];
			$r[$tmp['FakID']][ 'FakAbk'   ] = $tmp[ 'FakAbk'   ];
			$r[$tmp['FakID']][ 'FakFarbe' ] = $tmp[ 'FakFarbe' ];	
		}
		return $r;
  }
  
  function getAllFachBib()
  {
		$r = NULL;
		$SQL =  "   SELECT * FROM  HIBS;";

		$result =  $this->db->query( $SQL );
		 
		while ( $tmp = $result->fetchArray() )										// Daten zeilenweise in Array speichern  
		{	 
      $r[$tmp['BibID']][ 'BibID'     ] = $tmp[ 'BibID'     ];
			$r[$tmp['BibID']][ 'BibName'   ] = $tmp[ 'BibName'   ];
			$r[$tmp['BibID']][ 'BibAPName' ] = $tmp[ 'BibAPName' ];
			$r[$tmp['BibID']][ 'BibAPMail' ] = $tmp[ 'BibAPMail' ];
		}
		return $r;
  }
	}
?>
