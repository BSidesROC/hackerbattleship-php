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

$db_handle = pg_connect(
  "dbname=$db_name
   host=$db_host
   port=$db_port
   user=$db_ro_user
   password=$db_ro_pass
");


if ($db_handle) {
  $cols = array();

  $col_qry = "select distinct col_id from grid order by col_id";
  $col_rslt = pg_query($db_handle, $col_qry);
  if ($col_rslt) {
    while ($col = pg_fetch_row($col_rslt)) {
      array_push($cols, $col[0]);
    }

    $row_qry = "select max(row_id) as max from grid";
    $row_rslt = pg_query($db_handle, $row_qry);
    if ($row_rslt) {
      while ($row = pg_fetch_row($row_rslt)) {
       $max_rows = $row[0];
      }
    } else {
      $arr[] = array("err" => "row_qry failed" . pg_last_error($db_handle));
      echo json_encode($arr);
      echo pg_result_error($rslt);
    }

    # omg. make teh grid!
    #print "<div class=\"container\">";
    print "<div class=\"container\">";
    print "<div class=\"board\">";

    # rows
    for($i = 1; $i <= $max_rows; $i++) {
      print "<ul class=\"clear\">";
      # columns
      foreach($cols as $col) {
        $display_rslt = pg_query_params($db_handle,
                        "select status,chal_id from grid
                         where col_id = $1 and row_id = $2",
                        array($col,$i));
        if ($display_rslt) {
          while ($display = pg_fetch_row($display_rslt)) {
            if (! isset($_SESSION['u'])) {
            # user is not logged in, no links for them
              if ( $display[0] == 'open' ) {
                print "
                  <li data-id=\"$col$i\" class=\"agrid_space $display[0]\"
                      data-chal=\"$display[1]\">$col$i</li>
                ";
              } else {
                print "
                  <li data-id=\"$col$i\" class=\"agrid_space $display[0]\"
                      data-chal=\"$display[1]\"><strike>$col$i</strike></li>
                ";
              }
            } else {
            # user is logged in, they get linky goodness
              if ( $display[0] == 'open' ) {
                print "
                  <li data-id=\"$col$i\" class=\"grid_space $display[0]\"
                      data-chal=\"$display[1]\">$col$i</li>
                ";
              } else {
                print "
                  <li data-id=\"$col$i\" class=\"agrid_space $display[0]\"
                      data-chal=\"$display[1]\"><strike>$col$i</strike></li>
                ";
              }
            }
          }
        } else {
          $arr[] = array("err" => "display_qry failed" . pg_last_error($db_handle));
          echo json_encode($arr);
          echo pg_result_error($rslt);
        }
      }
      print "</ul>";
    }
    print "</div>";
    print "</div>";
  } else {
    $arr[] = array("err" => "col_qry failed" . pg_last_error($db_handle));
    echo json_encode($arr);
    echo pg_result_error($rslt);
  }

  $score_qry = "select team_name, score_tally from reg, score where team_id = score_team order by score_tally desc, team_name asc limit 5";
  $score_rslt = pg_query($db_handle, $score_qry);
  if ($score_rslt) {
    print "<div id=\"score\">";
    print "<h4>Top Scores</h4>";
    print "<table>";
    print "<thead><th>Team</th><th>Score</th></thead>";
    while ($score = pg_fetch_row($score_rslt)) {
      print "<tr><td class=\"team_name\">$score[0]</td>
             <td class=\"team_score\">$score[1]</td></tr>";
    }
    print "</table>";
  } else {
    $arr[] = array("err" => "col_qry failed" . pg_last_error($db_handle));
    echo json_encode($arr);
    echo pg_result_error($rslt);
  }

  pg_close($db_handle);

} else {
  $arr[] = array("err" => "db connection failed" . pg_last_error($db_handle));
  echo json_encode($arr);
}

}
?>
