<?php
require_once('../php/config.php');

class SQL
{
var $DB;
var $conf; 

function SQL ()
{
  $this->conf = getConf();
 
  $this->DB = new mysqli( $this->conf['db_host'], $this->conf['db_user'],  $this->conf['db_pass'],  $this->conf['db_name']);         
  if (mysqli_connect_errno()) 
  {   printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());   exit();
  }
}

function initMedia($book)
{
 $SQL = '
 INSERT INTO `document` 
 SET
 collection_id      = "' .$book['collection_id'   ]. '",
 title              = "' .$book['title'           ]. '", 
 author             = "' .$book['author'          ]. '", 
 edition            = "' .$book['edition'         ]. '", 
 year               = "' .$book['year'            ]. '", 
 journal            = "' .$book['journal'         ]. '", 
 volume             = "' .$book['volume'          ]. '",
 pages              = "' .$book['pages'           ]. '",
 publisher          = "' .$book['publisher'       ]. '",
 signature          = "' .$book['signature'       ]. '",
 ppn                = "' .$book['ppn'             ]. '", 
 doc_type_id        = "' .$book['doc_type_id'     ]. '", 
 url                = "' .$book['url'             ]. '", 
 physicaldesc       = "' .$book['physicaldesc'    ]. '", 
 state_id           = "' .$book['state_id'        ]. '", 
 relevance          = "' .$book['relevance'       ]. '",
 notes_to_staff     = "' .$book['notes_to_staff'  ]. '",
 shelf_remain       = "' .$book['shelf_remain'    ]. '",    
 notes_to_studies   = "' .$book['notes_to_studies']. '", 
 created            = NOW() ,
 last_modified      = NOW() ,
 last_state_change  = NOW()';     

 $res =  mysqli_query ( $this->DB, $SQL);
 $res = $this->getDocumentID ( $book );
 return $res;
}


function getDokumentList( $colID,$doc_type_id = null , $state_id = null  )
{
  $ret = NULL;
  
  $SQL = " 
  SELECT * 
  FROM `document` 
  WHERE `collection_id` = \"" . $colID  . "\"";  
  
  if ( isset($doc_type_id) ) { $SQL .= " AND `doc_type_id` = ". $doc_type_id;}
  if ( isset($state_id   ) ) { $SQL .= " AND `state_id`    = ". $state_id;   }
  
  $res =  mysqli_query ( $this->DB, $SQL);

  if ($res)
  while ($row = mysqli_fetch_assoc($res)) 
  { $ret[] = $row;
  }     
 
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
    WHERE document.state_id = '1' AND collection.location_id = '".$HIBS_loc['BibID']."'";    /* Status 1 = Neu Angefordert */
  
    $res =  mysqli_query ( $this->DB, $SQL2);
    $tmp  = mysqli_fetch_assoc($res);
    $ret[$HIBS_loc['BibID']][1] = $tmp['COUNT(*)']; 
  
    $SQL2 = "                                                    
    SELECT COUNT(*)
    FROM document 
    INNER JOIN collection ON document.collection_id = collection.id 
    WHERE document.state_id = '9' AND collection.location_id = '".$HIBS_loc['BibID']."'";   /* Status 9 = Kaufvorschlag  */
  
    $res =  mysqli_query ( $this->DB, $SQL2);
    $tmp  = mysqli_fetch_assoc($res);
    $ret[$HIBS_loc['BibID']][9] = $tmp['COUNT(*)'];  
  }
  return $ret;  
}


function getUserHSK ( $hawAccount )
{
  $SQL = "
  SELECT user.*,
  role.name AS role_name
  FROM user,state,role
  WHERE state.name='active' 
  AND user.state_id = state.id 
  AND user.hawaccount = \"".$hawAccount."\" 
  AND user.role_id = role.id LIMIT 1";

  $res =  mysqli_query ( $this->DB, $SQL);
  $ret[] = mysqli_fetch_assoc($res);
  return $ret;  
}


function checkUserExistence( $hawacc )
{
  $SQL = "
  SELECT * 
  FROM user 
  WHERE `hawaccount` = \"". $hawacc ."\"" ;
  $res =  mysqli_query ( $this->DB, $SQL);
  $ret = mysqli_fetch_assoc($res);
  return $ret;  
}


function doCollectionExist( $title_short )
{
  $SQL = "
  SELECT * 
  FROM collection 
  WHERE `title_short` = \"". $title_short ."\"" ;
  $res =  mysqli_query ( $this->DB, $SQL);
  $ret = mysqli_fetch_assoc($res);
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
  { $ret[$row['id']] = $row;
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
    WHERE `collection`.`id` = ".$IW['collection_id'];     

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
    WHERE `document`.`id` = ".$IW['document'];     
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
  WHERE `id` = ".$mediaID."
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
  SET `state_id` = '". $state ."' WHERE `collection`.`id` = ". $colID;
  
  $res =  mysqli_query ( $this->DB, $SQL);
  return $res;
}

function setMediaState( $mediaID, $state )
{
  $SQL = "
  UPDATE document 
  SET `state_id` = '". $state ."' WHERE `document`.`id` = ". $mediaID;
 
  $res =  mysqli_query ( $this->DB, $SQL);
  return $res;
}


function initUser($IU)
{
  $SQL = "
  INSERT INTO user SET
    role_id           = \"" . $IU['role'       ] . "\" ,
    surname           = \"" . $IU['nachname'   ] . "\" ,
    forename          = \"" . $IU['vorname'    ] . "\" ,
    sex               = \"" . $IU['sex'        ] . "\" ,
    email             = \"" . $IU['mail'       ] . "\" ,
    state_id          = 3                             ,
    created           = NOW()                         , 
    last_modified     = NOW()                         , 
    last_state_change = NOW()                         , 
    categories_id     = \"" . $IU['department']  . "\" ,
    department        = \"" . $IU['department']  . "\" ,
    `hawaccount`      = \"" . $IU['akennung'  ]  . "\"";
 
  $res =  mysqli_query ( $this->DB, $SQL);
  return $res;
}


function updateUser($IU)
{
  $SQL = "
  UPDATE `user`
  SET
    role_id           = \"" . $IU['role'       ] . "\" ,
    surname           = \"" . $IU['nachname'   ] . "\" ,
    forename          = \"" . $IU['vorname'    ] . "\" ,
    sex               = \"" . $IU['sex'        ] . "\" ,
    email             = \"" . $IU['mail'       ] . "\" ,
    categories_id     = \"" . $IU['department']  . "\" ,
    department        = \"" . $IU['department']  . "\" 
  WHERE 
    `hawaccount`     = \"" . $IU['akennung'  ]  . "\"";
	$res =  mysqli_query ( $this->DB, $SQL);
  return $res;
  
}


function initCollection($IW )
{
  $SQL = "
  INSERT INTO collection SET
  created          =      NOW()                     , 
  last_modified    =      NOW()                     , 
  last_state_change=      NOW()                     , 
  state_id         =      3                         ,
  id               = \"" .$IW['title_short'     ]. "\" ,
  user_id          = \"" .$_SESSION[ 'user' ]['hawaccount']. "\" ,
  title            = \"" .$IW['title'           ]. "\" ,
  title_short      = \"" .$IW['title_short'     ]. "\" ,
  location_id      = \"" .$IW['location_id'     ]. "\" ,
  expiry_date      = \"" .$IW['expiry_date'     ]. "\" ,
  notes_to_studies = \"" .$IW['notes_to_studies']. "\" ,
  categories_id    = \"" .$IW['categories_id'   ]. "\"" ; 

  $res =  mysqli_query ( $this->DB, $SQL);
  return $res;
}


function updateColMetaData($w)
{
  
  $SQL = "
  UPDATE `collection` 
  SET  location_id      = \"" .$w['location_id'     ]. "\" ,";

  if( isset( $w[ 'title'            ] ) )  { $SQL .= " title                = \"" .$w['title'           ]. "\" ,";  }
  if( isset( $w[ 'notes_to_studies' ] ) )  { $SQL .= " notes_to_studies     = \"" .$w['notes_to_studies']. "\" ,";  }
  if( isset( $w[ 'categories_id'    ] ) )  { $SQL .= " categories_id        = \"" .$w['categories_id'   ]. "\"  ";  }
  if( isset( $w[ 'title_short'      ] ) )  { $SQL .= " WHERE title_short    = \"" .$w['title_short'     ]. "\"";    }
  else                                     { $SQL .= " WHERE id             = \"" .$w['id'              ]. "\"";    }

  $res =  mysqli_query ( $this->DB, $SQL);
  return $res;
}



function updateMediaMetaData($w)
{  
  if( !isset( $w[ 'shelf_remain' ] ) ) { $w[ 'shelf_remain' ] = 0; }
  
                                        $SQL = " UPDATE document SET ";
  if (isset( $w['title'           ])) { $SQL .= " title            = \"" .$w['title'             ]. "\" ,";}
  if (isset( $w['signature'       ])) { $SQL .= " signature        = \"" .$w['signature'         ]. "\" ,";}
  if (isset( $w['notes_to_studies'])) { $SQL .= " notes_to_studies = \"" .$w['notes_to_studies'  ]. "\" ,";}
  if (isset( $w['notes_to_staff'  ])) { $SQL .= " notes_to_staff   = \"" .$w['notes_to_staff'    ]. "\" ,";}
  if (isset( $w['shelf_remain'    ])) { $SQL .= " shelf_remain     = \"" .$w['shelf_remain'      ]. "\"  ";}
                                        $SQL .= " WHERE id         = \"" .$w['document_id'       ]. "\"  "; 
  $res =  mysqli_query ( $this->DB, $SQL);
  return $res;
}


function updateCollectionSortOrder( $collection_id, $sortorder )
{  
  $SQL = " UPDATE collection SET ";
  $SQL .= " sortorder         = \"" . implode(',' , $sortorder ) . "\"";
  $SQL .= " WHERE id         = \"" . $collection_id. "\"  "; 
  $res =  mysqli_query ( $this->DB, $SQL);
  return $res;
}

function getCollectionInfos ($colID = null, $doc_type_id = null , $doc_state_id = null, $short = null  )
{
  $SQL = "SELECT * FROM `collection`";
  $SQL .= " WHERE  1 = 1  ";

  if ($colID )
  {
    $SQL .= " AND collection.id = \"" . $colID ."\"  ";  
  } 
  $SQL .= " ORDER BY collection.id ";
   
  /* ALLE Medieninfo zu dem entsprechenden SA werden ermittelt */
  $ret = false;
  $res =  mysqli_query ( $this->DB, $SQL );
  
  if ( $res )
  {  
   while ( $row = mysqli_fetch_assoc( $res ) ) 
   { 
     $sortorder = explode ( ',' , $row['sortorder'] );

     $userInfo = $this-> getUserHSK(  $row['user_id'] );

      $ret[ $row[ 'id' ] ] = $row;
      $ret[ $row[ 'id' ]][ 'user_info' ] = $userInfo[0] ; 

      $dl = $this->getDokumentList( $row[ 'id' ], $doc_type_id,  $doc_state_id );  /*  ( $doc_ID, $doc_type_id = null , $doc_state_id = null  ) */
      if( $dl )
      {  unset($withoutSortOrder);
         unset($withSortOrder);
      
        foreach($dl as $d)
        {  
          $withoutSortOrder[$d['id']] = array_merge( $d, getDocType($d) ); ## --- Attribute hinzufügen 'doc_type', 'item', 'doc_type_id', 'state_id'   
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
        
        $ret[ $row[ 'id' ] ][ 'document_info' ] = $withSortOrder ;
      }
      elseif( $short )  /* Wenn SA keine Medien beinhaltet, wird er wieder entfernt */
      {
        unset ($ret[$row['id']]);
      }
      elseif($doc_state_id != null )
      {
        unset ($ret[$row['id']]);
      #  $ret[$row['id']][ 'document_info' ] = null ;
      }
    }
  }
  return $ret ;
}


function getDocumentInfos ( $docID ) /* Kartesisches Produkt aller Dokumenten mit allen dazugehörigen Infos */
{ 
  $SQL = "SELECT * FROM `document` WHERE `id`  = ". $docID ;
  $res =  mysqli_query ( $this->DB, $SQL);
  $ans =  mysqli_fetch_assoc( $res );
  return $ans ;
}

function getDocumentID ( $book ) /* Kartesisches Produkt aller Dokumenten mit allen dazugehörigen Infos */
{ 
  $SQL = " SELECT id FROM `document` WHERE";
  if( $book['collection_id'   ] ) $SQL .="  `collection_id`         = \"" .$book['collection_id'   ]."\""; 
  if( $book['title'           ] ) $SQL .=" AND `title`              = \"" .$book['title'           ]."\""; 
  if( $book['author'          ] ) $SQL .=" AND `author`             = \"" .$book['author'          ]."\""; 
  if( $book['edition'         ] ) $SQL .=" AND `edition`            = \"" .$book['edition'         ]."\""; 
  if( $book['year'            ] ) $SQL .=" AND `year`               = \"" .$book['year'            ]."\""; 
  if( $book['journal'         ] ) $SQL .=" AND `journal`            = \"" .$book['journal'         ]."\""; 
  if( $book['volume'          ] ) $SQL .=" AND `volume`             = \"" .$book['volume'          ]."\""; 
  if( $book['pages'           ] ) $SQL .=" AND `pages`              = \"" .$book['pages'           ]."\""; 
  if( $book['publisher'       ] ) $SQL .=" AND `publisher`          = \"" .$book['publisher'       ]."\""; 
  if( $book['signature'       ] ) $SQL .=" AND `signature`          = \"" .$book['signature'       ]."\""; 
  if( $book['ppn'             ] ) $SQL .=" AND `ppn`                = \"" .$book['ppn'             ]."\"";  
  if( $book['doc_type_id'     ] ) $SQL .=" AND `doc_type_id`        = \"" .$book['doc_type_id'     ]."\""; 
  if( $book['url'             ] ) $SQL .=" AND `url`                = \"" .$book['url'             ]."\""; 
  if( $book['physicaldesc'    ] ) $SQL .=" AND `physicaldesc`       = \"" .$book['physicaldesc'    ]."\""; 
  if( $book['state_id'        ] ) $SQL .=" AND `state_id`           = \"" .$book['state_id'        ]."\""; 
  if( $book['relevance'       ] ) $SQL .=" AND `relevance`          = \"" .$book['relevance'       ]."\"";  
  if( $book['notes_to_staff'  ] ) $SQL .=" AND `notes_to_staff`     = \"" .$book['notes_to_staff'  ]."\"";  
  if( $book['shelf_remain'    ] ) $SQL .=" AND `shelf_remain`       = \"" .$book['shelf_remain'    ]."\""; 
  if( $book['notes_to_studies'] ) $SQL .=" AND `notes_to_studies`   = \"" .$book['notes_to_studies']."\""; 
  
  $res =  mysqli_query ( $this->DB, $SQL);
  
  while ($ret = $res->fetch_array(MYSQLI_ASSOC))
  {  
    $ans = $ret['id'];
  }

  return $ans ;
}


function getAllDocTypes()  
{
  $SQL = "SELECT * FROM `doc_type` ORDER BY doc_type_id asc" ;
  
  $res =  mysqli_query ( $this->DB, $SQL);
  while ($row = mysqli_fetch_assoc($res)) 
  {
    $ret[$row['id']] = $row;
  }
 return $ret;  
}


function getRoleName( $roleNr )  
{
  $SQL = "SELECT name FROM `role` WHERE id = $roleNr" ;
  
  $res =  mysqli_query ( $this->DB, $SQL);
  while ($row = mysqli_fetch_assoc($res)) 
  {
    $ret = $row['name'];
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
      $param[ 'cond' ] .= " AND user.id=" . $_SESSION[ 'user' ][ 'id' ] ;
    }
    if ( $mode == "view" )
    {
      $param[ 'cond' ] .= " AND state.name = 'active'" ;
    }
    else
    {
      $param[ 'cond' ] .= " AND state.name != 'new'" ;
    }
   
  $SQL = "SELECT " .$param['columns']. " FROM " .$param['tables']. "  WHERE  ".$param['cond'];

  $res =  mysqli_query ( $this->DB, $SQL);

  while ($row = mysqli_fetch_assoc($res)) 
  {
    $ret[] = $row;
  }
  
  return $ret; 
}

function getSAlist( $user, $mode, $categories )
{
 global $const_FAK;
 
 if($categories == 20) { $categories = "21 OR u.categories_id = 22 OR u.categories_id = 23 OR u.categories_id = 24"; }  
 if($categories == 30) { $categories = "31 OR u.categories_id = 32 OR u.categories_id = 33 OR u.categories_id = 34 OR u.categories_id = 35 OR u.categories_id = 36 OR u.categories_id = 37 OR u.categories_id = 430"; }  
 if($categories == 50) { $categories = "51 OR u.categories_id = 52 OR u.categories_id = 53 OR u.categories_id = 54 OR u.categories_id = 55"; }  
 if($categories == 60) { $categories = "61 OR u.categories_id = 62 OR u.categories_id = 63 OR u.categories_id = 64 OR u.categories_id = 65"; }  
 if($categories == 1 ) { $categories != "21 AND u.categories_id != 22 AND u.categories_id != 23 AND u.categories_id != 24 AND u.categories != 31 AND u.categories_id != 32 AND u.categories_id != 33 AND u.categories_id != 34 AND u.categories_id != 35 AND u.categories_id != 36 AND u.categories_id != 37  AND u.categories != 51 AND u.categories_id != 52 AND u.categories_id != 53 AND u.categories_id != 54 AND u.categories_id != 55 AND u.categories != 61 AND u.categories_id != 62 AND u.categories_id != 63 AND u.categories_id != 64 AND u.categories_id != 65 "; } 
 $SQL  =  " SELECT c.*, u.surname, u.forename, s.name AS state_name, s.description AS state_description"; 
 $SQL .=  " FROM collection c "; 
 $SQL .=  " LEFT JOIN  user u"; 
 $SQL .=  " ON u.hawaccount = c.user_id "; 
 $SQL .=  " LEFT JOIN  state s"; 
 $SQL .=  "  ON s.id = c.state_id"; 
 $SQL .=  " WHERE user_id = \"" .$user[ 'hawaccount' ]."\""; 
 if ( $mode       == "view" )  {  $SQL .= " AND c.state_id = 3";                    }                  /* Zustand 3 = aktiv */  
 if ( $categories == 1 OR $categories == 20 OR $categories == 30 OR$categories == 50 OR $categories == 60 )  {  $SQL .= " AND ( u.categories_id =". $categories .")" ;   }//Filter for category/department
 $SQL .=  " ORDER BY `id` "; 
 $SQL .=  " DESC "; 
 
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
			$r[$tmp['DepID']] = $tmp;
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
			$r[$tmp['FakID']] = $tmp;
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
			$r[$tmp['BibID']] = $tmp;
		}
		return $r;
  }
	}
?>
