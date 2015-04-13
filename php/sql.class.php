<?php

class SQL
{
var $DB;

function SQL ()
{
  require_once('../php/config.php');
    $db_host = "localhost";		
  $db_name = "semapp";	
  $db_user = "semapp";
  $db_pass = "semapp";
 
 # global $db_host , $db_user , $db_pass , $db_name ;
  $this->DB = new mysqli( $db_host, $db_user,  $db_pass,  $db_name);            if (mysqli_connect_errno()) {   printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());   exit(); }
}


function initMedia($book)
{
 $SQL = "
 INSERT INTO `document` 
 SET
 collection_id      = \"" .$book['collection_id'   ]. "\",
 title              = \"" .$book['title'           ]. "\", 
 author             = \"" .$book['author'          ]. "\", 
 edition            = \"" .$book['edition'         ]. "\", 
 year               = \"" .$book['year'            ]. "\", 
 journal            = \"" .$book['journal'         ]. "\", 
 volume             = \"" .$book['volume'          ]. "\",
 pages              = \"" .$book['pages'           ]. "\",
 publisher          = \"" .$book['publisher'       ]. "\",
 signature          = \"" .$book['signature'       ]. "\",
 ppn                = \"" .$book['ppn'             ]. "\", 
 doc_type_id        = \"" .$book['doc_type_id'     ]. "\", 
 url                = \"" .$book['url'             ]. "\", 
 state_id           = \"" .$book['state_id'        ]. "\", 
 relevance          = \"" .$book['relevance'       ]. "\",
 notes_to_staff     = \"" .$book['notes_to_staff'  ]. "\",
 shelf_remain       = \"" .$book['shelf_remain'    ]. "\",    
 notes_to_studies   = \"" .$book['notes_to_studies']. "\", 
 created            = NOW()                            ,
 last_modified      = NOW()                            ,
 last_state_change  = NOW()";             

 $res =  mysqli_query ( $this->DB, $SQL);
 return $res;
}


function getDokumentList( $colID,$doc_type_id = null , $state_id = null  )
{
  $SQL = " 
  SELECT * 
  FROM `document` 
  WHERE `collection_id` = ". $colID;  

  if ( isset($doc_type_id) ) { $SQL .= " AND `doc_type_id` = ". $doc_type_id;}
  if ( isset($state_id   ) ) { $SQL .= " AND `state_id`    = ". $state_id;   }
  
  $ret = NULL;
  
  $res =  mysqli_query ( $this->DB, $SQL);
  if ($res)
  while ($row = mysqli_fetch_assoc($res)) 
  {
    $row['url']  = 'https://kataloge.uni-hamburg.de/CHARSET=ISO-8859-1/DB=2/LNG=DU/CMD?ACT=SRCHA&IKT=12&SRT=YOP&TRM=' .$row['ppn']; 
    $ret[] = $row;
  }   
  return $ret; 
}
  
function checkPW ($i)
{
  $SQL = "
  SELECT user.*,
  role.name AS role_name
  FROM user,state,role
  WHERE state.name='active' 
  AND user.state_id = state.id 
  AND user.login = \"".$i['login']."\" 
  AND user.password = \"". $i['password'] ."\" 
  AND user.role_id = role.id LIMIT 1";

  $res =  mysqli_query ( $this->DB, $SQL);
  $ret[] = mysqli_fetch_assoc($res);
  return $ret;  
}

function getUserData( $uid )
{
  $SQL = "
  SELECT user.*, 
  role.name AS role_name 
  FROM user,state,role 
  WHERE user.state_id = state.id 
  AND user.id = \"". $uid ."\" 
  AND user.role_id = role.id LIMIT 1";
  
  $res =  mysqli_query ( $this->DB, $SQL);
  $ret = mysqli_fetch_assoc($res);
  return $ret;  
}




function getAllDepartments()
{
  $SQL = "
  SELECT * 
  FROM `categories` 
  ORDER BY `id` ASC";
  
  $res =  mysqli_query ( $this->DB, $SQL);
  while ($row = mysqli_fetch_assoc($res)) 
  {
    $ret[$row['id']] = $row['description'];
  }
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
    $ret[$row['id']] = $row;
  }
  return $ret;  
}



function getBibInfos( $style = NULL )
{
  $SQL = "
  SELECT * 
  FROM `location` 
  ORDER BY `id` ASC";
  
  $res =  mysqli_query ( $this->DB, $SQL);
  while ($row = mysqli_fetch_assoc($res)) 
  {
    if ( $style == 'name')
    {
      $ret[$row['id']] = $row['description'];
    }       
    else
    {
       $ret[$row['id']] = $row;
    }
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
  {
    if ( $style == 'name')
    {
      $ret[$row['id']] = $row['description'];
    }       
    else
    {
       $ret[$row['id']] = $row;
    }
  }
  return $ret;  
}


function deleteCollection($IW, $IU)
{
  if( $IU['role_name'] == 'staff' || $IU['role_name'] == 'admin'  )
  {
    $SQL = "
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
  {
    $SQL = "
    DELETE
    FROM `document` 
    WHERE `document`.`id` = ".$IW['document'];     

    
    $res =  mysqli_query ( $this->DB, $SQL);
    return  mysqli_fetch_assoc( $res );
  }
}




function getCollection($colID)
{
  $SQL = "
  SELECT * 
  FROM `collection` 
  WHERE `id` = ". $colID;
  
  $res =  mysqli_query ( $this->DB, $SQL);
  return  mysqli_fetch_assoc( $res );
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


function initCollection($IW, $IU)
{
  $SQL = "
  INSERT INTO collection SET
  created          =      NOW()                     , 
  last_modified    =      NOW()                     , 
  last_state_change=      NOW()                     , 
  state_id         =      3                         ,
  user_id          = \"" .$IU['id'              ]. "\" ,
  title            = \"" .$IW['title'           ]. "\" ,
  location_id      = \"" .$IW['location_id'     ]. "\" ,
  expiry_date      = \"" .$IW['expiry_date'     ]. "\" ,
  notes_to_studies = \"" .$IW['notes_to_studies']. "\" ,
  categories_id    = \"" .$IW['categories_id'   ]. "\"" ; 

  $res =  mysqli_query ( $this->DB, $SQL);
  return $res;
}


function updateColMetaData($w)
{
 
  $SQL="
  UPDATE `collection` 
  SET title            = \"" .$w['title'           ]. "\" ,
      location_id      = \"" .$w['location_id'     ]. "\" ,
      notes_to_studies = \"" .$w['notes_to_studies']. "\" ,
      categories_id    = \"" .$w['categories_id'   ]. "\"  
  WHERE id             = \"" .$w['id'              ]. "\"";

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

function getCollectionInfos ($colID = null, $doc_type_id = null , $doc_state_id = null, $short = null  )
{
  $SQL = "
  SELECT collection.*,
    categories.description AS department,
    user.forename AS forename, 
    user.surname AS surname, 
    user.sex AS sex 
    FROM user,collection,categories 
  WHERE  collection.categories_id = categories.id
  AND user.id = collection.user_id"; 
  if ($colID)
  {
  $SQL .= " AND collection.id = " . $colID ; 
  } 
  $SQL .= " ORDER BY collection.id ";


  /* ALLE Medieninfo zu dem entsprechenden SA werden ermittelt */
  $ret = false;
  $res =  mysqli_query ( $this->DB, $SQL );

  if ($res )
  {  
   while ($row = mysqli_fetch_assoc($res)) 
   {
      $ret[$row['id']] = $row;
      $dl = $this->getDokumentList( $row['id'], $doc_type_id,  $doc_state_id );  /*  ( $doc_ID, $doc_type_id = null , $doc_state_id = null  ) */
      if( $dl )
      {  
        $ret[$row['id']][ 'document_info' ] = $dl ;
      }
      elseif( $short )  /* Wenn SA keine Medien beinhaltet, wird er wieder entfernt */
      {
        unset ($ret[$row['id']]);
      }
      else
      {
        $ret[$row['id']][ 'document_info' ] = null ;
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


function getStatusName ( $statusID   )
{
  $ans = $this->sql_query ( 'select' , array ( 'tables' => "state" , 'cond' => "id = " . $statusID , )  ) ;       # translate state id to state 
  if ( isset ( $ans[ 0 ][ 'name' ] ) )
  {
    return $ans[ 0 ][ 'name' ] ;
  }
  else
  {
    return false ;
  }
}


function getAllDocTypes()  
{
  $SQL = "SELECT * FROM `doc_type` ORDER BY id asc" ;
  
  $res =  mysqli_query ( $this->DB, $SQL);
  while ($row = mysqli_fetch_assoc($res)) 
  {
    $ret[$row['id']] = $row;
  }
 return $ret;  
}



function getUser( $mode )
{
      $param = array (
        "tables" => "user,role,state" ,
        "cond" => "user.state_id = state.id  
          AND user.role_id = role.id
          AND role.name = 'edit'", 

        "columns" => "user.*" ,
        "order" => "surname,forename,sex"
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

function getExpiredCollections()
{
   $param = array
   (
       "tables" => "collection as c,state as s" ,
        "columns" => "c.id" ,
        'cond' => "c.state_id = s.id   AND   s.name = 'active'   AND   c.expiry_date <= NOW()" ,
    ) ;
  
  $SQL = "SELECT " .$param['columns']. " FROM " .$param['tables']. "  WHERE  ".$param['cond'];
  
  $res =  mysqli_query ( $this->DB, $SQL);

  return $res; 
}


function getSAlist( $user, $mode, $categories )
{
 global $const_FAK;
 
 if($categories == 20) { $categories = "21 OR u.categories_id = 22 OR u.categories_id = 23 OR u.categories_id = 24"; }  
 if($categories == 30) { $categories = "31 OR u.categories_id = 32 OR u.categories_id = 33 OR u.categories_id = 34 OR u.categories_id = 35 OR u.categories_id = 36 OR u.categories_id = 37 OR u.categories_id = 430"; }  
 if($categories == 50) { $categories = "51 OR u.categories_id = 52 OR u.categories_id = 53 OR u.categories_id = 54 OR u.categories_id = 55"; }  
 if($categories == 60) { $categories = "61 OR u.categories_id = 62 OR u.categories_id = 63 OR u.categories_id = 64 OR u.categories_id = 65"; }  
 
 $SQL  =  " SELECT c.*, u.surname, u.forename, s.name AS state_name, s.description AS state_description"; 
 $SQL .=  " FROM collection c "; 
 $SQL .=  " LEFT JOIN  user u"; 
 $SQL .=  " ON u.id = c.user_id "; 
 $SQL .=  " LEFT JOIN  state s"; 
 $SQL .=  "  ON s.id = c.state_id"; 
 $SQL .=  " WHERE user_id = " .$user[ 'id' ]; 
 if ( $mode       == "view" )  {  $SQL .= " AND c.state_id = 3";                    }                  /* Zustand 3 = aktiv */  
 if ( $categories != 1      )  {  $SQL .= " AND ( u.categories_id =". $categories .")" ;   }//Filter for category/department
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
?>
