<?php
define("DBHOST","localhost");
define("DBU","root");
define("DBPASS","");
define("DBNAME","pelicanconnect");

$TABLES=array('members'=>'members_info','user_schools'=>'users_selected_school','schools'=>'schools');
require_once(dirname(__FILE__)."/classes/database.php");
$db=new database();
