<?php
/**
*
* Based on this website integration : https://stefangabos.github.io/world_countries/
*
**/



$countries = json_decode(file_get_contents($argv[1]),true);
$language = $argv[2];
$current_list = json_decode(file_get_contents($argv[3]),true);

$countries_current_positions = array_column($current_list, "ISO3");

$iso_to_french = array_column($countries, "name", "alpha3");


foreach ($iso_to_french as $ISO3 => $name) {
	$ISO3 = strtoupper($ISO3);
	$key = array_search($ISO3, $countries_current_positions);
    $current_list[$key]["short"][$language]=ucfirst($name);
    unset($countries_current_positions[$key]);
}

echo("theses countries are missing :");
print_r($countries_current_positions);

file_put_contents("add_".$language."_to_countries.json", json_encode($current_list));