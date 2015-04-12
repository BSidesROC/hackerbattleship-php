<?php
session_start();
# -------------------------------------------
# OHAI! if you are reading this, it's because
# you have thought outside the box and tried
# to access a URL that isn't one of the pre-
# configured methods. Good for you! Sadly,
# this really won't be terribly helpful to
# you, but good effort!                   =)
# -------------------------------------------
$incdir = '../inc';
set_include_path("$incdir");
require_once('template.inc.php');

if( isset($_SESSION['u']) ) {
  pgCredHead();
  pgCredNav();
} else {
  pgAnoHead();
  pgAnoNav();
}

pgContent();

if( isset($_GET["mth"]) ){
  $mth = htmlspecialchars($_GET["mth"], ENT_QUOTES);
} else {
  $mth = "stats";
}

switch ($mth) {
  case "login":
  # login
  include_once('login.php');
  break;

  case "logoff":
  # logoff
  include_once('logoff.php');
  break;

  case "reg":
  # team registration
  include_once('reg.php');
  break;

  case "stats":
  include_once('stats.php');
  break;

  case "sub":
  include_once('sub.php');
  break;

  case "bon":
  include_once('bonus.php');
  break;

  case "admin":
  include_once('admin.php');
  break;

  case "rules":
  # da rules
  include_once('rules.php');
  break;

  default:
  include_once('stats.php');
  break;
}

pgFoot();

?>
