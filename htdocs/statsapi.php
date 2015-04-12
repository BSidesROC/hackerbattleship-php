<?php
# useful for cli testing
# DISABLE THIS FOR WEB OR $_GET WILL BE EMPTY
#parse_str(implode('&', array_slice($argv, 1)), $_GET);

$incdir = '../inc';
set_include_path("$incdir");
require_once('funcs.inc.php');
require_once('db.inc.php');

if(! isset($_GET['callback']) ) {
  // no callback, return regular JSON
  header('Content-Type: application/json; charset=utf8');
} else {
  // callback present, return javascript for JSONP
  header('Content-Type: text/javascript; charset=utf8');
  // set the callback into a var
  $rcvr = $_GET['callback'];
}

$arr = array();
$data = array();

if( isset($_GET['mth']) ) {
  $mth = $_GET['mth'];
} else {
  $arr[] = array("err" => "no method provided");
  echo json_encode($arr);
  exit;
}


$db_handle = pg_connect(
  "dbname=$db_name
   host=$db_host
   port=$db_port
   user=$db_ro_user
   password=$db_ro_pass
");

if ($db_handle) {
switch ($mth) {
case "grid":
#
# =====================================
# grid array
# =====================================
  $grid_qry = "select col_id, row_id, status from grid order by row_id, col_id";
  $grid_rslt = pg_query($db_handle, $grid_qry);
  if ($grid_rslt) {
    $cur = 0;
    $num = pg_num_rows($grid_rslt);

    while ($grid = pg_fetch_row($grid_rslt)) {
      $data[$grid[0].$grid[1]] = $grid[2];
    }
  } else {
    $arr[] = array("err" => "col_qry failed" . pg_last_error($db_handle));
    echo json_encode($arr);
    echo pg_result_error($rslt);
  }
  break;

case "score":
# =====================================
# top 5 scores array
# =====================================
  $score_qry = "select team_name, score_tally from reg, score where team_id = score_team order by score_tally desc, team_name asc limit 5";
  $score_rslt = pg_query($db_handle, $score_qry);
  if ($score_rslt) {
    $cur = 0;
    $num = pg_num_rows($score_rslt);

    while ($score = pg_fetch_row($score_rslt)) {
      $data[$score[0]] = $score[1];
    }
  } else {
    $arr[] = array("err" => "col_qry failed" . pg_last_error($db_handle));
    echo json_encode($arr);
    echo pg_result_error($rslt);
  }
  break;
}
  pg_close($db_handle);

if(! isset($_GET['callback']) ) {
  // JSON data
  echo json_encode($data);
} else {
  // JSONP data
  echo $rcvr . '(' . json_encode($data) . ')';
}

} else {
  $arr[] = array("err" => "db connection failed" . pg_last_error($db_handle));
  echo json_encode($arr);
}

?>
