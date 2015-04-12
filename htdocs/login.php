<?php
list($page,$ext) = explode('.php', $_SERVER["PHP_SELF"]);
list($script,$meth) = explode('/', $page);

if($meth != "index") {
  header("Location: /?mth=$meth");
} else {

$incdir = '../inc';
set_include_path("$incdir"); 
require_once('template.inc.php');
require_once('db.inc.php');

$arr = array();

if(isset($_SESSION['u'])) {
  $db_handle = pg_connect(
    "dbname=$db_name
     host=$db_host
     port=$db_port
     user=$db_rw_user
     password=$db_rw_pass
  ");
  $team = pg_escape_string($_SESSION['u']);
  $score_rslt = pg_query_params($db_handle, "update score set score_tally = 
                                  (select score_tally from score where score_team = $1) -10 where score_team = $2",
                          array($team,$team));
  if ($score_rslt) {
    $arr[] = array("err" => "you are already logged in. -10 points for your team.");
    echo json_encode($arr);
  } else {
    $arr[] = array("err" => "query failed" . pg_last_error($db_handle));
    echo json_encode($arr);
    echo pg_result_error($score_rslt);
  }
  exit();
}

if(! isset($_POST["name"]) || ! isset($_POST["pass"]) ) {
// POST didn't have the required params
  $err = <<<END
    <div id="err">
    <h4>you ain&apos;t got what it takes</h4>
END;
  echo $err;
} else {
// POST had the right params, do stuff
  $db_handle = pg_connect(
    "dbname=$db_name
     host=$db_host
     port=$db_port
     user=$db_ro_user
     password=$db_ro_pass
  ");

  $name = pg_escape_string($_POST["name"]);
  $pass = pg_escape_string($_POST["pass"]);

  if ($db_handle) {
    $rslt = pg_query_params($db_handle,
                     "select team_na from reg where team_name = $1",
                     array($name));
    if ($rslt) {
      if ( pg_numrows($rslt) == 0 ) {
        $arr[] = array("err" => "not enough chickens");
        echo json_encode($arr);
      } else if ( pg_numrows($rslt) > 1 ) {
        $arr[] = array("err" => "too many chickens");
        echo json_encode($arr);
      } else {
        for ($row = 0; $row < pg_numrows($rslt); $row++) {
          $na = pg_result($rslt, $row, 'team_na');
        }
      }
    }

    $hash = crypt($pass, $na);

    $rslt = pg_query_params($db_handle,
                            "select team_id, team_name from reg 
                             where team_name = $1 and team_hash = $2",
                            array($name,$hash));
    if ($rslt) {
      if ( pg_numrows($rslt) == 0 ) {
        // no matching rows found
        $arr[] = array("err" => "we don&apos;t know you");
        echo json_encode($arr);
      } else if ( pg_numrows($rslt) > 1 ) {
        // too many matching rows found
        $arr[] = array("err" => "tribbles!");
        echo json_encode($arr);
      } else {
        // proper login, all good
        $arr[] = array("200" => "OK");
        while ($row = pg_fetch_row($rslt)) {
          $_SESSION['u'] =  $row[0];
          $_SESSION['team'] =  htmlspecialchars($row[1], ENT_QUOTES);
        }
        //echo json_encode($arr);
        header('Location: /');
      }
    }
    pg_close($db_handle);
  } else {
    $arr[] = array("err" => "db connection failed");
    echo json_encode($arr);
  }
}

}
?>
