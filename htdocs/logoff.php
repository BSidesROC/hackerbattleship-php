<?php
$incdir = '../inc';
set_include_path("$incdir"); 
require_once('template.inc.php');

session_destroy();
header( 'Location: /' ) ;

?>
