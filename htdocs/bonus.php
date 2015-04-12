<?php
list($page,$ext) = explode('.php', $_SERVER["PHP_SELF"]);
list($script,$meth) = explode('/', $page);

//$page = preg_split('/\.php/', $_SERVER["PHP_SELF"])[0];
//$meth = preg_split('/\//', $page)[1];
if($meth != "index") {
  header("Location: /?mth=$meth");
} else {

$incdir = '../inc';
set_include_path("$incdir"); 
require_once('template.inc.php');
require_once('db.inc.php');

$arr = array();

if(!isset($_SESSION['u'])) {
  $arr[] = array("err" => "you got no game!");
  echo json_encode($arr);
  exit();
}

$db_handle = pg_connect(
  "dbname=$db_name
   host=$db_host
   port=$db_port
   user=$db_ro_user
   password=$db_ro_pass
");

  print "<h1>this page doesn\'t exist yet</h1>";

}
?>
