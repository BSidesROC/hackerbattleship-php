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

if(! isset($_POST["chal"]) || ! isset($_POST["key"]) ) {
// POST didn't have the required params
  if(! isset($_GET["chal"])) {
    $arr[] = array("err" => "lulz. nice try");
    echo json_encode($arr);
    exit();
  } else {
    $chal = htmlspecialchars($_GET["chal"], ENT_QUOTES);

    $descr_rslt = pg_query_params($db_handle,
                  "select chal_descr from chal where chal_id = $1",
                  array($chal));
    if($descr_rslt) {
      $descr = pg_fetch_row($descr_rslt)[0];
    } else {
      $arr[] = array("err" => "descr_rslt failed" . pg_last_error($db_handle));
      echo json_encode($arr);
      echo pg_result_error($descr_rslt);
    }

    $form = <<<END
      <div>
      <h4>Description</h4>
      <p id="descr_txt">$descr</p>
      </div>
      <br>
      <form method="post">
      	<h4>KEYForm</h4>
        <input name="chal" id="chal" type="hidden" value="$chal"></input>
        <input type="text" name="key" id="key" placeholder="challenge key"></input>
        <input type="submit" name="submit" id="submit" style="float:left; clear:left;"></input>
        <a href='/' class='button' style="float:left;clear:right;">go home</a>
      </form>
      </div>
END;
    echo $form;
  }
} else {
// POST had the right params, do stuff

  $chal = pg_escape_string($_POST["chal"]);
  $key = pg_escape_string($_POST["key"]);

  if ($db_handle) {
    $chal_rslt = pg_query_params($db_handle,
                          "select chal_value from chal
                           where chal_id = $1 and chal_key = ENCODE($2,'base64')
                           and chal_open = TRUE", array($chal,$key));
    if ($chal_rslt) {
      $chal_rows = pg_num_rows($chal_rslt);
      if($chal_rows < 1) {
        print $chal_rows;
        $arr[] = array("err" => "challenge closed");
        echo json_encode($arr);
      } else if($chal_rows > 1) {
        $arr[] = array("err" => "too many results");
        echo json_encode($arr);
      } else {
        $points = pg_fetch_row($chal_rslt)[0];
        $team = pg_escape_string($_SESSION['u']);
        $ship_rslt = pg_query_params($db_handle,
                              "select ship_id from grid where chal_id = $1",
                              array($chal));
        if ($ship_rslt) {
          $ship = pg_fetch_row($ship_rslt)[0];
          if($ship > 0) {
            $status = 'hit';
            $points = $points + 5;
            $sunk_rslt = pg_query_params($db_handle,
                                  "select is_sunk($1)", array($ship));
          } else {
            $status = 'miss';
          }

          $db_handle = pg_connect(
            "dbname=$db_name
             host=$db_host
             port=$db_port
             user=$db_rw_user
             password=$db_rw_pass
          ");

          $grid_rslt = pg_query_params($db_handle,
                        "update grid set status = $1 where chal_id = $2",
                        array($status,$chal));
          if($grid_rslt) {
            print "this was a $status<br />";
            if($status == 'hit') {
              if( $sunk_rslt ) {
                $sunk = pg_fetch_row($sunk_rslt)[0];
                if($sunk == 't'){
                  print "das boot ist sunk!<br />";
                  $points = $points + 10;
                } else {
                  print "omg! ship not sunk...";
                }
              } else {
                $arr[] = array("err" => "sunk_rslt failed" . pg_last_error($db_handle));
                echo json_encode($arr);
                echo pg_result_error($sunk_rslt);
              }
            }
          } else {
            $arr[] = array("err" => "grid_rslt failed" . pg_last_error($db_handle));
            echo json_encode($arr);
            echo pg_result_error($grid_rslt);
          }
        } else {
          $arr[] = array("err" => "ship_rslt failed" . pg_last_error($db_handle));
          echo json_encode($arr);
          echo pg_result_error($ship_rslt);
        }
        $score_rslt = pg_query_params($db_handle,
                      "update score set score_tally = (select score_tally from score where score_team = $1) + $2
                       where score_team = $3",
                       array($team,$points,$team));
        if ($score_rslt) {
          $close_rslt = pg_query_params($db_handle,
                        "update chal set chal_open = FALSE where chal_id = $1",
                         array($chal));
          if ($close_rslt) {
            $close_rows = pg_affected_rows($close_rslt);
            if($close_rows == 1) {
              print "you get $points points pointdexter";
            } else {
              $arr[] = array("err" => "close_rows asplode" . pg_last_error($db_handle));
              echo json_encode($arr);
              echo pg_result_error($close_rslt);
            }
          }else {
            $arr[] = array("err" => "close_rslt failed" . pg_last_error($db_handle));
            echo json_encode($arr);
            echo pg_result_error($close_rslt);
          }
        } else {
          $arr[] = array("err" => "score query failed" . pg_last_error($db_handle));
          echo json_encode($arr);
          echo pg_result_error($score_rslt);
        }
      }
    } else {
      $arr[] = array("err" => "chal query failed" . pg_last_error($db_handle));
      echo json_encode($arr);
      echo pg_result_error($chal_rslt);
    }
    pg_close($db_handle);
    print "<br/><br/><a href='/' class='button'>go home</a>";
  } else {
    $arr[] = array("err" => "db connection failed" . pg_last_error($db_handle));
    echo json_encode($arr);
    print "<br/><br/><a href='/' class='button'>go home</a>";
  }
}

}
?>
