<?php
include_once('funcs.inc.php');

function pgAnoHead() {
$theHead = <<<END
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>hacker battleship</title>
    <meta charset="utf-8">
    <script type="text/javascript" src="/s/jquery.js"></script>
    <script type="text/javascript" src="/s/app.js"></script>
    <link rel="stylesheet" href="/css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/css/style.css" type="text/css" media="screen" />
    <link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono' rel='stylesheet' type='text/css'>
  </head>
  <body>
    <div id="login">
      <form method="post" action="/?mth=login" id="loginform" name="loginform">
        username <input type="text" name="name" id="name"></input>
        <br />
        password <input type="password" name="pass" id="pass" onchange="document.forms['loginform'].submit();"></input>
        <br />
      </form>
    </div>
    <div id="header">
      <h3>&#35;HackerBattleship</h3>
    </div>
END;
echo $theHead;
}

function pgAnoNav() {
  $theNav = <<<END
    <div id="floater">
      <div id="nav">
        <a href="/">home</a>
        &nbsp;|&nbsp;
        <a href="/?mth=reg">join</a>
        &nbsp;|&nbsp;
        <a href="/?mth=rules">rules</a>
      </div>
    </div>
END;
  echo $theNav;
}

function pgCredHead() {
if (isset($_SESSION['team'])) {
  $team = htmlspecialchars($_SESSION['team'], ENT_QUOTES);
} else {
  $team = "you got no game!";
}
$theHead = <<<END
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>warzone</title>
    <meta charset="utf-8">
    <script type="text/javascript" src="/s/jquery.js"></script>
    <script type="text/javascript" src="/s/app.js"></script>
    <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
    <link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono' rel='stylesheet' type='text/css'>
  </head>
  <body>
    <div id="login" style="font-size: large; text-align: center;">
      <strong>$team</strong>
    </div>
    <div id="header">
      <h3>&#35;battleship</h3>
    </div>
END;
echo $theHead;
}

function pgCredNav() {
  $theNav = <<<END
    <div id="floater">
      <div id="nav">
        <a href="/?mth=stats">the grid</a>
        &nbsp;|&nbsp;
<!-- not active yet
        <a href="/?mth=bon">enter a bonus code</a>
        &nbsp;|&nbsp; -->
        <a href="/?mth=rules">rules</a>
        &nbsp;|&nbsp;
        <a href="/?mth=logoff">logoff</a>
        &nbsp;|&nbsp;
      </div>
    </div>
END;
  echo $theNav;
}

function pgContent() {
  $theContent = <<<END
    <div id="content">
END;
  echo $theContent;
}

function pgFoot() {
$theFoot = <<<END
    </div>
    <div id="footer">
      <p>&nbsp;</p>
    </div>
  </body>
</html>
END;
echo $theFoot;
}

?>
