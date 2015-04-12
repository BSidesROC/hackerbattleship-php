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

if(! isset($_POST["name"]) || ! isset($_POST["pass"]) || ! isset($_POST["email"]) ) {
// POST didn't have the required params
  $form = <<<END
    <div>
    <form method="post">
    <fieldset>
      <legend>Registration Form</legend>
      <p>
        <input type="text" name="name" id="name" placeholder="team name"></input>
      </p>
      <p>
        <input type="text" name="email" id="email" placeholder="team email"></input>
      </p>
      <p>
        <input type="password" name="pass" id="pass" placeholder="team password"></input>
      </p>
      <p>
        <input type="submit" name="submit" id="submit" style="float: left;"></input>
      </p>
      </fieldset>
    </form>
    </div>
END;
  echo $form;

} else {
// POST had the right params, do stuff
  $db_handle = pg_connect(
    "dbname=$db_name
     host=$db_host
     port=$db_port
     user=$db_rw_user
     password=$db_rw_pass
  ");

  $name = pg_escape_string($_POST["name"]);
  $pass = pg_escape_string($_POST["pass"]);
  $email = pg_escape_string($_POST["email"]);

  $na = gensalt();
  $hash = crypt($pass, $na);

  if ($db_handle) {
    $xists_rslt = pg_query_params($db_handle, "select team_name from reg where team_name = $1", array($name));
    if ($xists_rslt) {
      if(pg_num_rows($xists_rslt) > 0) {
        $arr[] = array("msg" => "team already exists");
        echo json_encode($arr);
      } else {
        // insert the new team into the reg table
        $add_rslt = pg_query_params($db_handle,
            "insert into reg(team_name,team_hash,team_na,team_email)
             values ($1,$2,$3,$4)", array($name,$hash,$na,$email));
        if ($add_rslt) {
          $id_rslt = pg_query($db_handle, "select last_value from reg_team_id_seq");
          if($id_rslt) {
            $team_id =  pg_fetch_row($id_rslt)[0];
            $_SESSION['u'] = $team_id;
            $_SESSION['team'] =  htmlspecialchars($name, ENT_QUOTES);
          } else {
            $arr[] = array("err" => "id query failed" . pg_last_error($db_handle));
            echo json_encode($arr);
            echo pg_result_error($rslt);
          }
        } else {
          $arr[] = array("err" => "add query failed" . pg_last_error($db_handle));
          echo json_encode($arr);
          echo pg_result_error($rslt);
        }

        // insert the new team into the score table
        $sadd_rslt = pg_query_params($db_handle,
                     "insert into score values ($1,1)", array($team_id));
        if ($sadd_rslt) {
          $arr[] = array("msg" => "score added ok");
          //echo json_encode($arr);
          header('Location: /?mth=stats');
        } else {
          $arr[] = array("err" => "sadd query failed" . pg_last_error($db_handle));
          echo json_encode($arr);
          echo pg_result_error($rslt);
        }
      }
      pg_close($db_handle);
    } else {
      $arr[] = array("err" => "db connection failed" . pg_last_error($db_handle));
      echo json_encode($arr);
    }
  }
}

}
?>
