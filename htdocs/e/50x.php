<?php
$incdir = '../../inc';
set_include_path("$incdir"); 

require_once('template.inc.php');
require_once('db.inc.php');

if( isset($_SESSION['u']) ) {
  pgCredHead();
  pgCredNav();
} else {
  pgAnoHead();
  pgAnoNav();
}

pgContent();

echo "<img src=\"/img/asplode.jpg\" height=\"325\" width=\"250\" />";

pgFoot();
?>
