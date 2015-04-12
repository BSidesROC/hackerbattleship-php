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
?>

<div>
<h4>Rules</h4>
<hr />
<p> <ul class="rules">
  <li><a href="/?mth=reg">Register</a> for the competition (teams of 1 are permitted)</li>
    <li>“The House” has a 7×7 <a href="/">battleship grid</a> laid out</li>
    <li>Each coordinate on the grid is tied to a hacking challenge of some kind.</li>
    <li>All of the teams work to sink all of The House’s ships.</li>
    <li>When a challenge is solved, the corresponding coordinate is revealed as either a hit or a miss. Additionally, the challenge closes, making it unavailable for other teams to solve.</li>
</ul> </p>
<h4>Scoring</h4>
<hr />
<p> Points are awarded thus:
    <table class="rules">
        <thead><tr><th>Event</th><th>Worth</th></tr></thead>
        <tr><td>solving a challenge</td><td>points awarded based on individual challenge worth</td></tr>
        <tr><td>hitting a ship</td><td>5 points</td></tr>
        <tr><td>sinking a ship</td><td>10 points</td></tr>
    </table></li>
    <br />
</p>
<h4>Winning</h4>
<hr />
<p>At the end of the competition, the team with the most points wins the game.</p>
</div>

<?php
}
?>
