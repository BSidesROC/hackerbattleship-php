<?php
include_once('funcs.inc.php');

function pgAnoHead() {
$theHead = <<<END
<!DOCTYPE html>
<html lang="en">
<head>
  <title>hacker battleship</title>
  <meta charset="utf-8">
  <link href='/css/reset.css' rel='stylesheet' type='text/css'>
  <link href='/css/main.css' rel='stylesheet' type='text/css'>
  <link href='/css/icon.css' rel='stylesheet' type='text/css'>
</head>
<body>
  <div id="statspanel">
    <form method="post" action="/?mth=login" id="loginform" name="loginform">
      <input type="text" placeholder="username" name="name" id="name"></input>
      <br />
      <input type="password" placeholder="password" name="pass" id="pass" onchange="document.forms['loginform'].submit();"></input>
      <br />
    </form>
  </div>
  <img id="logo" src="/img/battleship.png" width="500" height="200">
  <div id="leftbar">
    <h1 class="topic"><a href="/">hacker battleship</a></h1>
  </div>

END;
echo $theHead;
}

function pgAnoNav() {
  $theNav = <<<END
    <nav>
      <a href="/">home</a>
      &nbsp;|&nbsp;
      <a href="/?mth=reg">join</a>
      &nbsp;|&nbsp;
      <a href="/?mth=rules">rules</a>
    </nav>
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
  <title>hacker battleship</title>
  <meta charset="utf-8">
  <link href='/css/reset.css' rel='stylesheet' type='text/css'>
  <link href='/css/main.css' rel='stylesheet' type='text/css'>
  <link href='/css/icon.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <div id="statspanel" style="font-size: large; text-align: center;">
      <p>you are currently logged in to the team: <strong>$team</strong></p>
    </div>
  <img id="logo" src="/img/battleship.png" width="500" height="200">
  <div id="leftbar">
    <h1 class="topic"><a href="/">hacker battleship</a></h1>
  </div>

  <div id="content">
END;
echo $theHead;
}

function pgCredNav() {
  $theNav = <<<END
    <nav>
      <a href="/?mth=stats">the grid</a>
      &nbsp;|&nbsp;
<!-- not active yet
      <a href="/?mth=bon">enter a bonus code</a>
      &nbsp;|&nbsp; -->
      <a href="/?mth=rules">rules</a>
      &nbsp;|&nbsp;
      <a href="/?mth=logoff">logoff</a>
      &nbsp;|&nbsp;
    </nav>

END;
  echo $theNav;
}

function pgContent() {
  $theContent = <<<END
  <div id="content">
END;
  echo $theContent;
};

function pgFoot() {
$theFoot = <<<END
    </div>
  </div>
  <div id="footer">
    <p>&nbsp;</p>
  </div>
  <script type="text/javascript" src="/s/jquery.js"></script>
  <script type="text/javascript" src="/s/app.js"></script>
  <script type="text/javascript" src="/s/frustrated.js"></script>
  </body>
</html>
END;
echo $theFoot;
}

?>
