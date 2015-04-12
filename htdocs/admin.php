<?php
list($page,$ext) = explode('.php', $_SERVER["PHP_SELF"]);
list($script,$meth) = explode('/', $page);

//list($page,$ext) = explode('.php', $_SERVER["PHP_SELF"];
//list($script,$meth) = explode('/', $page);

if($meth != "index") {
  header("Location: /?mth=$meth");
} else {


$incdir = '../inc';
set_include_path("$incdir"); 
require_once('template.inc.php');
?>

<div style="text-align: center">
  <img src="/img/hackers.gif" />
  <p>the warzone is a series of tubes...</p>
</div>

<?php
}
?>
