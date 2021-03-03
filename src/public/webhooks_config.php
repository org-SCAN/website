<?php
$branche="";
if (!isset($_GET["branch"])){
	echo("Aucune branche précisée");
	$branche="main";
}
else{
	$branche=$_GET["branch"];
}
passthru("/usr/bin/git checkout {$branche}");
passthru("/usr/bin/git pull origin main");
